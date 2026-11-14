<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SwychrController extends Controller
{
    const BASE_URL = 'https://api.accountpe.com/api/payin';

    /**
     * Supported countries with ISO codes, dial codes, examples, and payment channels.
     */
    public static $countries = [
        'KE' => [
            'name'      => 'Kenya',
            'dial'      => '254',
            'flag'      => '🇰🇪',
            'example'   => '0712 345 678',
            'channels'  => 'M-Pesa, Airtel Money, Card',
        ],
        'UG' => [
            'name'      => 'Uganda',
            'dial'      => '256',
            'flag'      => '🇺🇬',
            'example'   => '0772 123 456',
            'channels'  => 'MTN Mobile Money, Airtel Money',
        ],
        'TZ' => [
            'name'      => 'Tanzania',
            'dial'      => '255',
            'flag'      => '🇹🇿',
            'example'   => '0754 123 456',
            'channels'  => 'M-Pesa, Airtel Money, Tigo, Halotel',
        ],
        'RW' => [
            'name'      => 'Rwanda',
            'dial'      => '250',
            'flag'      => '🇷🇼',
            'example'   => '0788 123 456',
            'channels'  => 'MTN Mobile Money, Airtel Money',
        ],
        'NG' => [
            'name'      => 'Nigeria',
            'dial'      => '234',
            'flag'      => '🇳🇬',
            'example'   => '0803 123 4567',
            'channels'  => 'Bank Transfer, Card, USSD',
        ],
        'GH' => [
            'name'      => 'Ghana',
            'dial'      => '233',
            'flag'      => '🇬🇭',
            'example'   => '024 123 4567',
            'channels'  => 'MTN, Telecel, AirtelTigo',
        ],
        'CM' => [
            'name'      => 'Cameroon',
            'dial'      => '237',
            'flag'      => '🇨🇲',
            'example'   => '671 23 45 67',
            'channels'  => 'MTN MoMo, Orange Money',
        ],
        'CI' => [
            'name'      => "Côte d'Ivoire",
            'dial'      => '225',
            'flag'      => '🇨🇮',
            'example'   => '07 12 34 56 78',
            'channels'  => 'Orange Money, MTN, Moov, Wave',
        ],
        'SN' => [
            'name'      => 'Senegal',
            'dial'      => '221',
            'flag'      => '🇸🇳',
            'example'   => '77 123 45 67',
            'channels'  => 'Orange Money, Wave, Free',
        ],
        'CD' => [
            'name'      => 'DR Congo',
            'dial'      => '243',
            'flag'      => '🇨🇩',
            'example'   => '081 234 5678',
            'channels'  => 'Vodacom M-Pesa, Airtel, Orange',
        ],
        'BJ' => [
            'name'      => 'Benin',
            'dial'      => '229',
            'flag'      => '🇧🇯',
            'example'   => '97 12 34 56',
            'channels'  => 'MTN Mobile Money, Moov',
        ],
        'TG' => [
            'name'      => 'Togo',
            'dial'      => '228',
            'flag'      => '🇹🇬',
            'example'   => '90 12 34 56',
            'channels'  => 'T-Money, Moov',
        ],
        'GA' => [
            'name'      => 'Gabon',
            'dial'      => '241',
            'flag'      => '🇬🇦',
            'example'   => '074 12 34 56',
            'channels'  => 'Airtel Money, Moov',
        ],
        'ZM' => [
            'name'      => 'Zambia',
            'dial'      => '260',
            'flag'      => '🇿🇲',
            'example'   => '097 123 4567',
            'channels'  => 'MTN, Airtel, Zamtel',
        ],
        'ZA' => [
            'name'      => 'South Africa',
            'dial'      => '27',
            'flag'      => '🇿🇦',
            'example'   => '071 234 5678',
            'channels'  => 'Card, Instant EFT',
        ],
    ];

    /**
     * Normalize a phone number to standard international digits without leading '+' or '0'.
     */
    public static function normalizePhone(?string $phone, string $countryCode): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if (empty($digits)) {
            return null;
        }

        $country = self::$countries[$countryCode] ?? null;
        $dial = $country ? $country['dial'] : '254';

        if (str_starts_with($digits, $dial)) {
            $normalized = $digits;
        } elseif (str_starts_with($digits, '0')) {
            $normalized = $dial . substr($digits, 1);
        } else {
            $normalized = $dial . $digits;
        }

        if (strlen($normalized) < 8 || strlen($normalized) > 16) {
            return null;
        }

        return $normalized;
    }

    /**
     * Submit payment request from the checkout confirmation view.
     */
    public function pay(Request $request, string $trx_id)
    {
        $request->validate([
            'country_code' => 'required|string|size:2',
            'phone'        => 'required|string|min:6|max:25',
        ]);

        $countryCode = strtoupper($request->input('country_code'));
        if (!isset(self::$countries[$countryCode])) {
            return back()->withInput()->with('error', __('Please select a valid country from the list.'));
        }

        $deposit = Deposit::with('gateway', 'user')
            ->where('trx_id', $trx_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($deposit->status == 1) {
            return redirect()->route('user.fund.index')->with('success', __('This deposit is already paid.'));
        }

        $normalizedPhone = self::normalizePhone($request->input('phone'), $countryCode);
        if (!$normalizedPhone) {
            $example = self::$countries[$countryCode]['example'] ?? '0712 345 678';
            return back()->withInput()->with('error', __("Please provide a valid mobile number for :country (e.g. :example).", [
                'country' => self::$countries[$countryCode]['name'],
                'example' => $example,
            ]));
        }

        // Country-specific length checks to prevent invalid provider calls
        if ($countryCode === 'NG' && strlen($normalizedPhone) !== 13) {
            return back()->withInput()->with('error', __('Please provide a valid 11-digit Nigerian phone number (e.g. 0803 123 4567 or 0701 234 5678).'));
        }
        if ($countryCode === 'KE' && strlen($normalizedPhone) !== 12) {
            return back()->withInput()->with('error', __('Please provide a valid Kenyan mobile number (e.g. 0712 345 678 or 0112 345 678).'));
        }

        $gateway = $deposit->gateway;
        $apiKey = trim($gateway->parameters->api_key ?? '');
        if (empty($apiKey)) {
            return back()->withInput()->with('error', __('Swychr Pay is not yet configured. Please contact support.'));
        }

        $user = auth()->user();
        $amount = (float) round($deposit->payable_amount, 2);
        $passCharge = !empty($gateway->parameters->pass_digital_charge) && (int) $gateway->parameters->pass_digital_charge === 1;

        $name = trim((string) (($user->firstname ?? '') . ' ' . ($user->lastname ?? '')));
        if (empty($name)) {
            $name = (string) ($user->username ?? 'Customer');
        }

        // Swychr requires transaction_id to be unique. If details change (e.g. customer switches country/phone),
        // we append a short hash of the details so Swychr doesn't reject with HTTP 409 "Transaction ID already used with different details".
        $detailHash = substr(md5($countryCode . ':' . $normalizedPhone . ':' . $amount), 0, 6);
        $swychrTrxId = $deposit->trx_id . '-' . $detailHash;

        $payload = [
            'country_code'        => $countryCode,
            'transaction_id'      => $swychrTrxId,
            'mobile'              => $normalizedPhone,
            'mobile_no'           => $normalizedPhone,
            'amount'              => $amount,
            'name'                => $name,
            'email'               => (string) ($user->email ?? 'customer@massfameshop.com'),
            'description'         => 'Deposit #' . $deposit->trx_id,
            'pass_digital_charge' => $passCharge,
            'callback_url'        => route('ipn', ['swychr', $deposit->trx_id]),
            'failed_callback_url' => route('failed'),
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
            $paymentLink = $res['data']['payment_link'] ?? $res['payment_link'] ?? null;

            if ($response->successful() && !empty($paymentLink)) {
                $deposit->update([
                    'payment_id' => $swychrTrxId,
                ]);
                return redirect()->away($paymentLink);
            }

            Log::error('Swychr create payment link failed in SwychrController', [
                'trx_id'   => $deposit->trx_id,
                'status'   => $response->status(),
                'response' => $res,
                'payload'  => $payload,
            ]);

            $errMsg = $res['message'] ?? __('Unable to initiate Swychr payment. Please check your phone number and try again.');
            return back()->withInput()->with('error', $errMsg);

        } catch (\Throwable $e) {
            Log::error('Swychr pay exception: ' . $e->getMessage(), [
                'trx_id' => $deposit->trx_id,
            ]);

            return back()->withInput()->with('error', __('Payment connection error: :msg', ['msg' => $e->getMessage()]));
        }
    }
}
