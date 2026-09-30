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
            <h3 class="mb-1">@lang("Pay with") @lang(optional($deposit->gateway)->name)</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Home")</a></li>
                    <li class="breadcrumb-item">@lang("add fund")</li>
                    <li class="breadcrumb-item active">@lang("Pay with") @lang(optional($deposit->gateway)->name)</li>
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
                                    src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                                    class="card-img-top gateway-img">
                            </div>
                            <div class="col-md-6">
                                <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                <form action="{{$data->url}}" method="{{$data->method}}">
                                    <script src="{{$data->checkout_js}}"
                                            @foreach($data->val as $key=>$value)
                                                data-{{$key}}="{{$value}}"
                                        @endforeach >
                                    </script>
                                    <input type="hidden" custom="{{$data->custom}}" name="hidden">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('input[type="submit"]').addClass("btn-sm btn-primary border-0 cmn-btn");
        })
    </script>
@endpush
