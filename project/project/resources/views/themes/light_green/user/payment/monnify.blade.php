@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection

@push('style')
    <style>
        .gateway-img {
            border: 1px solid #f3f3f3;
            border-radius: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-7 col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                 class="gateway-img mb-3" width="120">
                            <h5>@lang('Please Pay') {{ getAmount($deposit->payable_amount) }} {{ $deposit->payment_method_currency }}</h5>
                            <button class="cmn-btn rounded-1 mt-3" onclick="payWithMonnify()">@lang('Pay Now')</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://sdk.monnify.com/plugin/monnify.js"></script>
    <script>
        'use strict';
        function payWithMonnify() {
            MonnifySDK.initialize({
                amount: {{ $data->amount }},
                currency: "{{ $data->currency }}",
                reference: "{{ $data->ref }}",
                customerName: "{{ $data->customer_name }}",
                customerEmail: "{{ $data->customer_email }}",
                customerMobileNumber: "{{ $data->customer_phone }}",
                apiKey: "{{ $data->api_key }}",
                contractCode: "{{ $data->contract_code }}",
                paymentDescription: "{{ $data->description }}",
                isTestMode: {{ optional($deposit->gateway)->environment == 'test' ? 'true' : 'false' }},
                onComplete: function (response) {
                    if (response.status === "PAID" || response.status === "SUCCESS") {
                        window.location.href = "{{ route('ipn', ['monnify', $data->ref]) }}";
                    } else {
                        window.location.href = "{{ route('failed') }}";
                    }
                },
                onClose: function (data) {
                }
            });
        }
    </script>
@endpush
