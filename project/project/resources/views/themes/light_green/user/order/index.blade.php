@extends(template().'layouts.user')
@section('title', trans('All Orders'))
@section('content')
    @php
        $zkReviewsOn = \App\Services\Growth::get('reviews.enabled', true);
        $zkReviewed = \App\Models\Review::where('user_id', auth()->id())->pluck('order_id')->flip();
    @endphp
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("My orders")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            <i class="fa-light fa-house"></i> @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page"> @lang("Dashboard")</li>
                    <li class="breadcrumb-item" aria-current="page"> @lang("Order")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("All")</li>
                </ul>
            </div>

            @php
                $lastSegment = collect(request()->segments())->last();
            @endphp

            <ul class="nav nav-pills" id="pills-tab">
                <li class="nav-item">
                    <a href="{{ route('user.order.index') }}"
                       class="nav-link text-dark {{( $lastSegment == 'orders') ? 'active' : '' }}">
                        @lang("All Orders")
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['awaiting']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'awaiting') ? 'active' : '' }}">@lang("Awaiting")
                    </a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['pending']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'pending') ? 'active' : '' }}">@lang("Pending")
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['processing']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'processing') ? 'active' : '' }}">@lang("Processing")
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['progress']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'progress') ? 'active' : '' }}">@lang("In Progress")
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['completed']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'completed') ? 'active' : '' }}">@lang("Completed")
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['partial']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'partial') ? 'active' : '' }}">@lang("Partial")
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['canceled']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'canceled') ? 'active' : '' }}">@lang("Canceled")
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('user.order.index',['refunded']) }}"
                       class="nav-link text-dark {{( $lastSegment == 'refunded') ? 'active' : '' }}">@lang("Refunded")
                    </a>
                </li>
            </ul>


            <div class="card mt-10">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0 d-none d-md-block">@lang("My orders")</h4>
                    <div class="btn-area">
                        <button type="button" class="cmn-btn rounded-1" data-bs-toggle="offcanvas"
                                data-bs-target="#orderFilter" aria-controls="orderFilter">@lang("Filter")<i
                                class="fa-regular fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($orders) > 0)
                        <div class="cmn-table">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Order ID")</th>
                                        <th scope="col">@lang("Order Details")</th>
                                        <th scope="col">@lang("Price")</th>
                                        <th scope="col">@lang("Start Counter")</th>
                                        <th scope="col">@lang("Remains")</th>
                                        <th scope="col">@lang("Order AT")</th>
                                        <th scope="col">@lang("Status")</th>
                                        <th scope="col">@lang("Action")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td data-label="@lang("Order ID")">
                                        <span>
                                           #{{ $order->id }}
                                        </span>
                                            </td>
                                            <td data-label="@lang("Order Details")" class="zk-td-full">
                                            <span>
                                                <h5>@lang(Str::limit(optional($order->service)->service_title, 30))</h5>
                                                @lang('Link'): @lang(Str::limit($order->link,30))<br>
                                                @lang('Quantity'): @lang($order->quantity) <br>
                                            </span>
                                            </td>

                                            <td data-label="@lang("Price")">
                                                @if(auth()->user()->currency)
                                                    <span>
                                                    <span class="notranslate">{{ currencyPositionBySelectedCurrency($order->price * ($currency ? $currency->conversion_rate : 1), auth()->user()->currency) }}</span>
                                                </span>
                                                @else
                                                    <span>
                                                    <span class="notranslate">{{ currencyPosition($order->price) }}</span>
                                                </span>
                                                @endif
                                            </td>
                                            <td data-label="@lang("Start Counter")" class="{{ $order->start_counter === null ? 'zk-td-empty' : '' }}">@lang($order->start_counter ?? 'N/A')</td>
                                            <td data-label="@lang("Remains")" class="{{ $order->remains === null ? 'zk-td-empty' : '' }}">@lang($order->remains ?? 'N/A' )</td>
                                            <td data-label="@lang("Date")">{{ dateTime($order->created_at) }}</td>

                                            <td data-label="@lang("Status")">
                                                @if($order->status == 'awaiting')
                                                    <span>
                                                    <span class="badge text-bg-dark">@lang("Awaiting")</span>
                                                </span>
                                                @elseif($order->status == 'pending')
                                                    <span>
                                                    <span class="badge text-bg-warning">@lang("Pending")</span>
                                                </span>
                                                @elseif($order->status == 'processing')
                                                    <span>
                                                    <span class="badge text-bg-info">@lang("Processing")</span>
                                                </span>
                                                @elseif($order->status == 'progress')
                                                    <span>
                                                    <span class="badge text-bg-primary">@lang("In Progress")</span>
                                                </span>
                                                @elseif($order->status == 'completed')
                                                    <span>
                                                    <span class="badge text-bg-success">@lang("Completed")</span>
                                                </span>
                                                @elseif($order->status == 'partial')
                                                    <span>
                                                    <span class="badge text-bg-secondary">@lang("Partial")</span>
                                                </span>
                                                @elseif($order->status == 'canceled')
                                                    <span>
                                                    <span class="badge text-bg-danger">@lang("Canceled")</span>
                                                </span>
                                                @elseif($order->status == 'refunded')
                                                    <span>
                                                    <span class="badge text-bg-danger">@lang("Refunded")</span>
                                                </span>
                                                @endif
                                                @if(isset($order->refill_status) &&
                                                    !in_array($order->refill_status, ['completed', 'partial', 'canceled', 'refunded']))
                                                    <span>
                                                        <span class="badge text-bg-warning">@lang("Refilling")</span>
                                                    </span>
                                                @endif
                                            </td>

                                            <td data-label="@lang("Action")">
                                                <div class="dropdown">
                                                    <button class="action-btn-secondary" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item orderDetailsBtn"
                                                               href="javascript:void(0)"
                                                               data-service_title="{{ optional($order->service)->service_title }}"
                                                               data-service_link="{{ $order->link }}"
                                                               data-service_quantity="{{ $order->quantity }}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#orderDetailsModal">@lang("Order Details")</a>
                                                        </li>
                                                        @if(optional($order->service)->service_status == 1)
                                                            <li><a class="dropdown-item orderBtn"
                                                                   href="javascript:void(0)"
                                                                   data-service_id="{{ $order->service_id }}"
                                                                   data-service_title="{{ optional($order->service)->service_title }}"
                                                                   data-service_description="{{ optional($order->service)->description }}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#orderNowModal">@lang("Order Now")</a>
                                                            </li>
                                                        @endif
                                                        @if($order->reason)
                                                            <li><a class="dropdown-item infoBtn"
                                                                   href="javascript:void(0)"
                                                                   data-reason="{{ $order->reason }}"
                                                                   data-bs-toggle="modal" title="@lang('Reason')"
                                                                   data-bs-target="#infoModal"> @lang("Order Info")
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(optional($order->service)->service_status == 1 && $order->link)
                                                            <li><a class="dropdown-item" href="{{ route('user.order.create', ['serviceId' => $order->service_id, 'link' => $order->link, 'quantity' => $order->quantity]) }}">
                                                                    <i class="fa-regular fa-rotate-right me-1"></i> @lang("Buy again")</a></li>
                                                        @endif
                                                        @if(in_array($order->status, ['pending', 'awaiting', 'processing']))
                                                            <li>
                                                                <form method="post" action="{{ route('user.order.cancel', $order->id) }}" onsubmit="return confirm(@json(__('Cancel this order? If it has not started, the amount goes back to your balance.')))">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-regular fa-ban me-1"></i> @lang("Cancel order")</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        @if($order->status === 'completed' && $zkReviewsOn && !isset($zkReviewed[$order->id]))
                                                            <li><a class="dropdown-item zkRateBtn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#zkRateModal"
                                                                   data-route="{{ route('user.order.review', $order->id) }}" data-title="#{{ $order->id }} · {{ \Illuminate\Support\Str::limit(optional($order->service)->service_title, 50) }}">
                                                                    <i class="fa-regular fa-star me-1"></i> @lang("Rate this order")</a></li>
                                                        @endif
                                                        @if($order->canBeRefilled())
                                                            <li><a class="dropdown-item refillOrderBtn"
                                                                   href="javascript:void(0)"
                                                                   data-route="{{ route('user.order.refill',[$order->id]) }}"
                                                                   data-bs-toggle="modal"
                                                                   data-bs-target="#orderRefillModal">
                                                                    @lang("Refill Order")
                                                                </a></li>
                                                        @endif
                                                    </ul>
                                                </div>
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
                            <p class="mb-0">@lang("No orders available to display.")</p>
                        </div>
                    @endif

                </div>
            </div>

            <div class="pagination-section">
                <nav>
                    <ul class="pagination">
                        {{ $orders->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    @include( template(). 'user.order.components.filter')
    @include( template(). 'user.order.components.info_modal')
    @include( template(). 'user.order.components.order_details')
    @include( template(). 'user.order.components.order_now_modal')
    @include( template(). 'user.order.components.refill_modal')
    <div class="modal fade" id="zkRateModal" tabindex="-1" aria-labelledby="zkRateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="post" id="zkRateForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="zkRateTitle">@lang('Rate your order')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted mb-2" id="zkRateFor"></p>
                    <div class="zk-stars mb-3" role="radiogroup" aria-label="@lang('Rating')">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" value="{{ $i }}" id="zkStar{{ $i }}" @checked($i === 5) required>
                            <label for="zkStar{{ $i }}" title="{{ $i }}">★</label>
                        @endfor
                    </div>
                    <label class="form-label" for="zkRateComment">@lang('Your review')</label>
                    <textarea class="form-control mb-3" id="zkRateComment" name="comment" rows="3" minlength="10" maxlength="600" required
                              placeholder="@lang('How was the speed and quality?')"></textarea>
                    <div class="row g-2">
                        <div class="col-6"><label class="form-label small" for="zkRateName">@lang('Name to show')</label>
                            <input class="form-control" id="zkRateName" name="display_name" maxlength="60" value="{{ ucfirst(auth()->user()->username) }}"></div>
                        <div class="col-6"><label class="form-label small" for="zkRateCountry">@lang('Country (optional)')</label>
                            <input class="form-control" id="zkRateCountry" name="country" maxlength="60" value="{{ auth()->user()->country }}"></div>
                    </div>
                    <small class="text-muted d-block mt-2">@lang('Reviews appear on our website after a quick check.')</small>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="zk-btn zk-btn--primary">@lang('Send review')</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        'use strict';
        document.addEventListener('click', function (e) {
            const b = e.target.closest('.zkRateBtn');
            if (!b) return;
            document.getElementById('zkRateForm').action = b.dataset.route;
            document.getElementById('zkRateFor').textContent = b.dataset.title;
        });
    </script>
@endsection

@include( template(). 'user.order.components.orders_common_scripts')


