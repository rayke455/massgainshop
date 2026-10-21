@extends('rentals.layout')
@section('title', 'Tenant control panel')
@include('rentals.control-style')
@include('rentals.owner-orders-style')
@section('brand', $panel->subdomain ?: $panel->name)
@section('nav')<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.customer') : route('user.panels.index') }}">{{ ($ownerPreview ?? false) ? 'Customer preview' : 'All my panels' }}</a>@endsection
@section('content')
@php
    $base = basicControl()->base_currency;
    $url = $panel->subdomain ? 'https://' . $panel->subdomain : url('/panels/' . $panel->slug);
    $logo = \App\Services\PanelRentals::logoUrl($panel);
    $statusLabels = ['requested' => 'Request', 'awaiting_payment' => 'Waiting for payment', 'payment_failed' => 'Payment failed', 'submitted' => 'Paid · in progress', 'failed' => 'Paid · supplier refused'];
@endphp
<h1 class="owner-page-heading">{{ $panel->name }}</h1>
<div class="tenant-overview" aria-label="Tenant overview">
    <div><strong>{{ number_format($customerTotal) }}</strong><small>Customers</small></div>
    <div><strong>{{ number_format($orderTotal) }}</strong><small>Orders</small></div>
    <div><strong>{{ number_format($serviceTotal) }}</strong><small>Services</small></div>
    <div><strong>{{ number_format($earnings, 2) }} {{ $base }}</strong><small>Earnings</small></div>
</div>
<nav class="tenant-tabs" aria-label="Tenant workspace">
    @if($panel->allows('accounts'))<a data-control-link href="#customers">Users</a>@endif
    <a data-control-link href="#orders">Orders</a>
    @if($panel->allows('catalogue'))<a data-control-link href="#services">Services</a>@endif
    <a data-control-link href="#statistics">Statistics</a>
    @if($panel->allows('accounts'))<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'support']) : route('user.panels.support', $panel->id) }}">Customer support</a>@endif
    <a data-control-link href="#payments">Payments</a>
    <a data-control-link href="#general">Control panel</a>
</nav>
@include('rentals.control-navigation')
<p class="owner-panel-meta"><span class="tag">{{ $panel->available() ? 'Active' : ($panel->status === 'active' ? 'Expired' : ucfirst($panel->status)) }}</span> · Paid until {{ $panel->expires_at->format('d M Y H:i') }}</p>
<p class="owner-panel-meta">Your panel: <a href="{{ ($ownerPreview ?? false) ? route('rental.preview.public') : ($panel->available() ? route('panels.show', $panel->slug) : '#') }}" target="_blank" rel="noopener"><strong>{{ ($ownerPreview ?? false) ? 'Sample storefront' : $url }}</strong></a>
    @if($panel->subdomain)<br><small>Before your domain is live (DNS), it also works at <a href="{{ route('panels.show', $panel->slug) }}" target="_blank" rel="noopener">{{ url('/panels/' . $panel->slug) }}</a></small>@endif</p>

