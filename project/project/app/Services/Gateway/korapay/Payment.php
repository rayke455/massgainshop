<?php

namespace App\Services\Gateway\korapay;

use Illuminate\Support\Facades\Http;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $params = [
            'amount' => $deposit->payable_amount,
            'redirect_url' => route('kora.payment.verify'),
            'currency' => $deposit->payment_method_currency,
            'reference' => $deposit->trx_id,
            'narration' => 'Payment SMM Service',
            'merchant_bears_cost' => false,
            'customer' => [
                'name' => $deposit->user->firstname . ' ' . $deposit->user->lastname,
                'email' => $deposit->user->email,
            ],
        ];

        $response = Http::withToken(optional($gateway->parameters)->secret_key)
            ->post('https://api.korapay.com/merchant/api/v1/charges/initialize', $params);

        if ($response->successful() && isset($response['data']['checkout_url'])) {
            return json_encode([
                'redirect' => true,
                'redirect_url' => $response['data']['checkout_url'],
            ]);
        }

        return json_encode([
            'error' => true,
            'message' => $json['errors'][0]['detail'] ?? 'Payment initialization failed.',
        ]);
    }
}
