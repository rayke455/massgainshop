@extends(template().'layouts.user')
@section('title',trans('Transaction History'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("Transaction History")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Transaction History")</li>
                </ul>
            </div>

            <div class="card mt-50">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0">@lang("Transaction History")</h4>
                    <div class="btn-area">
                        <button type="button" class="cmn-btn rounded-1" data-bs-toggle="offcanvas"
                                data-bs-target="#transactionFilter" aria-controls="transactionFilter">@lang("Filter")<i
                                class="fa-regular fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($transactions) > 0)
                    <div class="cmn-table">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">@lang("Transaction ID")</th>
                                    <th scope="col">@lang("Amount")</th>
                                    <th scope="col">@lang("Remarks")</th>
                                    <th scope="col">@lang("Time & Date")</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td data-label="@lang("Transaction ID")">
                                            <span>{{ '#' . $transaction->trx_id }}</span>
                                        </td>
                                        <td data-label="@lang("Amount")">
                                            <span
                                                class="text-{{ $transaction->trx_type == "+" ? 'success'  : 'danger' }}">
                                                {{ $transaction->trx_type }}
                                                @if(auth()->user()->currency)
                                                    {{ currencyPositionBySelectedCurrency($transaction->amount * ($currency ? $currency->conversion_rate : 1), auth()->user()->currency) }}
                                                @else
                                                    {{ currencyPosition($transaction->amount) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td data-label="@lang("Remarks")">
                                            <span>@lang($transaction->remarks)</span>
                                        </td>
                                        <td data-label="@lang("Time & Date")">
                                            <span>
                                                {{ dateTime($transaction->created_at) }}
                                            </span>
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
                            <p class="mb-0">@lang("No transactions available to display.")</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="pagination-section">
                <nav>
                    <ul class="pagination">
                        {{ $transactions->appends($_GET)->links(template().'partials.pagination') }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="transactionFilter" aria-labelledby="transactionFilterLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="transactionFilterLabel">@lang("Transaction Filter")</h5>
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
                        <label for="remarks" class="form-label">@lang("Remarks")</label>
                        <input type="text" class="form-control" name="remarks" id="remarks"
                               value="{{ request()->remarks }}"
                               autocomplete="off">
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

