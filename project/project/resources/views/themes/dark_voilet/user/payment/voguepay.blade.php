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
            <h3 class="mb-1">@lang("Pay with ") @lang(optional($deposit->gateway)->name)</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Home")</a></li>
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
                                        src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                                        class="card-img-top gateway-img">
                            </div>
                            <div class="col-md-6">
                                <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                <button type="button" class="cmn-btn"
                                        id="btn-confirm">@lang('Pay with VoguePay')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('script')
    <script src="https://pay.voguepay.com/js/voguepay.js"></script>
    <script>
        closedFunction = function () {
        }
        successFunction = function (transaction_id) {
            let txref = "{{ $data->merchant_ref }}";
            window.location.href = '{{ url('payment/voguepay') }}/' + txref + '/' + transaction_id;
        }
        failedFunction = function (transaction_id) {
            window.location.href = '{{ route('failed') }}';
        }

        function pay(item, price) {
            Voguepay.init({
                v_merchant_id: "{{ $data->v_merchant_id }}",
                total: price,
                notify_url: "{{ $data->notify_url }}",
                cur: "{{ $data->cur }}",
                merchant_ref: "{{ $data->merchant_ref }}",
                memo: "{{ $data->memo }}",
                developer_code: '5af93ca2913fd',
                custom: "{{ $data->custom }}",
                customer: {
                    name: "{{ $data->customer_name }}",
                    address: "{{ $data->customer_address }}",
                    email: "{{ $data->customer_email }}"
                },
                closed: closedFunction,
                success: successFunction,
                failed: failedFunction
            });
        }

        $(document).ready(function () {
            $(document).on('click', '#btn-confirm', function (e) {
                e.preventDefault();
                pay('Buy', {{ $data->Buy }});
            });
        });
    </script>
@endpush


