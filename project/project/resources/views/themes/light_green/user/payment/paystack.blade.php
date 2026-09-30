@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')

    @push('style')
        <style>
            .gateway-img {
                border-radius: 5px;
            }
        </style>
    @endpush

    <div class="dashboard-wrapper d-flex align-items-center">
        <div class="container">
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
        </div>
    </div>


@endsection

