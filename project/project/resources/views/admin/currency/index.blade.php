@extends('admin.layouts.app')
@section('page_title', __('Manage Currency'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Manage Currency")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Manage Currency")</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.currency.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> @lang("Add Currency")
                    </a>
                </div>
            </div>
        </div>


        <div class="alert alert-soft-dark" role="alert">
            <div class="d-flex align-items-baseline">
                <div class="flex-shrink-0">
                    <i class="bi-info-circle me-1"></i>
                </div>

                <div class="flex-grow-1 ms-2">
                    @lang("To get started, please add your base currency. This is a required step, as the base currency acts as the default reference point for all exchange rate conversions across the multi-currency system.")
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-sm-0">
                    <h4 class="card-header-title">@lang("Currencies")</h4>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-info all-auto-update-currency"
                            data-route="{{ route('admin.currency.all.auto.update') }}"
                            data-bs-toggle="modal" data-bs-target="#autoUpdateCurrencyModal">
                        @lang("All Currency Auto Update")
                    </button>
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
                                    <h5 class="card-header-title">@lang('Filter Category')</h5>
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                            id="filter_close_btn">
                                        <i class="bi-x-lg"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <form action="" method="get">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <span class="text-cap text-body">@lang("Search")</span>
                                                <input type="text" class="form-control" name="search"
                                                       id="category_filter_input" value="{{ request()->search }}"
                                                       autocomplete="off">
                                            </div>

                                            <div class="col-sm-12 mb-5">
                                                <small class="text-cap text-body">@lang("Status")</small>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select"   name="status" autocomplete="off"
                                                            data-hs-tom-select-options='{
                                                              "placeholder": "Any status",
                                                              "hideSearch": true
                                                            }'>
                                                        <option value="">@lang("Any status")</option>
                                                        <option value="1"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                            @lang("Active")
                                                        </option>
                                                        <option value="0"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Inactive</span>'>
                                                            @lang("Inactive")
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" id="filter_button"
                                                    class="btn btn-primary">@lang('Apply')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="table-column-pe-0">
                            @lang("Sl")
                        </th>
                        <th>@lang('Name')</th>
                        <th>@lang('Conversion Rate')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody class="js-sortable">
                    @forelse($currencies as $key => $currency)
                        <tr>
                            <td class="table-column-pe-0">
                                {{ loopIndex($currencies) }}
                            </td>
                            <td>
                                <a class="d-flex align-items-center tr-href text-secondary" data-code="{{ $currency->id }}" href="javascript:void(0)">
                                    <div class="list-group-item">
                                        <i class="sortablejs-custom-handle bi-grip-horizontal list-group-icon"></i>
                                    </div>
                                    @lang($currency->name)
                                </a>
                            </td>
                            <td>{{ number_format($currency->conversion_rate, 2) ." ".$currency->code }}</td>
                            <td>
                                <span
                                    class="badge bg-soft-{{ $currency->status == 1 ? 'success' : 'danger' }} text-{{ $currency->status == 1 ? 'success' : 'danger' }}">
                                    <span
                                        class="legend-indicator bg-{{ $currency->status == 1 ? 'success' : 'danger' }}">
                                    </span>
                                    {{ $currency->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.currency.edit', $currency->id) }}"
                                       class="btn btn-white btn-sm edit_user_btn">
                                        <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                    </a>
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                id="userEditDropdown" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                             aria-labelledby="userEditDropdown">
                                            <a class="dropdown-item status-change"
                                               href="javascript:void(0)" data-bs-toggle="modal"
                                               data-bs-target="#activateDeactivateModal"
                                               data-route="{{ route('admin.currency.status.change', $currency->id) }}"
                                               data-text="{{ $currency->status == 0 ? 'activate' : 'deactivate' }}"
                                               data-status="{{ $currency->status }}">
                                                <i class="fa-light {{ $currency->status == 1 ? 'fa-ban' : 'fa-square-check'}} dropdown-item-icon"></i>
                                                @lang("Mark As") {{ $currency->status == 0 ? 'Activate' : 'Deactivate' }}
                                            </a>
                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                               data-bs-toggle="modal" data-bs-target="#deleteModal"
                                               data-route="{{ route('admin.currency.destroy', $currency->id) }}">
                                                <i class="fa-light fa-trash-can dropdown-item-icon"></i>
                                                @lang("Delete")
                                            </a>

                                            <a class="dropdown-item singleAutoUpdateBtn" href="javascript:void(0)"
                                               data-bs-toggle="modal" data-bs-target="#autoUpdateCurrencyModal"
                                               data-route="{{ route('admin.currency.single.auto.update') }}"
                                               data-currency="{{ $currency->code }}">
                                                <i class="fa-light fa-square-dollar dropdown-item-icon"></i>
                                                @lang("Auto Update Currency")
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

            @if($currencies->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <nav id="datatableWithFilterPagination" aria-label="Activity pagination">
                            {{ $currencies->appends($_GET)->links('admin.partials.pagination') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('admin.currency.components.delete_modal')
    @include('admin.currency.components.activate_deactivate')
    @include('admin.currency.components.auto_currency_update')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
@endpush


@push('script')
    <script>
        'use strict';
        $(document).on('click', '.status-change', function () {
            let route = $(this).data('route');
            $('.setRoute').attr('action', route);
            $('.modal-status-text').text($(this).data('text'));
            $('.status').val($(this).data('status'));
        });

        $(document).on('click', '.deleteBtn', function () {
            let route = $(this).data('route');
            $('.deleteRoute').attr('action', route);
        })

        $(document).on('click', '.all-auto-update-currency', function () {

            let route = $(this).data('route');
            $('.autoUpdateCurrency').attr('action', route);

            let text = $(this).data('text');
            $('.modal-text').text(text);
        })

        $(document).on('click', '.singleAutoUpdateBtn', function () {

            let route = $(this).data('route');
            $('.autoUpdateCurrency').attr('action', route);

            let currency = $(this).data('currency');
            $('#currency_code').val(currency);
        })

        HSCore.components.HSTomSelect.init('.js-select', {
            maxOptions: 250,
        })


        HSCore.components.HSSortable.init('.js-sortable')

        $(document).on('change', '.js-sortable', function () {

            let methods = [];
            $('.js-sortable tr td .tr-href').each(function (key, val) {
                let methodCode = $(val).data('code');
                methods.push(methodCode);
            });
            $.ajax({
                'url': "{{ route('admin.currency.sort.by') }}",
                'method': "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                'data': {sort: methods}
            })
        })
    </script>
@endpush


