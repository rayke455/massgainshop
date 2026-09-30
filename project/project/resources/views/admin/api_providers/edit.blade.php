@extends('admin.layouts.app')
@section('page_title', __('Edit Api Provider'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.api-provider.index') }}">
                                    @lang('Api Providers')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Api Provider')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Api Provider')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Edit API Provider')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.api-provider.update', $apiProvider->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="apiNameLabel" class="form-label">@lang('API Name')</label>
                                        <input type="text" class="form-control  @error('api_name') is-invalid @enderror"
                                               name="api_name" id="apiNameLabel" placeholder="@lang("API Name")"
                                               aria-label="@lang("API Name")"
                                               autocomplete="off"
                                               value="{{ old('api_name', $apiProvider->api_name) }}">
                                        @error('api_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="urlLabel" class="form-label">@lang('URL')</label>
                                        <input type="url" class="form-control  @error('url') is-invalid @enderror"
                                               name="url" id="urlLabel" placeholder="@lang("URL")"
                                               aria-label="@lang("URL")"
                                               autocomplete="off"
                                               value="{{ old('url', $apiProvider->url) }}">
                                        @error('url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="form-label"
                                                   for="apiKeyLabel">@lang('API Key')</label>
                                        </div>

                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <input type="password" name="api_key"
                                                   class="js-toggle-password form-control form-control-lg"
                                                   value="{{ old('api_key', $apiProvider->api_key) }}"
                                                   id="apiKeyLabel"
                                                   placeholder="API Key"
                                                   data-hs-toggle-password-options='{
                                             "target": "#changePassTarget",
                                             "defaultClass": "bi-eye-slash",
                                             "showClass": "bi-eye",
                                             "classChangeTarget": "#changePassIcon"
                                           }'>
                                            <a id="changePassTarget" class="input-group-append input-group-text"
                                               href="javascript:void(0)">
                                                <i id="changePassIcon" class="bi-eye"></i>
                                            </a>
                                        </div>
                                        @error('api_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="urlLabel" class="form-label">@lang('Conversion Rate')</label>
                                        <div class="input-group">
                                            <span
                                                class="input-group-text"> 1 {{ $apiProvider->currency }}</span>
                                            <input type="number" name="conversion_rate" class="form-control
                                             @error('conversion_rate') is-invalid @enderror"
                                                   aria-label="@lang("Conversion Rate")" step="0.0000001"
                                                   autocomplete="off"
                                                   value="{{ old('conversion_rate', $apiProvider->conversion_rate) }}">
                                            <span
                                                class="input-group-text">@lang("Base Currency:") {{ basicControl()->base_currency ?? 'USD' }}</span>
                                        </div>
                                        @error('conversion_rate')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="apiProviderStatus">
                                            <span class="col-4 col-sm-6 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("The API provider has been enabled for the order.")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="apiProviderStatus"
                                                        value="1" {{ $apiProvider->status == 1 ? 'checked' : ''}}>
                                                    <label class="form-check-label text-center"
                                                           for="apiProviderStatus"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-toggle-password.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        new HSTogglePassword('.js-toggle-password')
    </script>
@endpush
