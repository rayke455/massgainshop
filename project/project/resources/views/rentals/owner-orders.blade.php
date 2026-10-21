@php
    $ordersUrl = ($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id);
    $statuses = ['' => 'All', 'in progress' => 'In Progress', 'processing' => 'Processing', 'partial' => 'Partial', 'canceled' => 'Canceled', 'pending' => 'Pending', 'completed' => 'Completed', 'refunded' => 'Refunded'];
@endphp
<form class="owner-order-search" method="get" action="{{ $ordersUrl }}#orders" role="search">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="10" cy="10" r="6"/><path d="m15 15 6 6"/></svg>
    <label class="sr-only" for="owner-order-search">URL or order id</label>
    <input id="owner-order-search" name="order_search" value="{{ request('order_search') }}" placeholder="URL or order id" maxlength="100">
    @if(request('order_status'))<input type="hidden" name="order_status" value="{{ request('order_status') }}">@endif
    @if(request('customer_id'))<input type="hidden" name="customer_id" value="{{ (int) request('customer_id') }}">@endif
    <button type="submit" aria-label="Search orders">Search</button>
</form>
<nav class="owner-order-statuses" aria-label="Order status filters">
@foreach($statuses as $value => $label)
    <a href="{{ $ordersUrl }}?{{ http_build_query(array_filter(['order_status' => $value, 'order_search' => request('order_search'), 'customer_id' => request('customer_id')])) }}#orders" @if((string) request('order_status') === $value) aria-current="page" @endif>{{ $label }}</a>
@endforeach
</nav>
@if(request('customer_id'))<p class="muted">Customer #{{ (int) request('customer_id') }} · <a href="{{ $ordersUrl }}#orders">Clear filter</a></p>@endif
<div class="owner-order-list">
@forelse($customerOrders as $order)
    @php
        $title = $order->service ? \App\Services\SmartImport::cleanName($order->service->service_title) : 'Service unavailable';
        $completed = $order->status === 'completed';
    @endphp
    <details class="owner-order-row">
        <summary>
            <span class="owner-service-icon">@include('rentals.order-platform-icon', ['title' => $title])</span>
            <span class="owner-order-copy">
                <span class="owner-order-title"><span class="owner-service-id">{{ $order->service_id }}</span> {{ $title }}</span>
                <span class="owner-order-progress">{{ number_format($order->quantity) }} ordered · <span class="{{ $completed ? 'order-complete' : 'order-state' }}">{{ ucfirst($order->status) }}</span></span>
                <span class="owner-order-price">{{ number_format($order->price, 2) }} {{ $base }} · {{ $order->created_at->format('j M') }} <span class="order-complete">+{{ number_format($order->rental_earning ?? 0, 2) }} {{ $base }}</span></span>
            </span>
            <span class="owner-order-chevron" aria-hidden="true">›</span>
        </summary>
        <div class="owner-order-detail">Order #{{ $order->id }}<br>{{ $order->link }}<br>Profit: {{ number_format($order->rental_earning ?? 0, 2) }} {{ $base }}</div>
    </details>
@empty
    <div class="owner-orders-empty">@include('rentals.control-icon', ['name' => 'orders'])<h2>No orders yet</h2><p>No account orders match this filter.</p></div>
@endforelse
</div>
{{ $customerOrders->fragment('orders')->links() }}