{{-- ===== Branding & settings + live preview ===== --}}
<section id="general" class="tenant-section" aria-label="General settings">
<div class="grid">
    <div class="card">
        <h2>Branding & settings</h2>
        <form method="post" action="{{ route('user.panels.update', $panel->id) }}" enctype="multipart/form-data" id="brandForm">@csrf
            @if($panel->allows('branding'))
                <label>Panel name<input name="name" value="{{ old('name', $panel->name) }}" maxlength="100" required></label>
                <label>Tagline<input name="tagline" value="{{ old('tagline', $panel->tagline) }}" maxlength="160" placeholder="Followers & likes in minutes"></label>
                <label>Logo <small>PNG/JPG/WebP, up to 2 MB. Wide logos look best.</small><input type="file" name="logo" accept="image/png,image/jpeg,image/webp"></label>
                @if($logo)<p><img src="{{ $logo }}" alt="Current logo" style="height:40px;max-width:200px;object-fit:contain"> <label class="inline" style="display:inline-flex;font-weight:400"><input type="checkbox" name="remove_logo" value="1" style="width:auto"> Remove logo</label></p>@endif
                <label>Brand colour<input type="color" name="color" value="{{ old('color', $panel->color ?: '#2563eb') }}" style="height:46px;padding:4px"></label>
                <label>WhatsApp number for your customers<input name="whatsapp" value="{{ old('whatsapp', $panel->whatsapp ? '+' . $panel->whatsapp : '') }}" placeholder="+254 712 345 678"></label>
            @else
                <input type="hidden" name="name" value="{{ $panel->name }}">
            @endif
            <label>Currency your customers see
                <select name="currency">@foreach($currencies as $c)<option value="{{ $c->code }}" @selected($cur->code === $c->code)>{{ $c->code }} · {{ $c->name }}</option>@endforeach</select>
            </label>
            <label>How customers pay
                <select name="payment_mode">
                    <option value="mpesa" @selected($panel->payment_mode === 'mpesa')>M-Pesa through Mass Panel Shop's Paybill (automatic){{ $mpesaReady ? '' : ' (not available right now)' }}</option>
                    <option value="manual" @selected($panel->payment_mode === 'manual')>I collect payment myself (customers send a request)</option>
                </select>
                <small>With M-Pesa, the order starts automatically after payment and your profit goes to your earnings below.</small>
            </label>
            @if($panel->allows('markup'))
                <label>Default markup (%)<input name="markup" type="number" min="0" max="1000" step="0.01" value="{{ old('markup', $panel->markup) }}" required></label>
                <small>Used for every service without its own price (see "Your prices").</small>
            @else
                <input type="hidden" name="markup" value="{{ $panel->markup }}">
            @endif
            @if($panel->allows('custom_domain'))<label>Custom domain<input name="custom_domain" value="{{ $panel->custom_domain }}" placeholder="panel.yourdomain.com"></label>@endif
            <h3>Registration & customer dashboard</h3>
            @if($panel->allows('accounts'))
            <details><summary>Custom registration fields</summary>
                <p class="muted">Add up to five signup questions. Leave a label blank to hide that field. Saved answers keep the original question label.</p>
                @foreach(range(1, 5) as $index)
                    @php
                        $fieldKey = 'field_'.$index;
                    @endphp
                    <label>Question {{ $index }}<input name="workspace[registration_fields][{{ $fieldKey }}][label]" maxlength="80" value="{{ old('workspace.registration_fields.'.$fieldKey.'.label', data_get($panel->workspace(), 'registration_fields.'.$fieldKey.'.label', '')) }}"></label>
                    <input type="hidden" name="workspace[registration_fields][{{ $fieldKey }}][required]" value="0">
                    <label class="inline"><input type="checkbox" name="workspace[registration_fields][{{ $fieldKey }}][required]" value="1" @checked(old('workspace.registration_fields.'.$fieldKey.'.required', data_get($panel->workspace(), 'registration_fields.'.$fieldKey.'.required', false)))> Answer required</label>
                @endforeach
            </details>
            @endif
            @foreach(['registration' => 'Accept new registrations', 'show_orders' => 'Show customer order count', 'show_spent' => 'Show customer total spent'] as $key => $label)
                <input type="hidden" name="workspace[{{ $key }}]" value="0">
                <label class="inline"><input type="checkbox" name="workspace[{{ $key }}]" value="1" @checked(old('workspace.'.$key, $panel->workspace()[$key]))> {{ $label }}</label>
            @endforeach
            @if($panel->allows('branding'))
                <label>Customer announcement<textarea name="workspace[announcement]" rows="3" maxlength="1000">{{ old('workspace.announcement', $panel->workspace()['announcement']) }}</textarea></label>
                <label>Search engine title<input name="workspace[seo_title]" maxlength="100" value="{{ old('workspace.seo_title', $panel->workspace()['seo_title']) }}"></label>
                <label>Search engine description<textarea name="workspace[seo_description]" rows="3" maxlength="300">{{ old('workspace.seo_description', $panel->workspace()['seo_description']) }}</textarea></label>
                <h3>Public pages</h3><p class="muted">Your text appears at /terms and /privacy on your panel domain. Leave blank to hide a page.</p>
                <label>Terms of service<textarea name="workspace[terms]" rows="6" maxlength="20000">{{ old('workspace.terms', $panel->workspace()['terms']) }}</textarea></label>
                <label>Privacy policy<textarea name="workspace[privacy]" rows="6" maxlength="20000">{{ old('workspace.privacy', $panel->workspace()['privacy']) }}</textarea></label>
            @endif
            @if($panel->available())<button>Save settings</button>@endif
        </form>
    </div>
    <div class="card">
        <h2>Preview</h2>
        <p class="muted">Updates as you change the name, tagline, colour, markup or currency (logo after saving).</p>
        <iframe id="pv" title="Panel preview" src="{{ ($ownerPreview ?? false) ? route('rental.preview.public') : route('user.panels.preview', ['panel' => $panel->id]) }}" style="width:100%;height:600px;border:1px solid #e2e7f0;border-radius:14px;background:#fff"></iframe>
    </div>
