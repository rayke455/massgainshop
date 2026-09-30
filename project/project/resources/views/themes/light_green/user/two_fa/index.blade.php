@extends(template().'layouts.user')
@section('title',__('2FA Security'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("2Fa Security")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Dashboard")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("2Fa Security")</li>
                </ul>
            </div>

            <div class="row">
                @if(auth()->user()->two_fa == 1)
                    <div class="col-md-6">
                        <div class="card mt-50">
                            <div class="card-header d-flex justify-content-between align-items-center border-0">
                                <div class="card-header-title">
                                    <h4 class="mb-0">@lang('Two Factor Authenticator')</h4>
                                </div>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal"
                                        class="cmn-btn rounded-1">
                                    @lang("Regenerate")</button>
                            </div>
                            <div class="card-body">
                                <div class="box">
                                    <div class="input-group copy-code mt-0">
                                        <input type="text" value="{{$secret}}" class="form-control" id="copy-qr-code"
                                               readonly/>
                                        <button class="copy-text" id="copyBoard" onclick="copyFunction()">
                                            <i class="fa-light fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center qr-code mt-4">
                                    <img src="https://quickchart.io/chart?cht=qr&chs=200x200&chl={{$qrCodeUrl}}"
                                         alt="QR Code">
                                </div>
                                <button type="button" class="cmn-btn w-100 mt-4" data-bs-toggle="modal"
                                        data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator')</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="card mt-50">
                            <div class="card-header d-flex justify-content-between align-items-center border-0">
                                <div class="card-header-title">
                                    <h4 class="mb-0">@lang('Two Factor Authenticator')</h4>
                                </div>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal"
                                        class="cmn-btn rounded-1">
                                    @lang("Regenerate")</button>
                            </div>
                            <div class="card-body">
                                <div class="box">
                                    <div class="input-group copy-code mt-0">
                                        <input type="text" value="{{$secret}}" class="form-control" id="copy-qr-code"
                                               readonly/>
                                        <button class="copy-text" id="copyBoard" onclick="copyFunction()">
                                            <i class="fa-light fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-center qr-code mt-4">
                                    <img src="https://quickchart.io/chart?cht=qr&chs=200x200&chl={{$qrCodeUrl}}"
                                         alt="QR Code">
                                </div>
                                <button type="button" class="cmn-btn w-100 mt-4" data-bs-toggle="modal"
                                        data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator')</button>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <div class="card mt-50">
                        <div class="card-header text-center">
                            <h4 class="mb-0">@lang("Google Authenticator")</h4>
                        </div>
                        <div class="card-body">
                            <h6 class="text-uppercase">@lang('Use Google Authenticator to Scan the QR code  or use the code.')</h6>
                            <p class="py-4">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                            <a class="cmn-btn w-100"
                               href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                               target="_blank">@lang('DOWNLOAD APP')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include(template(). 'user.two_fa.components.regenerate_modal')
    @include(template(). 'user.two_fa.components.enable_modal')
    @include(template(). 'user.two_fa.components.disable_modal')
@endsection



@push('script')
    <script>
        function copyFunction() {
            let copyText = document.getElementById("copy-qr-code");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush

