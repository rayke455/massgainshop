@php($ownerControlUrl = ($ownerPreview ?? false) ? route('rental.preview.alias') : route('user.panels.show', $panel->id))
<nav class="tenant-tabs" aria-label="Tenant workspace">
@if($panel->allows('accounts'))<a href="{{ $ownerControlUrl }}#customers">Users</a>@endif
<a href="{{ $ownerControlUrl }}#orders">Orders</a>
@if($panel->allows('catalogue'))<a href="{{ $ownerControlUrl }}#services">Services</a>@endif
@if($panel->allows('accounts'))<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen'=>'support']) : route('user.panels.support', $panel->id) }}">Support</a>@endif
<a href="{{ $ownerControlUrl }}#statistics">Statistics</a>
<a href="{{ $ownerControlUrl }}#payments">Payments</a>
<a href="{{ $ownerControlUrl }}#general">Control panel</a>
</nav>
