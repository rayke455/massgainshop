@extends('admin.layouts.app')
@section('page_title', __('Social Media'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Social Media")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Social Media")</h1>
                </div>
                <div class="col-sm-auto">
                    <a href="{{ route('admin.social-media.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> @lang("Add Social Media")
                    </a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <!-- Card -->
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap">@lang("Total Social Media")</small>
                        <span class="js-counter d-block display-3 text-dark mb-2">
                            {{ $socialMediaData['totalSocialMedia'] }}
                        </span>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap"><span
                                class="legend-indicator bg-success"></span> @lang("Active Social Media")</small>
                        <span class="js-counter d-block display-3 text-dark mb-2">
                            {{ $socialMediaData['activeSocialMedia'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-cap"><span
                                class="legend-indicator bg-danger"></span> @lang("Inactive Social Media")
                        </small>
                        <span class="js-counter d-block display-3 text-dark mb-2">
                            {{ $socialMediaData['inactiveSocialMedia'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <div class="card-header card-header-content-sm-between">
                <div class="mb-2 mb-sm-0">
                    <h4 class="card-header-title">@lang("Social Media")</h4>
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
                                                <span class="text-cap text-body">@lang('SOCIAL MEDIA NAME')</span>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="name"
                                                               id="verification_type_filter_input"
                                                               value="{{ request()->name }}"
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

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-borderless table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang("Sl")</th>
                        <th class="table-column-ps-0">@lang("Name")</th>
                        <th>@lang("Status")</th>
                        <th>@lang("Action")</th>
                    </tr>
                    </thead>
                    <tbody class="js-sortable">
                    @forelse($socialMedia as $media)
                        <tr>
                            <td class="table-column-pe-0">
                                {{ loopIndex($socialMedia) }}
                            </td>
                            <td class="table-column-ps-0">
                                <a class="d-flex align-items-center tr-href" href="javascript:void(0)" data-code="{{ $media->id }}">
                                    <div class="list-group-item">
                                        <i class="sortablejs-custom-handle bi-grip-horizontal list-group-icon"></i>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-sm avatar-circle">
                                            <img class="avatar-img"
                                                 src="{{ getFile($media->icon_driver, $media->icon) }}"
                                                 alt="Icon">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="text-inherit mb-0">@lang($media->name)
                                        </h5>
                                    </div>
                                </a>
                            </td>
                            <td>
                                @if($media->status == 1)
                                    <span class="badge bg-soft-success text-success">
                                            <span class="legend-indicator bg-success"></span>@lang("Active")
                                        </span>
                                @else
                                    <span class="badge bg-soft-danger text-danger">
                                            <span class="legend-indicator bg-danger"></span>@lang("Inactive")
                                        </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-white btn-sm"
                                       href="{{ route('admin.social-media.edit', $media->id) }}">
                                        <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                    </a>

                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                             aria-labelledby="productsEditDropdown1">
                                            <a class="dropdown-item status-change" href="javascript:void(0)"
                                               data-route="{{route('admin.social.media.status.change', $media->id)}}"
                                               data-status="{{ $media->status }}"
                                               data-bs-toggle="modal" data-bs-target="#activeDeActiveModal">
                                                <i class="fa-light {{ $media->status == 0 ? 'fa-check' : 'fa-ban' }}  dropdown-item-icon"></i>
                                                {{ $media->status == 0 ? 'Mark As Activate' : 'Mark As Deactivate' }}
                                            </a>
                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                               data-route="{{ route('admin.social-media.destroy', $media->id) }}"
                                               data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="bi bi-trash dropdown-item-icon"></i> @lang("Delete")
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

            @if($socialMedia->lastPage() > 1)
                <div class="card-footer">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <nav id="datatableWithFilterPagination" aria-label="Activity pagination">
                            {{ $socialMedia->appends($_GET)->links('admin.partials.pagination') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('admin.social_media.components.delete_modal')
    @include('admin.social_media.components.activate_deactivate_modal')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-counter.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
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

            $('.deleteBtn').on('click', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            })

            new HSCounter('.js-counter')
            HSCore.components.HSTomSelect.init('.js-select')


            HSCore.components.HSSortable.init('.js-sortable')

            $(document).on('change', '.js-sortable', function () {

                let methods = [];
                $('.js-sortable tr td .tr-href').each(function (key, val) {
                    let methodCode = $(val).data('code');
                    methods.push(methodCode);
                });
                $.ajax({
                    'url': "{{ route('admin.social.media.sort.by') }}",
                    'method': "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    'data': {sort: methods}
                })
            })
        })
    </script>
@endpush




