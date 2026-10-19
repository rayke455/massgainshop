<?php

namespace App\Services;

use App\Models\Gateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Daraja calls shared by panel rentals, using the platform's M-Pesa gateway settings:
 * C2B (STK push + status query) to collect, B2C / B2B to pay resellers out.
 */
class MpesaService
{
    private $gateway;
    private $params;

    public function __construct()
    {
        $this->gateway = Gateway::where('code', 'mpesa')->first();
        $this->params = optional($this->gateway)->parameters;
    }

    public function ready(): bool
    {
        return $this->gateway && $this->gateway->status == 1
            && !empty($this->params->consumer_key) && !empty($this->params->consumer_secret) && !empty($this->params->short_code);
    }

    private function base(): string
    {
        return optional($this->gateway)->environment == 'live' ? 'https://api.safaricom.co.ke' : 'https://sandbox.safaricom.co.ke';
    }

    private function token(): string
    {
        $key = 'zk_mpesa_token_' . md5($this->params->consumer_key . $this->base());
        return Cache::remember($key, now()->addMinutes(50), function () {
            $res = Http::timeout(20)->withBasicAuth($this->params->consumer_key, $this->params->consumer_secret)
                ->get($this->base() . '/oauth/v1/generate?grant_type=client_credentials');
            if (!$res->successful() || !$res->json('access_token')) {
                throw new \RuntimeException('M-Pesa rejected the API keys.');
            }
            return $res->json('access_token');
        });
    }

    /** Public https URL Safaricom can call back (null on localhost: the status checks confirm instead). */
    public static function publicUrl(string $routeName): ?string
    {
        $url = route($routeName);
        if (preg_match('#//(localhost|127\.0\.0\.1)#', $url)) {
            $configured = optional(optional(Gateway::where('code', 'mpesa')->first())->parameters)->webhook;
            if ($configured && str_starts_with($configured, 'https://')) {
                // reuse the tunnel/host of the configured M-Pesa webhook
                return preg_replace('#/mpesa/webhook.*$#', '', $configured) . parse_url($url, PHP_URL_PATH);
            }
            return null;
        }
        return preg_replace('#^http://#', 'https://', $url);
    }

    // ------------------------------------------------------------------ C2B (collect)

    /** @return array{ok:bool, checkout_id:?string, message:string} */
    public function stkPush(string $phone254, int $amountKes, string $reference, string $description): array
    {
        $p = $this->params;
        $timestamp = Carbon::now('Africa/Nairobi')->format('YmdHis');
        $callback = self::publicUrl('mpesa.webhook') ?? 'https://example.com/mpesa/webhook';
        $res = Http::timeout(30)->withToken($this->token())->post($this->base() . '/mpesa/stkpush/v1/processrequest', [
            'BusinessShortCode' => $p->short_code,
            'Password' => base64_encode($p->short_code . $p->pass_key . $timestamp),
            'Timestamp' => $timestamp,
            'TransactionType' => !empty($p->till_number) ? 'CustomerBuyGoodsOnline' : 'CustomerPayBillOnline',
            'Amount' => max(1, $amountKes),
            'PartyA' => $phone254,
            'PartyB' => !empty($p->till_number) ? $p->till_number : $p->short_code,
            'PhoneNumber' => $phone254,
            'CallBackURL' => $callback,
            'AccountReference' => substr(preg_replace('/[^A-Za-z0-9]/', '', $reference), 0, 12),
            'TransactionDesc' => substr($description, 0, 13),
        ]);
        $body = $res->json() ?? [];
        if ($res->successful() && ($body['ResponseCode'] ?? null) == '0') {
            return ['ok' => true, 'checkout_id' => $body['CheckoutRequestID'], 'message' => 'sent'];
        }
        return ['ok' => false, 'checkout_id' => null, 'message' => $body['errorMessage'] ?? $body['ResponseDescription'] ?? 'M-Pesa is not available right now'];
    }