</div>
</section>

{{-- ===== Earnings & payouts ===== --}}
<section id="payments" class="tenant-section" aria-label="Payments and earnings">
<div class="card">
    <h2>Earnings</h2>
    <p style="font-size:28px;margin:0"><strong>{{ number_format($earnings, 2) }} {{ $base }}</strong></p>
    <p class="muted">Profit from customers who paid with M-Pesa on your panel{{ $settings['platform_fee'] > 0 ? ' (after the ' . $settings['platform_fee'] . '% platform fee)' : '' }}.</p>
    <div class="grid">
        @include('rentals.payout-destination')
        <form method="post" action="{{ route('user.panels.towallet') }}">@csrf
            <h3>Use for orders</h3>
            <p class="muted">Move earnings to your main balance to buy services.</p>
            <label>Amount ({{ $base }})<input type="number" name="amount" min="0.01" step="0.01" max="{{ $earnings }}" required></label>
            <button class="button" @disabled($earnings <= 0)>Move to main wallet</button>
        </form>
    </div>
    @if($payouts->isNotEmpty())
        <div class="table-wrap"><table><thead><tr><th>Date</th><th>Amount</th><th>To</th><th>Status</th></tr></thead><tbody>
            @foreach($payouts as $p)<tr><td>{{ $p->created_at->format('d M Y') }}</td><td>{{ number_format($p->amount, 2) }} {{ $base }} <small>{{ $p->payout_currency }} {{ number_format($p->payout_amount ?? $p->amount_kes) }}</small></td>
                <td>{{ ['b2c' => 'M-Pesa', 'paybill' => 'Paybill', 'till' => 'Till'][$p->method] }} {{ $p->account }}{{ $p->account_ref ? ' / ' . $p->account_ref : '' }}</td>
                <td>{{ ucfirst($p->status) }}@if($p->mpesa_ref)<small>{{ $p->mpesa_ref }}</small>@endif @if($p->status === 'rejected' && $p->note)<small>{{ $p->note }}</small>@endif</td></tr>@endforeach
        </tbody></table></div>
    @endif
</div>
</section>

<section id="statistics" class="tenant-section" aria-label="Statistics">
<div class="card">
    <h2>Statistics</h2>
    <p class="muted">Performance for this tenant panel only. Provider and other tenants' data is excluded.</p>
    <div class="grid">
        <div><h3>Order status</h3><div class="table-wrap"><table><thead><tr><th>Status</th><th>Orders</th><th>Revenue</th><th>Profit</th></tr></thead><tbody>
            @forelse($orderStats as $stat)<tr><td>{{ ucfirst($stat->status) }}</td><td>{{ number_format($stat->total) }}</td><td>{{ number_format($stat->revenue, 2) }} {{ $base }}</td><td>{{ number_format($stat->earnings, 2) }} {{ $base }}</td></tr>
            @empty<tr><td colspan="4">No account orders yet.</td></tr>@endforelse
        </tbody></table></div></div>
        <div><h3>Top services</h3><div class="table-wrap"><table><thead><tr><th>Service</th><th>Orders</th><th>Revenue</th></tr></thead><tbody>
            @forelse($topServices as $stat)<tr><td>{{ $stat->service ? \Illuminate\Support\Str::limit(\App\Services\SmartImport::cleanName($stat->service->service_title), 42) : 'Service unavailable' }}</td><td>{{ number_format($stat->total) }}</td><td>{{ number_format($stat->revenue, 2) }} {{ $base }}</td></tr>
            @empty<tr><td colspan="3">No services ordered yet.</td></tr>@endforelse
        </tbody></table></div></div>
    </div>
