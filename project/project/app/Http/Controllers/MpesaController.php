<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Facades\App\Services\BasicService;

class MpesaController extends Controller
{
    protected function baseUrl($environment): string
    {
        return $environment == 'live' ? 'https://api.safaricom.co.ke' : 'https://sandbox.safaricom.co.ke';
    }

    public function getAccessToken($consumerKey, $consumerSecret, $environment)
    {
        $response = Http::timeout(20)->withBasicAuth($consumerKey, $consumerSecret)
            ->get($this->baseUrl($environment) . '/oauth/v1/generate?grant_type=client_credentials');

        if ($response->successful() && $response->json('access_token')) {
            return $response->json('access_token');
        }

        throw new \Exception('M-Pesa rejected the API keys. Check the Consumer Key/Secret in Admin → Payment Gateway → M-Pesa.');
    }

    public function generatePassword($shortCode, $passkey, $timestamp)
    {
        return base64_encode($shortCode . $passkey . $timestamp);
    }

    /**
     * 0712345678 / 712345678 / +254712345678 / 0112345678 → 254712345678 (the only format Daraja accepts).
     */
    public static function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string)$phone);
        if (preg_match('/^(?:254|0)?([17]\d{8})$/', $digits, $m)) {
            return '254' . $m[1];
        }
        return null;
    }

    /** Safaricom must reach this URL; a placeholder/localhost URL means payments are never confirmed. */
    protected function callbackUrl($gateway): string
    {
        $configured = optional(optional($gateway)->parameters)->webhook;
        $isUsable = $configured
            && str_starts_with($configured, 'https://')
            && !preg_match('#//(localhost|127\.0\.0\.1)|/path$#', $configured);

        if ($isUsable) {
            return $configured;
        }

        // Behind a tunnel/proxy the app sees plain http, but Safaricom only accepts https callbacks.
        $url = route('mpesa.webhook');
        return preg_match('#^http://(localhost|127\.0\.0\.1)#', $url) ? $url : preg_replace('#^http://#', 'https://', $url);
    }

    public function stkPush(Request $request, $trx_id)
    {
        $phone = self::normalizePhone($request->input('phone'));
        if (!$phone) {
            return back()->withInput()->withErrors(['phone' => __('Enter your Safaricom number, e.g. 0712 345 678 or 0112 345 678.')]);
        }

        $deposit = Deposit::with('gateway')->where('trx_id', $trx_id)->where('user_id', auth()->id())->first();
        if (!$deposit) {
            return back()->with('error', 'Deposit not found');
        }
        if ($deposit->status == 1) {
            return redirect()->route('user.fund.index')->with('success', __('This deposit is already paid.'));
        }

        try {
            $gateway = $deposit->gateway;
            $params = optional($gateway)->parameters;
            $environment = optional($gateway)->environment;
            $shortCode = optional($params)->short_code;

            $accessToken = $this->getAccessToken(optional($params)->consumer_key, optional($params)->consumer_secret, $environment);
            $timestamp = Carbon::now('Africa/Nairobi')->format('YmdHis');

            $payload = [
                "BusinessShortCode" => $shortCode,
                "Password" => $this->generatePassword($shortCode, optional($params)->pass_key, $timestamp),
                "Timestamp" => $timestamp,
                // Till (Buy Goods): money goes to the Till, the short code is the store number. Paybill: both are the Paybill.
                "TransactionType" => optional($params)->till_number ? "CustomerBuyGoodsOnline" : "CustomerPayBillOnline",
                "Amount" => max(1, (int)ceil($deposit->payable_amount)),
                "PartyA" => $phone,
                "PartyB" => optional($params)->till_number ?: $shortCode,
                "PhoneNumber" => $phone,
                "CallBackURL" => $this->callbackUrl($gateway),
                // Daraja limits: AccountReference ≤ 12 chars, TransactionDesc ≤ 13 chars.
                "AccountReference" => substr(preg_replace('/[^A-Za-z0-9]/', '', basicControl()->site_title ?: 'Deposit'), 0, 12),
                "TransactionDesc" => 'Deposit',
            ];

            $response = Http::timeout(30)->withToken($accessToken)
                ->post($this->baseUrl($environment) . '/mpesa/stkpush/v1/processrequest', $payload);
            $res = $response->json() ?? [];

            if ($response->successful() && ($res['ResponseCode'] ?? null) == '0') {
                $deposit->update(['payment_id' => $res['CheckoutRequestID']]);

                return back()->with('mpesa_pending', true)
                    ->with('success', __('Check your phone and enter your M-Pesa PIN to complete the payment.'));
            }

            Log::warning('M-Pesa STK push failed', ['trx' => $trx_id, 'status' => $response->status(), 'body' => $res]);
            $reason = $res['errorMessage'] ?? $res['ResponseDescription'] ?? 'Unable to reach M-Pesa';
            if (($res['errorCode'] ?? '') === '404.001.03') {
                $reason = 'The M-Pesa keys are not allowed to request payments (enable "M-Pesa Express" for this app on the Daraja portal)';
            }
            return back()->withInput()->with('error', __('M-Pesa could not start the payment: ') . $reason);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Polled by the payment page. Asks Safaricom directly whether the STK push was paid, so deposits are
     * confirmed even when the callback can't reach the site (localhost, firewall, wrong webhook URL).
     */
    public function status($trx_id)
    {
        $deposit = Deposit::with('gateway')->where('trx_id', $trx_id)->where('user_id', auth()->id())->firstOrFail();

        if ($deposit->status == 1) {
            return response()->json(['state' => 'paid', 'redirect' => route('user.fund.index')]);
        }
        if (!$deposit->payment_id) {
            return response()->json(['state' => 'not_started']);
        }

        $result = $this->queryStk($deposit);
        if ($result === null) {
            return response()->json(['state' => 'pending']);
        }
        if ($result['code'] === '0') {
            BasicService::preparePaymentUpgradation($deposit->fresh());
            return response()->json(['state' => 'paid', 'redirect' => route('user.fund.index')]);
        }

        return response()->json(['state' => 'failed', 'message' => $result['desc'] ?: __('The payment was not completed.')]);
    }

    /** @return array{code:string,desc:?string}|null  null while the customer hasn't answered yet */
    protected function queryStk(Deposit $deposit): ?array
    {
        try {
            $params = optional($deposit->gateway)->parameters;
            $environment = optional($deposit->gateway)->environment;
            $shortCode = optional($params)->short_code;
            $token = $this->getAccessToken(optional($params)->consumer_key, optional($params)->consumer_secret, $environment);
            $timestamp = Carbon::now('Africa/Nairobi')->format('YmdHis');

            $res = Http::timeout(20)->withToken($token)
                ->post($this->baseUrl($environment) . '/mpesa/stkpushquery/v1/query', [
                    'BusinessShortCode' => $shortCode,
                    'Password' => $this->generatePassword($shortCode, optional($params)->pass_key, $timestamp),
                    'Timestamp' => $timestamp,
                    'CheckoutRequestID' => $deposit->payment_id,
                ])->json() ?? [];

            if (!isset($res['ResultCode'])) {
                return null; // "The transaction is being processed" → still waiting for the PIN
            }
            return ['code' => (string)$res['ResultCode'], 'desc' => $res['ResultDesc'] ?? null];
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    /**
     * Admin "Test connection": checks the keys and whether they may request payments,
     * without sending a prompt to any phone. Returns a list of [level, message] findings.
     */
    public function diagnose($gateway): array
    {
        $params = optional($gateway)->parameters;
        $environment = optional($gateway)->environment;
        $shortCode = optional($params)->short_code;
        $out = [];

        if ($environment != 'live') {
            $out[] = ['warning', __('Test (sandbox) mode is on. Safaricom never sends a real PIN prompt to phones in test mode — turn off "Test environment" and use your live keys to receive STK pushes.')];
        }
        foreach (['consumer_key' => 'Consumer Key', 'consumer_secret' => 'Consumer Secret', 'short_code' => 'Short code (Paybill/Till)', 'pass_key' => 'Passkey'] as $field => $label) {
            if (empty(optional($params)->{$field})) {
                $out[] = ['danger', __(':field is empty.', ['field' => $label])];
            }
        }
        if (count(array_filter($out, fn($f) => $f[0] === 'danger'))) {
            return $out;
        }

        try {
            $token = $this->getAccessToken($params->consumer_key, $params->consumer_secret, $environment);
            $out[] = ['success', __('Consumer Key and Secret are valid for :env.', ['env' => $environment == 'live' ? 'LIVE' : 'sandbox'])];
        } catch (\Throwable $e) {
            $out[] = ['danger', __('Safaricom rejected the Consumer Key/Secret for :env. Copy them again from your Daraja app.', ['env' => $environment == 'live' ? 'LIVE' : 'sandbox'])];
            return $out;
        }

        $timestamp = Carbon::now('Africa/Nairobi')->format('YmdHis');
        $res = Http::timeout(20)->withToken($token)
            ->post($this->baseUrl($environment) . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $shortCode,
                'Password' => $this->generatePassword($shortCode, $params->pass_key, $timestamp),
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => 'ws_CO_00000000000000000000000',
            ]);
        $body = $res->json() ?? [];
        $code = $body['errorCode'] ?? null;

        if ($code === '404.001.03') {
            $out[] = ['danger', __('These keys are NOT allowed to request M-Pesa payments ("Invalid Access Token"). On developer.safaricom.co.ke, use an app with the "M-Pesa Express" product, or your Go-Live (production) keys.')];
        } elseif (in_array($code, ['400.002.02', '500.001.1001'], true) && preg_match('/shortcode|credential|password/i', $body['errorMessage'] ?? '')) {
            $out[] = ['danger', __('The keys work, but the Short code and Passkey don\'t match (:msg). Use the Passkey Safaricom emailed for this Paybill/Till.', ['msg' => $body['errorMessage'] ?? $code])];
        } else {
            $out[] = ['success', __('The keys are allowed to request payments. (Safaricom answered: :msg)', ['msg' => $body['errorMessage'] ?? $body['ResultDesc'] ?? $res->status()])];
        }

        $callback = $this->callbackUrl($gateway);
        $out[] = str_starts_with($callback, 'https://') && !preg_match('#//(localhost|127\.)#', $callback)
            ? ['success', __('Payment confirmations will be sent to :url', ['url' => $callback])]
            : ['warning', __('Confirmations URL is :url — Safaricom can\'t reach it. That\'s fine for testing (the payment page checks with Safaricom itself), but on your live site it must be https://yourdomain/mpesa/webhook.', ['url' => $callback])];

        return $out;
    }

    /**
     * Safaricom callback. Never trusts the payload on its own: the result is re-checked with Safaricom
     * before any balance is added, so a forged callback can't credit an account.
     */
    public function webhook(Request $request)
    {
        $callback = data_get($request->json()->all(), 'Body.stkCallback');
        $checkoutRequestId = data_get($callback, 'CheckoutRequestID');

        if (!$checkoutRequestId) {
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid callback structure'], 400);
        }

        $deposit = Deposit::with('gateway')->where('payment_id', $checkoutRequestId)->first();
        if (!$deposit) {
            $guest = \App\Models\GuestOrder::where('payment_ref', $checkoutRequestId)->first();
            if ($guest) {
                \App\Services\GuestCheckout::sync($guest); // Verify with M-Pesa, never trust callback success or failure.
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received']);
            }
            // A customer paying on a rented panel (platform Paybill checkout).
            $rental = \App\Models\RentalRequest::where('payment_ref', $checkoutRequestId)->where('status', 'awaiting_payment')->first();
            if ($rental && (string) data_get($callback, 'ResultCode') === '0') {
                $verified = (new \App\Services\MpesaService())->stkQuery($checkoutRequestId);   // never trust the callback alone
                if ($verified && $verified['code'] === '0') {
                    \App\Services\PanelRentals::fulfil($rental, (float) $rental->paid_amount);
                }
            } elseif ($rental) {
                $rental->update(['status' => 'payment_failed', 'note' => data_get($callback, 'ResultDesc') ?: 'Payment was not completed']);
            }
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received']);
        }

        if ((string)data_get($callback, 'ResultCode') === '0' && $deposit->status != 1) {
            $verified = $this->queryStk($deposit);
            if ($verified && $verified['code'] === '0') {
                BasicService::preparePaymentUpgradation($deposit);
            } else {
                Log::warning('M-Pesa callback said paid but query did not confirm', ['checkout' => $checkoutRequestId, 'query' => $verified]);
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received']);
    }
}
