@extends('admin.layouts.app')
@section('page_title', __('Manage Orders'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Orders')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Manage Orders')</h1>
                </div>
            </div>
        </div>

        @php
            $lastSegment = basename(url()->current());
        @endphp

        <div class="js-nav-scroller hs-nav-scroller-horizontal mb-5">
            <span class="hs-nav-scroller-arrow-prev" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:void(0)">
                <i class="bi-chevron-left"></i>
              </a>
            </span>

            <span class="hs-nav-scroller-arrow-next" style="display: none;">
              <a class="hs-nav-scroller-arrow-link" href="javascript:void(0)">
                <i class="bi-chevron-right"></i>
              </a>
            </span>

            <ul class="nav nav-tabs align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'list' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'list') }}">@lang("All Orders")<span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->totalOrder }}</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'awaiting' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'awaiting') }}">@lang("Awaiting")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->awaitingOrder }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'pending' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'pending') }}">@lang("Pending")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->pendingOrder }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'processing' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'processing') }}">@lang("Processing") <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->processingOrder }}</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'progress' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'progress') }}">@lang("Progress")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->progressOrder }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'completed' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'completed') }}">@lang("Completed")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->completedOrder }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'partial' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'partial') }}">@lang("Partial")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->partialOrder }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'canceled' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'canceled') }}">@lang("Canceled")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->canceledOrder }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ $lastSegment == 'refunded' ? 'active' : '' }}"
                       href="{{ route('admin.order', 'refunded') }}">@lang("Refunded")
                        <span
                            class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $orderRecord->refundedOrder }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header card-header-content-md-between">
                        <div class="mb-2 mb-md-0">
                            <div class="input-group input-group-merge navbar-input-group">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input type="search" id="datatableSearch"
                                       class="search form-control form-control-sm"
                                       placeholder="@lang('Search Orders')"
                                       aria-label="@lang('Search Orders')"
                                       autocomplete="off">
                                <a class="input-group-append input-group-text" href="javascript:void(0)">
                                    <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <div id="datatableCounterInfo" style="display: none;">
                                <div class="d-sm-flex justify-content-lg-end align-items-sm-center">
                                  <span class="d-block d-sm-inline-block fs-5 me-3 mb-2 mb-sm-0">
                                    <span id="datatableCounter">0</span>
                                    @lang("Selected")
                                  </span>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-dark dropdown-toggle" type="button"
                                                id="dropdownMenuButtonDark"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-sharp fa-solid fa-list-ul me-2"></i>@lang("Action")
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonDark">
                                            <a class="dropdown-item btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2"
                                               href="javascript:void(0)" data-status="awaiting"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-dark"></span> @lang("Awaiting")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-success btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="pending"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-warning"></span> @lang("Pending")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-success btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="processing"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-info"></span> @lang("Processing")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="progress"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-primary"></span> @lang("In Progress")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-info btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="completed"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-success"></span> @lang("Completed")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-info btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="partial"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-secondary"></span> @lang("Partial")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-info btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="canceled"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-danger"></span> @lang("Canceled")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-info btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="refunded"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-danger"></span> @lang("Refunded")
                                            </a>
                                            <a class="dropdown-item btn btn-outline-info btn-sm mb-2 mb-sm-0 me-2 usersOrderChangeStatus"
                                               href="javascript:void(0)" data-status="fail"
                                               data-bs-toggle="modal" data-bs-target="#multipleStatusChangeModal">
                                                <span class="legend-indicator bg-danger"></span> @lang("Fail")
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                                <form id="filter_form">
                                                    <div class="row">
                                                        <div class="col-12 mb-4">
                                                            <span class="text-cap text-body">@lang("User")</span>
                                                            <input type="text" class="form-control"
                                                                   id="user_filter_input"
                                                                   autocomplete="off">
                                                        </div>
                                                        <div class="col-12 mb-4">
                                                            <span class="text-cap text-body">@lang("Order ID")</span>
                                                            <input type="text" class="form-control"
                                                                   id="order_id_filter_input"
                                                                   autocomplete="off">
                                                        </div>
                                                        <div class="col-12 mb-4">
                                                            <span class="text-cap text-body">@lang("Service")</span>
                                                            <input type="text" class="form-control"
                                                                   id="service_filter_input"
                                                                   autocomplete="off">
                                                        </div>

                                                        <div class="col-sm-12 mb-5">
                                                            <small class="text-cap text-body">@lang("Status")</small>
                                                            <div class="tom-select-custom">
                                                                <select
                                                                    class="js-select js-datatable-filter form-select form-select-sm"
                                                                    id="select_status"
                                                                    data-target-column-index="4"
                                                                    data-hs-tom-select-options='{
                                                                      "placeholder": "Any status",
                                                                      "searchInDropdown": false,
                                                                      "hideSearch": true,
                                                                      "dropdownWidth": "10rem"
                                                                    }'>
                                                                    <option value="list"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>@lang("Any Status")</span>'>
                                                                        @lang("Any Status")
                                                                    </option>
                                                                    <option value="awaiting"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>Awaiting</span>'>
                                                                        @lang("Awaiting")
                                                                    </option>
                                                                    <option value="pending"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Pending</span>'>
                                                                        @lang("Pending")
                                                                    </option>
                                                                    <option value="processing"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-info"></span>Processing</span>'>
                                                                        @lang("processing")
                                                                    </option>
                                                                    <option value="progress"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-primary"></span>In Progress</span>'>
                                                                        @lang("In Progress")
                                                                    </option>
                                                                    <option value="completed"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Completed</span>'>
                                                                        @lang("Completed")
                                                                    </option>
                                                                    <option value="partial"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>Partial</span>'>
                                                                        @lang("Partial")
                                                                    </option>
                                                                    <option value="Canceled"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Canceled</span>'>
                                                                        @lang("Canceled")
                                                                    </option>
                                                                    <option value="refunded"
                                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Refunded</span>'>
                                                                        @lang("Refunded")
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12 mb-4">
                                                            <span class="text-cap text-body">@lang('Date Range')</span>
                                                            <div class="input-group mb-3 custom">
                                                                <input type="text" id="filter_date_range"
                                                                       class="js-flatpickr form-control"
                                                                       placeholder="Select dates"
                                                                       data-hs-flatpickr-options='{
                                                                 "dateFormat": "d/m/Y",
                                                                 "mode": "range"
                                                               }' aria-describedby="flatpickr_filter_date_range">
                                                                <span class="input-group-text"
                                                                      id="flatpickr_filter_date_range">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </span>
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
                                                                <button type="button" class="btn btn-primary"
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


                    <div class=" table-responsive datatable-custom  ">
                        <table id="datatable"
                               class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                       "columnDefs": [{
                                          "targets": [0, 8],
                                          "orderable": false
                                        }],
                                        "ordering": false,
                                       "order": [],
                                       "info": {
                                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                                       },
                                       "search": "#datatableSearch",
                                       "entries": "#datatableEntries",
                                       "pageLength": 20,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"


                                     }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="table-column-pe-0">
                                    <div class="form-check">
                                        <input class="form-check-input check-all tic-check" type="checkbox"
                                               name="check-all"
                                               id="datatableCheckAll">
                                        <label class="form-check-label" for="datatableCheckAll"></label>
                                    </div>
                                </th>
                                <th>@lang('Order ID')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Order Details')</th>
                                <th>@lang('Order Type')</th>
                                <th>@lang('Service Type')</th>
                                <th>@lang('Created At')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer displayOnOff">
                        <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                            <div class="col-sm mb-2 mb-sm-0">
                                <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                    <span class="me-2">@lang('Showing:')</span>
                                    <div class="tom-select-custom">
                                        <select id="datatableEntries"
                                                class="js-select form-select form-select-borderless w-auto"
                                                autocomplete="off"
                                                data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                            <option value="10">10</option>
                                            <option value="20" selected>20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                        </select>
                                    </div>
                                    <span class="text-secondary me-2">of</span>
                                    <span id="datatableWithPaginationInfoTotalQty"></span>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="d-flex  justify-content-center justify-content-sm-end">
                                    <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.orders.components.delete_modal')
    @include('admin.orders.components.order_summery')
    @include('admin.orders.components.status_change_modal')
    @include('admin.orders.components.multiple_status_change_modal')
    @include('admin.orders.components.order_resend_modal')

