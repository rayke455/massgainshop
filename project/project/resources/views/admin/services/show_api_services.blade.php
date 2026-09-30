@extends('admin.layouts.app')
@section('page_title',__(ucfirst($apiProvider->api_name)))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Manage Service')</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang(ucfirst($apiProvider->api_name))</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang(ucfirst($apiProvider->api_name))</h1>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="datatableSearch" type="search" class="form-control" placeholder="Search users"
                               aria-label="Search users" autocomplete="off">
                    </div>
                </div>

                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                    <div id="datatableCounterInfo">
                        <div class="d-flex align-items-center">
                            <span class="fs-5 me-3">
                              <span id="datatableCounter">0</span>
                              @lang('Selected')
                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-primary import-multiple" data-bs-toggle="modal"
                            data-bs-target="#addBulkServiceModal"
                            data-route="{{ route('admin.api.service.import.multiple',['provider'=>$apiProvider->id]) }}">
                        @lang("Add Bulk Service")
                    </button>
                </div>
            </div>


            <div class=" table-responsive datatable-custom">
                <table id="datatable"
                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                       "columnDefs": [{
                          "targets": [0, 6],
                          "orderable": false
                        }],
                       "order": [],
                       "info": {
                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                       },
                       "search": "#datatableSearch",
                       "entries": "#datatableEntries",
                       "pageLength": 50,
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
                        <th class="table-column-ps-0">@lang('Service ID')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Category')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Drip Feed')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>


            <div class="card-footer">
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                            <span class="me-2">@lang('Showing:')</span>
                            <div class="tom-select-custom">
                                <select id="datatableEntries"
                                        class="js-select form-select form-select-borderless w-auto" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                    <option value="20">20</option>
                                    <option value="50" selected>50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
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


    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog" aria-labelledby="addServiceModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addServiceModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" id="importForm">
                    @csrf
                    <div class="modal-body">
                        <label>@lang('Select Percentage Increase')</label>
                        <div class="tom-select-custom">
                            <select class="js-select form-select" name="price_percentage_increase" autocomplete="off"
                                    data-hs-tom-select-options='{
                                    "placeholder": ""
                                }'>
                                <option value="100" selected>@lang('100%')</option>
                                @for($loop = 0; $loop <= 1000; $loop++)
                                    <option value="{{$loop }}">{{ $loop }} %</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mt-3">
                            <label for="selectCategoryLabel"
                                   class="form-label">@lang('Select Category') <sub>(@lang("Optional"))</sub></label>
                            <div class="tom-select-custom tom-select-custom-with-tags">
                                <select class="js-select form-select" name="category_id" autocomplete="off"
                                        data-hs-tom-select-options='{
                                    "placeholder": "Select Category"
                                  }'>
                                    <option value="">@lang("Select Category")</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <p class="mt-3">@lang("Are you really want to import service?")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-white"
                                data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-sm btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Service Modal -->



    <!-- Modal -->
    <div class="modal fade" id="addBulkServiceModal" tabindex="-1" role="dialog"
         aria-labelledby="addBulkServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addServiceModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Import Bulk Service")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="" method="post" id="importMultipleForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="selectedValue" class="form-label">@lang('Bulk add limit')</label>
                            <div class="tom-select-custom">
                                <select class="js-select form-select" name="import_quantity" id="selectedValue"
                                        autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hidePlaceholderOnSearch": true,
                                            "placeholder": ""
                                          }'>
                                    <option value="selectItem"
                                            class="selectedServices">@lang('Add Selected Service')</option>
                                    @for($loop = 25; $loop <= 1000; $loop+=25)
                                        <option value="{{$loop}}">{{$loop}}</option>
                                    @endfor
                                    <option value="all">@lang("All")</option>
                                </select>
                                <input type="hidden" value="" name="select_service" class="selectService">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="increasePercentageLabel"
                                   class="form-label">@lang('Select Percentage Increase')</label>
                            <div class="tom-select-custom">
                                <select class="js-select form-select" name="price_percentage_increase"
                                        autocomplete="off"
                                        data-hs-tom-select-options='{
                                        "searchInDropdown": false,
                                        "hidePlaceholderOnSearch": true,
                                        "placeholder": ""
                                      }'>
                                    <option value="100" selected>@lang('100%')</option>
                                    @for($loop = 0; $loop <= 1000; $loop++)
                                        <option value="{{ $loop }}">{{ $loop }} %</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <p>@lang('Are you really want to import all service?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-white"
                                data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-sm btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
@endpush


@push('script')
    <script>
        $(document).ready(function() {
            $('#selectedValue option[value="selectItem"]').text('my name jubyer');
            $('#selectedValue')[0].tomselect.updateOption('selectItem', {text: 'my name jubyer', value: 'selectItem'});
        });

        $(document).on('click', '.import-single', function () {
            let route = $(this).data('route');
            $('#importForm').attr('action', route);
        });

        $(document).on('click', '.import-multiple', function () {
            let all_value = [];
            $(".row-tic:checked").each(function () {
                all_value.push($(this).attr('data-id'));
            });
            let strIds = all_value.join(',');

            $('#selectedValue option[value="selectItem"]').text(`Add ${all_value.length} Selected Service`);
            $('#selectedValue')[0].tomselect.updateOption('selectItem', {text: `Add ${all_value.length} Selected Service`, value: 'selectItem'});

            $('.selectService').val(strIds);

            let route = $(this).data('route');
            $('#importMultipleForm').attr('action', route);
        });


        $(document).on('ready', function () {
            new HSCounter('.js-counter')
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 10001,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                DataType: "json",
                contentType: "application/json",
                ajax: {
                    url: "{{ route("admin.list.api.services", $apiProvider->id) }}",

                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'service_id', name: 'service_id'},
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
                    {data: 'price', name: 'price'},
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

            $.fn.dataTable.ext.errMode = 'throw';

            $(document).on('click', '#datatableCheckAll', function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
        });
    </script>
@endpush




