@extends('rentals.layout')
@include('rentals.control-style')
@section('title','API access · '.$panel->name)
@section('brand',$panel->name)
@section('nav')<a href="{{ route('user.dashboard') }}">Back to dashboard</a>@endsection
@section('content')
<h1>API access</h1><section class="card"><h2>Read-only API key</h2>
<p>This key can read your balance, available services and your order status. It cannot place orders or withdraw funds. Keys expire after 90 days.</p>
@if(session('rental_api_secret'))<div class="notice"><strong>Copy this key now. It is only shown once.</strong><code>{{ session('rental_api_secret') }}</code></div>@endif
<p>{{ $token ? 'Key expires: '.$token->expires_at : 'No active API key.' }}</p>
<form method="post" action="{{ route('rental.api.rotate') }}">@csrf<button name="operation" value="rotate">{{ $token ? 'Replace key' : 'Create key' }}</button>@if($token)<button name="operation" value="revoke">Revoke key</button>@endif</form>
</section><section class="card"><h2>Usage</h2><p>POST <code>{{ url('/api/panel/v1') }}</code> with the header <code>Authorization: Bearer YOUR_KEY</code> and <code>Accept: application/json</code>.</p>
<p>JSON body: <code>{"action":"balance"}</code>, <code>{"action":"services"}</code>, or <code>{"action":"status","order":123}</code>.</p>
<p>Rates and balances use {{ basicControl()->base_currency }}. Limit: 60 requests per minute per IP. Keys only work on this panel's verified host.</p>
</section>@endsection
