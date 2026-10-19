@extends('rentals.layout')
@section('nav')<a href="{{ route('user.panels.index') }}">All my panels</a>@endsection
@section('content')
@php
    $base = basicControl()->base_currency;
    $url = $panel->subdomain ? 'https://' . $panel->subdomain : url('/panels/' . $panel->slug);
    $logo = \App\Services\PanelRentals::logoUrl($panel);
    $statusLabels = ['requested' => 'Request', 'awaiting_payment' => 'Waiting for payment', 'payment_failed' => 'Payment failed', 'submitted' => 'Paid · in progress', 'failed' => 'Paid · supplier refused'];
@endphp
<h1>{{ $panel->name }}</h1>
<p><span class="tag">{{ $panel->available() ? 'Active' : ($panel->status === 'active' ? 'Expired' : ucfirst($panel->status)) }}</span> · Paid until {{ $panel->expires_at->format('d M Y H:i') }}</p>
<p>Your panel: <a href="{{ $panel->available() ? route('panels.show', $panel->slug) : '#' }}" target="_blank" rel="noopener"><strong>{{ $url }}</strong></a>
    @if($panel->subdomain)<br><small>Before your domain is live (DNS), it also works at <a href="{{ route('panels.show', $panel->slug) }}" target="_blank" rel="noopener">{{ url('/panels/' . $panel->slug) }}</a></small>@endif</p>

{{-- ===== Branding & settings + live preview ===== --}}
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
                    <option value="mpesa" @selected($panel->payment_mode === 'mpesa')>M-Pesa through {{ basicControl()->site_title }}'s Paybill (automatic){{ $mpesaReady ? '' : ' (not available right now)' }}</option>
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
            @if($panel->available())<button>Save settings</button>@endif
        </form>
    </div>
    <div class="card">
        <h2>Preview</h2>
        <p class="muted">Updates as you change the name, tagline, colour, markup or currency (logo after saving).</p>
        <iframe id="pv" title="Panel preview" src="{{ route('user.panels.preview', ['panel' => $panel->id]) }}" style="width:100%;height:600px;border:1px solid #e2e7f0;border-radius:14px;background:#fff"></iframe>
    </div>
</div>

{{-- ===== Earnings & payouts ===== --}}
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

{{-- ===== Your prices ===== --}}
@if($panel->allows('markup'))
<div class="card">
    <h2>Your prices <small>per 1,000, in {{ $cur->code }}</small></h2>
    <p class="muted">Leave a price empty to use your {{ rtrim(rtrim(number_format($panel->markup, 2), '0'), '.') }}% markup. Prices can't go below your cost (they are raised to the cost automatically). Tick "Hide" to remove a service from your panel.</p>
    <input type="search" id="pq" placeholder="Search services…" style="margin-bottom:10px">
    <form method="post" action="{{ route('user.panels.prices', $panel->id) }}">@csrf
        <div class="table-wrap" style="max-height:520px">
            <table id="ptable"><thead><tr><th>Service</th><th>Your cost</th><th>Customer pays</th><th>Your price</th><th>Hide</th></tr></thead><tbody>
            @foreach($services as $s)
                <tr data-n="{{ strtolower(\App\Services\SmartImport::cleanName($s->service_title)) }}">
                    <td>{{ \Illuminate\Support\Str::limit(\App\Services\SmartImport::cleanName($s->service_title), 70) }}</td>
                    <td>{{ number_format($s->cost_k, $s->cost_k < 1 ? 4 : 2) }}</td>
                    <td><strong>{{ number_format($s->panel_price, $s->panel_price < 1 ? 4 : 2) }}</strong></td>
                    <td><input type="number" name="price[{{ $s->id }}]" min="{{ $s->cost_k }}" step="0.0001" value="{{ $s->own }}" placeholder="{{ number_format($s->panel_price, 2, '.', '') }}" style="min-width:110px"></td>
                    <td><input type="checkbox" name="hidden[{{ $s->id }}]" value="1" @checked($s->hidden_k) style="width:auto"></td>
                </tr>
            @endforeach
            </tbody></table>
        </div>
        @if($panel->available())<button>Save prices</button>@endif
    </form>
