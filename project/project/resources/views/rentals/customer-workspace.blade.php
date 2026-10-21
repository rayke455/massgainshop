@php
    $preview = $preview ?? false;
    $screen = $screen ?? 'dashboard';
    $brand = $panel->allows('branding') ? $panel->name : 'Mass Panel Shop';
    $settings = $panel->workspace();
    $displayCurrency = $preview ? null : ($currency ?: \App\Services\PanelRentals::currency($panel));
    $money = fn($amount) => $preview ? number_format($amount, 2).' KES' : number_format((float) $amount * (float) $displayCurrency->conversion_rate, 2).' '.$displayCurrency->code;
    $links = [
        'dashboard' => 'user.dashboard', 'orders' => 'user.order.index',
        'services' => 'user.order.create', 'funds' => 'user.add.fund', 'support' => 'rental.support',
    ];
    $link = fn($key) => $preview ? route('rental.preview.customer', ['screen' => $key]) : route($links[$key]);
    $items = ['services' => ['New order', 'cart'], 'orders' => ['Order history', 'layers'], 'funds' => ['Add funds', 'wallet'], 'support' => ['Support', 'chat']];
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $brand }} · {{ ucfirst($screen) }}</title>
    @if($preview)<meta name="robots" content="noindex">@endif
    @include('rentals.workspace-style')
