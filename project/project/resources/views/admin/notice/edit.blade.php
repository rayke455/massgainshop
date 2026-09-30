@extends('admin.layouts.app')
@section('page_title', __('Edit Notice'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="{{ route('admin.blogs.index') }}">@lang('Notice')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Edit Notice")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit Notice")</h1>
                </div>
            </div>
        </div>

        <div class="alert alert-soft-dark mb-5" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="dark">
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">@lang("You are creating notice for `$pageEditableLanguage->name` version.")</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route("admin.notice.update", $notice->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-10">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Edit Notice")</h2>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="language_id" value="{{ $pageEditableLanguage->id }}">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label for="titleLabel" class="form-label">@lang("Title")</label>
                                        <div class="input-group input-group-sm-vertical">
                                            <input type="text" class="form-control change_name_input" name="title"
                                                   id="titleLabel" value="{{ old("title", optional($notice->details)->title) }}"
                                                   placeholder="@lang("Title")" autocomplete="off">
                                        </div>
                                        @error("title")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">@lang("Description")</label>
                                        <textarea class="form-control" name="description" id="summernote"
                                                  rows="20">{{ old("description", optional($notice->details)->description) }}</textarea>
                                        @error("description")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Notice Image")</label>
                                        <label class="form-check form-check-dashed" for="imageUploader-2">
                                            <img id="noticeImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($notice->image_driver, $notice->image, true) }}"
                                                 alt="@lang("Image")"
                                                 data-hs-theme-appearance="default">
                                            <img id="noticeImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($notice->image_driver, $notice->image, true) }}"
                                                 alt="@lang("Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="image" id="imageUploader-2"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#noticeImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        <span class="text-muted mt-2"> @lang('Image size should be 746 x 498 px')</span>
                                        @error("image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="noticeStatus">
                                            <span class="col-4 col-sm-6 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("The notice has enabled for the user.")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="noticeStatus"
                                                        value="1" {{ $notice->status == 1 ? 'selected' : '' }}>
                                                    <label class="form-check-label text-center"
                                                           for="noticeStatus"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="btn btn-primary">@lang("Save & Publish")
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select');
            new HSFileAttach('.js-file-attach')
            $('#summernote').summernote({
                placeholder: 'Enter Description',
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });
        });
    </script>
@endpush
