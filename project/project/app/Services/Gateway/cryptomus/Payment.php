<?php

namespace App\Services\Gateway\cryptomus;

use Illuminate\Support\Facades\Http;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $postParams = [
            'amount'      => (string) $deposit->payable_amount,
            'currency'    => $deposit->payment_method_currency,
            'order_id'    => (string) $deposit->trx_id,
            'url_return'  => route('user.add.fund'),
            'url_success' => route('user.order.create'),
            'url_callback'=> route('ipn', [$gateway->code, $deposit->trx_id]),
        ];

        $jsonData = json_encode($postParams, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $apiKey = trim($gateway->parameters->api_key ?? '');
        $merchantId = trim($gateway->parameters->mercent_id ?? '');
        $sign = md5(base64_encode($jsonData) . $apiKey);

        $response = Http::withHeaders([
            'merchant'     => $merchantId,
            'sign'         => $sign,
            'Content-Type' => 'application/json',
        ])->withBody($jsonData, 'application/json')->post('https://api.cryptomus.com/v1/payment');

        if ($response->successful() && isset($response['result']['url'])) {
            return json_encode([
                'redirect'      => true,
                'redirect_url'  => $response['result']['url'],
            ]);
        }

        \Illuminate\Support\Facades\Log::error('Cryptomus payment creation failed', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        return json_encode([
            'error'   => true,
            'message' => $response->json('message') ?? 'Unexpected Error! Please Try Again',
        ]);
    }


    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $postParams = [
            'order_id' => (string) $deposit->trx_id,
        ];

        $jsonData = json_encode($postParams, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $apiKey = trim($gateway->parameters->api_key ?? '');
        $merchantId = trim($gateway->parameters->mercent_id ?? '');
        $sign = md5(base64_encode($jsonData) . $apiKey);

        $response = Http::withHeaders([
            'merchant'     => $merchantId,
            'sign'         => $sign,
            'Content-Type' => 'application/json',
        ])->withBody($jsonData, 'application/json')->post('https://api.cryptomus.com/v1/payment/info');

        if ($response->successful() && isset($response['result']) &&
            in_array($response['result']['payment_status'], ['paid', 'paid_over'])) {

            BasicService::preparePaymentUpgradation($deposit);

            return [
                'status'   => 'success',
                'msg'      => 'Transaction was successful.',
                'redirect' => route('success'),
            ];
        }

        \Illuminate\Support\Facades\Log::warning('Cryptomus IPN check unfulfilled', [
            'order_id' => $deposit->trx_id,
            'response' => $response->json(),
        ]);

        return [
            'status'   => 'error',
            'msg'      => 'Unsuccessful transaction.',
            'redirect' => route('failed'),
        ];
    }
}
