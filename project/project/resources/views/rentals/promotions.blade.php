@extends('rentals.layout')
@include('rentals.owner-subpage')
@section('title', 'Balance promotions · '.$panel->name)
@section('brand', $panel->name)
@section('nav')<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id) }}">Back to control panel</a>@endsection
@section('content')
<h1>Balance promotions</h1>
<div class="notice">Each successful redemption transfers the amount from your platform wallet to the customer's wallet. Amounts are in {{ basicControl()->base_currency }} (the wallet accounting currency). One use per customer. Redemptions stop when your wallet has insufficient funds.</div>
<div class="grid"><section class="card"><h2>Create code</h2><form method="post" action="{{ route('user.panels.promotions.store', $panel->id) }}">@csrf
<label>Promo code<input name="code" required minlength="4" maxlength="40" value="{{ old('code') }}"></label>
<label>Wallet credit ({{ basicControl()->base_currency }})<input name="amount" type="number" step="0.0001" min="0.0001" max="1000000" value="{{ old('amount') }}" required></label>
<label>Maximum redemptions<input name="max_uses" type="number" min="1" max="100000" value="{{ old('max_uses', 1) }}" required></label>
<label>Expiry ({{ config('app.timezone') }}, optional)<input name="expires_at" type="datetime-local" value="{{ old('expires_at') }}"></label>
<button>Create balance promotion</button></form></section>
<section class="card"><h2>Your codes</h2>@forelse($promotions as $promo)<article class="notice"><strong>{{ $promo->code }}</strong><small>{{ $promo->amount }} {{ basicControl()->base_currency }} · {{ $promo->uses }}/{{ $promo->max_uses }} redeemed · {{ $promo->active ? 'Enabled' : 'Disabled' }}</small><small>Expires: {{ $promo->expires_at ?: 'Never' }}</small>
<form method="post" action="{{ route('user.panels.promotions.status', ['panelId'=>$panel->id, 'promotionId'=>$promo->id]) }}">@csrf<input type="hidden" name="active" value="{{ $promo->active ? 0 : 1 }}"><button>{{ $promo->active ? 'Disable' : 'Enable' }}</button></form></article>@empty<p>No promotions yet.</p>@endforelse{{ $promotions->links() }}</section></div>
@endsection
