@extends(template().'layouts.user')
@section('title',trans('Deposit History'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Deposit History")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Deposit History")</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between border-0">
                <h4>@lang("Deposit History")</h4>
                <div class="btn-area">
                    <button type="button" class="cmn-btn filter-btn" data-bs-toggle="offcanvas" data-bs-target="#depositFilter"
                            aria-controls="depositFilterExample">@lang("Filter")<i class="fa-regular fa-filter"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(count($deposits) > 0)
                    <div class="cmn-table">
                        <div class="table-responsive overflow-hidden">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">@lang("Transaction ID")</th>
                                    <th scope="col">@lang("Gateway")</th>
                                    <th scope="col">@lang("Amount")</th>
                                    <th scope="col">@lang("Charge")</th>
                                    <th scope="col">@lang("Status")</th>
                                    <th scope="col">@lang("Time & Date")</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td data-label="@lang("Transaction ID")">
                                            <span>{{ '#' . $deposit->trx_id }}</span></td>
                                        <td data-label="@lang("Gateway")">
                                            <span>@lang(optional($deposit->gateway)->name)</span>
                                        </td>
                                        <td data-label="@lang("Amount")">
                                            @if(auth()->user()->currency)
                                                {{ currencyPositionBySelectedCurrency(
                                                    ($deposit->payable_amount_in_base_currency * ($currency ? $currency->conversion_rate : 1)),
                                                    auth()->user()->currency
                                                ) }}
                                            @else
                                                {{ currencyPosition($deposit->payable_amount_in_base_currency) }}
                                            @endif
                                        </td>
                                        <td data-label="@lang("Charge")">
                                            @if(auth()->user()->currency)
                                                {{ currencyPositionBySelectedCurrency($deposit->base_currency_charge * ($currency->conversion_rate ?? 1), auth()->user()->currency) }}
                                            @else
                                                {{ currencyPosition($deposit->base_currency_charge) }}
                                            @endif
                                        </td>
                                        <td data-label="@lang("Status")">
                                            @if($deposit->status == 1)
                                                <span class="badge text-bg-success">@lang('Complete')</span>
                                            @elseif($deposit->status == 2)
                                                <span class="badge text-bg-warning">@lang('Pending')</span>
                                            @elseif($deposit->status == 3)
                                                <span class="badge text-bg-danger">@lang('Cancel')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang("Time & Date")">
                                            <span>{{ dateTime($deposit->created_at) }}</span>
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
                        <p class="mb-0">@lang("The deposit is not available.")</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="pagination-section">
            <nav>
                <ul class="pagination">
                    {{ $deposits->appends($_GET)->links(template().'partials.pagination') }}
                </ul>
            </nav>
        </div>
    </main>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="depositFilter" aria-labelledby="depositFilterLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="depositFilterLabel">@lang("Deposit Filter")</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="" method="get">
                <div class="row g-4">
                    <div>
                        <label for="TransactionID" class="form-label">@lang("Transaction ID")</label>
                        <input type="text" class="form-control" name="transaction_id" id="TransactionID"
                               value="{{ request()->transaction_id }}"
                               autocomplete="off">
                    </div>
                    <div>
                        <label for="gateway" class="form-label">@lang("Gateway")</label>
                        <input type="text" class="form-control" name="gateway" id="gateway"
                               value="{{ request()->gateway }}"
                               autocomplete="off">
                    </div>
                    <div id="formModal">
                        <label class="form-label">@lang("Status")</label>
                        <select class="modal-select" name="status">
                            <option value="">@lang("All Status")</option>
                            <option value="2" {{ request()->status == 2 ? 'selected' : '' }}>@lang("Pending")</option>
                            <option value="1" {{ request()->status == 1 ? 'selected' : '' }}>@lang("Complete")</option>
                            <option value="3" {{ request()->status == 3 ? 'selected' : '' }}>@lang("Cancel")</option>
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

@push('panel-user-script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('.flatpickr').flatpickr();
        });
    </script>
@endpush



