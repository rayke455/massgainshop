@extends('admin.layouts.app')
@section('page_title', __('Add Social Media'))
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
                                <a class="breadcrumb-link" href="{{ route('admin.social-media.index') }}">
                                    @lang('Social Media')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Add Social Media')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Add Social Media')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Add Social Media')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.social-media.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="@lang("Name")"
                                               aria-label="@lang("Name")"
                                               autocomplete="off"
                                               value="{{ old('name') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="apiProviderStatus">
                                            <span class="col-4 col-sm-6 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("The social media has been enabled for the customer.")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="apiProviderStatus"
                                                        value="1">
                                                    <label class="form-check-label text-center"
                                                           for="apiProviderStatus"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Icon")</label>
                                        <label class="form-check form-check-dashed" for="iconUploader-1">
                                            <img id="img"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                 alt="@lang("Icon")"
                                                 data-hs-theme-appearance="default">
                                            <img id="img"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file-light.svg") }}"
                                                 alt="@lang("Icon")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="icon" id="iconUploader-1"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#img",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        <span class="text-muted mt-2"> @lang('Icon size should be 24 x 24 px')</span>
                                        @error("icon")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
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
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
        });
    </script>
@endpush