</div>
</section>

{{-- ===== Your prices ===== --}}
@if($panel->allows('markup'))
<section id="services" class="tenant-section" aria-label="Service prices">
<div class="card">
    <h2>Your prices <small>per 1,000 or item, in {{ $cur->code }}</small></h2>
    <p class="muted">Leave a price empty to use your {{ rtrim(rtrim(number_format($panel->markup, 2), '0'), '.') }}% markup. Prices can't go below your cost (they are raised to the cost automatically). Tick "Hide" to remove a service from your panel.</p>
    <input type="search" id="pq" placeholder="Search services…" style="margin-bottom:10px">
    <form method="post" action="{{ route('user.panels.prices', $panel->id) }}">@csrf
        <div class="table-wrap" style="max-height:520px">
            <table id="ptable"><thead><tr><th>Service</th><th>Your cost</th><th>Customer pays</th><th>Your price</th><th>Hide</th></tr></thead><tbody>
            @foreach($services as $s)
                <tr data-n="{{ strtolower(\App\Services\SmartImport::cleanName($s->service_title)) }}">
                    <td>{{ \Illuminate\Support\Str::limit(\App\Services\SmartImport::cleanName($s->service_title), 70) }}</td>
                    <td>{{ number_format($s->cost_k, $s->cost_k < 1 ? 4 : 2) }} <small>/ {{ \App\Services\PanelRentals::quantityBased($s) ? '1k' : 'item' }}</small></td>
                    <td><strong>{{ number_format($s->panel_price, $s->panel_price < 1 ? 4 : 2) }}</strong> <small>/ {{ \App\Services\PanelRentals::quantityBased($s) ? '1k' : 'item' }}</small></td>
                    <td><input type="number" name="price[{{ $s->id }}]" min="{{ $s->cost_k }}" step="0.0001" value="{{ $s->own }}" placeholder="{{ number_format($s->panel_price, 2, '.', '') }}" style="min-width:110px"></td>
                    <td><input type="checkbox" name="hidden[{{ $s->id }}]" value="1" @checked($s->hidden_k) style="width:auto"></td>
                </tr>
            @endforeach
            </tbody></table>
        </div>
        @if($panel->available())<button>Save prices</button>@endif
    </form>
</div>
</section>
@endif

{{-- ===== Domains & renewal (unchanged behaviour) ===== --}}
<section id="domains" class="tenant-section" aria-label="Domains and subscription">
<div class="card"><h2>Domains & renewal</h2><p>Subdomain: <strong>{{ $panel->subdomain ?: 'Assigned automatically once the platform owner sets the panel domain' }}</strong></p>
<p class="muted">Domain ownership verification does not configure hosting or HTTPS. Your platform administrator must configure routing and certificates before the domain is ready.</p>
@if($panel->custom_domain)<p>Custom domain: {{ $panel->custom_domain }} · {{ $panel->domain_verified_at ? 'Ownership verified' : 'Verification required' }}</p><p>Add a DNS TXT record:</p><p><code>_zirka-panel.{{ $panel->custom_domain }}</code><br><code>zirka-panel={{ $panel->domain_token }}</code></p>
<p>Then point the domain to Mass Panel Shop: a <code>CNAME</code> record to <code>{{ $panel->subdomain ?: parse_url(config('app.url'), PHP_URL_HOST) }}</code>.</p>
@if($panel->allows('custom_domain'))<form method="post" action="{{ route('user.panels.verify', $panel->id) }}">@csrf<button>Verify domain ownership</button></form>@endif
@endif
@if($panel->status === 'active' && $settings['enabled'])
@php
    $renewalToken = session('rental_renew_'.$panel->id) ?: (string) \Illuminate\Support\Str::uuid();
    session(['rental_renew_'.$panel->id => $renewalToken]);
