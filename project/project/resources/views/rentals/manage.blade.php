@extends('rentals.layout')
@section('nav')<a href="{{ route('user.dashboard') }}">Back to dashboard</a>@endsection
@section('content')
@php $base = \App\Services\PanelRentals::baseDomain($settings); @endphp
<h1>Your brand. Your panel.</h1>
<p class="muted">Run your own SMM panel powered by {{ basicControl()->site_title }}: your name, logo and prices. Customers pay you with M-Pesa through our Paybill, orders start automatically and your profit goes to your earnings wallet.</p>

<div class="grid">
    <div class="card">
        <h2>Start a panel</h2>
        @if($settings['enabled'])
            <p><strong>{{ $settings['price'] }} {{ basicControl()->base_currency }}</strong> for {{ $settings['days'] }} days, paid from your balance. Renew before it expires.</p>
            <form method="post" action="{{ route('user.panels.store') }}" id="rentForm">@csrf
                <input type="hidden" name="price" value="{{ $settings['price'] }}">
                <label for="name">Panel name</label>
                <input id="name" name="name" maxlength="100" value="{{ old('name') }}" required placeholder="e.g. Nairobi Boost">
                <label for="slug">Panel address</label>
                <input id="slug" name="slug" pattern="[a-z][a-z0-9-]{2,39}" maxlength="40" value="{{ old('slug') }}" placeholder="nairobi-boost" required>
                <small id="addr">{{ $base ? 'Your address: your-name.' . $base : 'Your panel works at ' . url('/panels/your-name') . ' (the owner has not set up a domain for subdomains yet).' }}</small>
                <label for="tagline">Tagline <span class="muted">(preview only, set it after renting)</span></label>
                <input id="tagline" maxlength="160" placeholder="Followers & likes in minutes">
                <label for="color">Brand colour</label>
                <input id="color" type="color" value="#2563eb" style="height:46px;padding:4px">
                <button>Rent panel · {{ $settings['price'] }} {{ basicControl()->base_currency }}</button>
            </form>
        @else
            <p>Rentals will open when the platform owner enables them and sets the plans.</p>
        @endif
    </div>
    <div class="card">
        <h2>Live preview</h2>
        <p class="muted">This is how your customers will see your panel. It updates as you type.</p>
        <iframe id="pv" title="Panel preview" src="{{ route('user.panels.preview') }}" style="width:100%;height:520px;border:1px solid #e2e7f0;border-radius:14px;background:#fff"></iframe>
    </div>
</div>

<h2>My panels</h2>
<div class="grid">
    @forelse($panels as $panel)
        <div class="card">
            <span class="tag">{{ $panel->available() ? 'Active' : ($panel->status === 'active' ? 'Expired' : ucfirst($panel->status)) }}</span>
            <h3>{{ $panel->name }}</h3>
            <p>{{ $panel->subdomain ?: url('/panels/' . $panel->slug) }}</p>
            <p>Paid until {{ $panel->expires_at->format('d M Y') }}</p>
            <a class="button" href="{{ route('user.panels.show', $panel->id) }}">Manage panel</a>
        </div>
    @empty
        <p>No panels yet.</p>
    @endforelse
</div>

<script>
    (function () {
        const base = @json($base), pv = document.getElementById('pv'), url = @json(route('user.panels.preview'));
        const f = id => document.getElementById(id);
        let t;
        function refresh() {
            const q = new URLSearchParams({name: f('name')?.value || '', tagline: f('tagline')?.value || '', color: f('color')?.value || ''});
            pv.src = url + '?' + q.toString();
            if (base && f('slug')) f('addr').textContent = 'Your address: ' + (f('slug').value || 'your-name') + '.' + base;
        }
        ['name', 'tagline', 'color', 'slug'].forEach(id => f(id)?.addEventListener('input', () => { clearTimeout(t); t = setTimeout(refresh, 400); }));
        f('name')?.addEventListener('input', () => {
            const s = f('slug'); if (s && !s.dataset.touched) s.value = f('name').value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').replace(/^[^a-z]+/, '').slice(0, 40);
        });
        f('slug')?.addEventListener('input', () => { f('slug').dataset.touched = '1'; });
    })();
</script>
@endsection