@endsection

@push('css')
    <style>
        .page-header {
            margin-bottom: 1.25rem !important;
        }
    </style>
@endpush

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).on('ready', function () {

            $(document).on('click', '.orderResendBtn', function () {
                let route = $(this).data('route');
                $('.setResendRoute').attr('action', route);
            });

            $(document).on('click', '.deleteBtn', function () {
                let route = $(this).data('route');
                $('.deleteRoute').attr('action', route);
            });

            $(document).on('click', '.status-change', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            });

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.order.show", $status) }}",
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'order_id', name: 'order_id'},
                    {data: 'user', name: 'user'},
                    {data: 'order_details', name: 'order_details'},
                    {data: 'order_type', name: 'order_type'},
                    {data: 'service_type', name: 'service_type'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },
            })

            document.getElementById("filter_button").addEventListener("click", function () {
                let filterUser = $('#user_filter_input').val();
                let filterOrderId = $('#order_id_filter_input').val();
                let filterService = $('#service_filter_input').val();
                let filterStatus = $('#select_status').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route("admin.order.show", $status ) }}" + "?filterUser=" + filterUser + "&filterOrderId=" + filterOrderId + "&filterService=" + filterService
                    + "&filterStatus=" + filterStatus + "&filterDate=" + filterDate).load();
            });

            document.getElementById("clear_filter").addEventListener("click", function () {
                document.getElementById("filter_form").reset();
            });
            $.fn.dataTable.ext.errMode = 'throw';
        });


        $(document).on('click', '.usersOrderChangeStatus', function () {
            status = $(this).data('status');
            $('.text-status').text(status)
        });

        $(document).on('click', '.statusChangeMultiple', function (e) {
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
                url: "{{ route('admin.multi.status.change') }}",
                data: {strIds: strIds, status: status},
                datatType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();
                },
            });
        });


        $(document).on('click', '.openOrderSummary', function () {
            const order = $(this).data('order');

            let basicCurrency = "{{ basicControl()->base_currency ?? 'USD' }}";

            $('#modalService').text(order.service.service_title || 'N/A');
            $('#modalLink').text(order.link || 'N/A').attr('href', order.link || '#');

            $('#modalPrice').text(order.price ? parseFloat(order.price).toFixed(4) + ' ' + basicCurrency : 'N/A');

            $('#modalQuantity').text(order.quantity || 'N/A');
            $('#modalStartCounter').text(order.start_counter ?? 'N/A');
            $('#modalRemains').text(order.remains ?? 'N/A');
        });

        $(document).ajaxComplete(function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush



