@extends('admin.layouts.app')
@section('page_title', __('Edit Category'))
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
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.category.index') }}">
                                    @lang('Category')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Category')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Category')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Edit Category')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.category.update', $category->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                @include('admin.category.partials.form')
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="{{ asset("assets/admin/js/tom-select.complete.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select')
        });
    </script>
@endpush
