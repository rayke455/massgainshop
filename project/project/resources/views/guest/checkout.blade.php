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
function filter(){const keep=options.filter(o=>(!platform.value||o.dataset.platform===platform.value)&&o.text.toLowerCase().includes(search.value.toLowerCase()));service.replaceChildren(...keep);update();document.getElementById('payButton').disabled=!keep.length||!@json($ready && $rate>0);}
search.addEventListener('input',filter);platform.addEventListener('change',filter);service.addEventListener('change',update);quantity.addEventListener('input',update);update();
document.getElementById('guestOrder').addEventListener('submit',()=>{document.getElementById('payButton').disabled=true;document.getElementById('payButton').textContent='Starting payment…';});
})();
</script>
@endsection
