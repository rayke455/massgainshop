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
     * Map country ISO code to local currency.
     */
    public static $countryCurrencyMap = [
        'KE' => 'KES',
        'UG' => 'UGX',
        'TZ' => 'TZS',
        'RW' => 'RWF',
        'NG' => 'NGN',
        'GH' => 'GHS',
        'CM' => 'XAF',
        'CI' => 'XOF',
        'SN' => 'XOF',
        'CD' => 'CDF',
        'BJ' => 'XOF',
        'TG' => 'XOF',
        'GA' => 'XAF',
        'ZM' => 'ZMW',
        'ZA' => 'ZAR',
    ];

    /**
     * Fallback conversion rates (per USD) and currency symbols if not in DB.
     */
    public static $fallbackCurrencyInfo = [
        'USD' => ['rate' => 1.0,    'symbol' => '$'],
        'KES' => ['rate' => 130.0,  'symbol' => 'KSh'],
        'TZS' => ['rate' => 2650.0, 'symbol' => 'TSh'],
        'UGX' => ['rate' => 3700.0, 'symbol' => 'USh'],
        'RWF' => ['rate' => 1350.0, 'symbol' => 'FRw'],
        'NGN' => ['rate' => 1600.0, 'symbol' => '₦'],
        'GHS' => ['rate' => 15.50,  'symbol' => 'GH₵'],
        'XAF' => ['rate' => 615.0,  'symbol' => 'FCFA'],
        'XOF' => ['rate' => 615.0,  'symbol' => 'CFA'],
        'CDF' => ['rate' => 2800.0, 'symbol' => 'FC'],
        'ZMW' => ['rate' => 27.0,   'symbol' => 'ZK'],
        'ZAR' => ['rate' => 18.0,   'symbol' => 'R'],
    ];

    /**
     * Currencies that operate in whole numbers without decimal subdivisions.
     */
    public static $zeroDecimalCurrencies = [
        'TZS', 'UGX', 'RWF', 'XAF', 'XOF', 'CDF', 'KES', 'NGN'
    ];

    /**
     * Get currency info (currency code, rate per USD, symbol) for a country code.
     */
    public static function getCountryCurrencyInfo(string $countryCode, $gateway = null): array
    {
        $countryCode = strtoupper($countryCode);
        $curr = self::$countryCurrencyMap[$countryCode] ?? 'USD';
        return self::getCurrencyInfoByCode($curr, $gateway);
    }

    /**
     * Get currency info by ISO currency code (e.g. TZS, KES, USD).
     */
    public static function getCurrencyInfoByCode(string $currencyCode, $gateway = null): array
    {
        $curr = strtoupper($currencyCode);
        $fallback = self::$fallbackCurrencyInfo[$curr] ?? ['rate' => 1.0, 'symbol' => $curr];
        $rate = (float) $fallback['rate'];
        $symbol = (string) $fallback['symbol'];

        // 1. Check Gateway configured receivable_currencies
        if ($gateway && !empty($gateway->receivable_currencies)) {
            foreach ($gateway->receivable_currencies as $rc) {
                $rcName = strtoupper((string) ($rc->name ?? $rc->currency ?? ''));
                if ($rcName === $curr) {
                    if (!empty($rc->conversion_rate) && (float) $rc->conversion_rate > 0) {
                        $rate = (float) $rc->conversion_rate;
                    }
                    if (!empty($rc->currency_symbol)) {
                        $symbol = (string) $rc->currency_symbol;
                    }
                    return [
                        'currency' => $curr,
                        'rate'     => $rate,
                        'symbol'   => $symbol,
                    ];
                }
            }
        }

        // 2. Check global currencies table
        try {
            $dbCurr = \App\Models\Currency::where('code', $curr)->first();
            if ($dbCurr && (float) $dbCurr->conversion_rate > 0) {
                $rate = (float) $dbCurr->conversion_rate;
                $symbol = (string) ($dbCurr->symbol ?: $curr);
            }
        } catch (\Throwable $e) {}

        return [
            'currency' => $curr,
            'rate'     => $rate,
            'symbol'   => $symbol,
        ];
    }

    /**
     * Calculate the payable amount to send to Swychr in the target country's currency.
     */
    public static function calculateChargeAmount(Deposit $deposit, string $countryCode, $gateway = null): array
    {
        $countryCode = strtoupper($countryCode);
        $currInfo = self::getCountryCurrencyInfo($countryCode, $gateway);
        $targetCurrency = $currInfo['currency'];
        $targetRate = $currInfo['rate'];
        $symbol = $currInfo['symbol'];
        $isZeroDecimal = in_array($targetCurrency, self::$zeroDecimalCurrencies);

        // If deposit payment currency is already the target currency, use payable_amount directly
        if (strtoupper((string) $deposit->payment_method_currency) === $targetCurrency) {
            $amount = $isZeroDecimal ? round($deposit->payable_amount) : round($deposit->payable_amount, 2);
            return [
                'amount'   => (float) $amount,
                'currency' => $targetCurrency,
                'rate'     => $targetRate,
                'symbol'   => $symbol,
            ];
        }

        // Base amount in USD
        $baseAmount = (float) $deposit->payable_amount_in_base_currency;
        if ($baseAmount <= 0) {
            $depCurr = strtoupper((string) ($deposit->payment_method_currency ?: 'USD'));
            $depInfo = self::getCurrencyInfoByCode($depCurr, $gateway);
            $depRate = $depInfo['rate'] > 0 ? $depInfo['rate'] : 1.0;
            $baseAmount = (float) $deposit->payable_amount / $depRate;
        }

        $calculated = $baseAmount * $targetRate;
        $chargeAmount = $isZeroDecimal ? round($calculated) : round($calculated, 2);

        return [
            'amount'   => (float) $chargeAmount,
            'currency' => $targetCurrency,
            'rate'     => $targetRate,
            'symbol'   => $symbol,
        ];
    }

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

        // Calculate amount in the destination country's local currency
        $chargeInfo = self::calculateChargeAmount($deposit, $countryCode, $gateway);
        $amount = (float) $chargeInfo['amount'];
        $chargeCurrency = $chargeInfo['currency'];
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
            'description'         => 'Deposit #' . $deposit->trx_id . ' (' . $amount . ' ' . $chargeCurrency . ')',
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
