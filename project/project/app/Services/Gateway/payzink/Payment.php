<?php

namespace App\Services\Gateway\payzink;

use App\Models\Deposit;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Payment
{
    /**
     * Prepare Payzink hosted checkout payment.
     */
    public static function prepareData($deposit, $gateway)
    {
        $publishableKey = trim($gateway->parameters->publishable_key ?? '');
        $secretKey = trim($gateway->parameters->secret_key ?? '');
        $environment = strtolower(trim($gateway->parameters->environment ?? 'live'));

        $baseUrl = self::getBaseUrl($environment);

        $accessToken = self::getAccessToken($baseUrl, $publishableKey, $secretKey);
        if (!$accessToken) {
            Log::error('Payzink access token retrieval failed', [
                'order_id' => $deposit->trx_id,
                'environment' => $environment,
            ]);
            return json_encode([
                'error' => true,
                'message' => 'Unable to connect to Payzink gateway. Please verify your API keys in Admin -> Payment Methods.',
            ]);
        }

        $currency = strtoupper($deposit->payment_method_currency ?: 'USD');
        $amountMinor = self::toMinorUnits((float) $deposit->payable_amount, $currency);

        $callbackUrl = route('ipn', [$gateway->code, $deposit->trx_id]);

        $payload = [
            'order' => [
                'action' => 'PURCHASE',
                'amount' => [
                    'currencyCode' => $currency,
                    'value' => $amountMinor,
                ],
            ],
            'extra' => [
                'orderId' => (string) $deposit->trx_id,
            ],
            '_links' => [
                'notificationUrl' => $callbackUrl,
                'redirectUrl' => $callbackUrl,
                'returnUrl' => $callbackUrl,
                'cancelUrl' => route('failed'),
            ],
        ];

        if ($deposit->user) {
            $user = $deposit->user;
            $payload['customer'] = [
                'email' => $user->email,
                'emailVerified' => true,
                'firstName' => $user->firstname ?: ($user->username ?: 'Customer'),
                'lastName' => $user->lastname ?: 'User',
                'phoneNumber' => (string) ($user->phone ?: ''),
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($baseUrl . '/api/v1/payment/hosted', $payload);

        $res = $response->json();

        if ($response->successful() && isset($res['result']['_links']['payment']['href'])) {
            $reference = $res['result']['reference'] ?? null;
            if ($reference) {
                Cache::put('payzink_ref_' . $deposit->trx_id, $reference, now()->addDays(2));
                Cache::put('payzink_trx_' . $reference, $deposit->trx_id, now()->addDays(2));
                $deposit->payment_id = $reference;
                $deposit->save();
            }

            return json_encode([
                'redirect' => true,
                'redirect_url' => $res['result']['_links']['payment']['href'],
            ]);
        }

        Log::error('Payzink payment hosted initiation failed', [
            'order_id' => $deposit->trx_id,
            'status' => $response->status(),
            'response' => $res,
        ]);

        return json_encode([
            'error' => true,
            'message' => $res['message'] ?? 'Payzink checkout could not be created. Please try again.',
        ]);
    }

    /**
     * Handle Payzink IPN / Callback verification.
     */
    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $publishableKey = trim($gateway->parameters->publishable_key ?? '');
        $secretKey = trim($gateway->parameters->secret_key ?? '');
        $environment = strtolower(trim($gateway->parameters->environment ?? 'live'));

        $baseUrl = self::getBaseUrl($environment);

        // If deposit is missing, attempt to find by trx
        if (!$deposit && $trx) {
            $deposit = Deposit::where('trx_id', $trx)->first();
        }

        // 1. Resolve transaction reference
        $reference = self::resolveReference($request, $deposit ? $deposit->trx_id : $trx, $deposit);

        // If deposit is still missing, lookup by resolved reference
        if (!$deposit && $reference) {
            $deposit = Deposit::where('payment_id', $reference)->first();
            if (!$deposit) {
                $cachedTrx = Cache::get('payzink_trx_' . $reference);
                if ($cachedTrx) {
                    $deposit = Deposit::where('trx_id', $cachedTrx)->first();
                }
            }
        }

        // Check if transaction is already completed
        if ($deposit && $deposit->status == 1) {
            return [
                'status' => 'success',
                'msg' => 'Transaction has already been completed successfully.',
                'redirect' => route('success'),
            ];
        }

        if (!$reference) {
            Log::warning('Payzink IPN received without identifiable reference', [
                'trx' => $deposit->trx_id ?? $trx,
                'request' => $request->all(),
            ]);

            return [
                'status' => 'error',
                'msg' => 'No active payment transaction found. If you completed a payment, it may take a few moments to confirm.',
                'redirect' => route('failed'),
            ];
        }

        if (!$publishableKey || !$secretKey) {
            Log::error('Payzink API keys not configured in Admin -> Payment Methods', ['gateway_id' => $gateway->id]);
            return [
                'status' => 'error',
                'msg' => 'Payzink gateway is not yet fully configured with API keys.',
                'redirect' => route('failed'),
            ];
        }

        // 2. Fetch access token
        $accessToken = self::getAccessToken($baseUrl, $publishableKey, $secretKey);
        if (!$accessToken) {
            return [
                'status' => 'error',
                'msg' => 'Unable to authenticate with Payzink gateway.',
                'redirect' => route('failed'),
            ];
        }

        // 3. Query transaction info from Payzink
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($baseUrl . '/api/v1/payment/transaction/' . $reference . '/info');

        $info = $response->json();
        $state = strtoupper((string) data_get($info, 'result.state', ''));

        Log::info('Payzink verification response', [
            'trx' => $deposit->trx_id ?? $trx,
            'reference' => $reference,
            'state' => $state,
            'response' => $info,
        ]);

        if (in_array($state, ['PURCHASED', 'CAPTURED', 'AUTHORISED', 'PAID'], true)) {
            if ($deposit && $deposit->status == 0) {
                BasicService::preparePaymentUpgradation($deposit);
            }

            return [
                'status' => 'success',
                'msg' => 'Transaction was successful.',
                'redirect' => route('success'),
            ];
        }

        if (in_array($state, ['PENDING', 'INITIATED', 'PROCESSING', 'AUTHENTICATING'], true)) {
            return [
                'status' => 'warning',
                'msg' => 'Your payment is processing. Your account will update automatically once confirmed.',
                'redirect' => route('success'),
            ];
        }

        return [
            'status' => 'error',
            'msg' => 'Transaction state: ' . ($state ?: 'unsuccessful'),
            'redirect' => route('failed'),
        ];
    }

    /**
     * Resolve Payzink base URL.
     */
    protected static function getBaseUrl(string $environment): string
    {
        if ($environment === 'sandbox' || $environment === 'dev') {
            return 'https://merchant-dev.payzink.com';
        }

        return 'https://merchant.payzink.com';
    }

    /**
     * Fetch and cache access token.
     */
    protected static function getAccessToken(string $baseUrl, string $publishableKey, string $secretKey): ?string
    {
        if (!$publishableKey || !$secretKey) {
            return null;
        }

        $cacheKey = 'payzink_token_' . md5($publishableKey . $secretKey . $baseUrl);

        return Cache::remember($cacheKey, 240, function () use ($baseUrl, $publishableKey, $secretKey) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])->post($baseUrl . '/api/v1/auth/access-token', [
                    'publishableKey' => $publishableKey,
                    'secretKey' => $secretKey,
                ]);

                return $response->json('result.accessToken');
            } catch (\Throwable $e) {
                Log::error('Payzink getAccessToken exception: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Resolve reference from query parameters, body, deposit, or cache.
     */
    protected static function resolveReference($request, ?string $trxId, $deposit = null): ?string
    {
        $all = $request->all();

        foreach ([
            $request->input('pz_reference'),
            $request->input('reference'),
            $request->input('ref'),
            $request->input('id'),
            $request->input('transaction_id'),
            $request->input('payment_id'),
            data_get($all, 'result.reference'),
            data_get($all, 'data.reference'),
            data_get($all, 'transaction.reference'),
        ] as $candidate) {
            if (is_string($candidate) && trim($candidate) !== '') {
                return trim($candidate);
            }
        }

        if ($deposit && !empty($deposit->payment_id)) {
            return trim($deposit->payment_id);
        }

        if ($trxId) {
            $cached = Cache::get('payzink_ref_' . $trxId);
            if ($cached) {
                return $cached;
            }
        }

        return null;
    }

    /**
     * Convert decimal amount to minor currency units (cents).
     */
    protected static function toMinorUnits(float $amount, string $currency): int
    {
        if (in_array($currency, ['BHD', 'KWD', 'OMR', 'JOD'], true)) {
            return (int) round($amount * 1000);
        }

        if (in_array($currency, ['JPY', 'KRW', 'VND'], true)) {
            return (int) round($amount);
        }

        return (int) round($amount * 100);
    }
}
