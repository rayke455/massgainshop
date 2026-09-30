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

        .card {
            margin-top: 150px;
        }
    </style>
@endpush

@section('content')

    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Pay with") @lang(optional($deposit->gateway)->name)</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a></li>
                    <li class="breadcrumb-item">@lang("add fund")</li>
                    <li class="breadcrumb-item active">@lang("Pay with") @lang(optional($deposit->gateway)->name)</li>
                </ol>
            </nav>
        </div>

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
    </main>
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

