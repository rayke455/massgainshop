@extends('admin.layouts.app')
@section('page_title', __('Api Providers'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("API Providers")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("API Providers")</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.api-provider.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> @lang("Add Provider")
                    </a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap">@lang("Total API Provider")</small>
                        <span
                            class="js-counter d-block display-3 text-dark mb-2">{{ $providerData['totalApiProvider'] }}</span>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap"><span class="legend-indicator bg-success"></span> @lang("Active API Provider")</small>
                        <span
                            class="js-counter d-block display-3 text-dark mb-2">{{ $providerData['activeApiProvider'] }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap"><span class="legend-indicator bg-danger"></span> @lang("Inactive API Provider")
                        </small>
                        <span
                            class="js-counter d-block display-3 text-dark mb-2">{{ $providerData['inactiveApiProvider'] }}</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">

            <div class="card-header card-header-content-sm-between">
                <div class="mb-2 mb-sm-0">
                    <h4 class="card-header-title">@lang("API Providers")</h4>
                    <p class="card-text fs-5">@lang("API Providers are services or platforms that offer Application Programming
                        Interfaces.")
                    </p>
                </div>

                <div class="d-grid d-sm-flex justify-content-sm-between align-items-sm-center gap-2">
                    <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm w-100"
                                    id="dropdownMenuClickable" data-bs-auto-close="false"
                                    id="usersFilterDropdown"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="bi-filter me-1"></i> @lang('Filter')
                            </button>

                            <div
                                class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered filter_dropdown"
                                aria-labelledby="dropdownMenuClickable">
                                <div class="card">
                                    <div class="card-header card-header-content-between">
                                        <h5 class="card-header-title">@lang('Filter')</h5>
                                        <button type="button"
                                                class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                                id="filter_close_btn">
                                            <i class="bi-x-lg"></i>
                                        </button>
                                    </div>

                                    <div class="card-body">
                                        <form id="filter_form" action="" method="GET">
                                            <div class="mb-4">
                                                <span class="text-cap text-body">@lang('PROVIDER NAME')</span>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="provider_name"
                                                               id="verification_type_filter_input"
                                                               value="{{ request()->provider_name }}"
                                                               autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm mb-5">
                                                    <small class="text-cap text-body">@lang('Status')</small>
                                                    <div class="tom-select-custom">
                                                        <select
                                                            class="js-select js-datatable-filter form-select form-select-sm"
                                                            name="status"
                                                            id="filter_status"
                                                            data-target-column-index="4" data-hs-tom-select-options='{
                                                                  "placeholder": "Any status",
                                                                  "searchInDropdown": false,
                                                                  "hideSearch": true,
                                                                  "dropdownWidth": "10rem"
                                                                }'>
                                                            <option value="1"
                                                                    {{ request()->status == 1 ? 'selected' : '' }}
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                                @lang('Active')
                                                            </option>
                                                            <option value="0"
                                                                    {{ request()->status === 0 ? 'selected' : '' }}
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Inactive</span>'>
                                                                @lang('Inactive')
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row gx-2">
                                                <div class="col">
                                                    <div class="d-grid">
                                                        <button type="button" id="clear_filter"
                                                                class="btn btn-white">@lang('Clear Filters')</button>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary"
                                                                id="filter_button"><i
                                                                class="bi-search"></i> @lang('Apply')</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive datatable-custom">
                <table id="datatable"
                       class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="table-column-pe-0">
                            @lang("Sl")
                        </th>
                        <th class="table-column-ps-0">@lang("Name")</th>
                        <th>@lang("API Key")</th>
                        <th>@lang("Balance")</th>
                        <th>@lang("Status")</th>
                        <th>@lang("Action")</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($apiProviders as $key => $provider)
                        <tr>
                            <td class="table-column-pe-0">
                                {{ $loop->index + 1 }}
                            </td>
                            <td class="table-column-ps-0">{{ $provider->api_name }}</td>
                            <td>
                                <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                    <input type="password" class="js-toggle-password form-control form-control-lg" value="{{ $provider->api_key }}"
                                           name="password" id="signupSimpleLoginPassword" placeholder="@lang("API KEY")"
                                           aria-label="API KEY" required
                                           data-hs-toggle-password-options='{
                                             "target": "#changePassTarget{{$key}}",
                                             "defaultClass": "bi-eye-slash",
                                             "showClass": "bi-eye",
                                             "classChangeTarget": "#changePassIcon{{$key}}"
                                           }'>
                                    <a id="changePassTarget{{$key}}" class="input-group-append input-group-text" href="javascript:void(0)">
                                        <i id="changePassIcon{{$key}}" class="bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                            <td>
                                {{ $provider->balance }} {{ $provider->currency }}
                            </td>
                            <td>
                                <span class="badge bg-soft-{{ $provider->status == 1 ? 'success' : 'danger' }} text-{{ $provider->status == 1 ? 'success' : 'danger' }}">
                                    <span class="legend-indicator bg-{{ $provider->status == 1 ? 'success' : 'danger' }}"></span> {{ $provider->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-white btn-sm"
                                       href="{{ route('admin.api-provider.edit', $provider->id) }}">
                                        <i class="bi-pencil-fill me-1"></i> Edit
                                    </a>

                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                             aria-labelledby="productsEditDropdown1">
                                            <a class="dropdown-item status-change" href="javascript:void(0)"
                                               data-route="{{route('admin.provider.status', $provider->id)}}"
                                               data-status="{{ $provider->status }}"
                                               data-bs-toggle="modal" data-bs-target="#activeDeActiveModal">
                                                <i class="fa-light {{ $provider->status == 0 ? 'fa-check' : 'fa-ban' }}  dropdown-item-icon"></i>
                                                {{ $provider->status == 0 ? 'Mark As Activate' : 'Mark As Deactivate' }}
                                            </a>
                                            <a class="dropdown-item setCurrencyButton" href="javascript:void(0)"
                                               data-route="{{ route('admin.provider.set.currency', $provider->id) }}"
                                               data-conversion_rate="{{ $provider->conversion_rate }}"
                                               data-api_currency="{{ $provider->currency }}"
                                               data-bs-toggle="modal" data-bs-target="#setCurrencyModal">
                                                <i class="fa-light fa-square-dollar dropdown-item-icon"></i> @lang("Set Currency")
                                            </a>
                                            <a class="dropdown-item update-service-price" href="javascript:void(0)"
                                               data-route="{{ route('admin.provider.price.update', $provider->id) }}"
                                               data-bs-toggle="modal" data-bs-target="#updatePriceModal">
                                                <i class="fa-light fa-money-bill dropdown-item-icon"></i> @lang("Update Service Price")
                                            </a>
                                            <a class="dropdown-item balance-update" href="javascript:void(0)"
                                               data-route="{{ route('admin.provider.balance.update', $provider->id) }}"
                                               data-bs-toggle="modal" data-bs-target="#updateProviderBalanceModal">
                                                <i class="fa-light fa-credit-card dropdown-item-icon"></i> @lang("Update Provider Balance")
                                            </a>

                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                               data-route="{{ route('admin.api-provider.destroy', $provider->id) }}">
                                                <i class="bi-trash dropdown-item-icon"></i> @lang("Delete")
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="odd">
                            <td valign="top" colspan="8" class="dataTables_empty">
                                <div class="text-center p-4">
                                    <img class="mb-3 dataTables-image"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img class="mb-3 dataTables-image"
                                         src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang("No data to show")</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            @if($apiProviders->lastPage() > 1)
                <div class="card-footer">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <nav id="datatableWithFilterPagination" aria-label="Activity pagination">
                            {{ $apiProviders->appends($_GET)->links('admin.partials.pagination') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('admin.api_providers.components.activate_deactivate_modal')
    @include('admin.api_providers.components.set_currency_modal')
    @include('admin.api_providers.components.update_service_price_modal')
    @include('admin.api_providers.components.update_provider_balance_modal')
    @include('admin.api_providers.components.delete_modal')

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">

@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-toggle-password.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-counter.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            $(document).on('click', '.status-change', function () {
                let route = $(this).data('route');
                $('#statusForm').attr('action', route);
                $('.status').val($(this).data('status'));
            });

            $(document).on('click', '.deleteBtn', function () {
                let route = $(this).data('route');
                $('.deleteRoute').attr('action', route);
            })

            $(document).on('click', '.setCurrencyButton', function () {
                let conversion_rate = $(this).data('conversion_rate');
                let api_currency = $(this).data('api_currency');

                $('.api_currency').text(api_currency);
                $('#convRateInput').val(conversion_rate);

                let route = $(this).data('route');
                $('.currencySet').attr('action', route);
            });

            if ($('#conversionRateError').length) {
                $('#setCurrencyModal').modal('show');
            }

            $(document).on('click', '.update-service-price', function () {
                let route = $(this).data('route');
                $('#updatePriceForm').attr('action', route);
            });

            $(document).on('click', '.balance-update', function () {
                let route = $(this).data('route');
                $('#updateBalanceForm').attr('action', route);
            });

            HSCore.components.HSTomSelect.init('.js-select')
            new HSTogglePassword('.js-toggle-password')
            new HSCounter('.js-counter')
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
        })
    </script>
@endpush




