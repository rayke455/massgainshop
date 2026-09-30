@extends(template().'layouts.user')
@section('title',trans('Orders Refill'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("All Orders Refill")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">@lang("Home")</a></li>
                    <li class="breadcrumb-item active">@lang("All Orders Refill")</li>
                </ol>
            </nav>
        </div>

        @php
            $lastSegment = collect(request()->segments())->last();
        @endphp

        <ul class="nav nav-pills mb-2" id="pills-tab">
            <li class="nav-item">
                <a href="{{ route('user.show.order.refill') }}"
                   class="nav-link text-dark {{( $lastSegment == 'refill') ? 'active' : '' }}">
                    @lang("All Orders")
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['awaiting']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'awaiting') ? 'active' : '' }}">@lang("Awaiting")
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['pending']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'pending') ? 'active' : '' }}">@lang("Pending")
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['processing']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'processing') ? 'active' : '' }}">@lang("Processing")
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['progress']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'progress') ? 'active' : '' }}">@lang("In Progress")
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['completed']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'completed') ? 'active' : '' }}">@lang("Completed")
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['partial']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'partial') ? 'active' : '' }}">@lang("Partial")
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['canceled']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'canceled') ? 'active' : '' }}">@lang("Canceled")
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('user.show.order.refill',['refunded']) }}"
                   class="nav-link text-dark {{( $lastSegment == 'refunded') ? 'active' : '' }}">@lang("Refunded")
                </a>
            </li>
        </ul>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-0">
                <h4 class="mb-0">@lang("All Refill Orders")</h4>
                <div class="btn-area">
                    <button type="button" class="cmn-btn filter-btn" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasExample"
                            aria-controls="offcanvasExample">@lang("Filter")<i class="fa-regular fa-filter"></i>
                    </button>
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
                                           {{ $order->id }}
                                        </span>
                                        </td>
                                        <td data-label="@lang("Order Details")">
                                            <span>
                                                <h5>@lang(Str::limit(optional($order->service)->service_title, 30))</h5>
                                                @lang('Link'): @lang(Str::limit($order->link,30))<br>
                                                @lang('Quantity'): @lang($order->quantity) <br>
                                            </span>
                                        </td>

                                        <td data-label="@lang("Price")">
                                            @if(auth()->user()->currency)
                                                <span>
                                                    {{ currencyPositionBySelectedCurrency($order->price * ($currency ? $currency->conversion_rate : 1), auth()->user()->currency) }}
                                                </span>
                                            @else
                                                <span>
                                                    {{ currencyPosition($order->price) }}
                                                </span>
                                            @endif
                                        </td>

                                        <td data-label="@lang("Start Counter")">@lang($order->start_counter ?? 'N/A')</td>
                                        <td data-label="@lang("Remains")">@lang($order->remains ?? 'N/A' )</td>
                                        <td data-label="@lang("Order AT")">{{ dateTime($order->created_at) }}</td>

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
                                                    <span class="badge text-bg-primary">@lang("Progress")</span>
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
                                                        <li><a class="dropdown-item orderBtn" href="javascript:void(0)"
                                                               data-service_id="{{ $order->service_id }}"
                                                               data-service_title="{{ optional($order->service)->service_title }}"
                                                               data-service_description="{{ optional($order->service)->description }}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#orderNowModal">@lang("Order Now")</a>
                                                        </li>
                                                    @endif
                                                    @if($order->reason)
                                                        <li><a class="dropdown-item infoBtn" href="javascript:void(0)"
                                                               data-reason="{{ $order->reason }}"
                                                               data-bs-toggle="modal" title="@lang('Reason')"
                                                               data-bs-target="#infoModal">@lang("Order Info")
                                                            </a>
                                                        </li>
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
                        <p class="mb-0">@lang("The refill orders is not available.")</p>
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
    </main>

    @include( template(). 'user.order.components.order_details')
    @include( template(). 'user.order.components.filter')
    @include( template(). 'user.order.components.info_modal')
    @include( template(). 'user.order.components.order_now_modal')
    @include( template(). 'user.order.components.refill_modal')

@endsection

@include( template(). 'user.order.components.orders_common_scripts')




