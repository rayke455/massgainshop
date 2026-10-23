@php
    $controlBase = $controlBase ?? '';
    $screenUrl = fn($screen) => ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => $screen]) : route('user.panels.'.$screen, $panel->id);
    $groups = [
        'Setup' => [
            ['General settings', 'settings', $controlBase.'#general', true],
            ['Provider', 'provider', $controlBase.'#provider', true],
            ['Payments & earnings', 'wallet', $controlBase.'#payments', true],
            ['Landing page designer', 'design', $screenUrl('landing'), $panel->allows('branding')],
            ['Service settings', 'list', $controlBase.'#services', $panel->allows('markup')],
            ['Customer support', 'support', $screenUrl('support'), $panel->allows('accounts')],
        ],
        'Create' => [
            ['Pages, FAQ & announcements', 'list', $screenUrl('content'), $panel->allows('branding')],
            ['Balance promotions', 'promo', $screenUrl('promotions'), $panel->allows('accounts')],
        ],
        'Other' => [
            ['Customers', 'users', $controlBase.'#customers', $panel->allows('accounts')],
            ['Storefront orders', 'cart', $controlBase.'#orders', true],
            ['Statistics', 'chart', $controlBase.'#statistics', true],
            ['Domains & subscription', 'globe', $controlBase.'#domains', true],
            ['Platform support', 'chat', route('user.ticket.list'), true],
        ],
    ];
@endphp
<a id="control-back" href="{{ $controlBase }}#general" class="control-back" hidden>← Control panel</a>
<nav id="tenant-control" aria-label="Tenant control panel">
@foreach($groups as $heading => $items)
    <div class="control-group">
        <h2 class="control-group-label">{{ $heading }}</h2>
        @foreach($items as [$label, $icon, $href, $allowed])
        @if($allowed)
        <a @if(str_starts_with($href, '#')) data-control-link @endif href="{{ $href }}" @if(request()->fullUrl() === $href) aria-current="page" @endif>
            <span class="control-icon" aria-hidden="true">@include('rentals.control-icon', ['name' => $icon])</span><span>{{ $label }}</span>
        </a>
        @endif
        @endforeach
    </div>
@endforeach
<p class="muted control-note">Available settings depend on the features enabled by your platform administrator.</p>
</nav>
