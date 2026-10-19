<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Gateway;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Snippe (Tanzania) mobile money: M-Pesa, Airtel Money, Mixx by Yas, Halotel.
 * Docs: https://docs.snippe.sh — POST /v1/payments sends a USSD push; the customer confirms with their PIN.
 *
 * A deposit is only credited after Snippe's API confirms the payment (GET /v1/payments/{reference}),
 * inside a locked transaction, so a webhook and the page's status checks can never credit it twice.
 */
class SnippeController extends Controller
{
    private const API = 'https://api.snippe.sh/v1';
    private const MIN_AMOUNT = 500; // TZS, Snippe minimum

    /** "0754 123 456", "+255754123456", "754123456" → "255754123456" (Snippe's format). */
    public static function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '255' . substr($digits, 1);
        } elseif (strlen($digits) === 9) {
            $digits = '255' . $digits;
        }
        return preg_match('/^255[67]\d{8}$/', $digits) ? $digits : null;
    }

    private function client(Gateway $gateway)
    {
        $key = trim((string) optional($gateway->parameters)->api_key);
        throw_if($key === '', new \Exception(__('Snippe is not set up yet (missing API key). Please choose another payment method.')));
        return Http::timeout(30)->acceptJson()->withToken($key);
    }

    private function webhookUrl(): ?string
    {
        $url = route('snippe.webhook');
        // Snippe must be able to reach the site; skip on localhost (the page's status checks confirm instead).
        if (preg_match('#//(localhost|127\.0\.0\.1)#', $url)) {
            return null;
        }
        return preg_replace('#^http://#', 'https://', $url);
    }

    public function pay(Request $request, string $trx_id)
    {
        $phone = self::normalizePhone($request->input('phone'));
        if (!$phone) {
            return back()->withInput()->withErrors(['phone' => __('Enter a Tanzanian mobile number, e.g. 0754 123 456.')]);
        }

        $deposit = Deposit::with('gateway')->where('trx_id', $trx_id)->where('user_id', auth()->id())->firstOrFail();
        if ($deposit->status == 1) {
            return redirect()->route('user.fund.index')->with('success', __('This deposit is already paid.'));
        }

        $amount = (int) ceil($deposit->payable_amount);
        if ($amount < self::MIN_AMOUNT) {
            return back()->with('error', __('The minimum Snippe payment is :min TZS. Please add a larger amount.', ['min' => self::MIN_AMOUNT]));
        }

        try {
            $user = auth()->user();
            $name = trim((string) ($user->firstname ?: $user->username));
            $payload = [
                'payment_type' => 'mobile',
                'details' => ['amount' => $amount, 'currency' => 'TZS'],
                'phone_number' => $phone,
                'customer' => [
                    'firstname' => $name ?: 'Customer',
                    'lastname' => $user->lastname ?: $name ?: 'Customer',
                    'email' => $user->email,
                ],
                'metadata' => ['trx_id' => $deposit->trx_id, 'user_id' => (string) $user->id],
            ];
            if ($hook = $this->webhookUrl()) {
                $payload['webhook_url'] = $hook;
            }

            // Idempotency keys must be ≤ 30 chars; one per attempt so "send again" makes a fresh push.
            $res = $this->client($deposit->gateway)
                ->withHeaders(['Idempotency-Key' => substr('zk' . $deposit->id . '-' . now()->format('His') . '-' . random_int(100, 999), 0, 30)])
                ->post(self::API . '/payments', $payload);
            $body = $res->json() ?? [];
            $reference = data_get($body, 'data.reference');

            if ($res->successful() && $reference) {
                $deposit->update(['payment_id' => $reference]);
                return back()->with('snippe_pending', true)
                    ->with('success', __('Check your phone and enter your PIN to complete the payment.'));
            }

            Log::warning('Snippe payment request failed', ['trx' => $trx_id, 'status' => $res->status(), 'body' => $body]);
            return back()->withInput()->with('error', __('Snippe could not start the payment: ') . (data_get($body, 'message') ?: __('please try again in a moment.')));
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /** Polled by the payment page every few seconds. */
    public function status(string $trx_id)
    {
        $deposit = Deposit::with('gateway')->where('trx_id', $trx_id)->where('user_id', auth()->id())->firstOrFail();

        if ($deposit->status == 1) {
            return response()->json(['state' => 'paid', 'redirect' => route('user.fund.index')]);
        }
        if (!$deposit->payment_id) {
            return response()->json(['state' => 'not_started']);
        }

        $state = $this->confirm($deposit);
        return match ($state) {
            'completed' => response()->json(['state' => 'paid', 'redirect' => route('user.fund.index')]),
            'failed', 'voided', 'expired' => response()->json(['state' => 'failed', 'message' => __('The payment was not completed (:s).', ['s' => __($state)])]),
            default => response()->json(['state' => 'pending']),
        };
    }

    /** Snippe → us. Signature checked, then the payment is re-confirmed with the API before crediting. */
    public function webhook(Request $request)
    {
        $raw = $request->getContent();
        $reference = data_get(json_decode($raw, true), 'data.reference');
        $deposit = $reference ? Deposit::with('gateway')->where('payment_id', $reference)->first() : null;
        if (!$deposit) {
            return response()->json(['received' => true]); // not ours / unknown: acknowledge so Snippe stops retrying
        }

        if (!$this->validSignature($request, $raw, (string) optional($deposit->gateway->parameters)->webhook_secret)) {
            Log::warning('Snippe webhook with an invalid signature', ['reference' => $reference]);
            return response()->json(['error' => 'invalid signature'], 401);
        }

        $this->confirm($deposit);
        return response()->json(['received' => true]);
    }

    /**
     * Docs: HMAC-SHA256("{timestamp}.{raw_body}") hex in X-Webhook-Signature (the PHP SDK signs the raw body only,
     * so both are accepted). Without a secret configured we still re-check every payment with the API before crediting.
     */
    private function validSignature(Request $request, string $raw, string $secret): bool
    {
        if ($secret === '') {
            return true;
        }
        $signature = (string) $request->header('X-Webhook-Signature');
        $timestamp = (string) $request->header('X-Webhook-Timestamp');
        if ($signature === '') {
            return false;
        }
        if ($timestamp !== '' && ctype_digit($timestamp) && abs(time() - (int) $timestamp) > 300) {
            return false;
        }
        foreach ([$timestamp . '.' . $raw, $raw] as $message) {
            if (hash_equals(hash_hmac('sha256', $message, $secret), $signature)) {
                return true;
            }
        }
        return false;
    }

    /** Ask Snippe for the real status; credit the deposit exactly once if it's completed. */
    private function confirm(Deposit $deposit): ?string
    {
        try {
            $res = $this->client($deposit->gateway)->get(self::API . '/payments/' . urlencode($deposit->payment_id));
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
        $data = $res->json('data') ?? [];
        $status = strtolower((string) ($data['status'] ?? ''));

        if ($status === 'completed') {
            $paid = (int) data_get($data, 'amount.value', 0);
            $expected = (int) ceil($deposit->payable_amount);
            $currency = strtoupper((string) data_get($data, 'amount.currency', 'TZS'));
            if ($currency !== 'TZS' || $paid < $expected) {
                Log::warning('Snippe payment amount mismatch', ['trx' => $deposit->trx_id, 'paid' => $paid, 'expected' => $expected, 'currency' => $currency]);
                return 'pending';
            }

            DB::transaction(function () use ($deposit) {
                $locked = Deposit::with(['gateway', 'user'])->lockForUpdate()->find($deposit->id);
                if ($locked && $locked->status != 1) {
                    BasicService::preparePaymentUpgradation($locked);
                }
            });
        }

        return $status ?: null;
    }
}
