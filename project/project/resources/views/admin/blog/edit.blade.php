@extends('admin.layouts.app')
@section('page_title', __('Edit Blog'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link"
                                   href="{{ route('admin.blogs.index') }}">@lang('Blogs')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Edit Blog")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Edit Blog")</h1>
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
                        <p class="mb-0">@lang("You are editing blog for `$pageEditableLanguage->name` version.")</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route("admin.blog.update", $blog->id) }}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Create Blog")</h2>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="language_id" value="{{ $pageEditableLanguage->id }}">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="titleLabel" class="form-label">@lang("Title")</label>
                                        <div class="input-group input-group-sm-vertical">
                                            <input type="text" class="form-control change_name_input" name="title"
                                                   id="titleLabel"
                                                   value="{{ old("title", optional($blog->details)->title) }}"
                                                   placeholder="@lang("Title")" autocomplete="off">
                                        </div>
                                        @error("title")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="firstShowSlug">
                                            <label for="permalinkLabel" class="form-label">@lang("Permalink:")</label>
                                            <div class="d-inline-block">
                                                <span class="default-slug">{{ url('/')  }}/blog/</span>
                                                <span
                                                    id="editable-post-name">{{ optional($blog->details)->slug }}</span>
                                                <span id="edit-slug-buttons">
                                            <button class="btn btn-sm btn-white" id="change_slug" type="button">
                                                Edit
                                            </button>
                                        </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 laterShowSlug d-none">
                                        <div class="default-slug  d-flex md:align-items-center flex-wrap">
                                            <div class="d-flex">
                                                <label>@lang("Permalink:")</label>
                                                <span class="ps-1">{{ url('/')  }}/blog/</span>
                                            </div>
                                            <input type="text" class="form-control flex-grow-1 set-link-edit"
                                                   name="slug"
                                                   id="newSlug"
                                                   value="{{ optional($blog->details)->slug }}"
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
                                    <div class="col-md-6">
                                        <label for="NameLabel" class="form-label">@lang("Category")</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" autocomplete="off" name="category_id"
                                                    data-hs-tom-select-options='{
                                                      "placeholder": "Select Category",
                                                      "hideSearch": true
                                                    }'>
                                                <option value="">@lang('Select Category')</option>
                                                @forelse($blogCategory as $category)
                                                    <option
                                                        value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>{{ optional($category->details)->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error("category_id")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="authorLabel" class="form-label">@lang("Blog Author")</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" autocomplete="off" name="author_id"
                                                    data-hs-tom-select-options='{
                                                      "placeholder": "Select a author",
                                                      "hideSearch": true
                                                    }'>
                                                <option value="">@lang("Select Author")</option>
                                                @forelse($authors as $author)
                                                    <option
                                                        value="{{ $author->id }}" {{ old('author_id', $author->id) == $blog->author_id ? 'selected' : '' }}>{{ optional($author->details)->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error("author_id")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="form-label">@lang("Tags")</label>
                                    <div class="tom-select-custom">
                                        <select class="js-select form-select" name="tags[]" autocomplete="off" multiple
                                                data-hs-tom-select-options='{
                                              "create": true,
                                              "placeholder": "Create tag."
                                            }'>
                                            @forelse(optional($blog->details)->tags ?? [] as $tag)
                                            <option
                                                    value="{{ $tag }}" {{ old('tags', $tag) == $tag ? 'selected' : '' }}>{{$tag}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error("tags")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="mb-3">
                                        <label for="AuthorLabel" class="form-label">@lang("Quote Author")</label>
                                        <input type="text" class="form-control" name="quote_author"
                                               id="AuthorLabel"
                                               value="{{ old("quote_author", optional($blog->details)->quote_author) }}"
                                               placeholder="@lang("Quote Author")" autocomplete="off">
                                        @error("quote_author")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label for="QuoteLabel" class="form-label">@lang("Quote")</label>
                                        <textarea class="form-control" name="quote" id="QuoteLabel" rows="4"
                                                  placeholder="@lang("Write Quote")"
                                                  rows="20">{{old("quote", optional($blog->details)->quote)}}</textarea>
                                        @error("quote")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="" class="form-label">@lang("Description")</label>
                                        <textarea class="form-control" name="description" id="summernote"
                                                  rows="20">{{ old("description", optional($blog->details)->description) }}</textarea>
                                        @error("description")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Thumbnail Image")</label>
                                        <label class="form-check form-check-dashed" for="imageUploader-1">
                                            <img id="thumbnailImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->thumbnail_image_driver, $blog->thumbnail_image, true) }}"
                                                 alt="@lang("Thumbnail Image")"
                                                 data-hs-theme-appearance="default">
                                            <img id="thumbnailImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->thumbnail_image_driver, $blog->thumbnail_image, true) }}"
                                                 alt="@lang("Thumbnail Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="thumbnail_image" id="imageUploader-1"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#thumbnailImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        <span class="text-muted mt-2"> @lang('Image size should be 435 × 296 px')</span>
                                        @error("thumbnail_image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Thumbnail Image Two")</label>
                                        <label class="form-check form-check-dashed" for="imageUploader-3">
                                            <img id="thumbnailImgTwo"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->thumbnail_image_two_driver, $blog->thumbnail_image_two, true) }}"
                                                 alt="@lang("Thumbnail Image")"
                                                 data-hs-theme-appearance="default">
                                            <img id="thumbnailImgTwo"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->thumbnail_image_two_driver, $blog->thumbnail_image_two, true) }}"
                                                 alt="@lang("Thumbnail Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="thumbnail_image_two" id="imageUploader-3"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#thumbnailImgTwo",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        <span class="text-muted mt-2"> @lang('Image size should be 97 × 85 px')</span>
                                        @error("thumbnail_image_two")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <label for="" class="form-label">@lang("Description Image")</label>
                                        <label class="form-check form-check-dashed" for="imageUploader-2">
                                            <img id="descriptionImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->description_image_driver, $blog->description_image, true) }}"
                                                 alt="@lang("Description Image")"
                                                 data-hs-theme-appearance="default">
                                            <img id="descriptionImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->description_image_driver, $blog->description_image, true) }}"
                                                 alt="@lang("Description Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="description_image" id="imageUploader-2"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#descriptionImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                           }'>
                                        </label>
                                        <span class="text-muted mt-2"> @lang('Image size should be 746 × 498 px')</span>
                                        @error("description_image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Publish")</h2>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary" name="status"
                                            value="1">@lang("Save & Publish")</button>
                                    <button type="submit" class="btn btn-info" name="status"
                                            value="0">@lang("Save & Draft")</button>
                                </div>
                            </div>
                        </div>

                        <div class="card language_card">
                            <div class="card-header">
                                <h4 class="card-title">@lang("Language")</h4>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush list-group-no-gutters">
                                    @foreach($allLanguage as $language)
                                        @if($pageEditableLanguage->name !==  $language->name)
                                            <div class="list-group-item custom-list">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img class="avatar avatar-xss avatar-square me-2"
                                                             src="{{ getFile($language->flag_driver, $language->flag) }}"
                                                             alt="{{ ucwords($language->name) }} Flag">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col-sm mb-2 mb-sm-0">
                                                                <h5 class="mb-0">@lang($language->name)</h5>
                                                            </div>
                                                            <div class="col-sm-auto">
                                                                <a class="text-secondary"
                                                                   href="{{ route('admin.blog.edit', [$blog->id, $language->id]) }}"><i
                                                                        class="bi bi-pencil-square"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Breadcrumb Status")</h2>
                            </div>
                            <div class="card-body">
                                <label class="row form-check form-switch" for="breadcrumbSwitch">
                                    <span class="col-8 col-sm-9 ms-0">
                                      <span class="text-dark">@lang("Breadcrumb Status")
                                          <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                             data-bs-placement="top"
                                             aria-label="@lang("Enable status for page publish")"
                                             data-bs-original-title="@lang("Enable breadcrumb image this page")"></i></span>
                                    </span>
                                    <span class="col-4 col-sm-3 text-end">
                                        <input type="hidden" name="breadcrumb_status" value="0">
                                        <input type="checkbox" class="form-check-input" name="breadcrumb_status"
                                               id="breadcrumb" value="1" {{ $blog->breadcrumb_status == 1 ? 'checked' : '' }}>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang("Breadcrumb Image")</h2>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-8 mb-3 mb-md-0">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->breadcrumb_image_driver, $blog->breadcrumb_image, true) }}"
                                                 alt="@lang("Breadcrumb Image")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($blog->breadcrumb_image_driver, $blog->breadcrumb_image, true) }}"
                                                 alt="@lang("Breadcrumb Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="breadcrumb_image" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                                  "textTarget": "#logoImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                           }'>
                                        </label>
                                        <span
                                            class="text-muted text-center"> @lang('Image size should be 1440 × 1024 px')</span>
                                        @error("breadcrumb_image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

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

            var slug = "{{ optional($blog->details)->slug }}";
            $(document).on("click", "#change_slug", function () {
                let slug = "{{ optional($blog->details)->slug }}";
                $('#newSlug').val(slug)
                $('.firstShowSlug').addClass('d-none');
                $('.laterShowSlug').removeClass('d-none');
            });
            $(document).on("click", "#btn-ok", function () {
                let newSlug = $('#newSlug').val();
                $('#editable-post-name').text(newSlug);
                $('.laterShowSlug').addClass('d-none');
                $('.firstShowSlug').removeClass('d-none');

                let blogId = "{{ $blog->id }}";

                $.ajax({
                    url: "{{ route('admin.blog.slug.update') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        blogId,
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

            $('#summernote').summernote({
                placeholder: 'Enter Description',
                height: 160,
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
