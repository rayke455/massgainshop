@extends('admin.layouts.app')
@section('page_title', __('Edit Currency'))
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
                                <a class="breadcrumb-link" href="{{ route('admin.currency.index') }}">
                                    @lang('Currencies')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Currency')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Currency')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Edit Currency')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.currency.update', $currency->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="@lang("Name")"
                                               aria-label="@lang("Name")"
                                               autocomplete="off"
                                               value="{{ old('name', $currency->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="codeLabel" class="form-label">@lang('Code')</label>
                                        <input type="text" class="form-control  @error('code') is-invalid @enderror"
                                               name="code" id="codeLabel" placeholder="@lang("Code")"
                                               aria-label="@lang("Code")"
                                               autocomplete="off"
                                               value="{{ old('code', $currency->code) }}">
                                        @error('code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 justify-content-between">
                                    <div class="col-md-6">
                                        <label for="codeLabel" class="form-label">@lang('Conversion Rate')</label>
                                        <div class="input-group">
                                            <span class="input-group-text">1 {{ basicControl()->base_currency }}</span>
                                            <input type="text" name="conversion_rate" class="form-control"
                                                   placeholder="0.0" aria-label="0.0"
                                                   value="{{ old('conversion_rate', $currency->conversion_rate) }}">
                                        </div>
                                        @error('conversion_rate')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="symbolLabel" class="form-label">@lang('Symbol')</label>
                                        <input type="text" name="symbol" class="form-control"
                                               placeholder="@lang("Symbol")" value="{{ old('symbol', $currency->symbol) }}" autocomplete="off">
                                        @error('symbol')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="categoryStatus">
                                            <span class="col-4 col-sm-6 ms-0 ">
                                              <span class="d-block text-dark">@lang("Status")</span>
                                              <span
                                                  class="d-block fs-5">@lang("The currency has been enabled.")</span>
                                            </span>
                                            <span class="col-2 col-sm-3 text-end">
                                                 <input type='hidden' value='0' name='status'>
                                                    <input
                                                        class="form-check-input @error('status') is-invalid @enderror"
                                                        type="checkbox" name="status" id="categoryStatus"
                                                        value="1" {{ $currency->status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-center"
                                                           for="categoryStatus"></label>
                                                </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
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

