@extends(template().'layouts.user')
@section('title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    @push('style')
        <style>
            .card-js .icon {
                top: 5px;
            }
            .authorise-btn {
                background-color: #015581;
                padding: 6px 12px !important;
            }
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
                                    <div class="card">
                                        <img
                                            src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                            class="card-img-top gateway-img" alt="..">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <form class="form-horizontal" id="example-form"
                                          action="{{ route('ipn', [optional($deposit->gateway)->code ?? '', $deposit->transaction]) }}"
                                          method="post">
                                        <div class="card-js form-group --payment-card">
                                            <input class="card-number form-control"
                                                   name="card_number"
                                                   placeholder="@lang('Enter your card number')"
                                                   autocomplete="off"
                                                   required>
                                            <input class="name form-control"
                                                   id="the-card-name-id"
                                                   name="card_name"
                                                   placeholder="@lang('Enter the name on your card')"
                                                   autocomplete="off"
                                                   required>
                                            <input class="expiry form-control"
                                                   autocomplete="off"
                                                   required>
                                            <input class="expiry-month" name="expiry_month">
                                            <input class="expiry-year" name="expiry_year">
                                            <input class="cvc form-control"
                                                   name="card_cvc"
                                                   autocomplete="off"
                                                   required>
                                        </div>
                                        <button type="submit" class="cmn-btn authorise-btn rounded-1 mt-3">@lang('Submit')</button>
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

@push('css-lib')
    <link href="{{ asset('assets/admin/css/card-js.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/card-js.min.js') }}"></script>
@endpush