    /** @return array{code:string, desc:?string}|null  null while the customer hasn't answered */
    public function stkQuery(string $checkoutId): ?array
    {
        try {
            $p = $this->params;
            $timestamp = Carbon::now('Africa/Nairobi')->format('YmdHis');
            $res = Http::timeout(20)->withToken($this->token())->post($this->base() . '/mpesa/stkpushquery/v1/query', [
                'BusinessShortCode' => $p->short_code,
                'Password' => base64_encode($p->short_code . $p->pass_key . $timestamp),
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutId,
            ])->json() ?? [];
            return isset($res['ResultCode']) ? ['code' => (string) $res['ResultCode'], 'desc' => $res['ResultDesc'] ?? null] : null;
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    // ------------------------------------------------------------------ B2C / B2B (pay out)

    public function payoutsReady(array $cfg): bool
    {
        return $this->ready() && !empty($cfg['b2c_initiator']) && !empty($cfg['b2c_credential']);
    }

    /**
     * Send money to a reseller. $method: b2c (phone), paybill or till.
     * @return array{ok:bool, conversation_id:?string, message:string}
     */
    public function payout(array $cfg, string $method, string $account, ?string $accountRef, int $amountKes, string $remarks, int $payoutId, string $callbackToken): array
    {
        $shortCode = $cfg['b2c_shortcode'] ?: $this->params->short_code;
        $result = self::publicUrl('rentals.payout.result');
        if (!$result) {
            return ['ok' => false, 'conversation_id' => null, 'message' => 'Payouts need a public https address for Safaricom results (not localhost).'];
        }
        if (!$this->payoutsReady($cfg) || $amountKes < 1) return ['ok' => false, 'conversation_id' => null, 'message' => 'Payout credentials or amount are invalid.'];
        $result .= '?'.http_build_query(['payout' => $payoutId, 'token' => $callbackToken]);
        if ($method === 'b2c') {
            $endpoint = '/mpesa/b2c/v1/paymentrequest';
            $payload = [
                'InitiatorName' => $cfg['b2c_initiator'], 'SecurityCredential' => $cfg['b2c_credential'], 'CommandID' => 'BusinessPayment',
                'Amount' => $amountKes, 'PartyA' => $shortCode, 'PartyB' => $account,
                'Remarks' => substr($remarks, 0, 100), 'QueueTimeOutURL' => $result.'&timeout=1', 'ResultURL' => $result, 'Occasion' => 'Panel earnings',
            ];
        } else {
            $endpoint = '/mpesa/b2b/v1/paymentrequest';
            $payload = [
                'Initiator' => $cfg['b2c_initiator'], 'SecurityCredential' => $cfg['b2c_credential'],
                'CommandID' => $method === 'till' ? 'BusinessBuyGoods' : 'BusinessPayBill',
                'SenderIdentifierType' => '4', 'RecieverIdentifierType' => $method === 'till' ? '2' : '4',
                'Amount' => $amountKes, 'PartyA' => $shortCode, 'PartyB' => $account,
                'AccountReference' => substr($accountRef ?: 'Earnings', 0, 13),
                'Remarks' => substr($remarks, 0, 100), 'QueueTimeOutURL' => $result.'&timeout=1', 'ResultURL' => $result,
            ];
        }
        try {
            $res = Http::timeout(30)->withToken($this->token())->post($this->base() . $endpoint, $payload);
            $body = $res->json() ?? [];
            if ($res->successful() && ($body['ResponseCode'] ?? null) == '0') {
                return ['ok' => true, 'conversation_id' => $body['ConversationID'] ?? $body['OriginatorConversationID'] ?? null, 'message' => 'sent'];
            }
            return ['ok' => false, 'uncertain' => true, 'conversation_id' => null, 'message' => 'Submission was not confirmed. Funds remain reserved; reconcile with M-Pesa before retrying.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'uncertain' => true, 'conversation_id' => null, 'message' => 'Connection interrupted. Funds remain reserved; reconcile with M-Pesa before retrying.'];
        }
    }

    public static function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        return preg_match('/^(?:254|0)?([17]\d{8})$/', $digits, $m) ? '254' . $m[1] : null;
    }
}
