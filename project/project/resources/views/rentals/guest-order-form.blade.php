@if(!$panel->allows('orders'))<p>Ordering is currently disabled for this panel.</p>
@elseif(!$panel->workspace()['fast_orders'])
<p>Guest ordering is disabled. @if($login)<a href="{{ $login }}">Sign in to order</a>@else Please contact panel support to access your account.@endif</p>
@else
@php
    $quantityBased = \App\Services\PanelRentals::quantityBased($selected);
    $initialQty = old('quantity', $quantityBased ? max($selected->min_amount, min(100,$selected->max_amount)) : 1);
@endphp
<form method="post" action="{{ $preview ? '#' : $base.'/panel-order' }}" id="orderForm">
@csrf<input type="hidden" name="service_id" value="{{ $selected->id }}">
<p class="eyebrow">GUEST ORDER · NO SIGN-IN REQUIRED</p>
@if(isset($errors) && $errors->any())<div class="guest-feedback" role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
@if($panel->payment_mode === 'mpesa' && !$mpesa)<div class="guest-feedback">M-Pesa payments are temporarily unavailable. Please contact panel support.</div>@endif
@if($quantityBased)
<label for="quantity">Enter quantity</label><div class="order-presets">@foreach([100,500,1000,2500,5000] as $amount)@if($amount >= $selected->min_amount && $amount <= $selected->max_amount)<button type="button" data-quantity="{{ $amount }}" aria-pressed="false">{{ number_format($amount) }}</button>@endif @endforeach<button type="button" id="custom-quantity" aria-pressed="false">Choose own</button></div>
<input id="quantity" name="quantity" type="number" step="1" min="{{ $selected->min_amount }}" max="{{ $selected->max_amount }}" value="{{ $initialQty }}" required><small>Min {{ number_format($selected->min_amount) }} · Max {{ number_format($selected->max_amount) }}</small>
@else<input id="quantity" name="quantity" type="hidden" value="1">@endif
<label for="link">Paste link</label><input id="link" type="url" name="link" maxlength="2048" required value="{{ old('link') }}" placeholder="https://">
<label for="name">Your name</label><input id="name" name="name" maxlength="100" required autocomplete="name" value="{{ old('name') }}">
<label for="email">Email for your receipt</label><input id="email" type="email" name="email" maxlength="255" required autocomplete="email" value="{{ old('email') }}">
@if($mpesa)<label for="phone">M-Pesa number</label><input id="phone" type="tel" name="phone" maxlength="20" required autocomplete="tel" value="{{ old('phone') }}" placeholder="0712 345 678">@endif
<div class="order-summary"><div><small>Amount</small><strong id="order-amount">{{ $initialQty }}</strong></div><div><small>Price</small><strong id="total">{{ $money($quantityBased ? $selected->panel_price * $initialQty / 1000 : $selected->panel_price) }}</strong></div></div>
<button class="primary" style="width:100%" @disabled($preview || ($panel->payment_mode === 'mpesa' && !$mpesa))>{{ $preview ? 'Ordering is disabled in preview' : ($mpesa ? 'Pay with M-Pesa' : 'Send order request') }}</button>
<p style="margin-top:12px">{{ $mpesa ? 'Confirm the M-Pesa prompt on your phone. Keep your private tracking link.' : 'No payment is taken here. Panel support will confirm your request and payment.' }}</p>
</form>
<script>
(() => {const qty=document.getElementById('quantity'), presets=[...document.querySelectorAll('[data-quantity]')],custom=document.getElementById('custom-quantity');
const update=()=>{const amount=Number(qty.value)||0;document.getElementById('order-amount').textContent=amount.toLocaleString();document.getElementById('total').textContent=(@json($quantityBased)?@json((float)$selected->panel_price)*amount/1000:@json((float)$selected->panel_price)).toFixed(2)+' '+@json($cur->code);presets.forEach(b=>b.setAttribute('aria-pressed',Number(b.dataset.quantity)===amount?'true':'false'));custom?.setAttribute('aria-pressed',presets.some(b=>Number(b.dataset.quantity)===amount)?'false':'true');};
presets.forEach(b=>b.addEventListener('click',()=>{qty.value=b.dataset.quantity;update();}));custom?.addEventListener('click',()=>{qty.focus();qty.select();});qty.addEventListener('input',update);update();})();
</script>
@endif
