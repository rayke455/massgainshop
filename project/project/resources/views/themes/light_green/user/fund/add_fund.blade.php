@extends(template().'layouts.user')
@section('title',trans('Add Fund'))
@section('content')
    @php
        $zkCurrencyModel = auth()->user()->currency ? \App\Models\Currency::where('code', auth()->user()->currency)->first() : null;
        $zkBalance = $zkCurrencyModel
            ? currencyPositionBySelectedCurrency(auth()->user()->balance * $zkCurrencyModel->conversion_rate, auth()->user()->currency)
            : currencyPosition(auth()->user()->balance);
    @endphp
    <div class="dashboard-wrapper payment-section">
        <div class="container">
            <div class="zk-welcome">
                <div>
                    <h1 class="zk-welcome__title">@lang('Add funds')</h1>
                    <p class="zk-welcome__sub">@lang('Current balance'): <strong class="notranslate">{{ $zkBalance }}</strong></p>
                </div>
                <a href="{{ route('user.fund.index') }}" class="zk-btn zk-btn--ghost">
                    <i class="fa-regular fa-receipt"></i> @lang('Deposit history')
                </a>
            </div>

            @if(request()->filled('amount'))
                <div class="alert alert-info d-flex align-items-center gap-2" role="status">
                    <i class="fa-regular fa-circle-info"></i>
                    <span>@lang('Top up to finish your order. We filled in the missing amount — your order details are saved.')
                        <a href="{{ route('user.order.create') }}">@lang('Back to order')</a></span>
                </div>
            @endif
            <form action="{{ route('user.payment.request') }}" method="post" enctype="multipart/form-data" id="zkFundForm">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4><span class="zk-step-no">1</span>@lang('How much?')</h4>
                            </div>
                            <div class="card-body">
                                <label class="form-label" for="amount">@lang('Amount')</label>
                                <div class="zk-amount">
                                    <input type="number" class="form-control" name="amount" id="amount"
                                           placeholder="0.00" step="0.01" min="0" inputmode="decimal" autocomplete="off"/>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="zk-chips" role="group" aria-label="@lang('Quick amounts')">
                                    @foreach([5, 10, 25, 50, 100] as $preset)
                                        <button type="button" class="zk-chip" data-amount="{{ $preset }}">{{ $preset }}</button>
                                    @endforeach
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6 fiat-currency">
                                        <label class="form-label" for="supported_currency">@lang('Pay in')</label>
                                        <select class="form-select" name="supported_currency" id="supported_currency">
                                            <option value="" disabled selected>@lang('Select currency')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 crypto-currency"></div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4><span class="zk-step-no">2</span>@lang('Payment method')</h4>
                            </div>
                            <div class="card-body">
                                <div class="zk-gateways">
                                    @forelse($gateways as $key => $gateway)
                                        <label class="zk-gateway">
                                            <input class="select-payment-method" type="radio" name="gateway_id"
                                                   value="{{ $gateway->id }}" {{ $key == 0 ? 'checked' : '' }}>
                                            <span class="zk-gateway__box">
                                                <img src="{{ getFile($gateway->driver, $gateway->image) }}" alt="">
                                                <span class="zk-gateway__text">
                                                    <strong>@lang($gateway->name)</strong>
                                                    @if($gateway->description)
                                                        <small>@lang(\Illuminate\Support\Str::limit(strip_tags($gateway->description), 70))</small>
                                                    @endif
                                                </span>
                                                <i class="fa-solid fa-circle-check zk-gateway__tick"></i>
                                            </span>
                                        </label>
                                    @empty
                                        <div class="zk-empty">
                                            <span class="zk-empty__icon"><i class="fa-regular fa-credit-card"></i></span>
                                            <h3>@lang('No payment methods yet')</h3>
                                            <p>@lang('Please contact support to add funds.')</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card zk-sticky">
                            <div class="card-header"><h4>@lang('Summary')</h4></div>
                            <div class="card-body transfer-details-section">
                                <ul class="zk-summary show-deposit-summery">
                                    <li class="zk-summary__empty">@lang('Enter an amount to see the total.')</li>
                                </ul>
                                <div class="form-check mt-3">
                                    <input class="form-check-input agree-checked" type="checkbox" id="zkAgree">
                                    <label class="form-check-label" for="zkAgree">
                                        @lang('I agree to the') <a href="{{ url('terms-and-conditions') }}" target="_blank">@lang('terms and conditions')</a>
                                    </label>
                                </div>
                                <div class="payment-btn-group mt-3">
                                    <button type="submit" class="zk-btn zk-btn--primary zk-btn--lg w-100 zk-pay-btn" disabled>
                                        @lang('Continue to payment') <i class="fa-regular fa-arrow-right"></i>
                                    </button>
                                    <a href="{{ route('user.dashboard') }}" class="zk-cancel-link">@lang('Cancel')</a>
                                </div>
                                <p class="zk-secure"><i class="fa-regular fa-lock"></i> @lang('Payments are processed securely by the provider.')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            let selectedGateway = $('.select-payment-method:checked').val() || '';
            const amountField = $('#amount');

            // Coming from "Not enough balance" on the order page: pre-fill the missing amount.
            const presetAmount = parseFloat(new URLSearchParams(window.location.search).get('amount'));
            if (presetAmount > 0) {
                amountField.val(presetAmount);
            }

            function clearMessage(field) {
                field.removeClass('is-valid is-invalid');
                field.closest('div').find('.invalid-feedback').html('');
            }

            function resetSummary(message) {
                $('.show-deposit-summery').html(`<li class="zk-summary__empty">${message}</li>`);
                refreshPayButton(false);
            }

            let amountOk = false;
            function refreshPayButton(ok) {
                if (typeof ok === 'boolean') amountOk = ok;
                $('.zk-pay-btn').prop('disabled', !(amountOk && $('.agree-checked').is(':checked') && selectedGateway));
            }

            if (selectedGateway) {
                supportCurrency(selectedGateway);
            }

            // Switching method keeps the amount the user typed.
            $(document).on('change', '.select-payment-method', function () {
                selectedGateway = $(this).val();
                supportCurrency(selectedGateway);
            });

            $(document).on('click', '.zk-chip', function () {
                $('.zk-chip').removeClass('active');
                $(this).addClass('active');
                amountField.val($(this).data('amount')).trigger('input');
            });

            function supportCurrency(gatewayId) {
                $('#supported_currency').empty();
                $.ajax({
                    url: "{{ route('user.supported.currency') }}",
                    data: {gateway: gatewayId},
                    type: "GET",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        const minAmount = parseFloat(response.min_amount) || 0;
                        if (!amountField.val() || parseFloat(amountField.val()) < minAmount) {
                            amountField.val(Math.max(minAmount, 10));
                        }

                        if (response.currencyType == 0) {
                            $('.fiat-currency').hide();
                            $('.crypto-currency').show().html(`
                                <label class="form-label" for="supported_crypto_currency">@lang("Pay with")</label>
                                <select class="form-select" name="supported_crypto_currency" id="supported_crypto_currency"></select>`);
                            $(response.data).each(function (index, value) {
                                $('#supported_crypto_currency').append(`<option value="${value}" ${index == 0 ? 'selected' : ''}>${value}</option>`);
                            });
                            checkAmount(amountField.val(), response.currency, gatewayId, $('#supported_crypto_currency').val());
                        } else {
                            $('.crypto-currency').hide().empty();
                            $('.fiat-currency').show();
                            const list = (response.data && response.data.length) ? response.data : ['USD'];
                            $(list).each(function (index, value) {
                                $('#supported_currency').append(`<option value="${value}" ${index == 0 ? 'selected' : ''}>${value}</option>`);
                            });
                            checkAmount(amountField.val(), $('#supported_currency').val(), gatewayId);
                        }
                    },
                    error: function () {
                        resetSummary("@lang('Could not load this payment method. Please try another one.')");
                    }
                });
            }

            $(document).on('input change', '#amount, #supported_currency, #supported_crypto_currency', function () {
                let amount = amountField.val();
                if (this.id === 'amount') {
                    $('.zk-chip').removeClass('active');
                    $(`.zk-chip[data-amount="${parseFloat(amount)}"]`).addClass('active');
                }
                if (!isNaN(amount) && amount > 0) {
                    const parts = String(amount).split('.');
                    if (parts[1] && parts[1].length > 2) {
                        amount = (Math.floor(amount * 100) / 100).toFixed(2);
                        amountField.val(amount);
                    }
                    checkAmount(amount, $('#supported_currency').val(), selectedGateway, $('#supported_crypto_currency').val());
                } else {
                    clearMessage(amountField);
                    resetSummary("@lang('Enter an amount to see the total.')");
                }
            });

            function checkAmount(amount, selectedCurrency, gatewayId, selectedCryptoCurrency = null) {
                $.ajax({
                    method: "GET",
                    url: "{{ route('user.deposit.checkAmount') }}",
                    dataType: "json",
                    data: {
                        amount: amount,
                        selected_currency: selectedCurrency,
                        select_gateway: gatewayId,
                        selectedCryptoCurrency: selectedCryptoCurrency,
                    }
                }).done(function (response) {
                    clearMessage(amountField);
                    if (response.status) {
                        amountField.addClass('is-valid');
                        showSummary(response, "{{ basicControl()->base_currency }}");
                        refreshPayButton(true);
                    } else {
                        amountField.addClass('is-invalid');
                        amountField.closest('div').find('.invalid-feedback').html(response.message);
                        resetSummary(response.message);
                    }
                });
            }

            function showSummary(response, baseCurrency) {
                const fmt = n => Number(n).toFixed(2);
                let rows = `
                    <li><span>@lang("Amount")</span><span>${fmt(response.amount)} ${response.currency}</span></li>
                    <li><span>@lang("Fee")</span><span>${fmt(response.charge)} ${response.currency}</span></li>`;
                if (response.currency !== baseCurrency) {
                    rows += `<li><span>@lang("Rate")</span><span>1 ${baseCurrency} = ${response.conversion_rate} ${response.currency}</span></li>`;
                }
                rows += `
                    <li class="zk-summary__total"><span>@lang("You pay")</span><strong>${fmt(response.payable_amount)} ${response.currency}</strong></li>
                    <li class="zk-summary__credit"><span>@lang("Added to balance")</span><span>${response.amount_in_base_currency} ${baseCurrency}</span></li>`;
                $('.show-deposit-summery').html(rows);
            }

            $(document).on('change', '.agree-checked', () => refreshPayButton());
        });
    </script>
@endpush
