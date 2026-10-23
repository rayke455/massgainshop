@php
    $brand = $panel->allows('branding') ? $panel->name : 'Mass Panel Shop';
    $all = $groups->flatten(1);
    $displayMoney = fn($value) => rtrim(rtrim(number_format((float) $value, 6, '.', ','), '0'), '.').' '.$cur->code;
    $displayUnit = fn($service) => \App\Services\PanelRentals::displayUnit($panel, $service);
    $home = request()->fullUrlWithoutQuery(['shop','network','category','service','q','account']);
    $browse = $home.(str_contains($home,'?') ? '&' : '?').'shop=1';
    $linkTo = fn($params) => $browse.'&'.http_build_query($params);
    $network = is_string(request('network')) ? request('network') : '';
    $category = (int) request('category', 0);
    $search = is_string(request('q')) ? mb_substr(trim(request('q')),0,100) : '';
    $selected = $all->firstWhere('id', (int) old('service_id', request('service',0)));
    if ($selected) { $network = optional(optional($selected->category)->socialMedia)->name ?: 'Other'; $category = $selected->category_id; }
    $networkServices = $groups->get($network, collect());
    $categories = $networkServices->groupBy('category_id');
    $listed = $search !== '' ? $all->filter(fn($s) => str_contains(mb_strtolower($s->service_title), mb_strtolower($search))) : $networkServices->where('category_id', $category);
    $title = $selected ? \App\Services\SmartImport::cleanName($selected->service_title) : ($search !== '' || $category ? 'Select service' : ($network ? 'Select category' : 'Select network'));
    $money = fn($v) => number_format((float)$v, 2).' '.$cur->code;
    $ownHost = request()->attributes->get('rental_host');
    $accountHost = $panel->custom_domain && $panel->domain_verified_at && $panel->allows('custom_domain') ? $panel->custom_domain : $panel->subdomain;
    $accountBase = $ownHost ? url('/') : ($accountHost ? 'https://'.$accountHost : null);
    $login = $preview ? $home.(str_contains($home,'?') ? '&' : '?').'account=login' : ($accountBase ? $accountBase.'/login' : null);
    $register = $preview ? $home.(str_contains($home,'?') ? '&' : '?').'account=register' : ($accountBase ? $accountBase.'/register' : null);
@endphp
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>{{ $title }} | {{ $brand }}</title>
@if($preview)<meta name="robots" content="noindex">@endif
@include('rentals.workspace-style') @include('rentals.order-shell-style')
</head><body>@include('rentals.workspace-icons')
@if($preview)<div class="preview-note">PREVIEW · ordering is disabled</div>@endif
<div class="order-shell">
<aside class="order-sidebar" aria-label="Order navigation"><a href="{{ $browse }}" aria-current="page"><svg class="icon"><use href="#icon-layers"/></svg>New order</a><a href="{{ $home }}"><svg class="icon"><use href="#icon-home"/></svg>Home</a>@if($panel->allows('accounts') && $login)<a href="{{ $login }}"><svg class="icon"><use href="#icon-user"/></svg>Sign in</a>@endif</aside>
<header class="workspace-header"><a class="brand-pill" href="{{ $home }}"><span class="brand-mark">{{ mb_substr($brand,0,1) }}</span><strong>{{ $brand }}</strong></a><nav class="account-actions" aria-label="Account">@if($panel->allows('accounts') && $login)<a href="{{ $login }}">Sign in</a>@if($panel->workspace()['registration'])<a href="{{ $register }}">Sign up</a>@endif @endif</nav></header>
<main>
@if($selected)<a class="back" href="{{ $linkTo(['network'=>$network,'category'=>$category]) }}">← Back</a>
@elseif($category)<a class="back" href="{{ $linkTo(['network'=>$network]) }}">← Back</a>
@elseif($network || $search)<a class="back" href="{{ $browse }}">← All platforms</a>
@else<a class="back" href="{{ $home }}">← Back to home</a>@endif
@unless($selected)
<form class="order-search" method="get" action="{{ request()->url() }}">
@foreach(request()->except(['shop','network','category','service','q','account']) as $key=>$value)@if(is_scalar($value))<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endif @endforeach
<input type="hidden" name="shop" value="1"><label class="sr-only" for="q">Search services</label><input id="q" name="q" type="search" maxlength="100" value="{{ $search }}" placeholder="Search service or name"><button class="primary">Search</button></form>
@endunless
<h1>{{ $title }}</h1>
@if(!$panel->allows('catalogue'))<p>The service catalogue is currently unavailable.</p>
@elseif($selected)
<div class="order-form-layout">
@include('rentals.guest-order-form')
<section class="order-details"><h2>Service details</h2><p>Service ID · {{ $selected->id }}</p><p>{{ $displayMoney($selected->panel_price * $displayUnit($selected)['factor']) }} {{ $displayUnit($selected)['label'] }}</p><hr style="border:0;border-top:1px solid var(--line);margin:20px 0"><div class="order-description">{{ strip_tags($selected->description ?? '') }}</div></section>
</div>
@else
<div class="catalogue-list" id="list">
@if($search !== '' || $category)
@forelse($listed as $service)<a href="{{ $linkTo(['service'=>$service->id]) }}"><span class="network-icon" data-network="{{ strtolower(optional(optional($service->category)->socialMedia)->name ?: 'Other') }}">@include('rentals.order-platform-icon',['title'=>optional(optional($service->category)->socialMedia)->name ?: 'Other'])</span><span class="row-copy">{{ \App\Services\SmartImport::cleanName($service->service_title) }}<small>Min {{ number_format($service->min_amount) }} · Max {{ number_format($service->max_amount) }}</small></span><span class="row-price">{{ $displayMoney($service->panel_price * $displayUnit($service)['factor']) }}<small style="display:block;color:var(--muted)">{{ $displayUnit($service)['label'] }}</small></span><span class="row-arrow" aria-hidden="true">›</span></a>@empty<p>No services found.</p>@endforelse
@elseif($network)
@forelse($categories as $id=>$items)<a href="{{ $linkTo(['network'=>$network,'category'=>$id]) }}"><span class="network-icon" data-network="{{ strtolower($network) }}">@include('rentals.order-platform-icon',['title'=>$network])</span><span class="row-copy">{{ $items->first()->category->category_title }}</span><span class="row-arrow" aria-hidden="true">›</span></a>@empty<p>No categories are available.</p>@endforelse
@else
@forelse($groups as $name=>$items)<a href="{{ $linkTo(['network'=>$name]) }}"><span class="network-icon" data-network="{{ strtolower($name) }}">@include('rentals.order-platform-icon',['title'=>$name])</span><span class="row-copy">{{ $name }}</span><span class="row-arrow" aria-hidden="true">›</span></a>@empty<p>No services are available.</p>@endforelse
@endif
</div>
@endif
</main>
<footer class="workspace-bottom"><nav aria-label="Guest navigation"><a href="{{ $home }}"><svg class="icon"><use href="#icon-home"/></svg>Home</a><a href="{{ $browse }}" aria-current="page"><svg class="icon"><use href="#icon-layers"/></svg>Create</a>@if($panel->allows('accounts') && $login)<a href="{{ $login }}"><svg class="icon"><use href="#icon-user"/></svg>Sign in</a>@else<a href="{{ $home }}"><svg class="icon"><use href="#icon-user"/></svg>About</a>@endif</nav></footer>
</div></body></html>
