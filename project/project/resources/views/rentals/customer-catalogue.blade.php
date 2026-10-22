@php
    $brand = $panel->allows('branding') ? $panel->name : 'Mass Panel Shop';
    $money = fn($value) => number_format((float) $value * (float) $currency->conversion_rate, 2).' '.$currency->code;
    $browse = route('user.order.create');
@endphp
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $brand }} · Create order</title>
@include('rentals.workspace-style')
<style>
.workspace-bottom nav{grid-template-columns:repeat(3,minmax(0,1fr))}.catalogue-list{margin-top:20px}.catalogue-list>a{display:flex;align-items:center;gap:14px;min-height:75px;border-bottom:1px solid var(--line);padding:14px 0}.platform-icon{display:grid;place-items:center;width:44px;height:44px;background:#24313c;border-radius:14px;flex:none}.platform-icon svg{width:24px;height:24px}.row-copy{flex:1;min-width:0;overflow-wrap:anywhere}.row-copy small{display:block;color:var(--muted);margin-top:4px}.catalogue-search{display:flex;gap:8px;align-items:center}.catalogue-search input{border-radius:30px}.catalogue-search button{margin:6px 0 0}.preset-quantities{display:flex;gap:8px;flex-wrap:wrap;margin:15px 0}.preset-quantities button{border:1px solid var(--line);background:#24313c;color:var(--ink);padding:9px 14px;border-radius:22px;cursor:pointer}.order-total{display:flex;justify-content:space-between;padding:18px 0;margin-top:15px;border-bottom:1px solid var(--line)}.order-description{white-space:pre-wrap;overflow-wrap:anywhere}.order-consent{display:flex;align-items:flex-start;gap:10px}.order-consent input{width:auto}.feedback{padding:14px;background:#243848;border-radius:10px;margin-top:16px}[hidden]{display:none!important}
</style>@include('rentals.order-shell-style')</head><body>@include('rentals.workspace-icons')
<div class="order-shell">
<aside class="order-sidebar" aria-label="Order navigation"><a href="{{ $browse }}" aria-current="page"><svg class="icon"><use href="#icon-layers"/></svg>New order</a><a href="{{ route('user.order.index') }}"><svg class="icon"><use href="#icon-cart"/></svg>My orders</a><a href="{{ route('user.add.fund') }}"><svg class="icon"><use href="#icon-wallet"/></svg>Add funds</a><a href="{{ route('user.dashboard') }}"><svg class="icon"><use href="#icon-user"/></svg>Menu</a></aside>
<header class="workspace-header"><a class="brand-pill" href="{{ $browse }}"><span class="brand-mark">{{ mb_substr($brand,0,1) }}</span><strong>{{ $brand }}</strong></a><a class="balance-pill" href="{{ route('user.add.fund') }}">{{ $money($user->balance) }} <span class="plus">+</span></a></header>
<main>
@if($selected)
    <a class="back" href="{{ $browse }}?network={{ $network }}&category={{ $category }}">← Select service</a>
    <h1>{{ \App\Services\SmartImport::cleanName($selected->service_title) }}</h1>
    <div class="order-form-layout">
    @if(!in_array($selected->service_type, ['Default', 'Package']))
        <p>This service needs additional information.</p>
        <a class="primary" href="{{ route('user.order.create', ['serviceId' => $selected->id, 'advanced' => 1]) }}">Open {{ $selected->service_type }} order form</a>
    @elseif(!$panel->allows('orders'))
        <p class="feedback">Ordering is currently disabled for this panel.</p>
    @else
    <form id="tenant-order-form" method="post" action="{{ route('user.order.store') }}">
        @csrf<input type="hidden" name="category" value="{{ $selected->category_id }}"><input type="hidden" name="service" value="{{ $selected->id }}"><input type="hidden" name="order_type" value="{{ $selected->service_type }}">
        @if($selected->service_type === 'Default')
        <label for="quantity">Quantity ({{ number_format($selected->min_amount) }}–{{ number_format($selected->max_amount) }})</label>
        <div class="order-presets" aria-label="Suggested quantities">@foreach([100,500,1000,2500,5000] as $amount)@if($amount >= $selected->min_amount && $amount <= $selected->max_amount)<button type="button" data-quantity="{{ $amount }}" aria-pressed="false">{{ number_format($amount) }}</button>@endif @endforeach<button type="button" id="custom-quantity">Choose own</button></div>
        <input id="quantity" name="quantity" type="number" min="{{ $selected->min_amount }}" max="{{ $selected->max_amount }}" step="1" value="{{ max($selected->min_amount, min(100, $selected->max_amount)) }}" required>
        @else<input type="hidden" id="quantity" name="quantity" value="1">@endif
        <label for="target-link">Paste link</label><input id="target-link" name="link" type="url" maxlength="2000" placeholder="https://" required>
        <div class="order-summary"><div><small>Amount</small><strong id="order-amount"></strong></div><div><small>Price</small><strong id="order-total"></strong></div></div>
        <label class="order-consent"><input type="checkbox" name="check" value="1" required><span>I have checked the service, quantity and target link.</span></label>
        <button class="primary" id="submit-order" type="submit">Place order</button>
        <p id="order-feedback" class="feedback" role="status" hidden></p>
        <a id="order-fund-link" class="primary" href="{{ route('user.add.fund') }}" hidden>Add funds</a>
        <a id="order-history-link" class="primary" href="{{ route('user.order.index') }}" hidden>View orders</a>
    </form>
    @endif
    <section class="order-details"><h2>Service details</h2><p>Service #{{ $selected->id }} · {{ $money($rate($selected)) }} {{ $selected->service_type === 'Default' ? 'per 1,000' : 'per package' }}</p><p class="order-description">{{ strip_tags($selected->description ?? '') }}</p></section>
    </div>
@else
    @if($category || $network || request('q'))<a class="back" href="{{ $browse }}">← All platforms</a>@endif
    <form class="order-search" action="{{ $browse }}" method="get"><label class="sr-only" for="catalogue-search">Search services</label><input id="catalogue-search" type="search" name="q" maxlength="100" value="{{ request('q') }}" placeholder="Search services"><button class="primary">Search</button></form>
    <h1>{{ $category || request('q') ? 'Select service' : ($network ? 'Select category' : 'Select platform') }}</h1>
    <div class="catalogue-list">
    @if($category || request('q'))
        @forelse($services as $service)
        <a href="{{ $browse }}?serviceId={{ $service->id }}"><span class="network-icon" data-network="{{ strtolower($service->category->socialMedia->name) }}">@include('rentals.order-platform-icon', ['title' => $service->category->socialMedia->name])</span><span class="row-copy">{{ \App\Services\SmartImport::cleanName($service->service_title) }}</span><span class="row-price">{{ $money($rate($service)) }}<small style="display:block;color:var(--muted)">{{ \App\Services\PanelRentals::quantityBased($service) ? 'per 1,000' : 'per item' }}</small></span><span class="row-arrow" aria-hidden="true">›</span></a>
        @empty<p>No services found. Try another search or category.</p>@endforelse
        {{ $services->links() }}
    @elseif($network)
        @forelse($categories as $item)<a href="{{ $browse }}?network={{ $network }}&category={{ $item->id }}"><span class="row-copy">{{ $item->category_title }}</span><span aria-hidden="true">›</span></a>@empty<p>No categories are available.</p>@endforelse
    @else
        @forelse($networks as $item)<a href="{{ $browse }}?network={{ $item->id }}"><span class="network-icon" data-network="{{ strtolower($item->name) }}">@include('rentals.order-platform-icon', ['title' => $item->name])</span><span class="row-copy">{{ $item->name }}</span><span class="row-arrow" aria-hidden="true">›</span></a>@empty<p>No services are available on this panel yet.</p>@endforelse
    @endif
    </div>
@endif
</main>
<footer class="workspace-bottom"><nav aria-label="Customer navigation"><a href="{{ route('user.order.index') }}"><svg class="icon"><use href="#icon-cart"/></svg>My Orders</a><a href="{{ $browse }}" aria-current="page"><svg class="icon"><use href="#icon-layers"/></svg>Create</a><a href="{{ route('user.dashboard') }}"><svg class="icon"><use href="#icon-user"/></svg>Menu</a></nav></footer>
</div>
@if($selected && in_array($selected->service_type,['Default','Package']) && $panel->allows('orders'))
<script>
(() => {
    const form = document.getElementById('tenant-order-form'), quantity = document.getElementById('quantity');
    const button = document.getElementById('submit-order'), feedback = document.getElementById('order-feedback');
    const rate = @json((float) $rate($selected) * (float) $currency->conversion_rate), flat = @json($selected->service_type === 'Package');
    const update = () => {document.getElementById('order-total').textContent = (flat ? rate : Number(quantity.value || 0) * rate / 1000).toLocaleString(undefined, {minimumFractionDigits:2,maximumFractionDigits:2}) + ' ' + @json($currency->code);document.getElementById('order-amount').textContent=Number(quantity.value||0).toLocaleString();document.querySelectorAll('[data-quantity]').forEach(b=>b.setAttribute('aria-pressed',Number(b.dataset.quantity)===Number(quantity.value)?'true':'false'));};
    document.getElementById('custom-quantity')?.addEventListener('click',()=>{quantity.focus();quantity.select();});
    quantity.addEventListener('input',update);
    document.querySelectorAll('[data-quantity]').forEach(preset => preset.addEventListener('click', () => {quantity.value = preset.dataset.quantity;update();}));
    update(); let busy = false;
    form.addEventListener('submit', async event => {
        event.preventDefault(); if (busy || !form.reportValidity()) return;
        busy = true; button.disabled = true; feedback.hidden = false; feedback.textContent = 'Submitting order…';
        document.getElementById('order-fund-link').hidden = true;
        try {
            const response = await fetch(form.action, {method:'POST',body:new FormData(form),headers:{Accept:'application/json'},credentials:'same-origin'});
            const result = await response.json();
            feedback.textContent = result.message || Object.values(result.errors || {}).flat().join(' ') || 'Unable to submit this order.';
            if (response.ok && result.status === 'success') {
                document.getElementById('order-history-link').hidden = false;
                button.textContent = 'Order submitted';
                return;
            }
            if (result.code === 'insufficient_balance') document.getElementById('order-fund-link').hidden = false;
            busy = false; button.disabled = false;
        } catch (error) {
            feedback.textContent = 'Could not confirm the result. Check My Orders before retrying to avoid a duplicate order.';
            document.getElementById('order-history-link').hidden = false;
            button.textContent = 'Check order history';
        }
    });
})();
</script>
@endif
</body></html>
