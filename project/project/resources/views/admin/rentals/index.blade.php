@extends('admin.layouts.app')
@section('page_title', 'Panel rentals')
@section('content')
@php $zkPendingPayouts = \App\Models\RentalPayout::where('status', 'pending')->count(); @endphp
<div class="content container-fluid"><div class="page-header d-flex flex-wrap justify-content-between align-items-end gap-2"><div><h1>Panel rentals</h1><p class="mb-0">Super admin controls. Your catalogue is the default provider for every rental panel.</p></div>
<a class="btn btn-white" href="{{ route('admin.rentals.payouts') }}"><i class="bi-cash-coin me-1"></i> Reseller payouts @if($zkPendingPayouts)<span class="badge bg-danger ms-1">{{ $zkPendingPayouts }}</span>@endif</a></div>
@if($errors->any())<div class="alert alert-danger">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
<div class="card mb-4"><div class="card-body"><h2 class="h4">Rental settings</h2><form method="post" action="{{ route('admin.rentals.settings') }}">@csrf
<label class="d-block mb-3"><input type="checkbox" name="enabled" value="1" @checked($settings['enabled'])> Open panel rentals</label>
<div class="row g-3"><div class="col-md-4"><label class="form-label" for="rental-price">Price in {{ basicControl()->base_currency }}</label><input class="form-control" id="rental-price" name="price" type="number" min="0" max="100000" step="0.0001" value="{{ $settings['price'] }}" required></div><div class="col-md-4"><label class="form-label" for="rental-days">Days per payment</label><input class="form-control" id="rental-days" name="days" type="number" min="1" max="366" value="{{ $settings['days'] }}" required></div><div class="col-md-4"><label class="form-label" for="rental-domain">Base domain for new subdomains</label><input class="form-control" id="rental-domain" name="base_domain" value="{{ $settings['base_domain'] }}" placeholder="panels.example.com"></div></div>
@php $effective = \App\Services\PanelRentals::baseDomain($settings); $serverHost = parse_url(config('app.url'), PHP_URL_HOST); @endphp
<div class="alert alert-soft-primary mt-3 mb-0">
    <strong>Automatic panel addresses:</strong>
    @if($effective)
        every new panel gets <code>name.{{ $effective }}</code> instantly{{ $settings['base_domain'] ? '' : ' (your main domain is used because the field above is empty)' }}.
        <details class="mt-2"><summary>One-time DNS setup so these addresses open (Cloudflare)</summary>
            <ol class="mb-0 mt-2">
                <li>In Cloudflare → DNS, add a record: Type <code>A</code>, Name <code>*</code>{{ $settings['base_domain'] && $settings['base_domain'] !== preg_replace('/^www\./', '', (string) $serverHost) ? ' (or *.' . explode('.', $settings['base_domain'])[0] . ')' : '' }}, IPv4 = your server IP, Proxy <strong>on</strong> (orange cloud).</li>
                <li>SSL/TLS → set mode to <strong>Full</strong>. Cloudflare's free certificate covers <code>*.{{ $effective }}</code> one level deep.</li>
                <li>On your hosting, add <code>*.{{ $effective }}</code> as a wildcard subdomain / server alias pointing to this site's folder (cPanel → Domains → Subdomains → <code>*</code>).</li>
            </ol>
        </details>
    @else
        set a domain above (e.g. <code>massfameshop.com</code>). Until then panels work at <code>{{ url('/panels/name') }}</code>.
    @endif
</div>
<h3 class="h5 mt-4">Customer payments & reseller payouts</h3>
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Platform fee on each sale's profit (%)</label><input class="form-control" type="number" min="0" max="100" step="0.1" name="platform_fee" value="{{ $settings['platform_fee'] }}"><small class="text-muted">Customers pay through your M-Pesa Paybill (C2B). You keep the service cost + this % of the reseller's profit.</small></div>
    <div class="col-md-4"><label class="form-label">Minimum payout ({{ basicControl()->base_currency }})</label><input class="form-control" type="number" min="0" step="0.01" name="min_payout" value="{{ $settings['min_payout'] }}"></div>
    <div class="col-md-4"><label class="form-label">B2C/B2B short code <small class="text-muted">(blank = M-Pesa gateway short code)</small></label><input class="form-control" name="b2c_shortcode" value="{{ $settings['b2c_shortcode'] }}" inputmode="numeric"></div>
    <div class="col-md-4"><label class="form-label">Initiator name</label><input class="form-control" name="b2c_initiator" value="{{ $settings['b2c_initiator'] }}" autocomplete="off"></div>
    <div class="col-md-8"><label class="form-label">Security credential <small class="text-muted">({{ $settings['b2c_credential'] ? 'saved, leave blank to keep' : 'not set' }})</small></label><input class="form-control" type="password" name="b2c_credential" autocomplete="new-password" placeholder="From the Daraja portal: initiator password encrypted with the M-Pesa certificate"></div>
</div>
<p class="text-muted small mt-2 mb-0">With the initiator name and security credential, approved payouts are sent automatically by M-Pesa B2C (to phones) or B2B (to Paybills/Tills). Without them, pay manually and press "Mark paid". <a href="{{ route('admin.rentals.payouts') }}">Open payouts</a></p>
<h3 class="h5">Default features for new panels</h3><div class="d-flex flex-wrap gap-3">@foreach(\App\Services\PanelRentals::FEATURES as $feature => $label)<label><input name="features[]" type="checkbox" value="{{ $feature }}" @checked(in_array($feature, $settings['features']))> {{ $label }}</label>@endforeach</div><button class="btn btn-primary mt-3">Save rental settings</button></form></div></div>
<h2 class="h4">Tenant access</h2><p>Uncheck a feature to disable it on the server. Suspension and termination immediately block the storefront and tenant actions; records are retained. Expired panels stay blocked until renewed.</p>
@forelse($panels as $panel)<div class="card mb-3"><div class="card-body"><h3 class="h5">{{ $panel->name }} <small class="text-muted">· {{ $panel->owner->username ?? 'Unknown owner' }}</small></h3><p>{{ $panel->subdomain ?: $panel->slug }} @if($panel->custom_domain) · {{ $panel->custom_domain }} ({{ $panel->domain_verified_at ? 'verified' : 'not verified' }}) @endif</p><p>Expires {{ $panel->expires_at->format('d M Y H:i') }}</p><form method="post" action="{{ route('admin.rentals.panel', $panel->id) }}">@csrf<label class="form-label" for="status-{{ $panel->id }}">Panel status</label><select class="form-select mb-3" id="status-{{ $panel->id }}" name="status">@foreach(['active','suspended','terminated'] as $status)<option value="{{ $status }}" @selected($panel->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select><div class="d-flex flex-wrap gap-3">@foreach(\App\Services\PanelRentals::FEATURES as $feature => $label)<label><input type="checkbox" name="features[]" value="{{ $feature }}" @checked(in_array($feature, $panel->features ?? []))> {{ $label }}</label>@endforeach</div><button class="btn btn-primary mt-3">Apply panel controls</button></form></div></div>@empty<div class="card card-body">No tenants yet. Set your rental price and domain above, then enable rentals.</div>@endforelse
{{ $panels->links() }}</div>
@endsection
