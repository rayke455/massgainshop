<?php

namespace App\Services\Gateway\swychr;

use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Payment
{
    const BASE_URL = 'https://api.accountpe.com/api/payin';

    /**
     * Map common African/international currencies to default Swychr country codes.
     */
    protected static $currencyCountryMap = [
        'KES' => 'KE', // Kenya
        'NGN' => 'NG', // Nigeria
        'GHS' => 'GH', // Ghana
        'XAF' => 'CM', // Cameroon / CEMAC
        'XOF' => 'CI', // Ivory Coast / WAEMU
        'UGX' => 'UG', // Uganda
        'TZS' => 'TZ', // Tanzania
        'RWF' => 'RW', // Rwanda
        'ZAR' => 'ZA', // South Africa
        'USD' => 'KE', // Fallback default
    ];

    /**
     * Prepare payment request and obtain hosted checkout link from Swychr Pay.
     *
     * @param  \App\Models\Deposit  $deposit
     * @param  \App\Models\Gateway  $gateway
     * @return string (JSON encoded response)
     */
    public static function prepareData($deposit, $gateway)
    {
        $apiKey = trim($gateway->parameters->api_key ?? '');
        if (empty($apiKey)) {
            return json_encode([
                'error'   => true,
                'message' => 'Swychr Pay is not yet configured. Please add the API key in the admin dashboard.',
            ]);
        }

        $currency = strtoupper($deposit->payment_method_currency ?: 'KES');
        $configuredCountry = trim($gateway->parameters->country_code ?? '');
        $countryCode = !empty($configuredCountry)
            ? strtoupper($configuredCountry)
            : (self::$currencyCountryMap[$currency] ?? 'KE');

        // Customer mobile number (numeric digits only)
        $phone = preg_replace('/[^0-9]/', '', (string) ($deposit->user?->phone ?? $deposit->depositable?->phone ?? ''));

        // Amount in currency (rounded to 2 decimals)
        $amount = (float) round($deposit->payable_amount, 2);

        $passCharge = !empty($gateway->parameters->pass_digital_charge) && (int) $gateway->parameters->pass_digital_charge === 1;

        $payload = [
            'country_code'        => $countryCode,
            'transaction_id'      => (string) $deposit->trx_id,
            'mobile_no'           => $phone ?: '254700000000',
            'amount'              => $amount,
            'description'         => 'Deposit #' . $deposit->trx_id,
            'pass_digital_charge' => $passCharge,
            'success_url'         => route('success'),
            'failed_url'          => route('failed'),
        ];

        try {
            $response = Http::withHeaders([
                'Api-Key'      => $apiKey,
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(30)->post(self::BASE_URL . '/create_payment_links', $payload);

            $res = $response->json();

            // Swychr returns payment_link directly or under data
            $paymentLink = $res['data']['payment_link'] ?? $res['payment_link'] ?? null;

            if ($response->successful() && !empty($paymentLink)) {
                return json_encode([
                    'redirect'     => true,
                    'redirect_url' => $paymentLink,
                ]);
            }

            Log::error('Swychr create payment link failed', [
                'trx_id'   => $deposit->trx_id,
                'status'   => $response->status(),
                'response' => $res,
                'payload'  => $payload,
            ]);

            return json_encode([
                'error'   => true,
                'message' => $res['message'] ?? 'Unable to generate Swychr payment link. Please check gateway configuration.',
            ]);

        } catch (\Throwable $e) {
            Log::error('Swychr prepareData exception: ' . $e->getMessage(), [
                'trx_id' => $deposit->trx_id,
            ]);

            return json_encode([
                'error'   => true,
                'message' => 'Payment gateway error: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle incoming webhook or user return callback from Swychr Pay.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gateway       $gateway
     * @param  \App\Models\Deposit|null  $deposit
     * @param  string|null               $trx
     * @param  string|null               $type
     * @return array
     */
    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $apiKey = trim($gateway->parameters->api_key ?? '');
        $webhookSecret = trim($gateway->parameters->webhook_secret ?? '');

        // Resolve deposit reference
        if (!$deposit) {
            $trxId = $trx
                ?: $request->input('transaction_id')
                ?: $request->input('data.transaction_id')
                ?: $request->input('swychr_reference');

            if ($trxId) {
                $deposit = \App\Models\Deposit::with('user', 'depositable')
                    ->where('trx_id', $trxId)
                    ->orderBy('id', 'DESC')
                    ->first();
            }
        }

        if (!$deposit) {
            Log::warning('Swychr IPN: Deposit record not found', [
                'request' => $request->all(),
                'trx'     => $trx,
            ]);
            return [
                'status'   => 'error',
                'msg'      => 'Transaction not found.',
                'redirect' => route('failed'),
            ];
        }

        // Avoid duplicate credit
        if ($deposit->status == 1) {
            return [
                'status'   => 'success',
                'msg'      => 'Transaction was already successful.',
                'redirect' => route('success'),
            ];
        }

        // 1. Webhook Signature Verification (X-Webhook-Signature-V2) if secret is set and header is present
        $timestamp = $request->header('X-Webhook-Timestamp');
        $signature = $request->header('X-Webhook-Signature-V2');
        if (!empty($webhookSecret) && !empty($signature) && !empty($timestamp)) {
            $rawBody = $request->getContent();
            $expectedSignature = hash_hmac('sha256', "{$timestamp}.{$rawBody}", $webhookSecret);
            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Swychr IPN signature mismatch', [
                    'trx_id'   => $deposit->trx_id,
                    'provided' => $signature,
                    'expected' => $expectedSignature,
                ]);
                return [
                    'status'   => 'error',
                    'msg'      => 'Invalid webhook signature.',
                    'redirect' => route('failed'),
                ];
            }
        }

        // 2. Query Swychr Payment Link Status for authoritative confirmation
        try {
            $statusUrl = self::BASE_URL . '/payment_link_status?transaction_id=' . urlencode($deposit->trx_id);
            $statusResponse = Http::withHeaders([
                'Api-Key' => $apiKey,
                'Accept'  => 'application/json',
            ])->timeout(20)->get($statusUrl);

            $statusData = $statusResponse->json();
            $paymentStatus = strtolower(
                $statusData['data']['attributes']['status']
                ?? $statusData['data']['status']
                ?? $request->input('data.status')
                ?? $request->input('status')
                ?? ''
            );

            if ($paymentStatus === 'successful') {
                BasicService::preparePaymentUpgradation($deposit);

                Log::info('Swychr payment successfully verified and credited', [
                    'trx_id' => $deposit->trx_id,
                    'amount' => $deposit->payable_amount,
                ]);

                return [
                    'status'   => 'success',
                    'msg'      => 'Transaction was successful.',
                    'redirect' => route('success'),
                ];
            }

            if (in_array($paymentStatus, ['pending', 'processing', 'created'])) {
                return [
                    'status'   => 'warning',
                    'msg'      => 'Payment is pending confirmation. Your balance will be updated once confirmed.',
                    'redirect' => route('user.add.fund'),
                ];
            }

            Log::warning('Swychr payment status unfulfilled', [
                'trx_id'   => $deposit->trx_id,
                'status'   => $paymentStatus,
                'response' => $statusData,
            ]);

            return [
                'status'   => 'error',
                'msg'      => 'Payment was not successful or has expired.',
                'redirect' => route('failed'),
            ];

        } catch (\Throwable $e) {
            Log::error('Swychr IPN verification failed: ' . $e->getMessage(), [
                'trx_id' => $deposit->trx_id,
            ]);

            return [
                'status'   => 'error',
                'msg'      => 'Error verifying payment status: ' . $e->getMessage(),
                'redirect' => route('failed'),
            ];
        }
    }
}
