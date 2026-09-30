@extends('admin.layouts.app')
@section('page_title', __('Manage Service'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Manage Service")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Manage Service")</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.service.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> @lang("Add Service")
                    </a>
                </div>
            </div>
        </div>


        <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2 mb-4">

            <div id="datatableCounterInfo" style="display: none;">
                <div class="d-sm-flex justify-content-lg-end align-items-sm-center">
                      <span class="d-block d-sm-inline-block fs-5 me-3 mb-2 mb-sm-0">
                        <span id="datatableCounter">0</span>
                        @lang("Selected")
                      </span>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButtonDark"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-sharp fa-solid fa-list-ul me-2"></i>@lang("Action")
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonDark">
                            <a class="dropdown-item btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2"
                               href="javascript:void(0)"
                               data-bs-toggle="modal" data-bs-target="#multipleDeleteModal">
                                <i class="bi-trash"></i> @lang("Delete")
                            </a>
                            <a class="dropdown-item btn btn-outline-success btn-sm mb-2 mb-sm-0 me-2 active-multiple"
                               href="javascript:void(0)"
                               data-bs-toggle="modal" data-bs-target="#multipleActiveDeActiveModal">
                                <i class="bi bi-check2"></i> @lang("Active")
                            </a>

                            <a class="dropdown-item btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2 inactive-multiple"
                               href="javascript:void(0)"
                               data-bs-toggle="modal" data-bs-target="#multipleActiveDeActiveModal">
                                <i class="fa-light fa-ban"></i> @lang("Inactive")
                            </a>

                            <a class="dropdown-item btn btn-outline-info btn-sm mb-2 mb-sm-0 me-2"
                               href="javascript:void(0)"
                               data-bs-toggle="modal" data-bs-target="#priceUpdateModal">
                                <i class="fa-light fa-dollar-sign"></i> @lang("Price Update")
                            </a>

                            <a class="dropdown-item btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2 deleteAllService"
                               href="javascript:void(0)"
                               data-route="{{ route('admin.service.delete.all') }}"
                               data-bs-toggle="modal" data-bs-target="#deleteAllServiceModal">
                                <i class="fal fa-trash-alt"></i> @lang("Delete All Service")
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#importServiceModal">@lang("Import Services")
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
                            <h5 class="card-header-title">@lang('Filter Service')</h5>
                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                    id="filter_close_btn">
                                <i class="bi-x-lg"></i>
                            </button>
                        </div>

                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <span class="text-cap text-body">@lang("Name")</span>
                                        <input type="text" class="form-control" name="name" id="service_filter_input"
                                               value="{{ @request()->name }}"
                                               autocomplete="off">
                                    </div>


                                    <div class="col-12 tom-select-custom mb-4">
                                        <span class="text-cap text-body">@lang("Provider")</span>
                                        <select class="js-select form-select" name="provider" autocomplete="off"
                                                data-hs-tom-select-options='{
                                                  "placeholder": "Select a provider",
                                                  "hideSearch": true
                                                }'>
                                            <option value="">@lang("Select a provider")</option>
                                            @forelse($apiProviders as $provider)
                                                <option
                                                    value="{{ $provider->id }}" {{ @request()->provider ==  $provider->id ? 'selected' : '' }}>@lang($provider->api_name)</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                    <!-- End Select -->

                                    <div class="col-sm-12 mb-5">
                                        <small class="text-cap text-body">@lang("Status")</small>
                                        <div class="tom-select-custom">
                                            <select
                                                class="js-select js-datatable-filter form-select form-select-sm"
                                                name="status"
                                                id="select_status"
                                                data-target-column-index="4" data-hs-tom-select-options='{
                                                          "placeholder": "Any status",
                                                          "searchInDropdown": false,
                                                          "hideSearch": true,
                                                          "dropdownWidth": "10rem"
                                                        }'>
                                                <option value="all"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>@lang("All Status")</span>'>
                                                    @lang("All Status")
                                                </option>
                                                <option value="1" {{ @request()->status == 1 ? 'selected' : '' }}
                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                    @lang("Active")
                                                </option>
                                                <option value="0" {{ @request()->status == 0 ? 'selected' : '' }}
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


        @forelse($categories as $key =>  $category)
            <div class="accordion mb-3" id="accordionService">
                <div class="accordion-item shadow-lg p-2 mb-4 bg-white rounded border-0">
                    <div class="accordion-header" id="heading{{ $key }}">
                        <a class="accordion-button @if(!$loop->first) collapsed @endif text-secondary" role="button"
                           data-bs-toggle="collapse"
                           data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                           aria-controls="collapse{{ $key }}">
                            @lang($category->category_title)
                        </a>
                    </div>
                    <div id="collapse{{ $key }}" class="accordion-collapse collapse @if($loop->first) show @endif"
                         aria-labelledby="heading{{ $key }}"
                         data-bs-parent="#accordionService">
                        <div class="accordion-body">

                            <div class=" table-responsive datatable-custom mt-3">
                                <table id="datatable"
                                       class="js-datatable table table-borderless table-nowrap table-align-middle">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="table-column-pe-0">
                                            <div class="form-check">
                                                <input class="form-check-input check-all tic-check check-all-tic"
                                                       type="checkbox"
                                                       name="check-all"
                                                       id="cat-tic-{{ $key}}">
                                                <label class="form-check-label" for="cat-tic-{{ $key }}"></label>
                                            </div>
                                        </th>
                                        <th>@lang('ID')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Provider')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Drip Feed')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Action')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($category->service as $service)
                                        <tr>
                                            <td class="table-column-pe-0">
                                                <input type="checkbox" id="chk-{{ $service->id }}"
                                                       class="form-check-input row-tic tic-check row-tic-check"
                                                       name="check"
                                                       value="{{ $service->id }}"
                                                       data-id="{{ $service->id }}">
                                            </td>
                                            <td>{{ $service->id }}</td>
                                            <td>@lang(Str::limit($service->service_title, 100))</td>
                                            <td>{{ optional($service->provider)->api_name ?? 'Manual' }}</td>
                                            <td>{{ currencyPosition($service->price) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-soft-{{ $service->drip_feed == 1 ? 'primary' : 'secondary' }} text-{{ $service->drip_feed == 1 ? 'primary' : 'secondary' }}">
                                                    <span
                                                        class="legend-indicator bg-{{ $service->drip_feed == 1 ? 'primary' : 'secondary' }}">
                                                    </span>
                                                    {{ $service->drip_feed == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-soft-{{ $service->service_status == 1 ? 'success' : 'danger' }} text-{{ $service->service_status == 1 ? 'success' : 'danger' }}">
                                                    <span
                                                        class="legend-indicator bg-{{ $service->service_status == 1 ? 'success' : 'danger' }}"></span>
                                                    {{ $service->service_status == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.service.edit', $service->id) }}"
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
                                                               data-route="{{ route('admin.service.status.change', $service->id) }}"
                                                               data-text="{{ $service->service_status == 0 ? 'activate' : 'deactivate' }}"
                                                               data-status="{{ $service->service_status }}">
                                                                <i class="fa-light {{ $service->service_status == 1 ? 'fa-ban' : 'fa-square-check'}} dropdown-item-icon"></i>
                                                                @lang("Mark As") {{ $service->service_status == 0 ? 'Activate' : 'Deactivate' }}
                                                            </a>
                                                            <a class="dropdown-item service-info"
                                                               href="javascript:void(0)"
                                                               data-bs-toggle="modal" data-bs-target="#addInfoModal"
                                                               data-name="{{ $service->service_title }}"
                                                               data-rate_per="{{ $service->price }}"
                                                               data-order_limit="{{ $service->min_amount . ' - ' . $service->max_amount }}">
                                                                <i class="bi bi-info-square dropdown-item-icon"></i> @lang("Description")
                                                            </a>

                                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                                               data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                               data-route="{{ route('admin.service.destroy', $service->id) }}">
                                                                <i class="fa-light fa-trash-can dropdown-item-icon"></i>
                                                                @lang("Delete")
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    </div>

    @if($categories->hasPages())
        <div class="card-footer me-4">
            <div class="d-flex justify-content-center justify-content-sm-end">
                <nav id="datatableWithFilterPagination" aria-label="Activity pagination">
                    {{ $categories->appends($_GET)->links('admin.partials.pagination') }}
                </nav>
            </div>
        </div>
    @endif

    @include('admin.services.components.activate_deactivate')
    @include('admin.services.components.info_modal')
    @include('admin.services.components.delete_modal')
    @include('admin.services.components.import_service_modal')
    @include('admin.services.components.multiple_delete_modal')
    @include('admin.services.components.multiple_activate_deactivate_modal')
    @include('admin.services.components.price_update_modal')
    @include('admin.services.components.delete_all_service_modal')

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-counter.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';

        $(document).on('click', '.check-all-tic', function () {
            $('#datatableCounterInfo').addClass('d-block');
            $(this).closest('table').find('input:checkbox').prop('checked', this.checked);
            updateCheckedCount();
        });

        $(document).on('click', 'input:checkbox', function () {
            $('#datatableCounterInfo').addClass('d-block');
            updateCheckedCount();
        });

        function updateCheckedCount() {
            const checkedCount = $('input:checkbox:checked').length;
            $('#datatableCounter').text(checkedCount);
        }

        $(document).on('click', '.row-tic-check', function () {
            if ($(this).closest('table').find('.row-tic-check').length == $(this).closest('table').find('.row-tic-check:checked').length) {
                $(this).closest('table').find('.check-all-tic').prop('checked', this.checked);
            } else {
                $(this).closest('table').find('.check-all-tic').prop('checked', false);
            }
        });

        $(document).on('click', '.service-info', function () {
            let name = $(this).data('name');
            let rate_per = $(this).data('rate_per');
            let order_limit = $(this).data('order_limit');

            $('#name').text(name);
            $('#rate-per').text(rate_per);
            $('#order-limit').text(order_limit);
        });


        $(document).on('click', '.status-change', function () {
            let route = $(this).data('route');
            $('.setRoute').attr('action', route);
            $('.modal-status-text').text($(this).data('text'));
            $('.status').val($(this).data('status'));
        });

        $(document).ready(function () {
            new HSCounter('.js-counter')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 1001,
            })
        })


        let status;
        $(document).on('click', '.active-multiple', function (e) {
            status = 1;
            $('.status-text').text('activate');
        });

        $(document).on('click', '.inactive-multiple', function (e) {
            status = 0;
            $('.status-text').text('deactivate');
        });

        $(document).on('click', '.active-inactive-multiple', function (e) {
            e.preventDefault();

            let all_value = [];

            $(".row-tic:checked").each(function () {
                all_value.push($(this).attr('data-id'));
            });

            let strIds = all_value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.service.active.inactive.multiple') }}",
                data: {strIds: strIds, status: status},
                dataType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();
                },
            });
        });

        $(document).on('click', '.deleteBtn', function () {
            let route = $(this).data('route');
            $('.deleteRoute').attr('action', route);
        })

        $(document).on('click', '.deleteAllService', function () {
            let route = $(this).data('route');
            $('.allServicedDeleteRoute').attr('action', route);
        })

        $(document).on('click', '.multiple-delete', function (e) {
            e.preventDefault();

            let all_value = [];

            $(".row-tic:checked").each(function () {
                all_value.push($(this).attr('data-id'));
            });

            let strIds = all_value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.service.delete.multiple') }}",
                data: {strIds: strIds, status: status},
                dataType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();
                },
            });
        });

        //multiple price update
        $(document).on('click', '.price-update', function (e) {
            e.preventDefault();
            let all_value = [];
            let percentage = $('.price_percentage_increase').val();
            $(".row-tic:checked").each(function () {
                all_value.push($(this).attr('data-id'));
            });
            let strIds = all_value.join(",");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.service.multiple.price.update') }}",
                data: {strIds: strIds, percentage: percentage},
                dataType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();
                },
            });
        });

    </script>
@endpush





