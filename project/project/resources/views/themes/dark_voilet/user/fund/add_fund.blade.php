@extends(template().'layouts.user')
@section('title',trans('Add Fund'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("add fund")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("add fund")</li>
                </ol>
            </nav>
        </div>

        <form action="{{ route('user.payment.request') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-lg-7 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-15 ms-1">@lang("Select payment option to add balance to your funds.")</h4>
                            <div class="payment-section">
                                <ul class="payment-container-list">
                                    @forelse($gateways as $key => $gateway)
                                        <li class="item">
                                            <input class="form-check-input select-payment-method" type="radio"
                                                   name="gateway_id"
                                                   id="{{ $gateway->name }}"
                                                   value="{{ $gateway->id }}" {{ $key == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $gateway->name }}">
                                                <div class="image-area">
                                                    <img src="{{ getFile($gateway->driver, $gateway->image) }}"
                                                         alt="Gateway Image">
                                                </div>
                                                <div class="content-area">
                                                    <h5>@lang($gateway->name)</h5>
                                                    <span>
                                                        @lang($gateway->description)
                                                    </span>
                                                </div>
                                            </label>
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-12 side-bar">
                    <div class="deposit-info-box d-none d-lg-block">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <label class="form-label" for="amount">@lang("Enter Amount")</label>
                                        <input type="number" class="form-control" name="amount"
                                               id="amount"
                                               placeholder="0.00" step="0.0000000001" autocomplete="off"/>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-12 fiat-currency">
                                        <label class="form-label">@lang("Supported Currency")</label>
                                        <select class="cmn-select2" name="supported_currency" id="supported_currency">
                                            <option value="" disabled selected>@lang("Select Currency")</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 crypto-currency">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="transfer-details-section">
                                    <h5 class="title">@lang("Deposit Summery")</h5>
                                    <ul class="transfer-list show-deposit-summery ">

                                    </ul>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input agree-checked" type="checkbox" value=""
                                               id="Yes, i have confirmed the order!">
                                        <label class="form-check-label" for="Yes, i have confirmed the order!">
                                            @lang("I agree to the") <a href="{{ url('terms-and-conditions') }}"
                                                                       class="link">@lang("terms and conditions.")</a>
                                        </label>
                                    </div>
                                    <div class="payment-btn-group">
                                        <a href="{{ route('user.dashboard') }}" class="delete-btn rounded-1 w-50">
                                            @lang("cancel this deposit")</a>
                                        <button type="submit" class="cmn-btn rounded-1 w-50">@lang("confirm and continue")
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="paymentModal">
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                         aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="staticBackdropLabel">@lang('Payment Info')</h4>
                                    <button type="button" class="cmn-btn-close text-danger" data-bs-dismiss="modal" aria-label="Close">
                                        <i class="fa-light fa-xmark"></i>
                                    </button>
                                </div>
                                <div class="modal-body" id="paymentModalBody">


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </main>
@endsection


@push("style")
    <style>
        .condition_link, .condition_link:hover{
            color: var(--primary);
        }
        .form-control{
            background-color: var(--bg-color1);
            border: 1px solid var(--border-color1);
        }

        .form-control:focus{
            background-color: var(--bg-color1);
            border: 1px solid var(--primary);
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            $('.crypto-select').select2();

            let amountStatus = false;
            let selectedGateway = "";

            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid')
                $(fieldId).removeClass('is-invalid')
                $(fieldId).closest('div').find(".invalid-feedback").html('');
                $(fieldId).closest('div').find(".is-valid").html('');
            }

            let isGatewayChecked = $(".select-payment-method").is(":checked");
            if (isGatewayChecked) {
                selectedGateway = $('.select-payment-method').val();
                supportCurrency(selectedGateway);
            }


            $(document).on('click', '.select-payment-method', function () {
                let id = this.id;
                $('#paymentModalBody').html('');

                selectedGateway = $(this).val();
                let updatedWidth = window.innerWidth;
                window.addEventListener('resize', () => {
                    updatedWidth = window.innerWidth;
                });

                let html = `
                     <div class="card mb-3">
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <label class="form-label" for="amount">@lang("Enter Amount")</label>
                                        <input type="number" class="form-control" name="amount"
                                               id="amount"
                                               placeholder="0.00" step="0.0000000001" autocomplete="off"/>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="col-md-12 fiat-currency">
                                        <label class="form-label">@lang("Supported Currency")</label>
                                        <select class="cmn-select2" name="supported_currency" id="supported_currency">
                                            <option value="" disabled selected>@lang("Select Currency")</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12 crypto-currency">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="transfer-details-section">
                                    <h5 class="title">@lang("Deposit Summery")</h5>
                                    <ul class="transfer-list show-deposit-summery ">

                                    </ul>
                                    <div class="form-check ms-3">
                                        <input class="form-check-input agree-checked" type="checkbox" value=""
                                               id="Yes, i have confirmed the order!">
                                        <label class="form-check-label" for="Yes, i have confirmed the order!">
                                            @lang("I agree to the") <a href="{{ url('terms-and-conditions') }}"
                                                                       class="link">@lang("terms and conditions.")</a>
                                        </label>
                                    </div>
                                    <div class="payment-btn-group">
                                        <a href="{{ route('user.dashboard') }}" class="delete-btn rounded-1 w-50">
                                            @lang("cancel this deposit")</a>
                                        <button type="submit" class="cmn-btn rounded-1 w-50">@lang("confirm and continue")
                </button>
            </div>
        </div>
    </div>
</div>`;

                if (updatedWidth <= 991) {
                    $('.side-bar').html('');
                    $('#paymentModalBody').html(html);
                    let paymentModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                    paymentModal.show();
                } else {
                    $('.side-bar').html(html);
                }

                $('#supported_currency').select2({
                    placeholder: "@lang('Select Currency')",
                    width: '100%'
                });

                supportCurrency(selectedGateway);
            });

            function supportCurrency(selectedGateway) {
                if (!selectedGateway) {
                    console.error('Selected Gateway is undefined or null.');
                    return;
                }

                $('#supported_currency').empty();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('user.supported.currency') }}",
                    data: {gateway: selectedGateway},
                    type: "GET",
                    success: function (response) {
                        let amountField = $('#amount');
                        if (response.data == "") {
                            let markup = `<option value="USD">USD</option>`;
                            $('#supported_currency').append(markup);
                        }

                        let markup = '<option value="">Selected Currency</option>';
                        $('#supported_currency').append(markup);

                        if (response.currencyType == 1) {
                            $('.fiat-currency').show();
                            $('.crypto-currency').hide();
                            $(response.data).each(function (index, value) {
                                let selected = index == 0 ? ' selected' : '';
                                let markup = `<option value="${value}"${selected}>${value}</option>`;
                                $('#supported_currency').append(markup);
                            });

                            if (response.min_amount < 10) {
                                amountField.val(10);
                                $(amountField).addClass('is-valid');
                            } else {
                                amountField.val(response.min_amount);
                                $(amountField).addClass('is-valid');
                            }
                            let amount = $('#amount').val();
                            checkAmount(amount, response.currency, selectedGateway)
                        } else {
                            let markup = `<option value="USD">USD</option>`;
                            $('#supported_currency').append(markup);
                        }


                        if (response.currencyType == 0) {
                            $('.fiat-currency').hide();
                            $('.crypto-currency').show();
                            let markupCrypto = ` <label class="form-label">@lang("Select Crypto Currency")</label>
                                        <select class="form-control crypto-select"
                                                name="supported_crypto_currency"
                                                id="supported_crypto_currency">
                                              <option value="">@lang("Selected Crypto Currency")</option>
                                        </select>`;
                            $('.crypto-currency').empty().append(markupCrypto);

                            $(response.data).each(function (index, value) {
                                let selected = index == 0 ? ' selected' : '';
                                let markupOption = `<option value="${value}" ${selected}>${value}</option>`;
                                $('#supported_crypto_currency').append(markupOption);
                            });

                            $('#supported_crypto_currency').select2({
                                placeholder: "@lang('Select Crypto Currency')",
                            });

                            $('#amount').val(response.min_amount);
                            $('#amount').addClass('is-valid');
                            let amount = $('#amount').val();
                            checkAmount(amount, response.currency, selectedGateway, response.currency)
                        }
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }

            $(document).on('change, input', "#amount, #supported_currency, .select-payment-method, #supported_crypto_currency", function (e) {

                let amount = $('#amount').val();
                let selectedCurrency = $('#supported_currency').val();
                let selectedCryptoCurrency = $('#supported_crypto_currency').val();
                let currency_type = 1;

                if (!isNaN(amount) && amount > 0) {
                    let fraction = amount.split('.')[1];
                    let limit = currency_type == 0 ? 8 : 2;

                    if (fraction && fraction.length > limit) {
                        amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                        $('#amount').val(amount);
                    }
                    checkAmount(amount, selectedCurrency, selectedGateway, selectedCryptoCurrency)
                } else {
                    let amountField = $('#amount');
                    clearMessage(amountField)
                }
            });

            function checkAmount(amount, selectedCurrency, selectGateway, selectedCryptoCurrency = null) {
                $.ajax({
                    method: "GET",
                    url: "{{ route('user.deposit.checkAmount') }}",
                    dataType: "json",
                    data: {
                        'amount': amount,
                        'selected_currency': selectedCurrency,
                        'select_gateway': selectGateway,
                        'selectedCryptoCurrency': selectedCryptoCurrency,
                    }
                }).done(function (response) {
                    let amountField = $('#amount');
                    if (response.status) {

                        clearMessage(amountField);
                        $(amountField).addClass('is-valid');
                        $(amountField).closest('div').find(".valid-feedback").html(response.message);
                        $('.cmn-btn').removeClass('d-none').addClass('d-block');
                        $('.form-check').removeClass('d-none').addClass('d-block');
                        amountStatus = true;
                        let base_currency = "{{ basicControl()->base_currency }}"
                        showSummery(response, base_currency);
                    } else {
                        amountStatus = false;
                        clearMessage(amountField);
                        $(amountField).addClass('is-invalid');
                        $(amountField).closest('div').find(".invalid-feedback").html(response.message);
                    }
                });
            }

            function showSummery(response, currency) {
                let formattedAmount = response.amount.toFixed(2);
                let formattedChargeAmount = response.charge.toFixed(2);
                let formattedPayableAmount = response.payable_amount.toFixed(2);

                let updatedWidth = window.innerWidth;
                window.addEventListener('resize', () => {
                    updatedWidth = window.innerWidth;
                });

                let depositSummery = `
                                    <li class="item">
                                        <span>@lang("Amount")</span>
                                        <h5>${formattedAmount} ${response.currency}</h5>
                                    </li>
                                    <li class="item">
                                        <span>@lang("Charge")</span>
                                        <span>${formattedChargeAmount} ${response.currency}</span>
                                    </li>
                                    <li class="item">
                                        <h6><a href="javascript:void(0)">@lang("Payable Amount")</a></h6>
                                        <span>${formattedPayableAmount} ${response.currency}</span>
                                    </li>
                                    <li class="item">
                                        <span>@lang("Exchange Rate")</span>
                                        <span> 1 ${currency}
                                                            <i class="fa-light fa-arrow-right-arrow-left fa-sm ms-1 me-1"></i>
                                                        ${response.conversion_rate} ${response.currency}
                                        </span>
                                    </li>
                                    <li class="item">
                                        <span>@lang("Payable Amount") <sub>(@lang("In Base Currency"))</sub></span>
                                        <h5>${response.amount_in_base_currency} ${currency}</h5>
                                    </li>`;
                $('.show-deposit-summery').html(depositSummery);
            }
        });



        isAgree();

        $(document).on('click', '.agree-checked', function () {
            isAgree();
        });

        function isAgree() {
            let isAgreeChecked = $(".agree-checked").is(":checked");
            $('.payment-btn-group .cmn-btn').attr('disabled', !isAgreeChecked);
        }
    </script>
@endpush






