@extends('admin.layouts.app')
@section('page_title', __('Add Service'))
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
                                <a class="breadcrumb-link" href="{{ route('admin.service.index') }}">
                                    @lang('Services')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Add Service')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Add Service')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Add Service')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.service.store') }}" method="post"
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
                                        <label for="categoryLabel" class="form-label">@lang('Category')</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="category" autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                    "placeholder": "Select category"
                                                  }'>
                                                <option value="">@lang("Select category")</option>
                                                @forelse($categories as $category)
                                                    <option
                                                        value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->category_title }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                        @error('category')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="minAmountLabel" class="form-label">@lang('Min Amount')</label>
                                        <input type="number"
                                               class="form-control @error('min_amount') is-invalid @enderror"
                                               name="min_amount" id="minAmountLabel" placeholder="@lang('Min Amount')"
                                               aria-label="@lang('Min Amount')"
                                               autocomplete="off"
                                               value="{{ old('min_amount', 500) }}">
                                        @error('min_amount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="maxAmountLabel" class="form-label">@lang('Max Amount')</label>
                                        <input type="number"
                                               class="form-control  @error('max_amount') is-invalid @enderror"
                                               name="max_amount" id="maxAmountLabel" placeholder="@lang("Max Amount")"
                                               aria-label="@lang("Max Amount")"
                                               autocomplete="off"
                                               value="{{ old('max_amount', 5000) }}">
                                        @error('max_amount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <label for="priceLabel" class="form-label">@lang('Rate Per 1000')
                                            <sub>(@lang('Price'))</sub></label>
                                        <input type="text" class="form-control  @error('price') is-invalid @enderror"
                                               name="price" id="priceLabel" placeholder="@lang('Price')"
                                               aria-label="@lang("Price")"
                                               autocomplete="off"
                                               value="{{ old('price') }}">
                                        @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-3">
                                        <label class="row form-check form-switch mt-3" for="serviceStatus">
                                            <span class="col-4 col-sm-9 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="serviceStatus"
                                                        value="1">
                                                    <label class="form-check-label text-center"
                                                           for="serviceStatus"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>

                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <label class="row form-check form-switch mt-3" for="dripFeed">
                                            <span class="col-4 col-sm-9 ms-0 ">
                                              <span class="d-block text-dark">@lang("Drip Feed")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='drip_feed'>
                                                    <input
                                                        class="form-check-input @error('drip_feed') is-invalid @enderror"
                                                        type="checkbox" name="drip_feed" id="dripFeed"
                                                        value="1">
                                                    <label class="form-check-label text-center"
                                                           for="dripFeed"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>


                                <div class="text-start mt-5">
                                    <ul class="nav nav-segment mb-3" role="tablist">
                                        <li class="nav-item" data-value="0" id="manual">
                                            <a class="nav-link {{ old('manual_api') == 0 ? 'active' : ''  }}"
                                               id="nav-one-eg1-tab" href="#nav-one-eg1"
                                               data-bs-toggle="pill" aria-selected="true">@lang("Manual")</a>
                                        </li>
                                        <li class="nav-item" data-value="1" id="api">
                                            <a class="nav-link {{ old('manual_api') == 1 ? 'active' : ''  }}"
                                               id="nav-two-eg2-tab" href="#nav-two-eg2"
                                               data-bs-toggle="pill" aria-selected="false">@lang("API")</a>
                                        </li>
                                    </ul>
                                </div>


                                <input class="manual_api" type="hidden" name="manual_api"
                                       value="{{ old('manual_api', 0) }}">

                                <div class="tab-content">
                                    <div class="row mt-3 moreField {{ old('manual_api', 0) == 0 ? 'd-none' : ''  }}">
                                        <div class="col-md-6 mb-3">
                                            <label for="refillLabel"
                                                   class="form-label">@lang('Api Provider Name')</label>
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select" name="api_provider_id"
                                                        autocomplete="off"
                                                        data-hs-tom-select-options='{
                                                        "placeholder": "Select API Provider"
                                                      }'>
                                                    <option value="">@lang("Select API Provider")</option>
                                                    @foreach($apiProviders as $apiProvider)
                                                        <option
                                                            value="{{ $apiProvider->id }}" {{ old('api_provider_id') == $apiProvider->id ? 'selected' : '' }}>{{ $apiProvider->api_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('api_provider_id')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apiServiceIdLabel"
                                                   class="form-label">@lang("API Service ID")</label>
                                            <input type="text"
                                                   class="form-control  @error('API Service ID') is-invalid @enderror"
                                                   name="api_service_id" id="apiServiceIdLabel"
                                                   placeholder="@lang("API Service Id")"
                                                   aria-label="@lang("API Service Id")"
                                                   autocomplete="off"
                                                   value="{{ old('api_service_id') }}">
                                            @error('api_service_id')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div>
                                        <label for="refillLabel" class="form-label">@lang('Select Refill')</label>
                                        <select class="form-select" name="refill" id="refill" autocomplete="off">
                                            <option disabled value="" selected>@lang('Select Refill')</option>
                                            <option
                                                value="1" {{ (old('refill') == 1) ? 'selected' : '' }}>@lang('Manual')</option>
                                            <option class="last"
                                                    value="3" {{ (old('refill') == 3) ? 'selected' : '' }}>@lang('Off')</option>
                                        </select>
                                        @error('refill')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label" for="description">@lang("Description")</label>
                                        <textarea id="description" class="form-control" name="description"
                                                  placeholder="@lang("Description")"
                                                  rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start mt-5">
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
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/tom-select.complete.min.js") }}"></script>
    <script src="{{ asset("assets/global/js/select2.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 1000
            });


            var $serviceType = '';

            $serviceType = $('#manual').data('value');
            checkType($serviceType);

            let automatic = "{{ old('manual_api', 1) }}";

            if ($('#nav-two-eg2-tab').hasClass('active') && automatic) {

                $('#refill .automatic').remove();
                $('<option value="2" {{ (old('refill') == '2') ? 'selected':'' }} class="automatic">@lang('Automatic')</option>').insertBefore('.last');
            }

            $(document).on('click', '#nav-two-eg2-tab', function () {
                $('.manual_api').val(1);
                $(".moreField").removeClass('d-none');
                $('select[name=refill]').val('')
                $(`<option value="2" {{ (old('refill') == '2') ? 'selected':'' }} class="automatic">@lang('Automatic')</option>`).insertBefore('.last');
            });
            $(document).on('click', '#nav-one-eg1-tab', function () {
                $('#refill .automatic').remove();
                $('select[name=refill]').val('')
                $(".moreField").addClass('d-none');
                $('.manual_api').val(0);
            });

            $(document).on('click', "input[name=manual_api]:checked", function () {
                $serviceType = $(this).val();
                checkType($serviceType);
            });

            function checkType(serviceType) {
                if (serviceType == 0) {
                    $('#refill .automatic').remove();
                    return 0;
                } else {
                    $('#refill').append(`<option value="2" {{ (old('refill') == '2') ? 'selected':'' }} class="automatic">@lang('Automatic')</option>`);
                    return 0;
                }
            }

            $(document).ready(function () {
                $('#refill').select2({
                    width: '100%',
                    minimumResultsForSearch: -1
                });
            });

        });
    </script>
@endpush
