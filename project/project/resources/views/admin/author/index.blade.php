@extends('admin.layouts.app')
@section('page_title', __('Authors'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Blog')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Authors')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Authors')</h1>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0">@lang('Authors')</h4>
                <a href="{{ route('admin.author.create') }}" class="btn btn-sm btn-primary">@lang('Create Author')</a>
            </div>

            <div class="table-responsive">
                <table class="table table-thead-bordered  table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Location')</th>
                        <th>@lang('Status')</th>
                        <th class="text-center">
                            @foreach($allLanguage as $language)
                                <img class="avatar avatar-xss avatar-square me-2"
                                     src="{{ getFile($language->flag_driver, $language->flag) }}"
                                     alt="{{ $language->name }} Flag">
                            @endforeach
                        </th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($authors as $key => $item)
                        <tr>
                            <td class="table-text-start">
                                <a class="d-flex align-items-center" href="javascript:void(0)">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" src="{{ getFile($item->image_driver, $item->image) }}"
                                             alt="Image Description">
                                    </div>
                                    <div class="ms-3">
                                        <span class="d-block h5 text-inherit mb-0">@lang(optional($item->details)->name)</span>
                                    </div>
                                </a>
                            </td>
                            <td>@lang(optional($item->details)->address)</td>
                            <td>
                                @if($item->status == 0)
                                    <span class="badge bg-soft-danger text-danger">
                                                <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                            </span>
                                @elseif($item->status == 1)
                                    <span class="badge bg-soft-success text-success">
                                                <span class="legend-indicator bg-success"></span>@lang('Active')
                                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @foreach($allLanguage as $language)
                                    <a href="{{ route('admin.author.edit', [$item->id, $language->id]) }}"
                                       class="btn btn-white btn-icon btn-sm flag-btn"
                                       target="_blank">
                                        <i class="bi {{ $item->getLanguageEditClass($item->id, $language->id) }}"></i>
                                    </a>
                                @endforeach
                            </td>

                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-white btn-sm" href="{{ route('admin.author.edit', [$item->id, $defaultLanguage->id]) }}">
                                        <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                    </a>

                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                             aria-labelledby="productsEditDropdown1">
                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                               data-route="{{ route("admin.author.destroy", $item->id) }}"
                                               data-bs-toggle="modal" data-bs-target="#deleteModal">
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
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method("delete")
                    <div class="modal-body">
                        <p>@lang("Do you want to delete this author?")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Delete Modal -->

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/flatpickr/dist/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            $('.deleteBtn').on('click', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            })
        })

    </script>
@endpush



