@extends(template().'layouts.user')
@section('title',trans('KYC Verification History'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("KYC Verification History")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Dashboard")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("KYC Verification History")</li>
                </ul>
            </div>

            <div class="card mt-50">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0">@lang("KYC Verification History")</h4>
                    <div class="btn-area">
                        <button type="button" class="cmn-btn rounded-1" data-bs-toggle="offcanvas"
                                data-bs-target="#kycFilter" aria-controls="kycFilter">Filter<i
                                class="fa-regular fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($userKyc) > 0)
                    <div class="cmn-table">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Type')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Submitted Date')</th>
                                    <th scope="col">@lang('Approved Date')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($userKyc as $item)
                                    <tr>
                                        <td data-label="@lang("SL")">
                                            <span>{{ '#' . loopIndex($userKyc) + $loop->index }}</span>
                                        </td>
                                        <td data-label="@lang("Type")">
                                            <span>@lang($item->kyc_type)</span>
                                        </td>
                                        <td data-label="@lang("Status")">
                                            @if($item->status == 0)
                                                <span class="badge text-bg-warning">@lang('Pending')</span>
                                            @elseif($item->status == 1)
                                                <span class="badge text-bg-success">@lang('Accepted')</span>
                                            @elseif($item->status == 2)
                                                <span class="badge text-bg-danger">@lang('Rejected')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang("Submitted Date")">
                                        <span>
                                            {{ dateTime($item->created_at) }}
                                        </span>
                                        </td>
                                        <td data-label="@lang("Approved Date")">
                                            @if($item->approved_at == null)
                                                @lang("N/A")
                                            @else
                                                <span>
                                                    {{ dateTime($item->approved_at) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <div class="text-center p-4">
                            <img class="error-image mb-3"
                                 src="{{ asset('assets/global/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-0">@lang("No kyc available to display.")</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="pagination-section">
                <nav>
                    <ul class="pagination">
                        {{ $userKyc->appends($_GET)->links(template().'partials.pagination') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="kycFilter" aria-labelledby="kycFilterLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="kycFilterLabel">@lang("KYC Filter")</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="get">
                <div class="row g-4">
                    <div>
                        <label for="kycType" class="form-label">@lang("Kyc Type")</label>
                        <input type="text" class="form-control" name="kyc_type" id="kycType"
                               value="{{ request()->kyc_type }}"
                               autocomplete="off">
                    </div>
                    <div id="formModal">
                        <label class="form-label">@lang("Status")</label>
                        <select class="modal-select" name="status">
                            <option value="" selected>@lang("All Status")</option>
                            <option value="0" {{ request()->status == 0 ? 'selected' : '' }}>@lang("Pending")</option>
                            <option value="1" {{ request()->status == 1 ? 'selected' : '' }}>@lang("Accepted")</option>
                            <option value="2" {{ request()->status == 2 ? 'selected' : '' }}>@lang("Rejected")</option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="form-label">@lang("From Date")</label>
                        <input type="text" class="form-control flatpickr" name="from_date" id="date"
                               value="{{ request()->from_date }}"
                               autocomplete="off">
                    </div>
                    <div>
                        <label for="date" class="form-label">@lang("To Date")</label>
                        <input type="text" class="form-control flatpickr" name="to_date" id="date"
                               value="{{ request()->to_date }}"
                               autocomplete="off">
                    </div>
                    <div class="btn-area mt-4">
                        <button type="submit" class="cmn-btn rounded-1">@lang("Filter")</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script')
    <script>
        "use strict";
        $('.flatpickr').flatpickr()
    </script>
@endpush

