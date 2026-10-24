@extends('rentals.layout')
@section('title','Order without account')
@section('brand')<a class="checkout-brand" href="{{ url('/') }}"><img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}" alt="{{ basicControl()->site_title }}"></a>@endsection
@section('nav')<a href="{{ url('/') }}">Home</a>@endsection
@push('styles')
<style>
body{background:radial-gradient(ellipse at 10% 0%,#e8edff,transparent 55%),#f6f8fc;color:#15213b}header{max-width:1180px;border-bottom:1px solid #e1e7f2;padding-top:20px;padding-bottom:20px}header>a{font-size:14px;font-weight:650;text-decoration:none}.checkout-brand{display:flex;align-items:center;max-width:220px}.checkout-brand img{width:auto;max-width:100%;max-height:38px;object-fit:contain}main{max-width:1180px;padding-top:42px}.checkout-intro{max-width:680px;margin-bottom:30px}.checkout-intro h1{font-size:clamp(30px,4vw,44px);letter-spacing:-1.5px;margin:14px 0}.checkout-intro p{font-size:17px;margin:0;color:#637087}.checkout-eyebrow{font-size:12px;font-weight:750;letter-spacing:1px;text-transform:uppercase;color:#3154cb}.grid{gap:26px;align-items:start;grid-template-columns:minmax(0,1fr) minmax(0,1fr)}.card{border-radius:24px;padding:28px;box-shadow:0 12px 36px -25px #64748b;margin:0}.card h2{font-size:21px;letter-spacing:-.5px;margin:0 0 8px}.step-description{margin:0 0 22px;font-size:14px;color:#637087}label{font-size:14px;font-weight:650;margin-top:20px}input:not([type=checkbox]),select{min-height:48px;border-radius:12px;border-color:#dce3ef;background:#fafbfe}input:focus,select:focus{outline:3px solid #dce5ff;border-color:#496ae3;background:white}.notice{border:1px solid #dbe6ff;background:#f1f5ff;font-size:14px;border-radius:16px}#serviceDetails{line-height:1.8}#payButton{width:100%;min-height:52px;background:#2454dc;font-weight:700;border-radius:13px;box-shadow:0 8px 18px -12px #2454dc}#payButton:disabled{opacity:.55;cursor:not-allowed}.checkout-total{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:10px;font-size:15px}.checkout-total strong{font-size:28px;letter-spacing:-1px;color:#173dba}.checkout-terms{display:flex;gap:10px;align-items:flex-start;line-height:1.6}.checkout-terms input{flex:0 0 18px;width:18px;height:18px;margin-top:3px;accent-color:#2454dc}.checkout-footnote{margin-top:16px;line-height:1.7}.checkout-account{border-top:1px solid #e7ecf4;padding-top:20px;font-size:14px}.checkout-account a{font-weight:650}a{text-underline-offset:3px}.error{border-color:#f3cccc;background:#fff0ef}
@media(max-width:700px){.grid{grid-template-columns:minmax(0,1fr)}main{padding-top:28px}.card{padding:22px;border-radius:20px}.checkout-intro{margin-bottom:24px}.checkout-brand{max-width:190px}.checkout-intro p{font-size:15px}header{flex-wrap:nowrap}}
@media(max-width:360px){.card{padding:18px}.checkout-brand{max-width:155px}.checkout-total strong{font-size:24px}}
</style>
@endpush
@section('content')
@php
    $network = (string) request('network', '');
    $categoryId = (int) request('category', 0);
    $selectedId = (int) old('service_id', request('service', 0));
    $platformGroups = $services->groupBy(fn($s) => optional(optional($s->category)->socialMedia)->name ?: 'Other');
    $networkServices = $platformGroups->get($network, collect());
@endphp
<nav class="guest-sidebar" aria-label="Guest navigation">
<a class="active" href="{{ route('guest.order') }}"><span aria-hidden="true">＋</span> New order</a>
<a href="{{ route('login') }}">Sign in</a><a href="{{ route('register') }}">Create account</a>
<a href="{{ url('/contact') }}">Support</a><a href="{{ url('/') }}">Back to shop</a>
</nav>
@if(!$selectedId)
<section class="network-browser">
<div class="browser-top"><input type="search" id="networkSearch" placeholder="Search {{ $network ? 'categories' : 'platforms' }}" aria-label="Search selection"><span>Guest checkout · No account needed</span></div>
@if($network)<a class="browse-back" href="{{ route('guest.order') }}">← All platforms</a>@endif
@if($categoryId)<a class="browse-back" href="{{ route('guest.order', ['network' => $network]) }}">← Categories</a>@endif
<h1>{{ $categoryId ? 'Select service' : ($network ?: 'Select network') }}</h1>
<div class="network-rows">
@if(!$network)
@foreach($platformGroups as $title => $items)
<a class="network-row" href="{{ route('guest.order', ['network' => $title]) }}"><span class="brand-icon" data-network="{{ strtolower($title) }}">@include('rentals.order-platform-icon', ['title' => $title])</span><strong>{{ $title }}</strong><span class="row-chevron">›</span></a>
@endforeach
@elseif(!$categoryId)
@foreach($networkServices->groupBy('category_id') as $id => $items)
<a class="network-row" href="{{ route('guest.order', ['network' => $network, 'category' => $id]) }}"><span class="brand-icon" data-network="{{ strtolower($network) }}">@include('rentals.order-platform-icon', ['title' => $network])</span><strong>{{ $items->first()->category->category_title }}</strong><span class="row-chevron">›</span></a>
@endforeach
@else
@foreach($networkServices->where('category_id', $categoryId) as $item)
<a class="network-row" href="{{ route('guest.order', ['network' => $network, 'category' => $categoryId, 'service' => $item->id]) }}"><span class="brand-icon" data-network="{{ strtolower($network) }}">@include('rentals.order-platform-icon', ['title' => $network])</span><strong>{{ \App\Services\SmartImport::cleanName($item->service_title) }}<small>KES {{ number_format((float)$item->price*$rate, 4) }} / 1,000 · Min {{ number_format($item->min_amount) }}</small></strong><span class="row-chevron">›</span></a>
@endforeach
@endif
</div><p id="noMatches" class="muted" hidden>No matching results. Try another search.</p>
@if($platformGroups->isEmpty() || ($network && $networkServices->isEmpty()))<p>No services are available in this selection.</p>@endif
</section>
<script>document.getElementById('networkSearch').addEventListener('input',function(){let found=0;document.querySelectorAll('.network-row').forEach(row=>{row.hidden=!row.textContent.toLowerCase().includes(this.value.trim().toLowerCase());if(!row.hidden)found++;});document.getElementById('noMatches').hidden=found>0;});</script>
@else
<a class="browse-back" href="{{ route('guest.order', ['network' => $network, 'category' => $categoryId ?: null]) }}">← Choose another service</a>
<div class="checkout-intro"><span class="checkout-eyebrow">Quick checkout · No signup needed</span><h1>Order without an account.</h1>
<p>A few details. One secure payment. Choose your service below and follow its progress with your private tracking link.</p></div>
<div class="grid">
<section class="card"><h2>1. Choose your service</h2>
<p class="step-description">Find the right service for your platform.</p>
<label for="platform">Platform</label><select id="platform"><option value="">All platforms</option>@foreach($services->groupBy(fn($s)=>optional(optional($s->category)->socialMedia)->name ?: 'Other') as $platform=>$items)<option>{{ $platform }}</option>@endforeach</select>
<label for="serviceSearch">Search services</label><input type="search" id="serviceSearch" placeholder="Followers, likes, views…">
<label for="service">Service</label><select id="service" name="service_id" form="guestOrder" required>
@foreach($services as $s)<option value="{{ $s->id }}" data-platform="{{ optional(optional($s->category)->socialMedia)->name ?: 'Other' }}" data-price="{{ (float)$s->price*$rate }}" data-min="{{ $s->min_amount }}" data-max="{{ $s->max_amount }}" @selected((string)old('service_id',request('service'))===(string)$s->id)>{{ \App\Services\SmartImport::cleanName($s->service_title) }}</option>@endforeach
</select>
<p id="serviceDetails" class="notice" aria-live="polite"></p>
<p class="muted">Checkout currently accepts Kenya M-Pesa in KES. Payment is collected by {{ basicControl()->site_title }}. Delivery time depends on the selected service.</p>
<p class="checkout-account">Want a wallet and order history?<br><a href="{{ route('login') }}">Sign in</a> or <a href="{{ route('register') }}">create an account</a>.</p>
</section>
<section class="card"><h2>2. Your order & payment</h2>
<p class="step-description">We only need your public link—not your password.</p>
@if(!$ready || $rate<=0)<div class="notice error">Guest payments are temporarily unavailable. Please try again later.</div>@endif
<form id="guestOrder" method="post" action="{{ route('guest.store') }}">@csrf
<input type="hidden" name="checkout_key" value="{{ $checkoutKey }}"><input type="hidden" id="expectedTotal" name="expected_total">
<label for="link">Post or profile link</label><input id="link" name="link" type="url" placeholder="https://…" value="{{ old('link') }}" maxlength="2048" required>
<label for="quantity">Quantity</label><input id="quantity" name="quantity" type="number" value="{{ old('quantity') }}" step="1" required>
<label for="email">Contact email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
<label for="phone">Kenya M-Pesa number</label><input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="0712 345 678" inputmode="tel" maxlength="20" required>
<div class="notice"><div class="checkout-total"><span>Order total</span><strong id="orderTotal" aria-live="polite">—</strong></div><small>Rounded up to whole KES. Confirm the exact amount on your M-Pesa prompt before entering your PIN.</small></div>
<div id="minimumSummary" class="notice" hidden><strong>Minimum payment: KES {{ number_format($limits['min']) }}</strong><p>You pay <strong id="paymentTotal"></strong>. After this order, <strong id="walletRemainder"></strong> stays in your account wallet for future orders.</p><small>Your account is created after payment is confirmed. Use the password-reset page with your contact email to set your password. If that email already has an account, <a href="{{ route('login') }}">sign in first</a>.</small></div>
<label id="accountConsentWrap" class="checkout-terms" hidden><input type="checkbox" name="account_consent" value="1" id="accountConsent"><span>I agree to an account being created with my contact email and the unused payment being saved in its wallet.</span></label>
<label class="checkout-terms" style="font-weight:400"><input name="terms" type="checkbox" value="1" required><span>I have checked my link and quantity and agree to the <a href="{{ url('/terms-and-conditions') }}" target="_blank" rel="noopener">terms</a> and <a href="{{ url('/refund-policy') }}" target="_blank" rel="noopener">refund policy</a>.</span></label>
<button id="payButton" @disabled(!$ready || $rate<=0 || $services->isEmpty())>Pay with M-Pesa</button>
<small class="checkout-footnote">Save your private tracking link after checkout. Never share your M-Pesa PIN with us.</small>
</form></section></div>
<script>
(()=>{
const service=document.getElementById('service'),quantity=document.getElementById('quantity'),search=document.getElementById('serviceSearch'),platform=document.getElementById('platform'),options=[...service.options];
function update(){const o=service.selectedOptions[0];if(!o){document.getElementById('serviceDetails').textContent='No matching services.';document.getElementById('orderTotal').textContent='—';return;}quantity.min=o.dataset.min;quantity.max=o.dataset.max;if(!quantity.value)quantity.value=o.dataset.min;
document.getElementById('serviceDetails').textContent=`${o.text} — KES ${Number(o.dataset.price).toLocaleString(undefined,{maximumFractionDigits:4})} per 1,000 · Minimum ${o.dataset.min} · Maximum ${o.dataset.max}`;
const total=Math.ceil(Number(o.dataset.price)*Number(quantity.value)/1000),minimum=@json($limits['min']),pay=Math.max(total,minimum),needsAccount=total<minimum;
document.getElementById('expectedTotal').value=pay;document.getElementById('orderTotal').textContent='KES '+total.toLocaleString();
document.getElementById('minimumSummary').hidden=!needsAccount;document.getElementById('accountConsentWrap').hidden=!needsAccount;document.getElementById('accountConsent').required=needsAccount;
document.getElementById('paymentTotal').textContent='KES '+pay.toLocaleString();document.getElementById('walletRemainder').textContent='KES '+(pay-total).toLocaleString();
document.getElementById('payButton').textContent='Pay KES '+pay.toLocaleString()+' with M-Pesa';}
function filter(){const selected=service.value;const keep=options.filter(o=>(!platform.value||o.dataset.platform===platform.value)&&o.text.toLowerCase().includes(search.value.toLowerCase()));service.replaceChildren(...keep);if(keep.some(o=>o.value===selected))service.value=selected;update();document.getElementById('payButton').disabled=!keep.length||!@json($ready && $rate>0);}
search.addEventListener('input',filter);platform.addEventListener('change',filter);service.addEventListener('change',update);quantity.addEventListener('input',update);platform.value=service.selectedOptions[0]?.dataset.platform||'';filter();
document.getElementById('guestOrder').addEventListener('submit',()=>{document.getElementById('payButton').disabled=true;document.getElementById('payButton').textContent='Starting payment…';});
})();
</script>
@endif
@endsection
@push('styles')
<style>
body{background:#171f26;color:#d5dce2;font-family:Inter,Arial,sans-serif}header{max-width:none;margin:0 32px;border-color:#29323a;padding:20px 0}header a{color:#c5cdd5}header strong{width:210px}main{max-width:none;margin-left:230px;padding:26px 38px 70px}.guest-sidebar{position:absolute;left:24px;top:110px;width:190px;display:grid;gap:12px}.guest-sidebar a{padding:12px 18px;border-radius:28px;color:#c4ccd4;text-decoration:none;font-weight:600;font-size:15px}.guest-sidebar a.active{background:#252f39;color:#ff902e}.guest-sidebar a:hover{background:#252f39}.browser-top{display:flex;gap:20px;align-items:center;margin:4px 0 36px}.browser-top input[type=search]{max-width:380px;border:0;border-radius:28px;background:#242e38;color:#e0e5eb;padding:12px 20px}.browser-top>span{background:linear-gradient(110deg,#8057f4,#347ff3);border-radius:28px;padding:10px 20px;font-size:13px;color:#fff}.network-browser h1{font-size:22px;font-weight:600;margin:0 0 22px;letter-spacing:0}.network-row{display:flex;gap:20px;align-items:center;min-height:80px;padding:18px 0;border-top:1px solid #283139;text-decoration:none;color:#d5dce2}.network-row:hover{background:#202a33}.network-row strong{font-size:16px;font-weight:600;flex:1;min-width:0}.network-row small{font-size:12px;font-weight:400;margin-top:7px;color:#93a0ad}.row-chevron{border:1px solid #2c3741;border-radius:24px;font-size:24px;line-height:28px;width:50px;text-align:center;margin-right:8px}.brand-icon{width:46px;height:46px;border-radius:50%;background:#242f39;display:grid;place-items:center;flex-shrink:0}.brand-icon svg{height:25px;width:25px}.brand-icon[data-network*=facebook]{color:#39a4ff}.brand-icon[data-network*=instagram]{color:#fa4b95}.brand-icon[data-network*=youtube]{color:#ff3030}.brand-icon[data-network*=telegram]{color:#39b7f1}.brand-icon[data-network*=whatsapp]{color:#20d86e}.brand-icon[data-network*=linkedin]{color:#49a9e8}.brand-icon[data-network*=tiktok]{color:#fff;filter:drop-shadow(1px 1px 0 #ff2850) drop-shadow(-1px -1px 0 #25f4ee)}.browse-back{display:inline-block;color:#9aabbb;margin-bottom:22px;text-decoration:none;margin-right:18px}.card{background:#1d2730;border-color:#303c48;box-shadow:none;border-radius:16px}.checkout-intro h1{font-size:30px;color:#e1e7ed}.checkout-intro p,.step-description,.muted{color:#9aa8b5}.checkout-eyebrow{color:#8daeff}input:not([type=checkbox]),select{background:#151f28;color:#e1e7ed;border-color:#384653}input:focus,select:focus{background:#1b2936}.notice{background:#223247;border-color:#344d69;color:#d8e4f1}.notice small{color:#9cadbf}.checkout-total strong{color:#8cb8ff}.checkout-account{border-color:#34414d}.checkout-account a,a{color:#78adff}.checkout-terms{color:#c0cbd6}.error{background:#42252a;color:#ffd5d5;border-color:#7b424c}#payButton{background:#267def}.checkout-footnote{color:#9baab9}
@media(max-width:800px){header{margin:0 18px}main{margin-left:0;padding:22px 18px 90px}.guest-sidebar{position:static;display:flex;overflow:auto;width:auto;gap:6px;margin-bottom:26px}.guest-sidebar a{white-space:nowrap;padding:9px 14px;font-size:13px}.browser-top{flex-wrap:wrap;margin-bottom:24px;gap:12px}.browser-top input[type=search]{max-width:none}.browser-top>span{font-size:12px}.network-row{gap:14px;min-height:76px}.network-row strong{font-size:15px}.grid{grid-template-columns:1fr}.brand-icon{width:42px;height:42px}.row-chevron{width:34px}.checkout-intro h1{font-size:27px}}
</style>
<style>.checkout-brand{background:#f6f8fc;border-radius:10px;padding:8px 12px}.brand-icon svg{width:28px;height:28px;border-radius:7px;padding:4px;color:white}.brand-icon[data-network*=facebook] svg{background:#168bfa}.brand-icon[data-network*=instagram] svg{background:radial-gradient(circle at 25% 110%,#ffe06b 0%,#ff523e 38%,#c521a1 68%,#6355da 100%)}.brand-icon[data-network*=youtube] svg{background:#ff170f}.brand-icon[data-network*=whatsapp] svg{background:#0acb63}.brand-icon[data-network*=telegram] svg{background:#20a8df}.brand-icon[data-network*=linkedin] svg{background:#087fba}.brand-icon[data-network*=tiktok]{filter:none}.brand-icon[data-network*=tiktok] svg{background:#090b0d;filter:drop-shadow(1px 0 0 #ff2850) drop-shadow(-1px 0 0 #25f4ee)}</style>
@endpush
