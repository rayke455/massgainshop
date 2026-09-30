@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    @push('style')
        <style>
            .payment-stripe .card{
                margin-top: 150px;
            }
            .gateway-img {
                border-radius: 5px;
            }

            .card {
                margin-top: 150px;
            }
        </style>
    @endpush

    <main id="main" class="main bg-color2 payment-stripe">
        <div class="pagetitle">
            <h3 class="mb-1">@lang('Pay with ' . optional($deposit->gateway)->name)</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">@lang("Home")</a></li>
                    <li class="breadcrumb-item">@lang("add fund")</li>
                    <li class="breadcrumb-item active">@lang('Pay with ' . optional($deposit->gateway)->name)</li>
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
                                <button type="button" id="btn-confirm" class="cmn-btn rounded-1">@lang('Pay Now')</button>
                                <form action="{{ route('ipn', [optional($deposit->gateway)->code, $deposit->trx_id]) }}"
                                      method="POST">
                                    @csrf
                                    <script
                                        src="//js.paystack.co/v1/inline.js"
                                        data-key="{{ $data->key }}"
                                        data-email="{{ $data->email }}"
                                        data-amount="{{$data->amount}}"
                                        data-currency="{{$data->currency}}"
                                        data-ref="{{ $data->ref }}"
                                        data-custom-button="btn-confirm">
                                    </script>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

