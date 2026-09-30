@extends('admin.layouts.app')
@section('page_title', __('Referral Commission Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.dashboard') }}">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Referral Commission Setting")</a>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Referral Commission Setting")</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-5 mb-3 mb-lg-5">
                <div class="row">
                    <div class="col-md-12 col-lg-12 mb-3 mb-lg-5">
                        <div class="card">
                            <div class="card-body ">
                                <form method="post" action="{{route('admin.referral.commission.configure')}}" class="d-flex justify-content-between align-items-center">
                                    @csrf
                                    <div>
                                        <small class="text-cap"> @lang("Upline Deposit Bonus")</small>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="deposit_commission" type="checkbox" id="depositBonusCheckbox" value="1"
                                                   {{ basicControl()->deposit_commission == 0 ? 'checked' : '' }}checked>
                                            <label class="form-check-label" for="depositBonusCheckbox"></label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">@lang("Save Changes")</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12 mb-3 mb-lg-5">
                        <div class="card">
                            <div class="card-body">
                                <small class="text-cap"> @lang("Deposit Bonus")</small>
                                <div class="table-responsive">
                                    <table
                                        class="table table-borderless table table-nowrap table-text-center table-align-middle card-table">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">@lang("Level")</th>
                                            <th scope="col">@lang("Bonus")</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @forelse($referrals as $item)
                                            <tr>
                                                <td data-label="Level">@lang('LEVEL') {{ '#' . $item->level }}</td>
                                                <td data-label="@lang('Bonus')">
                                                    {{ $item->percent }} %
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-7 mb-3 mb-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row formField justify-content-between align-items-center">
                            <div class="col-md-4">
                                <small class="text-cap"> @lang("Select Type")</small>
                                <select class="form-select type" name="commission_type" autocomplete="off">
                                    <option value="" disabled>@lang("Select Type")</option>
                                    <option value="deposit">@lang("Deposit Bonus")</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <small class="text-cap"> @lang("Set Level")</small>
                                <input type="number" name="level" placeholder="@lang('Number Of Level')"
                                       class="form-control numberOfLevel" autocomplete="off">
                            </div>

                            <div class="col-md-4 mt-4">
                                <button type="button" class="btn btn-primary btn-sm makeForm">
                                    <i class="fa fa-spinner"></i> @lang('GENERATE')
                                </button>
                            </div>
                        </div>
                        <form action="{{ route('admin.referral-commission.store') }}" method="post" class="form-row">
                            @csrf
                            <input type="hidden" name="commission_type" value="">
                            <div class="col-md-12 newFormContainer"></div>
                            <div class="col-md-12">
                                <button type="submit"
                                        class="btn btn-primary btn-block mt-3 submit-btn">@lang('Save Changes')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/select2.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {

            $('.submit-btn').addClass('d-none');
            $(".makeForm").on('click', function () {

                let levelGenerate = $(this).parents('.formField').find('.numberOfLevel').val();
                let selectType = $('.type :selected').val();

                if (selectType == '') {
                    Notiflix.Notify.failure("{{ trans('Please select a type') }}");
                    return 0;
                }
                $('input[name=commission_type]').val(selectType);

                var value = 1;
                var viewHtml = '';

                if (levelGenerate !== '' && levelGenerate > 0) {
                    for (var i = 0; i < parseInt(levelGenerate); i++) {
                        viewHtml += `<div class="input-group mt-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text no-right-border">LEVEL</span>
                            </div>
                            <input name="level[]" class="form-control" type="number" readonly value="${value++}" placeholder="@lang('Level')" required>
                            <input name="percent[]" class="form-control" type="text" placeholder="@lang("Level Bonus (%)")">
                            <span class="input-group-btn">
                            <button class="btn btn-danger removeForm" type="button"><i class='fa fa-trash'></i></button></span>
                            </div>`;
                    }

                    $('.newFormContainer').html(viewHtml);
                    $('.submit-btn').addClass('d-block');
                    $('.submit-btn').removeClass('d-none');

                } else {
                    $('.submit-btn').addClass('d-none');
                    $('.submit-btn').removeClass('d-block');
                    $('.newFormContainer').html(``);
                    Notiflix.Notify.failure("{{ trans('Please set number of level.') }}");
                }
            });

            $(document).on('click', '.removeForm', function () {
                $(this).closest('.input-group').remove();
            });

            $('select').select2({
                selectOnClose: true
            });
        });
    </script>
@endpush





