@extends('admin.layouts.app')
@section('page_title', __('Manage Category'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Manage Category")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Manage Category")</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.category.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> @lang("Add Category")
                    </a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap">@lang("Total Category")</small>
                        <span class="js-counter d-block display-3 text-dark mb-2">
                            {{ $categoryData['totalCategory'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap"><span
                                class="legend-indicator bg-success"></span> @lang("Active Category")</small>
                        <span class="js-counter d-block display-3 text-dark mb-2">
                            {{ $categoryData['activeCategory'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap"><span
                                class="legend-indicator bg-danger"></span> @lang("Inactive Category")
                        </small>
                        <span class="js-counter d-block display-3 text-dark mb-2">
                            {{ $categoryData['inactiveCategory'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-end mb-3">
            <div class="col-lg">
                <div id="datatableCounterInfo" style="display: none;">
                    <div class="d-sm-flex justify-content-lg-end align-items-sm-center">
                      <span class="d-block d-sm-inline-block fs-5 me-3 mb-2 mb-sm-0">
                        <span id="datatableCounter">0</span>
                        @lang("Selected")
                      </span>
                        <a class="btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2" href="javascript:void(0)"
                           data-bs-toggle="modal" data-bs-target="#multipleDeleteModal">
                            <i class="bi-trash"></i> @lang("Delete")
                        </a>
                        <a class="btn btn-outline-success btn-sm mb-2 mb-sm-0 me-2 active-multiple"
                           href="javascript:void(0)"
                           data-bs-toggle="modal" data-bs-target="#multipleActiveDeActiveModal">
                            <i class="bi bi-check2"></i> @lang("Active")
                        </a>

                        <a class="btn btn-outline-danger btn-sm mb-2 mb-sm-0 me-2 inactive-multiple"
                           href="javascript:void(0)"
                           data-bs-toggle="modal" data-bs-target="#multipleActiveDeActiveModal">
                            <i class="fa-light fa-ban"></i> @lang("Inactive")
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <div class="input-group input-group-merge navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input type="search" id="datatableSearch"
                               class="search form-control form-control-sm"
                               placeholder="@lang('Search Category')"
                               aria-label="@lang('Search Category')"
                               autocomplete="off">
                        <a class="input-group-append input-group-text" href="javascript:void(0)">
                            <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
                        </a>
                    </div>
                </div>

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
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <span class="text-cap text-body">@lang("Name")</span>
                                        <input type="text" class="form-control" id="category_filter_input"
                                               autocomplete="off">
                                    </div>
                                    <div class="col-sm-12 mb-5">
                                        <small class="text-cap text-body">@lang("Status")</small>
                                        <div class="tom-select-custom">
                                            <select
                                                class="js-select js-datatable-filter form-select form-select-sm"
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
                                    <button type="button" id="filter_button"
                                            class="btn btn-primary">@lang('Apply')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Header -->

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                       "columnDefs": [{
                          "targets": [0, 3],
                          "orderable": false
                        }],
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
                                <input class="form-check-input check-all tic-check" type="checkbox" name="check-all"
                                       id="datatableCheckAll">
                                <label class="form-check-label" for="datatableCheckAll"></label>
                            </div>
                        </th>
                        <th>@lang('Name')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>

                    <tbody class="js-sortable">

                    </tbody>
                </table>
            </div>

            @if($categoryData['totalCategory'] > 20)
                <div class="card-footer">
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
            @endif
        </div>
    </div>

    @include('admin.category.components.single_activate_deactivate_modal')
    @include('admin.category.components.multiple_delete_modal')
    @include('admin.category.components.single_delete_modal')
    @include('admin.category.components.multiple_activate_deactivate_modal')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-counter.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            $(document).on('click', '.status-change', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
                $('.modal-status-text').text($(this).data('text'));
                $('.status').val($(this).data('status'));
            });

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.category.showing.with.datatable") }}",

                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'name', name: 'name'},
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
            });

            $(document).on('click', '#filter_button', function () {
                let filterSelectedStatus = $('#select_status').val();
                let filterName = $('#category_filter_input').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);

                datatable.ajax.url("{{ route('admin.category.showing.with.datatable') }}" + "?filterStatus=" + filterSelectedStatus +
                    "&filterName=" + filterName).load();
            });
            $.fn.dataTable.ext.errMode = 'throw';

            new HSCounter('.js-counter')
            HSCore.components.HSTomSelect.init('.js-select')
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
                url: "{{ route('admin.category.active.inactive') }}",
                data: {strIds: strIds, status: status},
                datatType: 'json',
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
                url: "{{ route('admin.category.delete.multiple') }}",
                data: {strIds: strIds, status: status},
                dataType: 'json',
                type: "post",
                success: function (data) {
                    location.reload();
                },
            });
        });

        HSCore.components.HSSortable.init('.js-sortable')

        $(document).on('change', '.js-sortable', function () {
            let methods = [];
            $('.js-sortable tr td .tr-href').each(function (key, val) {
                let methodCode = $(val).data('code');
                methods.push(methodCode);
            });
            $.ajax({
                'url': "{{ route('admin.category.sorting') }}",
                'method': "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                'data': {sort: methods}
            })
        })

    </script>
@endpush





