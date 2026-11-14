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
     * Prepare payment data and present checkout confirmation view for country & phone input.
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

        $send['amount'] = round($deposit->payable_amount, 2);
        $send['currency'] = $deposit->payment_method_currency;
        $send['view'] = 'user.payment.swychr';

        return json_encode($send);
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
                $baseTrx = explode('-', $trxId)[0];
                $deposit = \App\Models\Deposit::with('user', 'depositable')
                    ->where(function ($q) use ($trxId, $baseTrx) {
                        $q->where('trx_id', $trxId)
                          ->orWhere('trx_id', $baseTrx)
                          ->orWhere('payment_id', $trxId);
                    })
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
            $queryTrx = $deposit->payment_id ?: $deposit->trx_id;
            $statusUrl = self::BASE_URL . '/payment_link_status?transaction_id=' . urlencode($queryTrx);
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
