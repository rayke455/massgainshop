<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Facades\App\Services\BasicService;

class KoraPayPaymentController extends Controller
{
    public function verifyPayment(Request $request)
    {
        $reference = $request->get('reference');

        if (!$reference) {
            return redirect()->route('failed')->with('error', 'Payment not completed.');
        }

        $gateway = Gateway::where('code', 'korapay')->first();

        $response = Http::withToken($gateway->parameters->secret_key)
            ->get("https://api.korapay.com/merchant/api/v1/charges/{$reference}");

        $verify = $response->json();

        if (isset($verify['data']['status']) && $verify['data']['status'] === 'success') {

            $deposit = Deposit::where('trx_id', $reference)->first();

            BasicService::preparePaymentUpgradation($deposit);

            return redirect()->route('success')->with('message', 'Payment successful!');
        }

        return redirect()->route('failed')->with('error', 'Payment not completed.');
    }
}