</head>
<body>
@include('rentals.workspace-icons')
<div class="workspace">
    @if($preview)
    <aside class="preview-note">Customer preview · sample account · payments and orders are disabled</aside>
    @endif
    <header class="workspace-header">
        <a class="brand-pill" href="{{ $link('dashboard') }}"><span class="brand-mark">{{ mb_substr($brand, 0, 1) }}</span><strong>{{ $brand }}</strong></a>
        <a class="balance-pill" href="{{ $link('funds') }}" aria-label="Wallet balance, add funds">{{ $money($walletBalance) }} <span class="plus">+</span></a>
    </header>
    <main>
    @if(session('success'))<aside class="announcement" role="status">{{ session('success') }}</aside>@endif
    @if(isset($errors) && $errors->any())<aside class="announcement" role="alert">{{ $errors->first() }}</aside>@endif
    @if($screen === 'dashboard')
        <div class="heading"><span class="eyebrow">YOUR WORKSPACE</span><h1>Welcome back{{ $preview ? '' : ', '.auth()->user()->username }}</h1><p>Manage your orders and grow your social accounts.</p></div>
        @if($settings['announcement'])<aside class="announcement">{{ $settings['announcement'] }}</aside>@endif
        @if(!$preview && $panel->allows('branding'))
        @foreach(\App\Models\RentalContent::visibleFor($panel, ['notice'])->limit(10)->get() as $notice)
        <aside class="announcement"><strong>{{ $notice->title }}</strong><p style="white-space:pre-wrap">{{ $notice->body }}</p></aside>
        @endforeach
        @include('rentals.content-links')
        @endif
        <section class="summary-grid" aria-label="Account statistics">
            <div class="summary blue"><small>Wallet balance</small><strong>{{ $money($walletBalance) }}</strong><a href="{{ $link('funds') }}">Add funds <span aria-hidden="true">↗</span></a></div>
            <div class="summary purple"><small>Active orders</small><strong>{{ number_format(data_get($orders, 'active', 0)) }}</strong><a href="{{ $link('orders') }}">Track orders <span aria-hidden="true">↗</span></a></div>
            @if($settings['show_orders'])<div class="summary"><small>Total orders</small><strong>{{ number_format(data_get($orders, 'total', 0)) }}</strong></div>@endif
            @if($settings['show_spent'])<div class="summary green"><small>Total spent</small><strong>{{ $money(data_get($orders, 'spent', 0)) }}</strong></div>@endif
        </section>
        <nav class="menu-list" aria-label="Customer actions">
            @foreach($items as $key => [$label, $icon])
            <a href="{{ $link($key) }}"><svg class="icon" aria-hidden="true"><use href="#icon-{{ $icon }}"/></svg><span>{{ $label }}</span><svg class="icon chevron" aria-hidden="true"><use href="#icon-chevron"/></svg></a>
            @endforeach
            @unless($preview)
            <a href="{{ route('user.transaction.history') }}"><svg class="icon" aria-hidden="true"><use href="#icon-wallet"/></svg><span>Transactions</span><svg class="icon chevron" aria-hidden="true"><use href="#icon-chevron"/></svg></a>
            <a href="{{ route('user.profile') }}"><svg class="icon" aria-hidden="true"><use href="#icon-user"/></svg><span>Account settings</span><svg class="icon chevron" aria-hidden="true"><use href="#icon-chevron"/></svg></a>
            <a href="{{ route('rental.api.settings') }}"><svg class="icon" aria-hidden="true"><use href="#icon-layers"/></svg><span>API access</span><svg class="icon chevron" aria-hidden="true"><use href="#icon-chevron"/></svg></a>
            @endunless
        </nav>
        <section class="content-card">
            <div class="section-heading"><h2>Recent orders</h2><a href="{{ $link('orders') }}">View all</a></div>
            @forelse($recentOrders as $order)
            <div class="order-row"><div><strong>{{ $order->service->service_title ?? 'Service order' }}</strong><small>#{{ $order->id }} · {{ $order->created_at->format('d M Y') }}</small></div><span class="status {{ $order->status === 'completed' ? 'success' : '' }}">{{ ucfirst($order->status) }}</span></div>
            @empty
            <div class="empty"><svg class="icon" aria-hidden="true"><use href="#icon-cart"/></svg><h3>Your next order starts here</h3><p>Your orders and delivery updates will appear here.</p><a class="primary" href="{{ $link('services') }}">Browse services</a></div>
            @endforelse
        </section>
        @unless($preview)
        <section class="content-card"><h2>Redeem a promo code</h2><form method="post" action="{{ route('rental.promotion.redeem') }}">@csrf<label for="promo-code">Promo code</label><input id="promo-code" name="code" maxlength="40" required autocomplete="off"><button class="primary" type="submit">Redeem</button></form></section>
        <form method="post" action="{{ route('logout') }}">@csrf<button class="primary" type="submit">Sign out</button></form>
        @endunless
    @else
        <a class="back" href="{{ $link('dashboard') }}">← Dashboard</a>
        <div class="heading"><h1>{{ ['orders' => 'Order history', 'services' => 'New order', 'funds' => 'Add funds', 'support' => 'Support'][$screen] }}</h1><p>Sample customer screen. Sign in on your panel to use this feature.</p></div>
        <section class="content-card">
        @if($screen === 'services')
            <label>Search services<input type="search" placeholder="Search by service name" disabled></label>
            <div class="empty"><svg class="icon"><use href="#icon-layers"/></svg><h2>Your panel’s services appear here</h2><p>The live order form uses your panel’s enabled services, prices, minimum quantities and available wallet balance.</p></div>
        @elseif($screen === 'funds')
            <div class="summary green"><small>Available balance</small><strong>0.00 KES</strong></div>
            <label>Amount<input type="number" placeholder="Amount in your selected currency" disabled></label>
            <label>Payment method<select disabled><option>M-Pesa / enabled payment methods</option></select></label>
            <button class="primary" disabled>Payment disabled in preview</button>
        @elseif($screen === 'support')
            <label>Subject<input placeholder="What can we help with?" disabled></label>
            <label>Message<textarea rows="4" disabled></textarea></label>
            <button class="primary" disabled>Sending disabled in preview</button>
        @else
            <div class="empty"><svg class="icon"><use href="#icon-cart"/></svg><h2>No orders in this sample account</h2><p>Signed-in customers can view their order history and delivery status here.</p><a class="primary" href="{{ $link('services') }}">Browse services</a></div>
        @endif
        </section>
    @endif
    </main>
    <footer class="workspace-bottom"><nav aria-label="Customer navigation">
        @foreach(['dashboard' => ['Home', 'user'], 'orders' => ['Orders', 'cart'], 'services' => ['Services', 'layers'], 'support' => ['Support', 'chat']] as $key => [$label, $icon])
        <a href="{{ $link($key) }}" @if($screen === $key) aria-current="page" @endif><svg class="icon" aria-hidden="true"><use href="#icon-{{ $icon }}"/></svg><span>{{ $label }}</span></a>
        @endforeach
    </nav></footer>
</div>
</body></html>
