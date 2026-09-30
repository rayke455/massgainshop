@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')

    @push('style')
        <style>
            .main .card {
                margin-top: 150px;
            }
            .gateway-img {
                border-radius: 5px;
            }
        </style>
    @endpush

    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang('Pay with' . optional($deposit->gateway)->name)</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">@lang("Home")</a></li>
                    <li class="breadcrumb-item">@lang("add fund")</li>
                    <li class="breadcrumb-item active">@lang('Pay with' . optional($deposit->gateway)->name)</li>
                </ol>
            </nav>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-7 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-3">
                                <img
                                    src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                    class="gateway-img">
                            </div>
                            <div class="col-md-6">
                                <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                <button type="button" class="cmn-btn rounded-1" id="btn-confirm"
                                        onClick="payWithRave()">@lang('Pay Now')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('script')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script>
        'use strict';
        let btn = document.querySelector("#btn-confirm");
        btn.setAttribute("type", "button");
        const API_publicKey = "{{$data->API_publicKey }}";

        function payWithRave() {
            let x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: "{{ $data->customer_email }}",
                amount: "{{ $data->amount }}",
                customer_phone: "{{ $data->customer_phone }}",
                currency: "{{ $data->currency }}",
                txref: "{{ $data->txref }}",
                onclose: function () {
                },
                callback: function (response) {
                    let txref = response.tx.txRef;
                    let status = response.tx.status;
                    window.location = '{{ url('payment/flutterwave') }}/' + txref + '/' + status;
                }
            });
        }
    </script>
@endpush
