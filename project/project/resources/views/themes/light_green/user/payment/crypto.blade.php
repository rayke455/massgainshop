@extends(template().'layouts.user')
@section('title')
    {{ 'Pay with ' . optional($deposit->gateway)->name ?? 'Manual Transfer' }}
@endsection

@push("style")
    <style>
        .crypto img {
            border-radius: 3px;
            box-shadow: rgba(0, 0, 0, 0.09) 0px 3px 12px;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper mt-3 crypto">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-8 col-md-8">
                    <h5>{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</h5>
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="mt-3"> @lang('PLEASE SEND EXACTLY')
                                <span class="text-success"> {{ getAmount($data->amount) . " " .$data->currency}}</span>
                            </h5>
                            <h5>@lang('TO')</h5>

                            <div class="col-md-6 mx-auto">
                                <div class="input-group">
                                    <input type="text" id="copy-code" value="{{ $data->sendto }}" class="form-control">
                                    <div class="input-group-text" onclick="copyFunction()"><i class="fa-light fa-copy" ></i></div>
                                </div>
                            </div>

                            <img src="{{ $data->img }}">
                            <h5 class="mt-2">@lang('SCAN TO SEND')</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function copyFunction() {
            let copyText = document.getElementById("copy-code");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush

