@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@push('style')
    <style>
        .payment-stripe .card{
            margin-top: 150px;
        }
        .gateway-img {
            border-radius: 5px;
        }

        .stripe-button-el {
            background-image: none !important;
            box-shadow: none !important;
        }

        .stripe-button-el span {
            background: linear-gradient(90deg, rgba(98, 89, 209, 1) 35%, rgba(97, 151, 242, 1) 100%) !important;
        }
    </style>
    <link href="{{ asset('assets/global/css/stripe.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <main id="main" class="main bg-color2 payment-stripe">
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
                                <img src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                    class="card-img-top gateway-img">
                            </div>
                            <div class="col-md-6">
                                <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                <form action="{{ $data->url }}" method="{{ $data->method }}">
                                    @csrf
                                    <script src="{{ $data->src }}" class="stripe-button"
                                            @foreach($data->val as $key=> $value)
                                                data-{{$key}}="{{$value}}"
                                        @endforeach>
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

@push('js-lib')
    <script src="https://js.stripe.com/v3/"></script>
@endpush