</div>
@endif

{{-- ===== Domains & renewal (unchanged behaviour) ===== --}}
<div class="card"><h2>Domains & renewal</h2><p>Subdomain: <strong>{{ $panel->subdomain ?: 'Assigned automatically once the platform owner sets the panel domain' }}</strong></p>
@if($panel->custom_domain)<p>Custom domain: {{ $panel->custom_domain }} · {{ $panel->domain_verified_at ? 'Ownership verified' : 'Verification required' }}</p><p>Add a DNS TXT record:</p><p><code>_zirka-panel.{{ $panel->custom_domain }}</code><br><code>zirka-panel={{ $panel->domain_token }}</code></p>
<p>Then point the domain to {{ basicControl()->site_title }}: a <code>CNAME</code> record to <code>{{ $panel->subdomain ?: parse_url(config('app.url'), PHP_URL_HOST) }}</code>.</p>
@if($panel->allows('custom_domain'))<form method="post" action="{{ route('user.panels.verify', $panel->id) }}">@csrf<button>Verify domain ownership</button></form>@endif
@endif
@if($panel->status === 'active' && $settings['enabled'])
@php
    $renewalToken = session('rental_renew_'.$panel->id) ?: (string) \Illuminate\Support\Str::uuid();
    session(['rental_renew_'.$panel->id => $renewalToken]);
@endphp
<form method="post" action="{{ route('user.panels.renew', $panel->id) }}">@csrf<input type="hidden" name="renewal_token" value="{{ $renewalToken }}"><input type="hidden" name="price" value="{{ $settings['price'] }}"><button>Renew {{ $settings['days'] }} days · {{ $settings['price'] }} {{ $base }}</button></form>@endif</div>

{{-- ===== Customer accounts ===== --}}
@if($panel->allows('accounts'))
<div class="card"><h2>Customers <small>{{ number_format($customerTotal) }} with an account</small></h2>
    <p>Customers sign up at <strong>{{ $panel->custom_domain && $panel->domain_verified_at ? $panel->custom_domain : ($panel->subdomain ?: 'your panel domain') }}/register</strong>, add funds to their wallet and order from a dashboard with your name, logo and prices. Your profit on every order is added to your earnings automatically.</p>
    @if($customers->isEmpty())
        <p>No customer accounts yet.</p>
    @else
        <div style="overflow-x:auto"><table><thead><tr><th>Customer</th><th>Contact</th><th>Wallet</th><th>Orders</th><th>Your profit</th><th>Joined</th></tr></thead><tbody>
        @foreach($customers as $c)
            <tr><td>{{ $c->username }}</td><td>{{ $c->email }}{{ $c->phone ? ' · ' . $c->phone_code . $c->phone : '' }}</td>
                <td>{{ number_format((float) $c->balance, 2) }} {{ $base }}</td><td>{{ number_format($c->orders_count) }}</td>
                <td>{{ number_format((float) $c->earned, 2) }} {{ $base }}</td><td>{{ $c->created_at->format('d M Y') }}</td></tr>
        @endforeach
        </tbody></table></div>
    @endif
</div>
@endif

{{-- ===== Storefront (guest) orders ===== --}}
<h2>Storefront orders</h2>
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
            <a href="{{ route('user.order.create', ['serviceId' => $item->service_id, 'link' => $item->link, 'quantity' => $item->quantity]) }}" target="_blank" rel="noopener">Place matching order with {{ basicControl()->site_title }}</a>
            <form method="post" action="{{ route('user.panels.link', [$panel->id, $item->id]) }}">@csrf<label>Order ID<input name="provider_order_id" type="number" min="1" required></label><button>Link order for tracking</button></form>
        @endif
    </div>
@empty
    <p>No customer orders yet. Share your panel link to start selling.</p>
@endforelse
{{ $requests->links() }}

<script>
    (function () {
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
