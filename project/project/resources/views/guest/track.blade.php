@extends('rentals.layout')
@section('title','Track your guest order')
@section('brand',basicControl()->site_title)
@section('nav')<a href="{{ url('/') }}">Home</a>@endsection
@section('content')
<h1>Your guest order</h1><p class="muted">Bookmark this private page. Anyone with its link can view your order status.</p>
<div class="card"><h2>{{ $guest->service ? \App\Services\SmartImport::cleanName($guest->service->service_title) : 'Service order' }}</h2>
<p>Reference: G{{ $guest->id }} · Quantity: {{ number_format($guest->quantity) }} · Total: <strong>KES {{ number_format($guest->amount_kes) }}</strong></p>
@if($guest->auto_account)
<div class="notice"><strong>Account & wallet</strong><p>Order: KES {{ number_format($guest->order_amount_kes) }} · Remaining wallet credit: {{ number_format($guest->wallet_credit, 4) }} {{ basicControl()->base_currency }}.</p>
@if($guest->paid_at && $guest->user_id)<p>Your unused payment is saved in your account wallet. <a href="{{ url('/password/reset') }}">Set or reset your password</a> using the email entered at checkout, then <a href="{{ route('login') }}">sign in</a>.</p>
@elseif($guest->status === 'account_review')<p>Your payment is confirmed, but the account needs verification. Contact support with your receipt; do not pay again.</p>
@else<p>Your account and wallet credit will be created once payment is confirmed.</p>@endif</div>
@endif
<p id="guestState" class="notice" role="status">{{ str_replace('_',' ',ucfirst($guest->status)) }}{{ $guest->order ? ' · '.$guest->order->status : '' }}</p>
<p id="guestHelp">Check your M-Pesa prompt if payment is pending. If money was deducted but the status is unclear, do not pay again. Contact support with reference G{{ $guest->id }}.</p>
<p><a href="{{ url('/contact') }}">Contact support</a> · <a href="{{ route('guest.order') }}">Browse services</a></p>
<small>{{ $guest->auto_account ? 'Any eligible order refund will follow your account wallet policy.' : 'Cancelled or partially delivered orders require support to arrange any refund to your original payment method; no account wallet is created.' }}</small></div>
<script>
(()=>{let timer,attempt=0;const labels={starting:'Starting payment',awaiting_payment:'Waiting for M-Pesa payment',payment_failed:'Payment was not completed',payment_review:'Payment needs review — do not pay again',review:'Payment received — your order needs review',submitted:'Payment received — order submitted'};
const show=(s,o)=>document.getElementById('guestState').textContent=(labels[s]||s)+(o?' · '+o:'');show(@json($guest->status),@json(optional($guest->order)->status));
async function poll(){try{const r=await fetch(@json(route('guest.status',$guest->token)),{headers:{Accept:'application/json'}});if(!r.ok)return;const d=await r.json();show(d.status,d.order_status);if(['payment_failed','payment_review'].includes(d.status)||['completed','canceled','refunded','partial'].includes(d.order_status))return;}catch(e){}if(++attempt<60)timer=setTimeout(poll,10000);}
timer=setTimeout(poll,1500);window.addEventListener('pagehide',()=>clearTimeout(timer));})();
</script>
@endsection
