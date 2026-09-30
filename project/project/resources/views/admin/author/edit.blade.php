@extends('admin.layouts.app')
@section('page_title', __('Edit Author'))
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
                                <a class="breadcrumb-link"
                                   href="{{ route('admin.author.index') }}">@lang('Authors')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Author')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Author')</h1>
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
                        <p class="mb-0">@lang("You are editing blog category for `$pageEditableLanguage->name` version.")</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="add_kyc_form_table">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Edit Author')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.author.update', [$author->id, $pageEditableLanguage->id]) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')

                                <input type="hidden" name="language" value="{{ $pageEditableLanguage->id }}">
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text"
                                               class="form-control change_name_input  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="Name" aria-label="Name"
                                               autocomplete="off"
                                               value="{{ old('name', optional($author->details)->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="addressLabel" class="form-label">@lang('Address')</label>
                                        <input type="text" class="form-control  @error('address') is-invalid @enderror"
                                               name="address" id="nameLabel" placeholder="@lang("Address")"
                                               aria-label="@lang("Address")"
                                               autocomplete="off"
                                               value="{{ old('address', optional($author->details)->address) }}">
                                        @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="firstShowSlug">
                                            <label for="permalinkLabel" class="form-label">@lang("Permalink:")</label>
                                            <div class="d-inline-block">
                                                <span class="default-slug">{{ url('/')  }}/@lang("author")/</span>
                                                <span
                                                    id="editable-post-name">{{ optional($author->details)->slug }}</span>
                                                <span id="edit-slug-buttons">
                                            <button class="btn btn-sm btn-white" id="change_slug" type="button">
                                                @lang("Edit")
                                            </button>
                                        </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 laterShowSlug d-none">
                                        <div class="default-slug  d-flex md:align-items-center flex-wrap">
                                            <div class="d-flex">
                                                <label>@lang("Permalink:")</label>
                                                <span class="ps-1">{{ url('/')  }}/@lang("author")/</span>
                                            </div>
                                            <input type="text" class="form-control flex-grow-1 set-link-edit"
                                                   name="slug"
                                                   id="newSlug"
                                                   value="{{ optional($author->details)->slug }}"
                                                   placeholder="@lang("Slug")">
                                            <button class="btn btn-sm btn-success ms-2" id="btn-ok"
                                                    type="button">
                                                @lang("OK")
                                            </button>
                                            <button class="cancel btn btn-danger btn-sm ms-1" id="btn-cancel"
                                                    type="button">
                                                @lang("Cancel")
                                            </button>
                                        </div>

                                    </div>
                                    @error("slug")
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <span class="newSlug"></span>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">@lang("About The Author")</label>
                                        <textarea class="form-control" name="description" id="summernote"
                                                  rows="20">{{ old("description", optional($author->details)->description) }}</textarea>
                                        @error("description")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="js-add-field card mb-3 mb-lg-5">
                                    <div class="card-header card-header-content-sm-between">
                                        <h4 class="card-header-title mb-2 mb-sm-0">@lang("Social Media")</h4>
                                        <div class="d-sm-flex align-items-center gap-2">
                                            <a class="js-create-field btn btn-white btn-sm add_field_btn"
                                               href="javascript:void(0);">
                                                <i class="bi-plus"></i> @lang("Add Social Media")
                                            </a>
                                        </div>
                                    </div>

                                    <div class="table-responsive datatable-custom dynamic-feild-table">
                                        <table id="datatable"
                                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table overflow-visible">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>@lang("Name")</th>
                                                <th>@lang("Icon")</th>
                                                <th>@lang("Link")</th>
                                                <th></th>
                                            </tr>
                                            </thead>

                                            <tbody id="addFieldContainer">
                                            @php
                                                $oldValueCounts = old('', optional($author->details)->social_media) ? count( old('social_media_name', (array) optional($author->details)->social_media)) : 0;
                                            @endphp

                                            @if( 0 < $oldValueCounts)
                                                @php
                                                    $oldValueInputForm = collect(old('social_media_name', (array) optional($author->details)->social_media))->values();
                                                @endphp
                                                @for($i = 0; $i < $oldValueCounts; $i++)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="social_media_name[]"
                                                                   class="form-control"
                                                                   value="{{ old("social_media_name.$i", $oldValueInputForm[$i]['social_media_name'] ?? null) }}"
                                                                   placeholder="@lang("Social Media Name")"
                                                                   autocomplete="off">
                                                            @error("social_media_name.$i")
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </td>

                                                        <td>
                                                            <input type="text" name="icon[]" class="form-control iconPicker"
                                                                   value="{{ old("icon.$i", $oldValueInputForm[$i]['icon'] ?? null) }}"
                                                                   placeholder="@lang("Icon")" autocomplete="off">
                                                            @error("icon.$i")
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </td>

                                                        <td>
                                                            <input type="text" name="link[]" class="form-control"
                                                                   value="{{ old("link.$i", $oldValueInputForm[$i]['link'] ?? null) }}"
                                                                   placeholder="@lang("Link")" autocomplete="off">
                                                            @error("link.$i")
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </td>

                                                        <td class="table-column-ps-0">
                                                            <button type="button" class="btn btn-white remove-row">
                                                                <i class="bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Image")</label>
                                        <label class="form-check form-check-dashed" for="imageUploader-1">
                                            <img id="img"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($author->image_driver, $author->image, true) }}"
                                                 alt="@lang("Image")"
                                                 data-hs-theme-appearance="default">
                                            <img id="img"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($author->image_driver, $author->image, true) }}"
                                                 alt="@lang("Image")" data-hs-theme-appearance="dark">
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
                                        <span class="text-muted mt-2"> @lang('Image size should be 240 x 240 px')</span>
                                        @error("image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="kyc_status">
                                            <span class="col-4 col-sm-6 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("Status: Author privileges enabled for blog.")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="kycStatusSwitch"
                                                        value="1" {{ $author->status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-center"
                                                           for="kycStatusSwitch"></label>
                                                </span>
                                            @error('kyc_status')
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
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/fontawesome-iconpicker.min.css') }}">

@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset('assets/global/js/fontawesome-iconpicker.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
                $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
            });

            new HSFileAttach('.js-file-attach')
            $('#summernote').summernote({
                placeholder: 'Write about author',
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });

            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
            });

            HSCore.components.HSTomSelect.init('.js-select')
            $(document).on('click', '.add_field_btn', function () {
                let rowCount = $('#addFieldContainer tr').length;
                let markUp = `
                            <tr id="addVariantsTemplate">
                                <td>
                                    <input type="text" class="form-control" name="social_media_name[]" placeholder="@lang("Social Media Name")" autocomplete="off">
                                </td>
                                 <td>
                                    <input type="text" class="form-control iconPicker" name="icon[]" placeholder="@lang("Icon")" autocomplete="off">
                                </td>
                                 <td>
                                    <input type="text" class="form-control" name="link[]" placeholder="@lang("Link")" autocomplete="off">
                                </td>

                                <td class="table-column-ps-0">
                                    <button type="button" class="btn btn-white remove-row">
                                                            <i class="bi-trash"></i>
                                                        </button>
                                </td>
                            </tr>`;

                $("#addFieldContainer").append(markUp);

                const selectClass = `.js-select-dynamic-input-type${rowCount}, .js-select-dynamic-validation-type${rowCount}`;

                $("#addFieldContainer").find(selectClass).each(function () {
                    HSCore.components.HSTomSelect.init($(this));
                });

                $('.iconPicker').iconpicker().on('iconpickerSelected', function (e) {
                    $(this).closest('.form-group').find('.iconpicker-input').val(`<i class="${e.iconpickerValue}"></i>`);
                });

            });

            $(document).on('click', '.remove-row', function (e) {
                e.preventDefault();
                $(this).closest('tr').remove();
            });

            var slug = "{{ $author->slug }}";
            $(document).on("click", "#change_slug", function () {
                let slug = "{{ $author->slug }}";
                $('#newSlug').val(slug)
                $('.firstShowSlug').addClass('d-none');
                $('.laterShowSlug').removeClass('d-none');
            });
            $(document).on("click", "#btn-ok", function () {
                let newSlug = $('#newSlug').val();
                $('#editable-post-name').text(newSlug);
                $('.laterShowSlug').addClass('d-none');
                $('.firstShowSlug').removeClass('d-none');

                let author_id = "{{ $author->id }}";

                $.ajax({
                    url: "{{ route('admin.author.slug.update') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        author_id,
                        newSlug
                    },
                    success: function (response) {
                        if (response.errors) {
                            for (let item in response.errors) {
                                $('.newSlug').removeClass('text-success');
                                $('.newSlug').addClass('text-danger');
                                $('.newSlug').text(response.errors[item][0])
                            }
                            setTimeout(function () {
                                $('.newSlug').text('')
                            }, 3000)
                            return 0;
                        }
                        $('.newSlug').text('')
                        slug = response.slug
                    },
                    error: function (error) {
                    }
                });
            });
            $(document).on("click", "#btn-cancel", function () {
                $('.laterShowSlug').addClass('d-none');
                $('.firstShowSlug').removeClass('d-none');
            });

        });
    </script>
@endpush







