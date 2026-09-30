@extends('admin.layouts.app')
@section('page_title', __('Add Service'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.dashboard') }}">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.service.index') }}">@lang('Services')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Add Service')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Add Service')</h1>
                </div>
            </div>
        </div>

        <div class="card pb-3">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title m-0">@lang('Add Service')</h4>
            </div>
            <div class="card-body mt-2">
                <form action="{{ route('admin.service.store') }}" method="post">
                    @csrf
                    @include('admin.services.partials.form')
                    <div class="d-flex justify-content-start mt-5">
                        <button type="submit" class="btn btn-primary submit_btn">@lang('Save changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
