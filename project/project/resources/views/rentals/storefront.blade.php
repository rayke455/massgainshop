@php
    $brand = $panel->allows('branding') ? $panel->name : 'Mass Panel Shop';
    $logo = \App\Services\PanelRentals::logoUrl($panel);
    $color = $panel->color ?: '#2563eb';
    $money = fn($v) => number_format((float) $v, (float) $v < 1 ? 4 : 2) . ' ' . $cur->code;
    $all = $groups->flatten(1);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $panel->workspace()['seo_title'] ?: $brand }}</title>
    <meta name="description" content="{{ $panel->workspace()['seo_description'] ?: ($panel->tagline ?: __('Followers, likes and views delivered fast.')) }}">
    @if($preview)<meta name="robots" content="noindex">@endif
    @if($logo)<link rel="icon" href="{{ $logo }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap">
    <style>
        :root { --brand: {{ $color }}; --ink: #0f172a; --muted: #64748b; --line: #e5e9f2; --bg: #f6f8fc; }
        * { box-sizing: border-box; }
        body { margin: 0; font: 15px/1.6 'Plus Jakarta Sans', system-ui, sans-serif; color: var(--ink); background: var(--bg); }
        a { color: var(--brand); }
        .wrap { max-width: 1120px; margin: 0 auto; padding: 0 16px; }
        .ribbon { background: #111827; color: #fff; text-align: center; font-size: 13px; font-weight: 700; padding: 8px; letter-spacing: .04em; }
        header { background: #fff; border-bottom: 1px solid var(--line); position: sticky; top: 0; z-index: 5; }
        header .wrap { display: flex; align-items: center; justify-content: space-between; height: 68px; gap: 12px; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 19px; color: var(--ink); text-decoration: none; min-width: 0; }
        .brand img { height: 40px; width: auto; max-width: 180px; object-fit: contain; }
        .brand .mark { width: 36px; height: 36px; border-radius: 10px; background: var(--brand); color: #fff; display: grid; place-items: center; font-weight: 800; flex: none; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: 0; border-radius: 12px; padding: 11px 18px; font: inherit; font-weight: 700;
            background: var(--brand); color: #fff; text-decoration: none; cursor: pointer; }
        .btn:disabled { opacity: .55; cursor: not-allowed; }
        .btn-ghost { background: #fff; color: var(--ink); border: 1px solid var(--line); }
        .hero { background: linear-gradient(135deg, color-mix(in srgb, var(--brand) 14%, #fff), #fff 70%); padding: 48px 0 36px; }
        .hero h1 { font-size: clamp(28px, 4vw, 44px); line-height: 1.12; margin: 0 0 10px; letter-spacing: -.02em; }
        .hero p { color: var(--muted); font-size: 17px; max-width: 640px; margin: 0 0 18px; }
        .ticks { display: flex; flex-wrap: wrap; gap: 8px 18px; list-style: none; padding: 0; margin: 0; font-weight: 600; font-size: 14px; }
        .ticks li::before { content: "✓ "; color: var(--brand); }
        .grid { display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 24px; padding: 28px 0 56px; align-items: start; }
        .card { background: #fff; border: 1px solid var(--line); border-radius: 18px; padding: 20px; }
        .tabs { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
        .tab { border: 1px solid var(--line); background: #fff; border-radius: 999px; padding: 7px 14px; font: inherit; font-weight: 700; font-size: 14px; cursor: pointer; }
        .tab.on { background: var(--brand); border-color: var(--brand); color: #fff; }
        .search { width: 100%; padding: 11px 14px; border: 1px solid var(--line); border-radius: 12px; font: inherit; margin-bottom: 12px; }
        .svc { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 14px; margin-bottom: 8px; background: #fff; cursor: pointer; }
        .svc:hover, .svc.on { border-color: var(--brand); box-shadow: 0 0 0 3px color-mix(in srgb, var(--brand) 18%, transparent); }
        .svc .nm { flex: 1; min-width: 0; }
        .svc .nm strong { display: block; font-size: 14.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .svc .nm span { font-size: 12.5px; color: var(--muted); }
        .svc .pr { font-weight: 800; white-space: nowrap; }
        .svc .pr small { color: var(--muted); font-weight: 600; }
        .order { position: sticky; top: 88px; }
        label { display: block; font-weight: 700; font-size: 13.5px; margin: 12px 0 4px; }
        input, select { width: 100%; padding: 11px 13px; border: 1px solid var(--line); border-radius: 12px; font: inherit; background: #fff; }
        input:focus, select:focus { outline: 2px solid color-mix(in srgb, var(--brand) 40%, transparent); border-color: var(--brand); }
        .total { display: flex; justify-content: space-between; align-items: baseline; margin: 16px 0 4px; padding-top: 14px; border-top: 1px dashed var(--line); }
        .total strong { font-size: 24px; }
        .note { font-size: 13px; color: var(--muted); }
        .alert { padding: 12px 14px; border-radius: 12px; background: #fef2f2; color: #b91c1c; margin-bottom: 12px; font-size: 14px; }
        .wa { position: fixed; right: 18px; bottom: 18px; width: 54px; height: 54px; border-radius: 50%; background: #25D366; display: grid; place-items: center; box-shadow: 0 10px 24px -8px rgba(37,211,102,.8); }
        footer { text-align: center; color: var(--muted); font-size: 13px; padding: 24px 0 40px; }
        @media (max-width: 900px) { .grid { grid-template-columns: minmax(0, 1fr); } .order { position: static; } }
        header .brand { flex: 1 1 0; overflow: hidden; }
        header .brand img { max-width: 100%; height: auto; max-height: 40px; }
        header .brand span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        header .wrap > .btn { flex: 0 0 auto; }
        @media(max-width:480px){header .wrap{height:auto;min-height:68px;flex-wrap:wrap;padding-top:10px;padding-bottom:10px}.svc{flex-wrap:wrap}.svc .pr{white-space:normal}.total{flex-wrap:wrap}.card{min-width:0}.hero h1{overflow-wrap:anywhere}}
    </style>
</head>
<body>
@if(!$preview && request()->attributes->get('rental_host'))
<nav class="ribbon" aria-label="Panel policies">
    @if($panel->workspace()['terms'])<a href="/terms" style="color:inherit;margin-right:16px">Terms of service</a>@endif
    @if($panel->workspace()['privacy'])<a href="/privacy" style="color:inherit">Privacy policy</a>@endif
</nav>
@endif
@if($preview)<div class="ribbon">PREVIEW · this is how your panel will look · ordering is disabled</div>@endif
<header>
    <div class="wrap">
        <a class="brand" href="{{ $base ?: '/' }}">
            @if($logo)<img src="{{ $logo }}" alt="{{ $brand }}">@else<span class="mark">{{ strtoupper(mb_substr($brand, 0, 1)) }}</span>{{ $brand }}@endif
        </a>
        @php $zkOwnHost = request()->attributes->get('rental_host'); /* account links only work on the panel's own domain */ @endphp
        <nav style="display:flex;gap:8px;align-items:center">
            @if($panel->allows('accounts'))
                @if(auth()->check() && $zkOwnHost)
                    <a class="btn" href="{{ url('/user/dashboard') }}">{{ __('Dashboard') }}</a>
                @else
                    <a class="btn btn-ghost" href="{{ $zkOwnHost ? url('/login') : '#' }}">{{ __('Log in') }}</a>
                    @if($panel->workspace()['registration'])<a class="btn" href="{{ $zkOwnHost ? url('/register') : '#' }}">{{ __('Create account') }}</a>@endif
                @endif
            @else
                <a class="btn" href="#order">{{ __('Order now') }}</a>
            @endif
        </nav>
    </div>
</header>

<section class="hero">
    <div class="wrap">
        <h1>{{ $panel->tagline ?: __('Grow your social media, fast.') }}</h1>
        <p>{{ __('Followers, likes, views and more. Paste your link, pay and watch it start in minutes. No password needed.') }}</p>
        <ul class="ticks">
            <li>{{ __('Instant start') }}</li><li>{{ __('No password needed') }}</li>
            <li>{{ $mpesa ? __('Pay with M-Pesa') : __('Secure ordering') }}</li><li>{{ __('Live order tracking') }}</li>
        </ul>
    </div>
</section>

<main class="wrap">
    @if(!$panel->allows('catalogue'))
        <div class="card" style="margin:28px 0">{{ __('The service catalogue is currently unavailable.') }}</div>
    @else
    <div class="grid">
        <section aria-label="{{ __('Services') }}">
            <div class="tabs" role="tablist">
                <button type="button" class="tab on" data-p="">{{ __('All') }}</button>
                @foreach($groups->keys() as $platform)<button type="button" class="tab" data-p="{{ $platform }}">{{ $platform }}</button>@endforeach
            </div>
            <input class="search" type="search" id="q" placeholder="{{ __('Search: followers, likes, views…') }}" aria-label="{{ __('Search services') }}">
            <div id="list">
                @forelse($all as $s)
                    <div class="svc" tabindex="0" role="button" data-id="{{ $s->id }}" data-p="{{ optional(optional($s->category)->socialMedia)->name ?: 'Other' }}"
                         data-price="{{ $s->panel_price }}" data-quantity-based="{{ \App\Services\PanelRentals::quantityBased($s) ? '1' : '0' }}" data-min="{{ $s->min_amount }}" data-max="{{ $s->max_amount }}"
                         data-name="{{ strtolower(\App\Services\SmartImport::cleanName($s->service_title)) }}">
                        <div class="nm">
                            <strong>{{ \App\Services\SmartImport::cleanName($s->service_title) }}</strong>
                            <span>{{ __('Min') }} {{ number_format($s->min_amount) }} · {{ __('Max') }} {{ number_format($s->max_amount) }}</span>
                        </div>
                        <div class="pr">{{ $money($s->panel_price) }} <small>/ {{ \App\Services\PanelRentals::quantityBased($s) ? '1k' : 'item' }}</small></div>
                    </div>
                @empty
                    <p>{{ __('No services are available right now.') }}</p>
                @endforelse
            </div>
        </section>

        @if($panel->allows('orders'))
        <aside class="order" id="order">
            <form class="card" method="post" action="{{ $preview ? '#' : $base . '/panel-order' }}" id="orderForm">
                @csrf
                <h2 style="margin:0 0 4px;font-size:20px">{{ __('Place your order') }}</h2>
                @if(isset($errors) && $errors->any())<div class="alert" role="alert">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
                <label for="service_id">{{ __('Service') }}</label>
                <select name="service_id" id="service_id" required>
                    <option value="">{{ __('Tap a service on the left or pick here') }}</option>
                    @foreach($all as $s)<option value="{{ $s->id }}" @selected(old('service_id') == $s->id)>{{ \Illuminate\Support\Str::limit(\App\Services\SmartImport::cleanName($s->service_title), 60) }}</option>@endforeach
                </select>
                <label for="link">{{ __('Link to your post or profile') }}</label>
                <input type="url" name="link" id="link" maxlength="2048" required placeholder="https://instagram.com/…" value="{{ old('link') }}">
                <label for="quantity">{{ __('Quantity') }} <span class="note" id="limits"></span></label>
                <input type="number" name="quantity" id="quantity" min="1" required value="{{ old('quantity', 1000) }}">
                <label for="name">{{ __('Your name') }}</label>
                <input name="name" id="name" maxlength="100" required autocomplete="name" value="{{ old('name') }}">
                <label for="email">{{ __('Email (for your receipt)') }}</label>
                <input type="email" name="email" id="email" maxlength="255" required autocomplete="email" value="{{ old('email') }}">
                @if($mpesa)
                    <label for="phone">{{ __('M-Pesa number') }}</label>
                    <input type="tel" name="phone" id="phone" maxlength="20" required placeholder="0712 345 678" inputmode="tel" autocomplete="tel" value="{{ old('phone') }}">
                @endif
                <div class="total"><span>{{ __('Total') }}</span><strong id="total">0.00 {{ $cur->code }}</strong></div>
                <button class="btn" style="width:100%;margin-top:10px" type="submit" @disabled($preview)>
                    {{ $preview ? __('Ordering is disabled in preview') : ($mpesa ? __('Pay with M-Pesa') : __('Send order request')) }}
                </button>
                <p class="note" style="margin:10px 0 0">{{ $mpesa ? __('You will get an M-Pesa prompt on your phone. Your order starts as soon as you pay.') : __('No payment is taken here. We will contact you to confirm and collect payment.') }}</p>
            </form>
        </aside>
        @endif
    </div>
    @endif
</main>

<footer>@include('rentals.content-links') © {{ date('Y') }} {{ $brand }}</footer>
@if($panel->whatsapp)
    <a class="wa" href="https://wa.me/{{ $panel->whatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp">
        <svg viewBox="0 0 32 32" width="28" height="28" aria-hidden="true"><path fill="#fff" d="M16.04 3C9.4 3 4 8.4 4 15.04c0 2.12.55 4.18 1.6 6L4 29l8.17-1.57a12 12 0 0 0 3.87.64C22.68 28.07 28 22.67 28 16.03 28 9.4 22.68 3 16.04 3zm5.44 15.5c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.47 1.07 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49 2.98 1.29 2.98.86 3.52.81.54-.05 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/></svg>
    </a>
@endif
<script>
    (function () {
        const cur = @json($cur->code);
        const list = [...document.querySelectorAll('.svc')];
        const sel = document.getElementById('service_id'), qty = document.getElementById('quantity'), total = document.getElementById('total'), limits = document.getElementById('limits');
        let platform = '';
        function filter() {
            const q = (document.getElementById('q').value || '').toLowerCase().trim();
            list.forEach(el => { el.hidden = (platform && el.dataset.p !== platform) || (q && !el.dataset.name.includes(q)); });
        }
        document.querySelectorAll('.tab').forEach(t => t.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(x => x.classList.toggle('on', x === t)); platform = t.dataset.p; filter();
        }));
        document.getElementById('q')?.addEventListener('input', filter);
        function current() { return list.find(el => el.dataset.id === sel?.value); }
        function update() {
            const s = current();
            list.forEach(el => el.classList.toggle('on', el === s));
            if (!s) { total.textContent = '0.00 ' + cur; limits.textContent = ''; return; }
            qty.min = s.dataset.min; qty.max = s.dataset.max;
            limits.textContent = '(' + Number(s.dataset.min).toLocaleString() + '–' + Number(s.dataset.max).toLocaleString() + ')';
            const t = s.dataset.quantityBased === '1'
                ? Number(s.dataset.price) * (Number(qty.value) || 0) / 1000
                : Number(s.dataset.price);
            total.textContent = t.toFixed(2) + ' ' + cur;
        }
        list.forEach(el => {
            const pick = () => { if (sel) { sel.value = el.dataset.id; update(); document.getElementById('order')?.scrollIntoView({behavior: 'smooth', block: 'start'}); } };
            el.addEventListener('click', pick); el.addEventListener('keydown', e => { if (e.key === 'Enter') pick(); });
        });
        sel?.addEventListener('change', update); qty?.addEventListener('input', update);
        update();
    })();
</script>
</body>
</html>
