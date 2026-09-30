@extends('admin.layouts.app')
@section('page_title', __('Edit Category'))
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
                                <a class="breadcrumb-link" href="{{ route('admin.category.index') }}">
                                    @lang('Category')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Category')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Category')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Edit Category')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.category.update', $category->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="@lang("Name")"
                                               aria-label="@lang("Name")"
                                               autocomplete="off"
                                               value="{{ old('name', $category->category_title) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="socialMediaLabel" class="form-label">@lang("Social Media")</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="social_media" autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                    "placeholder": "Select social media.."
                                                  }'>
                                                <option value="">Select social media..</option>
                                                @forelse($socialMedia as $item)
                                                    <option value="{{ $item->id }}" {{ $category->social_media_id == $item->id ? 'selected' : '' }}>@lang($item->name)</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        @error('social_media')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 justify-content-between">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Image")</label>
                                        <label class="form-check form-check-dashed" for="imageUploader-1">
                                            <img id="img"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($category->image_driver, $category->image, true) }}"
                                                 alt="@lang("Icon")"
                                                 data-hs-theme-appearance="default">
                                            <img id="img"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($category->image_driver, $category->image, true) }}"
                                                 alt="@lang("Icon")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="image" id="imageUploader-1"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#img",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        <span class="text-muted mt-2"> @lang('Icon size should be 64 x 64 px')</span>
                                        @error("image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="categoryStatus">
                                            <span class="col-4 col-sm-6 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("The category has enabled for the user.")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="categoryStatus"
                                                        value="1" {{ $category->status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-center"
                                                           for="categoryStatus"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
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


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/tom-select.complete.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select')

        });
    </script>
@endpush
