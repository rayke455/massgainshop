@extends('admin.layouts.app')
@section('page_title', __('Edit Service'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.dashboard') }}">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.service.index') }}">@lang('Services')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Service')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Service')</h1>
                </div>
            </div>
        </div>

        <div class="card pb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title m-0">@lang('Edit Service')</h4>
                <button type="button" class="btn btn-sm btn-white singlePriceUpdateBtn"
                        data-route="{{ route('admin.service.single.price.update', $service->id) }}"
                        data-bs-target="#singlePriceUpdateModal" data-bs-toggle="modal">
                    <i class="fa-light fa-money-check-dollar-pen"></i> @lang('Price Update')
                </button>
            </div>
            <div class="card-body mt-2">
                <form action="{{ route('admin.service.update', $service->id) }}" method="post">
                    @csrf
                    @method('put')
                    @include('admin.services.partials.form')
                    <div class="d-flex justify-content-start mt-5">
                        <button type="submit" class="btn btn-primary submit_btn">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.services.components.single_price_update_modal')
@endsection

@push('script')
    <script>
        'use strict';
        $(document).on('click', '.singlePriceUpdateBtn', function () {
            $('.singlePriceUpdateRoute').attr('action', $(this).data('route'));
        });
    </script>
@endpush