@endphp
<form method="post" action="{{ route('user.panels.renew', $panel->id) }}">@csrf<input type="hidden" name="renewal_token" value="{{ $renewalToken }}"><input type="hidden" name="price" value="{{ $settings['price'] }}"><button>Renew {{ $settings['days'] }} days · {{ $settings['price'] }} {{ $base }}</button></form>@endif</div>
</section>

{{-- ===== Customer accounts ===== --}}
@if($panel->allows('accounts'))
<section id="customers" class="tenant-section" aria-label="Customers">
<div class="card"><h2>Customers <small>{{ number_format($customerTotal) }} with an account</small></h2>
    <form method="get" action="{{ route('user.panels.show', $panel->id) }}#customers">
        <label for="customer-search">Find a customer</label>
        <input id="customer-search" name="customer_search" maxlength="100" value="{{ request('customer_search') }}" placeholder="Username or email">
        <button>Search customers</button>
    </form>
    <p>Customers sign up at <strong>{{ $panel->custom_domain && $panel->domain_verified_at ? $panel->custom_domain : ($panel->subdomain ?: 'your panel domain') }}/register</strong>, add funds to their wallet and order from a dashboard with your name, logo and prices. Your profit on every order is added to your earnings automatically.</p>
    @if($customers->isEmpty())
        <p>No customer accounts yet.</p>
    @else
        <div style="overflow-x:auto"><table><thead><tr><th>Customer</th><th>Contact</th><th>Wallet</th><th>Orders</th><th>Your profit</th><th>Joined</th><th>Access</th></tr></thead><tbody>
        @foreach($customers as $c)
            <tr><td><a href="{{ route('user.panels.show', [$panel->id, 'customer_id' => $c->id]) }}#orders">{{ $c->username }}</a><small>ID {{ $c->id }}</small>
                @if($c->rental_profile)<details><summary>Signup answers</summary>@foreach($c->rental_profile as $answer)<p><strong>{{ $answer['label'] }}</strong><br>{{ $answer['value'] }}</p>@endforeach</details>@endif
            </td><td>{{ $c->email }}{{ $c->phone ? ' · ' . $c->phone_code . $c->phone : '' }}</td>
                <td>{{ number_format((float) $c->balance, 2) }} {{ $base }}</td><td>{{ number_format($c->orders_count) }}</td>
                <td>{{ number_format((float) $c->earned, 2) }} {{ $base }}</td><td>{{ $c->created_at->format('d M Y') }}</td>
                <td><span class="tag">{{ $c->status ? 'Active' : 'Suspended' }}</span><form method="post" action="{{ route('user.panels.customer.status', [$panel->id, $c->id]) }}">@csrf
                    <input type="hidden" name="status" value="{{ $c->status ? 0 : 1 }}"><button>{{ $c->status ? 'Suspend access' : 'Restore access' }}</button>
                </form></td></tr>
        @endforeach
        </tbody></table></div>
    @endif
    {{ $customers->fragment('customers')->links() }}
</div>
</section>
@endif

{{-- ===== Storefront (guest) orders ===== --}}
<section id="orders" class="tenant-section" aria-label="Storefront orders">
@if($panel->allows('accounts'))
@include('rentals.owner-orders')
@endif
<details class="owner-guest-orders"><summary>Guest orders · {{ $requests->total() }}</summary>
@forelse($requests as $item)
    <div class="card"><h3>#{{ $item->id }} · {{ $item->service ? \App\Services\SmartImport::cleanName($item->service->service_title) : 'Service unavailable' }}</h3>
        <p><span class="tag">{{ $statusLabels[$item->status] ?? ucfirst($item->status) }}</span> {{ $item->name }} · {{ $item->email }}{{ $item->phone ? ' · ' . $item->phone : '' }}</p>
        <p>Quantity: {{ number_format($item->quantity) }} · Total: {{ number_format((float) $item->quote, 2) }} {{ $item->currency }}
            @if($item->paid_amount && $item->paid_at) · Paid KES {{ number_format($item->paid_amount) }}@endif
            @if($item->earning !== null) · Your profit: <strong>{{ number_format($item->earning, 2) }} {{ $base }}</strong>@endif</p>
        <p>Link: {{ $item->link }}</p>
        @if($item->note && in_array($item->status, ['failed', 'payment_failed']))<div class="notice error">{{ $item->note }}</div>@endif
        @if($item->provider_order_id)<p>Order #{{ $item->provider_order_id }} · {{ $item->providerOrder->status ?? $item->status }}</p>
        @elseif($item->status === 'requested' && $panel->allows('orders'))
            <a href="{{ route('user.order.create', ['serviceId' => $item->service_id, 'link' => $item->link, 'quantity' => $item->quantity]) }}" target="_blank" rel="noopener">Place matching order with Mass Panel Shop</a>
            <form method="post" action="{{ route('user.panels.link', [$panel->id, $item->id]) }}">@csrf<label>Order ID<input name="provider_order_id" type="number" min="1" required></label><button>Link order for tracking</button></form>
        @endif
    </div>
