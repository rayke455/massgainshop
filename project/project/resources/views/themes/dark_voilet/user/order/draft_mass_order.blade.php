@extends(template().'layouts.user')
@section('title',trans('Draft Mass Order'))
@section('content')

    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Draft Mass Order")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Dashboard")</li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Order")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Mass Order")</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between border-0">
                <h4>@lang("Draft Mass Order")</h4>
                <div class="btn-area">
                    <a href="{{ route('user.mass.order') }}" class="cmn-btn"><i
                            class="fa-light fa-backward"></i>@lang("Back")</a>
                </div>
            </div>
            <div class="card-body">
                <div class="cmn-table">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                            <tr>
                                <th scope="col">@lang("Service ID")</th>
                                <th scope="col">@lang("Service Title")</th>
                                <th scope="col">@lang("Quantity")</th>
                                <th scope="col">@lang("Link")</th>
                                <th scope="col">@lang("Price")</th>
                                <th scope="col">@lang("Remarks")</th>
                                <th scope="col">@lang("Action")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $currency = session()->get('currency');
                            @endphp
                            @forelse($draftMassOrders as $draft)
                                <tr>
                                    <td data-label="@lang("Service ID")">
                                        <span>
                                            <input type="text" class="form-control service_id"
                                                   value="{{ $draft->service_id }}"
                                                   required {{ $draft->remarks == null && $draft->status == 1 ? 'disabled' : '' }}>
                                        </span>
                                    </td>
                                    <td data-label="@lang("Service Title")">
                                        <span>{{ Str::limit(optional($draft->service)->service_title, 40) ?? 'N/A' }}</span>
                                    </td>
                                    <td data-label="@lang("Quantity")">
                                            <span>
                                                <input type="text" class="form-control quantity"
                                                       value="{{ $draft->quantity }}" required {{ $draft->remarks == null && $draft->status == 1 ? 'disabled' : '' }}>
                                            </span>
                                    </td>
                                    <td data-label="@lang("Link")">
                                            <span>
                                                <input type="text" class="form-control link-url"
                                                       value="{{ $draft->link }}"
                                                       required {{ $draft->remarks == null && $draft->status == 1 ? 'disabled' : '' }}>
                                            </span>
                                    </td>
                                    <td data-label="@lang("Price")">
                                        @if(auth()->user()->currency)
                                            <span>
                                                    {{ currencyPositionBySelectedCurrency($draft->price * $currency->conversion_rate, auth()->user()->currency) }}
                                                </span>
                                        @else
                                            <span>
                                                    {{ currencyPosition($draft->price) }}
                                                </span>
                                        @endif
                                    </td>
                                    <td data-label="@lang("Time")">
                                        @if($draft->remarks)
                                            <span class="text-danger">
                                                        {{ Str::limit($draft->remarks, 40) }}
                                                    </span>
                                        @else
                                            <span class="badge text-bg-success">
                                                        @lang("Valid")
                                                    </span>
                                        @endif
                                    </td>
                                    <td data-label="@lang("Action")">
                                        <button class="action-btn-secondary action-btn" type="button"
                                                data-draft_order="{{ $draft->id }}" {{ $draft->remarks == null && $draft->status == 1 ? 'disabled' : '' }}>
                                            <i class="fa-regular {{ $draft->remarks == null && $draft->status == 1 ? 'fa-shield-check' : 'fa-check' }}"></i>
                                        </button>
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
        <div class="btn-area mt-3">
            <form action="{{ route('user.mass.order.store') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $draftMassOrders }}" name="orders">
                <button type="submit" class="cmn-btn"><i
                        class="fa-light fa-bag-shopping"></i> @lang("Order Now")
                </button>
            </form>
        </div>
    </main>
@endsection

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('.action-btn').on('click', function () {

                let $row = $(this).closest('tr');
                let serviceId = $row.find('.service_id').val();
                let quantity = $row.find('.quantity').val();
                let linkUrl = $row.find('.link-url').val();
                let draftOrderId = $(this).data('draft_order');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('user.edit.draft.order') }}",
                    data: {draftOrderId: draftOrderId, serviceId: serviceId, quantity: quantity, link: linkUrl},
                    type: "post",
                    success: function (res) {
                        if (res.status == 'error') {
                            Notiflix.Notify.failure(`${res.message}`);
                        }
                        if (res.status == 'success') {
                            Notiflix.Notify.success(`${res.message}`);
                            location.reload();
                        }
                    },
                });
            })
        });
    </script>
@endpush


