@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name ?? 'Swychr Pay') }}
@endsection
@section('content')
    @php
        $zkUser = auth()->user();
        $countries = \App\Http\Controllers\SwychrController::$countries;
        $gw = $deposit->gateway;

        // Auto-detect default country: currency chosen by customer takes priority!
        $currMap = [
            'NGN' => 'NG', 'KES' => 'KE', 'UGX' => 'UG', 'TZS' => 'TZ',
            'RWF' => 'RW', 'GHS' => 'GH', 'XAF' => 'CM', 'XOF' => 'CI',
            'ZAR' => 'ZA', 'ZMW' => 'ZM', 'CDF' => 'CD',
        ];
        $currency = strtoupper((string) ($deposit->payment_method_currency ?? ''));
        $userDial = preg_replace('/\D+/', '', (string) ($zkUser?->phone_code ?? ''));

        if (!empty($currency) && isset($currMap[$currency])) {
            $defaultCountry = $currMap[$currency];
        } elseif (!empty($userDial)) {
            $defaultCountry = 'KE';
            foreach ($countries as $cCode => $cInfo) {
                if ($cInfo['dial'] === $userDial) {
                    $defaultCountry = $cCode;
                    break;
                }
            }
        } else {
            $defaultCountry = 'KE';
        }

        $selectedCountry = old('country_code', $defaultCountry);

        // Precompute charge amounts and formatted labels for each country
        $countryData = [];
        foreach ($countries as $cCode => $cInfo) {
            $charge = \App\Http\Controllers\SwychrController::calculateChargeAmount($deposit, $cCode, $gw);
            $isZeroDecimal = in_array($charge['currency'], \App\Http\Controllers\SwychrController::$zeroDecimalCurrencies);
            $formattedAmount = ($isZeroDecimal ? number_format($charge['amount'], 0) : number_format($charge['amount'], 2)) . ' ' . $charge['currency'];
            $countryData[$cCode] = [
                'dial'      => $cInfo['dial'],
                'example'   => $cInfo['example'],
                'channels'  => $cInfo['channels'],
                'flag'      => $cInfo['flag'],
                'name'      => $cInfo['name'],
                'currency'  => $charge['currency'],
                'symbol'    => $charge['symbol'],
                'rate'      => $charge['rate'],
                'amount'    => $charge['amount'],
                'formatted' => $formattedAmount,
            ];
        }

        $selectedCountryInfo = $countryData[$selectedCountry] ?? ($countryData['KE'] ?? reset($countryData));

        // Pre-fill phone ONLY if user's saved country/dial code matches this country
        $rawPhone = preg_replace('/\D+/', '', (string) ($zkUser?->phone ?? ''));
        $phoneVal = old('phone');
        if ($phoneVal === null && !empty($rawPhone) && !empty($userDial) && $userDial === $selectedCountryInfo['dial']) {
            $cDial = $selectedCountryInfo['dial'];
            if (str_starts_with($rawPhone, $cDial)) {
                $phoneVal = '0' . substr($rawPhone, strlen($cDial));
            } elseif (str_starts_with($rawPhone, '0')) {
                $phoneVal = $rawPhone;
            } else {
                $phoneVal = '0' . $rawPhone;
            }
        }
    @endphp

    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card zk-mpesa">
                        <div class="card-body text-center">
                            <img class="zk-mpesa__logo"
                                 src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                 alt="{{ optional($deposit->gateway)->name ?? 'Swychr Pay' }}">
                            <p class="zk-mpesa__label">@lang('Amount to pay')</p>
                            <h1 class="zk-mpesa__amount" id="displayPayAmount">{{ $selectedCountryInfo['formatted'] }}</h1>
                            <p class="zk-mpesa__credit">@lang('Adds') <strong><span class="notranslate">{{ currencyPosition($deposit->payable_amount_in_base_currency) }}</span></strong> @lang('to your balance')</p>

                            @if(session('error'))
                                <div class="alert alert-danger text-start small mb-3" role="alert">
                                    <i class="fa-regular fa-circle-exclamation me-1"></i> {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('swychr.pay', $deposit->trx_id) }}" method="post" class="zk-swychr__form text-start" id="zkSwychrForm">
                                @csrf

                                <div class="mb-3">
                                    <label for="countryCode" class="form-label fw-semibold">
                                        <i class="fa-regular fa-earth-africa me-1 text-primary"></i> @lang('Choose your country')
                                    </label>
                                    <select name="country_code" id="countryCode" class="form-select @error('country_code') is-invalid @enderror" required>
                                        @foreach($countryData as $code => $country)
                                            <option value="{{ $code }}"
                                                    data-dial="{{ $country['dial'] }}"
                                                    data-example="{{ $country['example'] }}"
                                                    data-channels="{{ $country['channels'] }}"
                                                    data-formatted="{{ $country['formatted'] }}"
                                                    data-currency="{{ $country['currency'] }}"
                                                    data-amount="{{ $country['amount'] }}"
                                                    {{ $selectedCountry === $code ? 'selected' : '' }}>
                                                {{ $country['flag'] }} {{ $country['name'] }} (+{{ $country['dial'] }}) — {{ $country['formatted'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phoneNumber" class="form-label fw-semibold">
                                        <i class="fa-regular fa-mobile-notch me-1 text-primary"></i> @lang('Mobile Money / Phone number')
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text font-monospace fw-bold" id="dialPrefix">+{{ $selectedCountryInfo['dial'] }}</span>
                                        <input type="tel" id="phoneNumber" name="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ $phoneVal }}"
                                               placeholder="{{ $selectedCountryInfo['example'] }}"
                                               inputmode="tel" autocomplete="tel" required>
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-1 d-flex align-items-center flex-wrap gap-1">
                                        <span class="badge bg-light text-dark border"><i class="fa-solid fa-bolt text-warning"></i> @lang('Channels'):</span>
                                        <span id="channelBadge" class="text-muted small">{{ $selectedCountryInfo['channels'] }}</span>
                                    </div>
                                </div>

                                <button type="submit" class="zk-btn zk-btn--primary zk-btn--lg w-100 mt-3" id="zkSwychrPay">
                                    <i class="fa-solid fa-lock me-1"></i> @lang('Proceed to Payment')
                                </button>

                                <div class="zk-swychr__channels text-center mt-3">
                                    <small class="text-muted d-block mb-1">@lang('Accepted payment methods'):</small>
                                    <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                        <span class="badge bg-light text-secondary border">M-Pesa</span>
                                        <span class="badge bg-light text-secondary border">MTN MoMo</span>
                                        <span class="badge bg-light text-secondary border">Airtel Money</span>
                                        <span class="badge bg-light text-secondary border">Orange Money</span>
                                        <span class="badge bg-light text-secondary border">Wave</span>
                                        <span class="badge bg-light text-secondary border">Card & Bank</span>
                                    </div>
                                </div>

                                <p class="zk-secure text-center mt-3 mb-0">
                                    <i class="fa-solid fa-shield-halved text-success me-1"></i>
                                    @lang('Secured by Swychr Connect. We never store your credentials.')
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .zk-mpesa { max-width: 480px; margin: 0 auto; }
        .zk-mpesa .card-body { padding: 32px 28px; }
        .zk-mpesa__logo { width: 72px; height: 72px; object-fit: contain; border-radius: 16px; margin-bottom: 14px; }
        .zk-mpesa__label { margin: 0; color: var(--zk-muted); font-size: 14px; font-weight: 600; }
        .zk-mpesa__amount { font-size: 32px; font-weight: 800; margin: 4px 0; text-transform: none; }
        .zk-mpesa__credit { color: var(--zk-muted); font-size: 14px; margin-bottom: 22px; }
        .zk-swychr__form .form-select { height: 50px; font-size: 15px; font-weight: 600; }
        .zk-swychr__form .input-group-text { font-size: 16px; background-color: #f1f5f9; }
        .zk-swychr__form .form-control { height: 50px; font-size: 16px; font-weight: 600; }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.getElementById('countryCode');
            const dialPrefix = document.getElementById('dialPrefix');
            const phoneInput = document.getElementById('phoneNumber');
            const channelBadge = document.getElementById('channelBadge');
            const displayAmount = document.getElementById('displayPayAmount');
            const form = document.getElementById('zkSwychrForm');
            const payBtn = document.getElementById('zkSwychrPay');

            if (countrySelect) {
                let currentDial = dialPrefix ? dialPrefix.textContent.replace('+', '').trim() : '';
                countrySelect.addEventListener('change', function () {
                    const opt = this.options[this.selectedIndex];
                    if (opt) {
                        const dial = opt.getAttribute('data-dial');
                        const example = opt.getAttribute('data-example');
                        const channels = opt.getAttribute('data-channels');
                        const formatted = opt.getAttribute('data-formatted');
                        if (dialPrefix) dialPrefix.textContent = '+' + dial;
                        if (displayAmount && formatted) displayAmount.textContent = formatted;
                        if (phoneInput) {
                            phoneInput.placeholder = example || '0712 345 678';
                            if (currentDial && dial !== currentDial) {
                                phoneInput.value = '';
                            }
                        }
                        if (channelBadge) channelBadge.textContent = channels || '';
                        currentDial = dial;
                    }
                });
            }

            if (form && payBtn) {
                form.addEventListener('submit', function () {
                    payBtn.disabled = true;
                    payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> @lang("Connecting to Swychr...")';
                });
            }
        });
    </script>
@endpush
