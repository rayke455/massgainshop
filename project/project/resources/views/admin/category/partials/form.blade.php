{{-- Shared Add / Edit category form. Optional: $category (edit mode). --}}
@php $category = $category ?? null; @endphp
<div class="row mb-4 d-flex align-items-center">
    <div class="col-md-6">
        <label for="nameLabel" class="form-label">@lang('Name')</label>
        <input type="text" class="form-control  @error('name') is-invalid @enderror"
               name="name" id="nameLabel" placeholder="@lang("e.g. Instagram Followers")"
               aria-label="@lang("Name")"
               autocomplete="off"
               value="{{ old('name', $category->category_title ?? '') }}">
        @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="socialMediaLabel" class="form-label">@lang("Platform") <small class="text-muted">(@lang('optional'))</small></label>
        <div class="tom-select-custom">
            <select class="js-select form-select" name="social_media" autocomplete="off"
                    data-hs-tom-select-options='{
                    "placeholder": "Detect automatically from the name"
                  }'>
                <option value="">@lang("Detect automatically from the name")</option>
                @forelse($socialMedia as $item)
                    <option value="{{ $item->id }}" {{ old('social_media', $category->social_media_id ?? null) == $item->id ? 'selected' : '' }}>@lang($item->name)</option>
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
        <label for="" class="form-label">@lang("Image") <small class="text-muted">(@lang('optional'))</small></label>
        <label class="form-check form-check-dashed" for="imageUploader-1">
            <img id="img"
                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                 src="{{ !empty($category?->image) ? getFile($category->image_driver, $category->image, true) : asset("assets/admin/img/oc-browse-file.svg") }}"
                 alt="@lang("Icon")"
                 data-hs-theme-appearance="default">
            <img id="img"
                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                 src="{{ !empty($category?->image) ? getFile($category->image_driver, $category->image, true) : asset("assets/admin/img/oc-browse-file-light.svg") }}"
                 alt="@lang("Icon")" data-hs-theme-appearance="dark">
            <span class="d-block">@lang("Browse your file here")</span>
            <input type="file" class="js-file-attach form-check-input"
                   name="image" id="imageUploader-1"
                   data-hs-file-attach-options='{
                  "textTarget": "#img",
                  "mode": "image",
                  "targetAttr": "src",
                  "allowTypes": [".png", ".jpeg", ".jpg", ".webp"]
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
                  class="d-block fs-5">@lang("Customers can see and order from this category.")</span>
            </span>
            <span class="col-2 col-sm-3 text-end">
                 <input type='hidden' value='0' name='status'>
                    <input
                        class="form-check-input @error('status') is-invalid @enderror"
                        type="checkbox" name="status" id="categoryStatus"
                        value="1" {{ (int) old('status', $category->status ?? 1) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label text-center"
                           for="categoryStatus"></label>
                </span>
            @error('status')
            <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </label>
    </div>
</div>