@empty
    <p>No customer orders yet. Share your panel link to start selling.</p>
@endforelse
{{ $requests->fragment('orders')->links() }}
</details>
</section>
<section id="provider" class="tenant-section card" aria-label="Provider">
    <h2>Your service provider</h2>
<p><strong>Mass Panel Shop</strong> supplies your panel's service catalogue and fulfils orders through the platform.</p>
    <p class="muted">You manage your brand and permitted resale prices. Provider connections and feature access remain controlled by the platform administrator.</p>
</section>

@include('rentals.workspace-icons')
<nav class="owner-bottom-nav" aria-label="Panel navigation">
    @if($panel->allows('accounts'))<a data-control-link href="#customers"><svg aria-hidden="true"><use href="#icon-user"/></svg><span>Users</span></a>@endif
    <a data-control-link href="#orders"><svg aria-hidden="true"><use href="#icon-cart"/></svg><span>Orders</span></a>
    @if($panel->allows('catalogue'))<a data-control-link href="#services"><svg aria-hidden="true"><use href="#icon-layers"/></svg><span>Services</span></a>@endif
    @if($panel->allows('accounts'))<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'support']) : route('user.panels.support', $panel->id) }}"><svg aria-hidden="true"><use href="#icon-chat"/></svg><span>Support</span></a>@endif
    <a data-control-link href="#"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M3 5h18v2H3zm0 6h18v2H3zm0 6h18v2H3z"/></svg><span>Menu</span></a>
</nav>
<script>
    (function () {
        const sections = [...document.querySelectorAll('.tenant-section')];
        const links = [...document.querySelectorAll('[data-control-link]')];
        const home = document.getElementById('tenant-control');
        function navigate() {
            const key = location.hash.slice(1);
            document.body.classList.toggle('owner-orders-screen', key === 'orders');
            const selected = sections.find(section => section.id === key);
            sections.forEach(section => section.hidden = selected ? section !== selected : true);
            home.hidden = !!selected;
            document.getElementById('control-back').hidden = !selected;
            links.forEach(link => {
                if (link.hash === location.hash) link.setAttribute('aria-current', 'page');
                else link.removeAttribute('aria-current');
            });
        }
        // Leave every section readable when JavaScript is unavailable.
        window.addEventListener('hashchange', navigate);
        if (@json($errors->any())) {
            // Validation may belong to any form: expose all sections to make the error recoverable.
            sections.forEach(section => section.hidden = false);
        } else navigate();
        const m = document.getElementById('pmethod'), ref = document.getElementById('refWrap');
        m?.addEventListener('change', () => { ref.hidden = m.value !== 'paybill'; });
        const q = document.getElementById('pq');
        q?.addEventListener('input', () => { const v = q.value.toLowerCase(); document.querySelectorAll('#ptable tbody tr').forEach(tr => tr.hidden = v && !tr.dataset.n.includes(v)); });
        const form = document.getElementById('brandForm'), pv = document.getElementById('pv'), url = @json(route('user.panels.preview'));
        let t;
        form?.addEventListener('input', e => {
            if (e.target.type === 'file') return;
            clearTimeout(t);
            t = setTimeout(() => {
                const q = new URLSearchParams({panel: @json($panel->id), name: form.name?.value || '', tagline: form.tagline?.value || '',
                    color: form.color?.value || '', markup: form.markup?.value || '', currency: form.currency?.value || ''});
                pv.src = url + '?' + q.toString();
            }, 400);
        });
    })();
</script>
@endsection
