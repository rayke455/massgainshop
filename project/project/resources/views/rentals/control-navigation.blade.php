<a id="control-back" href="#control" class="control-back" hidden>← Control panel</a>
<nav id="tenant-control" aria-label="Tenant control panel">
    <div class="control-heading"><span class="tag">TENANT WORKSPACE</span><h2>Control panel</h2><p class="muted">Everything you need to run your storefront, in one place.</p></div>
    <div class="control-group">
        <a data-control-link href="#general"><span class="control-icon purple" aria-hidden="true">@include('rentals.control-icon', ['name' => 'settings'])</span><span>General settings<small>Branding, logo, currency and live preview</small></span><span aria-hidden="true">›</span></a>
        <a data-control-link href="#provider"><span class="control-icon purple" aria-hidden="true">@include('rentals.control-icon', ['name' => 'provider'])</span><span>Provider<small>Powered by Mass Panel Shop</small></span><span aria-hidden="true">›</span></a>
        <a data-control-link href="#payments"><span class="control-icon green" aria-hidden="true">@include('rentals.control-icon', ['name' => 'wallet'])</span><span>Payments & earnings<small>Payout destination, withdrawals and history</small></span><span aria-hidden="true">›</span></a>
        <a data-control-link href="#statistics"><span class="control-icon green" aria-hidden="true">@include('rentals.control-icon', ['name' => 'chart'])</span><span>Statistics<small>Orders, revenue, profit and top services</small></span><span aria-hidden="true">›</span></a>
        @if($panel->allows('markup'))
        <a data-control-link href="#services"><span class="control-icon blue" aria-hidden="true">@include('rentals.control-icon', ['name' => 'list'])</span><span>Service settings<small>Retail prices, markup and visibility</small></span><span aria-hidden="true">›</span></a>
        @endif
    </div>
    <div class="control-group">
        @if($panel->allows('accounts'))
        <a data-control-link href="#customers"><span class="control-icon orange" aria-hidden="true">@include('rentals.control-icon', ['name' => 'users'])</span><span>Customers<small>{{ number_format($customerTotal) }} registered customers</small></span><span aria-hidden="true">›</span></a>
        @endif
        @if($panel->allows('branding'))
        <a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'content']) : route('user.panels.content', $panel->id) }}"><span class="control-icon purple" aria-hidden="true">@include('rentals.control-icon', ['name' => 'list'])</span><span>Pages, FAQ & announcements<small>Publish content and customer updates</small></span><span aria-hidden="true">›</span></a>
        @endif
        <a data-control-link href="#orders"><span class="control-icon orange" aria-hidden="true">@include('rentals.control-icon', ['name' => 'list'])</span><span>Storefront orders<small>Customer requests, payments and tracking</small></span><span aria-hidden="true">›</span></a>
        @if($panel->allows('accounts'))
        <a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'support']) : route('user.panels.support', $panel->id) }}"><span class="control-icon orange" aria-hidden="true">@include('rentals.control-icon', ['name' => 'chat'])</span><span>Customer support<small>Read, reply and close your customers' conversations</small></span><span aria-hidden="true">›</span></a>
        <a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'promotions']) : route('user.panels.promotions', $panel->id) }}"><span class="control-icon green" aria-hidden="true">@include('rentals.control-icon', ['name' => 'promo'])</span><span>Balance promotions<small>Fund customer rewards from your wallet</small></span><span aria-hidden="true">›</span></a>
        @endif
        <a href="{{ route('user.ticket.list') }}"><span class="control-icon orange" aria-hidden="true">@include('rentals.control-icon', ['name' => 'chat'])</span><span>Platform support<small>Contact Mass Panel Shop</small></span><span aria-hidden="true">›</span></a>
        <a data-control-link href="#domains"><span class="control-icon blue" aria-hidden="true">@include('rentals.control-icon', ['name' => 'globe'])</span><span>Domains & subscription<small>Subdomain, DNS verification and renewal</small></span><span aria-hidden="true">›</span></a>
    </div>
    <p class="muted control-note">Available settings depend on the features enabled by your platform administrator.</p>
</nav>
