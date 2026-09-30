<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Facades\App\Services\BasicService;

class MpesaController extends Controller
{
    public function getAccessToken($consumerKey, $consumerSecret, $environment)
    {
        if ($environment == 'live') {
            $baseUrl = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        } else {
            $baseUrl = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        }

        $response = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get($baseUrl);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Unable to generate access token: ' . $response->body());

    }

    public function generatePassword($shortCode, $passkey, $timestamp)
    {
        $data = $shortCode . $passkey . $timestamp;
        return base64_encode($data);
    }

    public function stkPush(Request $request, $trx_id)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^(?:\+?254|0)?(7\d{8})$/'],
        ]);

        $deposit = Deposit::with('gateway')->where('trx_id', $trx_id)->first();
        if (!$deposit)
            return back()->with('error', 'Deposit not found');

        try {
            $consumerKey = optional(optional($deposit->gateway)->parameters)->consumer_key;
            $consumerSecret = optional(optional($deposit->gateway)->parameters)->consumer_secret;
            $shortCode = optional(optional($deposit->gateway)->parameters)->short_code;
            $passkey = optional($deposit->gateway)->parameters->pass_key;
            $callbackUrl = optional(optional($deposit->gateway)->parameters)->webhook;
            $environment = optional($deposit->gateway)->environment;

            $accessToken = $this->getAccessToken($consumerKey, $consumerSecret, $environment);
            $timestamp = Carbon::now('Africa/Nairobi')->format('YmdHis');
            $password = $this->generatePassword($shortCode, $passkey, $timestamp);

            $payload = [
                "BusinessShortCode" => $shortCode,
                "Password" => $password,
                "Timestamp" => $timestamp,
                "TransactionType" => "CustomerPayBillOnline",
                "Amount" => round($deposit->payable_amount),
                "PartyA" => $request->phone,
                "PartyB" => $shortCode,
                "PhoneNumber" => $request->phone,
                "CallBackURL" => $callbackUrl,
                "AccountReference" => 'CompanyX SMM LTD',
                "TransactionDesc" => 'Payment of SMM Service',
            ];

            if ($environment == 'live') {
                $baseUrl = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            } else {
                $baseUrl = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            }

            $response = Http::withToken($accessToken)
                ->post($baseUrl, $payload);

            if ($response->successful()) {
                $res = $response->json();

                if (isset($res['ResponseCode']) && $res['ResponseCode'] == '0') {

                    $deposit->update(['payment_id' => $res['CheckoutRequestID']]);

                    return redirect()->route('success')->with('success', $res['CustomerMessage'] ?? 'STK Push initiated successfully.');
                }
                return redirect()->route('failed')->with('error', $res['errorMessage'] ?? 'Failed to initiate STK Push.');
            }

            return back()->with('error', 'Unable to connect to M-Pesa API');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }

    }


    public function webhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);

        $callback = $payload['Body']['stkCallback'] ? $request->Body['stkCallback'] : null;

        if (!$callback) {
            return response()->json(['message' => 'Invalid callback structure'], 400);
        }

        $resultCode = $callback['ResultCode'] ?? null;
        $checkoutRequestId = $callback['CheckoutRequestID'] ?? null;


        if ($resultCode === 0 && $checkoutRequestId) {

            $deposit = Deposit::where('payment_id', $checkoutRequestId)->first();

            if ($deposit) {
                BasicService::preparePaymentUpgradation($deposit);
                return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
            }
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Deposit not found']);
        }
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Received']);
    }

}
