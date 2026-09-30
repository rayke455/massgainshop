@extends('admin.layouts.app')
@section('page_title', __('Two Fa Security'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter mb-2">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0);">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Two Fa Security')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Two Fa Security')</h1>
                </div>
            </div>
        </div>

        <div class="row justify-content-center gy-4">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                        <h5 class="mb-0 fw-semibold">@lang('Two Factor Authenticator')</h5>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="fa fa-rotate-right me-1"></i> @lang("Regenerate")
                        </button>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-4">
                            <label for="copy-qr-code" class="form-label fw-medium">@lang('Your Secret Code')</label>
                            <div class="input-group">
                                <input type="text" id="copy-qr-code" class="form-control bg-light" value="{{ $secret }}" readonly>
                                <button class="btn btn-outline-secondary" onclick="copyFunction()" type="button">
                                    <i class="fa fa-copy me-1"></i> @lang('Copy')
                                </button>
                            </div>
                        </div>

                        <div class="text-center mb-4">
                            <div class="border rounded p-3 d-inline-block bg-light">
                                <img src="https://quickchart.io/chart?cht=qr&chs=200x200&chl={{ $qrCodeUrl }}"
                                     alt="@lang('QR Code')" class="img-fluid" style="max-width: 200px;">
                            </div>
                        </div>


                        @if(auth()->user()->two_fa == 1)
                            <button type="button" class="btn btn-danger w-100 py-2" data-bs-toggle="modal" data-bs-target="#disableModal">
                                <i class="fa fa-lock me-1"></i> @lang('Disable Two Factor Authenticator')
                            </button>
                        @else
                            <button type="button" class="btn btn-success w-100 py-2" data-bs-toggle="modal" data-bs-target="#enableModal">
                                <i class="fa fa-shield-halved me-1"></i> @lang('Enable Two Factor Authenticator')
                            </button>
                        @endif
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-3 h-100">
                    <div class="card-header bg-white text-center border-bottom py-3 px-4">
                        <h5 class="mb-0 fw-semibold text-primary">
                            <i class="fa fa-shield-alt me-2"></i> @lang("Google Authenticator")
                        </h5>
                    </div>

                    <div class="card-body d-flex flex-column justify-content-between px-4 pt-3 pb-4">
                        <div>
                            <h6 class="text-uppercase text-dark fw-semibold mb-2 fs-6">
                                @lang('Scan the QR Code or Use the Code')
                            </h6>

                            <p class="text-secondary mb-2 fs-6">
                                @lang('Google Authenticator is a secure app that generates time-based codes for 2-step verification.')
                            </p>

                            <p class="text-secondary fs-6 mb-0">
                                @lang('Install the app on your mobile device, then scan the QR code or enter the code manually to enable protection.')
                            </p>
                        </div>

                        <a class="btn btn-primary mt-4 w-100 py-2 fw-semibold d-flex align-items-center justify-content-center"
                           href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                           target="_blank">
                            <i class="fa fa-download me-2"></i> @lang('Download App')
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    @include('admin.two_fa_security.components.regenerate_modal')
    @include('admin.two_fa_security.components.enable_modal')
    @include('admin.two_fa_security.components.disable_modal')
@endsection

@push('script')
    <script>
        function copyFunction() {
            const copyText = document.getElementById("copy-qr-code");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush
