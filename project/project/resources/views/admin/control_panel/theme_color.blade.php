@extends('admin.layouts.app')
@section('page_title', __('Theme Color Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Theme Color')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Theme Color')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="{{ basicControl()->theme == 'minimal' ? 'col-lg-9' : 'col-lg-6' }}" id="basic_control">
                <div class="d-grid gap-3 gap-lg-5'">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Theme Color')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.theme.color.update') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="color_setting">
                                            @if(basicControl()->theme == 'light_green')
                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="primaryColorLabel"
                                                               class="form-label">@lang('Light Blue Primary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('light_green_primary_color') is-invalid @enderror"
                                                               name="light_green_primary_color"
                                                               id="primaryColorLabel"
                                                               placeholder="Primary Color" aria-label="Primary Color"
                                                               value="{{ old('light_green_primary_color', $themeColor->light_green_primary_color) }}">
                                                        @error('light_green_primary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="light_green_secondary_color"
                                                               class="form-label">@lang('Light Blue Secondary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('light_green_secondary_color') is-invalid @enderror"
                                                               name="light_green_secondary_color"
                                                               id="light_green_secondary_color"
                                                               placeholder="light green secondary color"
                                                               aria-label="light green secondary color"
                                                               value="{{ old('light_green_secondary_color', $themeColor->light_green_secondary_color) }}">
                                                        @error('light_green_secondary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="light_green_hero_color"
                                                               class="form-label">@lang('Light Blue Hero Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('light_green_hero_color') is-invalid @enderror"
                                                               name="light_green_hero_color"
                                                               id="light_green_hero_color"
                                                               placeholder="light green hero color"
                                                               aria-label="light green hero color"
                                                               value="{{ old('light_green_hero_color', $themeColor->light_green_hero_color) }}">
                                                        @error('light_green_hero_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif(basicControl()->theme == 'dark_voilet')
                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="dark_violet_primary_color"
                                                               class="form-label">@lang('Dark Violet Primary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('dark_violet_primary_color') is-invalid @enderror"
                                                               name="dark_violet_primary_color"
                                                               id="dark_violet_primary_color"
                                                               placeholder="dark voilet primary color"
                                                               aria-label="dark voilet primary color"
                                                               value="{{ old('dark_violet_primary_color', $themeColor->dark_violet_primary_color) }}">
                                                        @error('dark_violet_primary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Dark Violet Secondary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('dark_voilet_secondary_color') is-invalid @enderror"
                                                               name="dark_violet_secondary_color"
                                                               id="dark_violet_secondary_color"
                                                               placeholder="dark voilet secondary color"
                                                               aria-label="dark voilet secondary color"
                                                               value="{{ old('dark_violet_secondary_color', $themeColor->dark_violet_secondary_color) }}">
                                                        @error('dark_violet_secondary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif(basicControl()->theme == 'minimal')
                                                <div class="row mb-4">
                                                    <div class="col-sm-3">
                                                        <label for="minimal_primary_color"
                                                               class="form-label">@lang('Minimal Primary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_primary_color') is-invalid @enderror"
                                                               name="minimal_primary_color"
                                                               id="minimal_primary_color"
                                                               placeholder="Minimal Primary Color"
                                                               aria-label="Minimal Primary Color"
                                                               value="{{ old('minimal_primary_color', $themeColor->minimal_primary_color) }}">
                                                        @error('minimal_primary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Secondary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_secondary_color') is-invalid @enderror"
                                                               name="minimal_secondary_color"
                                                               id="minimal_secondary_color"
                                                               placeholder="Minimal Secondary Color"
                                                               aria-label="Minimal Secondary Color"
                                                               value="{{ old('minimal_secondary_color', $themeColor->minimal_secondary_color) }}">
                                                        @error('minimal_secondary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Subheading color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_subheading_color') is-invalid @enderror"
                                                               name="minimal_subheading_color"
                                                               id="minimal_subheading_color"
                                                               placeholder="Minimal Subheading Color"
                                                               aria-label="Minimal Subheading Color"
                                                               value="{{ old('minimal_subheading_color', $themeColor->minimal_sub_heading_color) }}">
                                                        @error('minimal_subheading_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Background left color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_bg_left_color') is-invalid @enderror"
                                                               name="minimal_bg_left_color"
                                                               id="minimal_bg_left_color"
                                                               placeholder="Minimal Bg Left Color"
                                                               aria-label="Minimal Bg Left Color"
                                                               value="{{ old('minimal_bg_left_color', $themeColor->minimal_bg_left_color) }}">
                                                        @error('minimal_bg_left_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                </div>

                                                <div class="row mb-4">
                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Background Right color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_bg_right_color') is-invalid @enderror"
                                                               name="minimal_bg_right_color"
                                                               id="minimal_bg_right_color"
                                                               placeholder="Minimal Bg Right Color"
                                                               aria-label="Minimal Bg Right Color"
                                                               value="{{ old('minimal_bg_right_color', $themeColor->minimal_bg_right_color) }}">
                                                        @error('minimal_bg_right_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Button Left Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_button_left_color') is-invalid @enderror"
                                                               name="minimal_button_left_color"
                                                               id="minimal_button_left_color"
                                                               placeholder="Minimal Button Left Color"
                                                               aria-label="Minimal Button Left Color"
                                                               value="{{ old('minimal_button_left_color', $themeColor->minimal_button_left_color) }}">
                                                        @error('minimal_button_left_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Background Left 2 Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_bg_left_two_color') is-invalid @enderror"
                                                               name="minimal_bg_left_two_color"
                                                               id="minimal_bg_left_two_color"
                                                               placeholder="Minimal Bg Left 2 Color"
                                                               aria-label="Minimal Bg Left 2 Color"
                                                               value="{{ old('minimal_bg_left_two_color', $themeColor->minimal_bg_left_two_color) }}">
                                                        @error('minimal_bg_left_two_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-3">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Minimal Copyrights Background Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('minimal_copy_right_bg_color') is-invalid @enderror"
                                                               name="minimal_copy_right_bg_color"
                                                               id="minimal_copy_right_bg_color"
                                                               placeholder="Minimal Copy Right Bg Color"
                                                               aria-label="Minimal Copy Right Bg Color"
                                                               value="{{ old('minimal_copy_right_bg_color', $themeColor->minimal_copy_right_bg_color) }}">
                                                        @error('minimal_copy_right_bg_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                </div>
                                            @elseif(basicControl()->theme == 'deep_blue')
                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="deep_blue_primary_color"
                                                               class="form-label">@lang('Deep Blue Primary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('deep_blue_primary_color') is-invalid @enderror"
                                                               name="deep_blue_primary_color"
                                                               id="deep_blue_primary_color"
                                                               placeholder="deep blue primary color"
                                                               aria-label="deep blue primary color"
                                                               value="{{ old('deep_blue_primary_color', $themeColor->deep_blue_primary_color) }}">
                                                        @error('deep_blue_primary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Deep Blue Secondary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('deep_blue_secondary_color') is-invalid @enderror"
                                                               name="deep_blue_secondary_color"
                                                               id="deep_blue_secondary_color"
                                                               placeholder="deep blue secondary color"
                                                               aria-label="deep blue secondary color"
                                                               value="{{ old('deep_blue_secondary_color', $themeColor->deep_blue_secondary_color) }}">
                                                        @error('deep_blue_secondary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif(basicControl()->theme == 'dark_mode')
                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="dark_mode_primary_color"
                                                               class="form-label">@lang('Dark Mode Primary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('dark_mode_primary_color') is-invalid @enderror"
                                                               name="dark_mode_primary_color"
                                                               id="dark_mode_primary_color"
                                                               placeholder="dark mode primary color"
                                                               aria-label="dark mode primary color"
                                                               value="{{ old('dark_mode_primary_color', $themeColor->dark_mode_primary_color) }}">
                                                        @error('dark_mode_primary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="secondaryColorLabel"
                                                               class="form-label">@lang('Dark Mode Secondary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('dark_mode_secondary_color') is-invalid @enderror"
                                                               name="dark_mode_secondary_color"
                                                               id="dark_mode_secondary_color"
                                                               placeholder="dark mode secondary color"
                                                               aria-label="dark mode secondary color"
                                                               value="{{ old('dark_mode_secondary_color',$themeColor->dark_mode_secondary_color) }}">
                                                        @error('dark_mode_secondary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @elseif(basicControl()->theme == 'light_orange')
                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="light_orange_primary_color"
                                                               class="form-label">@lang('Light Orange Theme Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('light_orange_primary_color') is-invalid @enderror"
                                                               name="light_orange_primary_color"
                                                               id="light_orange_primary_color"
                                                               placeholder="light orange primary color"
                                                               aria-label="light orange primary color"
                                                               value="{{ old('light_orange_primary_color', $themeColor->light_orange_primary_color) }}">
                                                        @error('light_orange_primary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-sm-6">
                                                        <label for="light_orange_theme_light_color"
                                                               class="form-label">@lang('Light Orange Theme light Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('light_orange_theme_light_color') is-invalid @enderror"
                                                               name="light_orange_theme_light_color"
                                                               id="light_orange_theme_light_color"
                                                               placeholder="light orange theme light color"
                                                               aria-label="light orange theme light color"
                                                               value="{{ old('light_orange_theme_light_color', $themeColor->light_orange_theme_light_color) }}">
                                                        @error('light_orange_theme_light_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <div class="col-sm-6">
                                                        <label for="light_orange_secondary_color"
                                                               class="form-label">@lang('Light Orange Secondary Color')</label>
                                                        <input type="color"
                                                               class="form-control color-form-input @error('light_orange_secondary_color') is-invalid @enderror"
                                                               name="light_orange_secondary_color"
                                                               id="light_orange_secondary_color"
                                                               placeholder="light_orange_secondary_color"
                                                               aria-label="light_orange_secondary_color"
                                                               value="{{ old('light_orange_secondary_color', $themeColor->light_orange_secondary_color) }}">
                                                        @error('light_orange_secondary_color')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
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
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500
            })
        })
    </script>
@endpush
