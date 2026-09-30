@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@push('style')
    <style>
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
        </div>
    </div>
@endsection

@push('script')
    <script src="https://js.stripe.com/v3/"></script>
@endpush




