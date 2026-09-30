@extends('admin.layouts.app')
@section('page_title', __('Addon Manager'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Addon Manager')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Addon Manager')</h1>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div id="socialAccountsSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Addon Manager")</h4>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xs avatar-4x3 list-group-icon"
                                                 src="{{ asset('assets/admin/add-on.png') }}"
                                                 alt="Plugin Image">
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="row align-items-center">
                                                <div class="col-sm mb-2 mb-sm-0">
                                                    <h4 class="mb-0">@lang('Child Panel')
                                                        @if(Module::has('ChildPanel') && Module::isEnabled('ChildPanel'))
                                                            <span
                                                                class="badge bg-soft-success text-success">@lang("Enabled")</span>
                                                        @else
                                                            <span
                                                                class="badge bg-soft-danger text-danger">@lang("Disabled")</span>
                                                        @endif
                                                    </h4>
                                                    <p class="fs-5 text-body mb-0">@lang("The Child Panel module enables or disables features for managing child panels efficiently.")</p>
                                                </div>
                                                <div class="col-sm-auto">
                                                    @php
                                                        $isChildPanelEnabled = Module::has('ChildPanel') && Module::isEnabled('ChildPanel');
                                                    @endphp
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-{{ $isChildPanelEnabled ? 'danger deactive-module' : 'success active-module' }}"
                                                            value="ChildPanel">
                                                        @lang($isChildPanelEnabled ? "Deactivated" : "Activated")
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).on('click', '.active-module, .deactive-module', function () {
            let moduleValue = $(this).val();
            let moduleStatus = $(this).hasClass('active-module') ? 1 : 0;

            $.ajax({
                url: "{{ route('admin.addon.manager.update') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    module: moduleValue,
                    status: moduleStatus
                },
                success: function (response) {
                    if (response.success) {
                        Notiflix.Notify.success(response.message);
                        location.reload();
                    }
                },
                error: function (error) {
                    console.error('AJAX Error:', error);
                    Notiflix.Notify.failure('Something went wrong!');
                }
            });
        });
    </script>
@endpush
