@extends('rentals.layout')
@section('title', 'Tenant control panel')
@section('owner-panel', '1')
@include('rentals.control-style')
@include('rentals.owner-orders-style')
@include('rentals.owner-shell-style')
@section('brand', $panel->subdomain ?: $panel->name)
@section('nav')<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.customer') : route('user.panels.index') }}">{{ ($ownerPreview ?? false) ? 'Customer preview' : 'All my panels' }}</a>@endsection
@section('content')
@php
    $base = basicControl()->base_currency;
    $url = $panel->subdomain ? 'https://' . $panel->subdomain : url('/panels/' . $panel->slug);
    $logo = \App\Services\PanelRentals::logoUrl($panel);
    $statusLabels = ['requested' => 'Request', 'awaiting_payment' => 'Waiting for payment', 'payment_failed' => 'Payment failed', 'submitted' => 'Paid · in progress', 'failed' => 'Paid · supplier refused'];
@endphp
<h1 class="owner-page-heading">{{ $panel->name }}</h1>
<div class="tenant-overview" aria-label="Tenant overview">
    <div><strong>{{ number_format($customerTotal) }}</strong><small>Customers</small></div>
    <div><strong>{{ number_format($orderTotal) }}</strong><small>Orders</small></div>
    <div><strong>{{ number_format($serviceTotal) }}</strong><small>Services</small></div>
    <div><strong>{{ number_format($earnings, 2) }} {{ $base }}</strong><small>Earnings</small></div>
</div>
@include('rentals.control-navigation')
<p class="owner-panel-meta"><span class="tag">{{ $panel->available() ? 'Active' : ($panel->status === 'active' ? 'Expired' : ucfirst($panel->status)) }}</span> · Paid until {{ $panel->expires_at->format('d M Y H:i') }}</p>
<p class="owner-panel-meta">Your panel: <a href="{{ ($ownerPreview ?? false) ? route('rental.preview.public') : ($panel->available() ? route('panels.show', $panel->slug) : '#') }}" target="_blank" rel="noopener"><strong>{{ ($ownerPreview ?? false) ? 'Sample storefront' : $url }}</strong></a>
    @if($panel->subdomain)<br><small>Before your domain is live (DNS), it also works at <a href="{{ route('panels.show', $panel->slug) }}" target="_blank" rel="noopener">{{ url('/panels/' . $panel->slug) }}</a></small>@endif</p>

<section id="general" class="tenant-section owner-settings" aria-label="General settings">
    <h1>General settings</h1>
    <h3>Registration</h3>
    <a class="settings-row" data-control-link href="#common-registration"><span>Registration settings</span><span>{{ $panel->workspace()['registration'] ? 'Enabled' : 'Disabled' }} ›</span></a>
    @if($panel->allows('accounts'))
    <a class="settings-row" data-control-link href="#common-registration"><span>Custom registration fields</span><span>Configure fields ›</span></a>
    @endif
    <h3>Services & ordering</h3>
    <a class="settings-row" data-control-link href="#common-services"><span>@include('rentals.control-icon', ['name'=>'list']) Service rules & display</span><span>{{ $panel->workspace()['price_format'] === 'per_k' ? 'Per 1,000' : ($panel->workspace()['price_format'] === 'per_hundred' ? 'Per 100' : 'Per 1') }} ›</span></a>
    @if($panel->allows('catalogue'))<a class="settings-row" data-control-link href="#services"><span>@include('rentals.control-icon', ['name'=>'list']) Service pricing catalogue</span><span>{{ number_format($serviceTotal) }} services ›</span></a>@endif
    <a class="settings-row" href="{{ ($ownerPreview ?? false) ? route('rental.preview.public', ['shop'=>1]) : route('panels.show', ['slug'=>$panel->slug, 'shop'=>1]) }}"><span>@include('rentals.control-icon', ['name'=>'cart']) Guest storefront</span><span>Open storefront ›</span></a>
    <a class="settings-row" data-control-link href="#payments"><span>@include('rentals.control-icon', ['name'=>'wallet']) Payments & earnings</span><span>Manage ›</span></a>
    @if($panel->allows('branding'))
    <h3>Metadata & branding</h3>
    <a class="settings-row" data-control-link href="#common-seo"><span>SEO & customer announcements</span><span>Configure ›</span></a>
    @endif
    <h3>Domain & subscription</h3>
    <a class="settings-row" data-control-link href="#common-domains"><span>DNS settings & validity</span><span>{{ $panel->available() ? 'Active' : 'Expired' }} ›</span></a>
    <h3>Customer visibility</h3>
    <a class="settings-row" data-control-link href="#common-visibility"><span>Customer dashboard display</span><span>Orders & spend statistics ›</span></a>
</section>
{{-- ===== Branding & settings + live preview ===== --}}
{{-- ===== SocPanel Common settings (tabs: General, Services, Registration, SEO, Domains, Visibility) ===== --}}
<section id="branding" class="tenant-section" aria-label="Common settings">
<div class="card" style="margin-bottom:20px">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px">
        <div>
            <h2 style="margin:0 0 4px">Common settings</h2>
            <p class="muted" style="margin:0;font-size:13px">Configure storefront, service rules, registration questions, search optimization, and domain routing.</p>
        </div>
        <div>
            <span class="soc-badge">{{ $panel->available() ? 'System Online' : 'Subscription Required' }}</span>
        </div>
    </div>

    {{-- SocPanel Tab Navigation --}}
    <div class="soc-common-tabs" role="tablist">
        <button type="button" class="soc-common-tab active" data-tab="general">⚙️ General</button>
        <button type="button" class="soc-common-tab" data-tab="services">📦 Services</button>
        <button type="button" class="soc-common-tab" data-tab="registration">📝 Registration</button>
        <button type="button" class="soc-common-tab" data-tab="seo">🔍 SEO & Meta</button>
        <button type="button" class="soc-common-tab" data-tab="domains">🌐 Domains & DNS</button>
        <button type="button" class="soc-common-tab" data-tab="visibility">👁️ Visibility</button>
    </div>

    <form method="post" action="{{ route('user.panels.update', $panel->id) }}" enctype="multipart/form-data" id="brandForm">@csrf
        <input type="hidden" name="active_tab" id="soc-active-tab" value="general">

        {{-- TAB 1: GENERAL --}}
        <div id="soc-tab-general" class="soc-tab-content active">
            <div class="soc-field-grid">
                @if($panel->allows('branding'))
                    <label>Panel name<input name="name" value="{{ old('name', $panel->name) }}" maxlength="100" required></label>
                    <label>Tagline<input name="tagline" value="{{ old('tagline', $panel->tagline) }}" maxlength="160" placeholder="Followers & likes in minutes"></label>
                    <label>Brand primary colour<input type="color" name="color" value="{{ old('color', $panel->color ?: '#2563eb') }}" style="height:44px;padding:4px"></label>
                    <label>WhatsApp support for customers<input name="whatsapp" value="{{ old('whatsapp', $panel->whatsapp ? '+' . $panel->whatsapp : '') }}" placeholder="+254 712 345 678"></label>
                    <label>Telegram support handle<input name="workspace[support_telegram]" value="{{ old('workspace.support_telegram', $panel->workspace()['support_telegram'] ?? '') }}" placeholder="@yourpanel_support"></label>
                    <label>Support email<input type="email" name="workspace[support_email]" value="{{ old('workspace.support_email', $panel->workspace()['support_email'] ?? '') }}" placeholder="support@yourdomain.com"></label>
                @else
                    <input type="hidden" name="name" value="{{ $panel->name }}">
                @endif
                <label>Currency your customers see
                    <select name="currency">@foreach($currencies as $c)<option value="{{ $c->code }}" @selected($cur->code === $c->code)>{{ $c->code }} · {{ $c->name }}</option>@endforeach</select>
                </label>
                <label>How customers pay
                    <select name="payment_mode">
                        <option value="mpesa" @selected($panel->payment_mode === 'mpesa')>M-Pesa through Mass Panel Shop's Paybill (automatic){{ $mpesaReady ? '' : ' (not available right now)' }}</option>
                        <option value="manual" @selected($panel->payment_mode === 'manual')>I collect payment myself (customers send a request)</option>
                    </select>
                    <small class="muted">With M-Pesa, customer orders start automatically and profit is credited to your earnings wallet.</small>
                </label>
                @if($panel->allows('markup'))
                    <label>Default catalogue markup (%)<input name="markup" type="number" min="0" max="1000" step="0.01" value="{{ old('markup', $panel->markup) }}" required>
                        <small class="muted">Applied to all services unless an individual price override is specified.</small>
                    </label>
                @else
                    <input type="hidden" name="markup" value="{{ $panel->markup }}">
                @endif
            </div>

            @if($panel->allows('branding'))
            <div style="background:#15222c;border:1px solid #283948;border-radius:10px;padding:14px 16px;margin-bottom:16px">
                <label style="margin-bottom:6px;display:block">Brand Logo <small class="muted">PNG, JPG, or WebP up to 2 MB</small></label>
                <input type="file" name="logo" accept="image/png,image/jpeg,image/webp">
                @if($logo)
                    <div style="display:flex;align-items:center;gap:14px;margin-top:10px">
                        <img src="{{ $logo }}" alt="Current logo" style="height:38px;max-width:180px;object-fit:contain;background:#0d161d;padding:4px;border-radius:6px">
                        <label class="inline" style="margin:0;font-weight:400"><input type="checkbox" name="remove_logo" value="1"> Remove logo</label>
                    </div>
                @endif
            </div>
            @endif

            <div class="soc-toggle-card">
                <div>
                    <strong>Fast guest ordering</strong>
                    <div class="desc">Allow visitors to place orders directly without registering an account.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[fast_orders]" value="0">
                    <input type="checkbox" name="workspace[fast_orders]" value="1" @checked(old('workspace.fast_orders', $panel->workspace()['fast_orders'] ?? true))>
                    <span class="soc-slider"></span>
                </label>
            </div>
        </div>

        {{-- TAB 2: SERVICES --}}
        <div id="soc-tab-services" class="soc-tab-content">
            <div class="soc-field-grid">
                <label>Service pricing format
                    <select name="workspace[price_format]">
                        <option value="per_k" @selected(($panel->workspace()['price_format'] ?? 'per_k') === 'per_k')>Per 1,000 (Default SMM Standard)</option>
                        <option value="per_hundred" @selected(($panel->workspace()['price_format'] ?? '') === 'per_hundred')>Per 100</option>
                        <option value="per_one" @selected(($panel->workspace()['price_format'] ?? '') === 'per_one')>Per single unit (1 item)</option>
                    </select>
                    <small class="muted">Controls how prices are presented across customer catalogue and ordering forms.</small>
                </label>
            </div>

            <div class="soc-toggle-card">
                <div>
                    <strong>Auto-cancel failed orders & refund</strong>
                    <div class="desc">Automatically return funds to the customer account when upstream provider rejects an order.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[auto_cancel_failed]" value="0">
                    <input type="checkbox" name="workspace[auto_cancel_failed]" value="1" @checked(old('workspace.auto_cancel_failed', $panel->workspace()['auto_cancel_failed'] ?? true))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div class="soc-toggle-card">
                <div>
                    <strong>Show average completion time</strong>
                    <div class="desc">Display estimated speed & historical delivery duration for available services.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[show_avg_time]" value="0">
                    <input type="checkbox" name="workspace[show_avg_time]" value="1" @checked(old('workspace.show_avg_time', $panel->workspace()['show_avg_time'] ?? true))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div class="soc-toggle-card">
                <div>
                    <strong>Highlight popular services ("Hot" badge)</strong>
                    <div class="desc">Display attention-grabbing badges on high-volume services to boost conversions.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[popular_badges]" value="0">
                    <input type="checkbox" name="workspace[popular_badges]" value="1" @checked(old('workspace.popular_badges', $panel->workspace()['popular_badges'] ?? true))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div class="soc-toggle-card">
                <div>
                    <strong>Multi-order / Mass ordering mode</strong>
                    <div class="desc">Enable bulk multi-line order submissions for advanced customers.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[multi_orders]" value="0">
                    <input type="checkbox" name="workspace[multi_orders]" value="1" @checked(old('workspace.multi_orders', $panel->workspace()['multi_orders'] ?? true))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div class="soc-toggle-card">
                <div>
                    <strong>Drip-feed ordering</strong>
                    <div class="desc">Allow customers to spread link deliveries across multiple runs and intervals.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[drip_feed]" value="0">
                    <input type="checkbox" name="workspace[drip_feed]" value="1" @checked(old('workspace.drip_feed', $panel->workspace()['drip_feed'] ?? true))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap">
                <a href="#services" data-control-link class="button" style="background:#1d354a;border:1px solid #325575">Manage Individual Prices</a>
                <a href="{{ route('user.panels.services.import', $panel->id) }}" class="button" style="background:#1e3d34;border:1px solid #336757">Service Importer Tool</a>
            </div>
        </div>

        {{-- TAB 3: REGISTRATION --}}
        <div id="soc-tab-registration" class="soc-tab-content">
            <div class="soc-toggle-card">
                <div>
                    <strong>Accept new customer registrations</strong>
                    <div class="desc">When turned off, only existing users can log in and sign-up links are disabled.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[registration]" value="0">
                    <input type="checkbox" name="workspace[registration]" value="1" @checked(old('workspace.registration', $panel->workspace()['registration']))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            @if($panel->allows('accounts'))
            <h3 style="margin:20px 0 8px">Custom Registration Questions</h3>
            <p class="muted" style="font-size:13px;margin-bottom:14px">Customize up to five questions collected during sign up. Unused questions should have blank labels.</p>
            @foreach(range(1, 5) as $index)
                @php $fieldKey = 'field_'.$index; @endphp
                <div style="background:#162430;border:1px solid #2a3c4c;border-radius:10px;padding:12px 16px;margin-bottom:10px">
                    <label style="display:block;margin-bottom:6px">Field {{ $index }} label
                        <input name="workspace[registration_fields][{{ $fieldKey }}][label]" maxlength="80" value="{{ old('workspace.registration_fields.'.$fieldKey.'.label', data_get($panel->workspace(), 'registration_fields.'.$fieldKey.'.label', '')) }}" placeholder="e.g. WhatsApp number, Referral source, Telegram ID">
                    </label>
                    <input type="hidden" name="workspace[registration_fields][{{ $fieldKey }}][required]" value="0">
                    <label class="inline" style="font-size:12px;font-weight:400"><input type="checkbox" name="workspace[registration_fields][{{ $fieldKey }}][required]" value="1" @checked(old('workspace.registration_fields.'.$fieldKey.'.required', data_get($panel->workspace(), 'registration_fields.'.$fieldKey.'.required', false)))> Required field</label>
                </div>
            @endforeach
            @endif
        </div>

        {{-- TAB 4: SEO & METADATA --}}
        <div id="soc-tab-seo" class="soc-tab-content">
            @if($panel->allows('branding'))
                <label>Customer Announcement Banner
                    <textarea name="workspace[announcement]" rows="3" maxlength="1000" placeholder="Special announcement shown to customers across your panel...">{{ old('workspace.announcement', $panel->workspace()['announcement']) }}</textarea>
                    <small class="muted">Displayed at the top of the customer dashboard.</small>
                </label>
                <div class="soc-field-grid" style="margin-top:14px">
                    <label>Search engine title (Meta Title)
                        <input name="workspace[seo_title]" maxlength="100" value="{{ old('workspace.seo_title', $panel->workspace()['seo_title']) }}" placeholder="{{ $panel->name }} — Top SMM Reseller Panel">
                    </label>
                    <label>Meta Keywords
                        <input name="workspace[seo_keywords]" maxlength="300" value="{{ old('workspace.seo_keywords', $panel->workspace()['seo_keywords'] ?? '') }}" placeholder="smm panel, buy likes, followers, instagram growth">
                    </label>
                </div>
                <label>Search engine description (Meta Description)
                    <textarea name="workspace[seo_description]" rows="3" maxlength="300" placeholder="The premier automated Social Media Marketing panel with instant delivery...">{{ old('workspace.seo_description', $panel->workspace()['seo_description']) }}</textarea>
                </label>
                <h3 style="margin:20px 0 8px">Legal & Custom Policy Pages</h3>
                <p class="muted" style="font-size:13px;margin-bottom:12px">Your text is published at <code>/terms</code> and <code>/privacy</code> on your domain. Leave empty to hide.</p>
                <label>Terms of service
                    <textarea name="workspace[terms]" rows="5" maxlength="20000" placeholder="Write your terms of service...">{{ old('workspace.terms', $panel->workspace()['terms']) }}</textarea>
                </label>
                <label>Privacy policy
                    <textarea name="workspace[privacy]" rows="5" maxlength="20000" placeholder="Write your privacy policy...">{{ old('workspace.privacy', $panel->workspace()['privacy']) }}</textarea>
                </label>
            @endif
        </div>

        {{-- TAB 5: DOMAINS & DNS --}}
        <div id="soc-tab-domains" class="soc-tab-content">
            <div style="background:#162430;border:1px solid #2a3c4c;border-radius:10px;padding:16px;margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
                    <div>
                        <strong style="font-size:15px">Primary Subdomain</strong>
                        <div style="font-size:13px;color:#a4bbd0;margin-top:2px">
                            <a href="{{ $panel->subdomain ? 'https://' . $panel->subdomain : url('/panels/' . $panel->slug) }}" target="_blank" rel="noopener">{{ $panel->subdomain ?: url('/panels/' . $panel->slug) }}</a>
                        </div>
                    </div>
                    <span class="soc-badge" style="background:#223c4e;color:#7eccfc">Included</span>
                </div>
            </div>

            @if($panel->allows('custom_domain'))
            <div style="background:#162430;border:1px solid #2a3c4c;border-radius:10px;padding:16px;margin-bottom:16px">
                <label style="display:block;margin-bottom:8px">Custom domain name
                    <input name="custom_domain" value="{{ old('custom_domain', $panel->custom_domain) }}" placeholder="panel.yourbrand.com">
                </label>
                @if($panel->custom_domain)
                    <div style="margin-top:12px;padding:10px;background:#0f1a23;border-radius:8px;font-size:13px">
                        <div>Status: @if($panel->domain_verified_at) <strong style="color:#78dcb2">✓ Verified & Active</strong> @else <strong style="color:#f59e0b">⏳ Pending Verification</strong> @endif</div>
                        @if(!$panel->domain_verified_at)
                            <div style="margin-top:6px;font-size:12px" class="muted">Add a DNS TXT record for <code>_zirka-panel.{{ $panel->custom_domain }}</code> with value: <code style="user-select:all">{{ $panel->domain_token }}</code></div>
                        @endif
                    </div>
                @endif
            </div>
            @endif

            <div style="padding:14px;background:#13202b;border:1px solid #233544;border-radius:10px;font-size:13px">
                <strong>Subscription & Validity:</strong>
                <p class="muted" style="margin:4px 0 0">Paid until <strong>{{ $panel->expires_at->format('d M Y, H:i') }}</strong> ({{ $panel->expires_at->diffForHumans() }}).</p>
            </div>
        </div>

        {{-- TAB 6: VISIBILITY & LOYALTY --}}
        <div id="soc-tab-visibility" class="soc-tab-content">
            <div class="soc-toggle-card">
                <div>
                    <strong>Show customer order count</strong>
                    <div class="desc">Display the total number of orders placed in the customer's personal dashboard.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[show_orders]" value="0">
                    <input type="checkbox" name="workspace[show_orders]" value="1" @checked(old('workspace.show_orders', $panel->workspace()['show_orders']))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div class="soc-toggle-card">
                <div>
                    <strong>Show customer total spent</strong>
                    <div class="desc">Display the lifetime expenditure amount on the customer's header and dashboard.</div>
                </div>
                <label class="soc-switch">
                    <input type="hidden" name="workspace[show_spent]" value="0">
                    <input type="checkbox" name="workspace[show_spent]" value="1" @checked(old('workspace.show_spent', $panel->workspace()['show_spent']))>
                    <span class="soc-slider"></span>
                </label>
            </div>

            <div class="soc-field-grid" style="margin-top:16px">
                <label>Customer affiliate referral commission (%)
                    <input type="number" min="0" max="100" step="0.5" name="workspace[referral_percent]" value="{{ old('workspace.referral_percent', $panel->workspace()['referral_percent'] ?? 5) }}">
                    <small class="muted">Non-withdrawable percentage credited to a customer's panel wallet when their invited friends add funds.</small>
                </label>
            </div>
        </div>

        @if($panel->available())
            <div style="display:flex;justify-content:flex-end;margin-top:20px;border-top:1px solid var(--panel-border);padding-top:16px">
                <button type="submit" class="button" style="padding:11px 26px;font-size:14px">Save common settings</button>
            </div>
        @endif
    </form>
</div>

<div class="card" style="margin-top:20px">
    <h2>Live preview</h2>
    <p class="muted">Updates in real time as you adjust your panel configuration.</p>
    <iframe id="pv" title="Panel preview" src="{{ ($ownerPreview ?? false) ? route('rental.preview.public') : route('user.panels.preview', ['panel' => $panel->id]) }}" style="width:100%;height:560px;border:1px solid #2a3c4c;border-radius:12px;background:#fff"></iframe>
</div>
</section>

{{-- ===== Earnings & payouts ===== --}}
<section id="payments" class="tenant-section" aria-label="Payments and earnings">
<div class="card">
    <h2>Earnings</h2>
    <p style="font-size:28px;margin:0"><strong>{{ number_format($earnings, 2) }} {{ $base }}</strong></p>
    <p class="muted">Profit from customers who paid with M-Pesa on your panel{{ $settings['platform_fee'] > 0 ? ' (after the ' . $settings['platform_fee'] . '% platform fee)' : '' }}.</p>
    <div class="grid">
        @include('rentals.payout-destination')
        <form method="post" action="{{ route('user.panels.towallet') }}">@csrf
            <h3>Use for orders</h3>
            <p class="muted">Move earnings to your main balance to buy services.</p>
            <label>Amount ({{ $base }})<input type="number" name="amount" min="0.01" step="0.01" max="{{ $earnings }}" required></label>
            <button class="button" @disabled($earnings <= 0)>Move to main wallet</button>
        </form>
    </div>
    @if($payouts->isNotEmpty())
        <div class="table-wrap"><table><thead><tr><th>Date</th><th>Amount</th><th>To</th><th>Status</th></tr></thead><tbody>
            @foreach($payouts as $p)<tr><td>{{ $p->created_at->format('d M Y') }}</td><td>{{ number_format($p->amount, 2) }} {{ $base }} <small>{{ $p->payout_currency }} {{ number_format($p->payout_amount ?? $p->amount_kes) }}</small></td>
                <td>{{ ['b2c' => 'M-Pesa', 'paybill' => 'Paybill', 'till' => 'Till'][$p->method] }} {{ $p->account }}{{ $p->account_ref ? ' / ' . $p->account_ref : '' }}</td>
                <td>{{ ucfirst($p->status) }}@if($p->mpesa_ref)<small>{{ $p->mpesa_ref }}</small>@endif @if($p->status === 'rejected' && $p->note)<small>{{ $p->note }}</small>@endif</td></tr>@endforeach
        </tbody></table></div>
    @endif
</div>
</section>

<section id="statistics" class="tenant-section" aria-label="Statistics">
<div class="card">
    <h2>Statistics</h2>
    <p class="muted">Performance for this tenant panel only. Provider and other tenants' data is excluded.</p>
    <div class="grid">
        <div><h3>Order status</h3><div class="table-wrap"><table><thead><tr><th>Status</th><th>Orders</th><th>Revenue</th><th>Profit</th></tr></thead><tbody>
            @forelse($orderStats as $stat)<tr><td>{{ ucfirst($stat->status) }}</td><td>{{ number_format($stat->total) }}</td><td>{{ number_format($stat->revenue, 2) }} {{ $base }}</td><td>{{ number_format($stat->earnings, 2) }} {{ $base }}</td></tr>
            @empty<tr><td colspan="4">No account orders yet.</td></tr>@endforelse
        </tbody></table></div></div>
        <div><h3>Top services</h3><div class="table-wrap"><table><thead><tr><th>Service</th><th>Orders</th><th>Revenue</th></tr></thead><tbody>
            @forelse($topServices as $stat)<tr><td>{{ $stat->service ? \Illuminate\Support\Str::limit(\App\Services\SmartImport::cleanName($stat->service->service_title), 42) : 'Service unavailable' }}</td><td>{{ number_format($stat->total) }}</td><td>{{ number_format($stat->revenue, 2) }} {{ $base }}</td></tr>
            @empty<tr><td colspan="3">No services ordered yet.</td></tr>@endforelse
        </tbody></table></div></div>
    </div>
</div>
</section>

{{-- ===== Your prices ===== --}}
@if($panel->allows('markup'))
<section id="services" class="tenant-section" aria-label="Service prices">
@unless($ownerPreview ?? false)<p><a class="button" href="{{ route('user.panels.services.import', $panel->id) }}">Import services from Mass Panel Shop</a></p>@endunless
<div class="card">
    <h2>Your prices <small>per 1,000 or item, in {{ $cur->code }}</small></h2>
    <p class="muted">Leave a price empty to use your {{ rtrim(rtrim(number_format($panel->markup, 2), '0'), '.') }}% markup. Prices can't go below your cost (they are raised to the cost automatically). Tick "Hide" to remove a service from your panel.</p>
    <input type="search" id="pq" placeholder="Search services…" style="margin-bottom:10px">
    <form method="post" action="{{ route('user.panels.prices', $panel->id) }}">@csrf
        <div class="table-wrap" style="max-height:520px">
            <table id="ptable"><thead><tr><th>Service</th><th>Your cost</th><th>Customer pays</th><th>Your price</th><th>Hide</th></tr></thead><tbody>
            @foreach($services as $s)
                <tr data-n="{{ strtolower(\App\Services\SmartImport::cleanName($s->service_title)) }}">
                    <td>{{ \Illuminate\Support\Str::limit(\App\Services\SmartImport::cleanName($s->service_title), 70) }}</td>
                    <td>{{ number_format($s->cost_k, $s->cost_k < 1 ? 4 : 2) }} <small>/ {{ \App\Services\PanelRentals::quantityBased($s) ? '1k' : 'item' }}</small></td>
                    <td><strong>{{ number_format($s->panel_price, $s->panel_price < 1 ? 4 : 2) }}</strong> <small>/ {{ \App\Services\PanelRentals::quantityBased($s) ? '1k' : 'item' }}</small></td>
                    <td><input type="number" name="price[{{ $s->id }}]" min="{{ $s->cost_k }}" step="0.0001" value="{{ $s->own }}" placeholder="{{ number_format($s->panel_price, 2, '.', '') }}" style="min-width:110px"></td>
                    <td><input type="checkbox" name="hidden[{{ $s->id }}]" value="1" @checked($s->hidden_k) style="width:auto"></td>
                </tr>
            @endforeach
            </tbody></table>
        </div>
        @if($panel->available())<button>Save prices</button>@endif
    </form>
</div>
</section>
@endif

{{-- ===== Domains & renewal (unchanged behaviour) ===== --}}
<section id="domains" class="tenant-section" aria-label="Domains and subscription">
<div class="card"><h2>Domains & renewal</h2><p>Subdomain: <strong>{{ $panel->subdomain ?: 'Assigned automatically once the platform owner sets the panel domain' }}</strong></p>
<p class="muted">Domain ownership verification does not configure hosting or HTTPS. Your platform administrator must configure routing and certificates before the domain is ready.</p>
@if($panel->custom_domain)<p>Custom domain: {{ $panel->custom_domain }} · {{ $panel->domain_verified_at ? 'Ownership verified' : 'Verification required' }}</p><p>Add a DNS TXT record:</p><p><code>_zirka-panel.{{ $panel->custom_domain }}</code><br><code>zirka-panel={{ $panel->domain_token }}</code></p>
<p>Then point the domain to Mass Panel Shop: a <code>CNAME</code> record to <code>{{ $panel->subdomain ?: parse_url(config('app.url'), PHP_URL_HOST) }}</code>.</p>
@if($panel->allows('custom_domain'))<form method="post" action="{{ route('user.panels.verify', $panel->id) }}">@csrf<button>Verify domain ownership</button></form>@endif
@endif
@if($panel->status === 'active' && $settings['enabled'])
@php
    $renewalToken = session('rental_renew_'.$panel->id) ?: (string) \Illuminate\Support\Str::uuid();
    session(['rental_renew_'.$panel->id => $renewalToken]);
@endphp
<form method="post" action="{{ route('user.panels.renew', $panel->id) }}">@csrf<input type="hidden" name="renewal_token" value="{{ $renewalToken }}"><input type="hidden" name="price" value="{{ $settings['price'] }}"><button>Renew {{ $settings['days'] }} days · {{ $settings['price'] }} {{ $base }}</button></form>@endif</div>
</section>

{{-- ===== Customer accounts ===== --}}
@if($panel->allows('accounts'))
<section id="customers" class="tenant-section" aria-label="Customers">
<div class="card"><h2>Customers <small>{{ number_format($customerTotal) }} with an account</small></h2>
    <form method="get" action="{{ route('user.panels.show', $panel->id) }}#customers">
        <label for="customer-search">Find a customer</label>
        <input id="customer-search" name="customer_search" maxlength="100" value="{{ request('customer_search') }}" placeholder="Username or email">
        <button>Search customers</button>
    </form>
    <p>Customers sign up at <strong>{{ $panel->custom_domain && $panel->domain_verified_at ? $panel->custom_domain : ($panel->subdomain ?: 'your panel domain') }}/register</strong>, add funds to their wallet and order from a dashboard with your name, logo and prices. Your profit on every order is added to your earnings automatically.</p>
    @if($customers->isEmpty())
        <p>No customer accounts yet.</p>
    @else
        <div style="overflow-x:auto"><table><thead><tr><th>Customer</th><th>Contact</th><th>Wallet</th><th>Orders</th><th>Your profit</th><th>Joined</th><th>Access</th></tr></thead><tbody>
        @foreach($customers as $c)
            <tr><td><a href="{{ route('user.panels.show', [$panel->id, 'customer_id' => $c->id]) }}#orders">{{ $c->username }}</a><small>ID {{ $c->id }}</small>
                @if($c->rental_profile)<details><summary>Signup answers</summary>@foreach($c->rental_profile as $answer)<p><strong>{{ $answer['label'] }}</strong><br>{{ $answer['value'] }}</p>@endforeach</details>@endif
            </td><td>{{ $c->email }}{{ $c->phone ? ' · ' . $c->phone_code . $c->phone : '' }}</td>
                <td>{{ number_format((float) $c->balance, 2) }} {{ $base }}</td><td>{{ number_format($c->orders_count) }}</td>
                <td>{{ number_format((float) $c->earned, 2) }} {{ $base }}</td><td>{{ $c->created_at->format('d M Y') }}</td>
                <td><span class="tag">{{ $c->status ? 'Active' : 'Suspended' }}</span><form method="post" action="{{ route('user.panels.customer.status', [$panel->id, $c->id]) }}">@csrf
                    <input type="hidden" name="status" value="{{ $c->status ? 0 : 1 }}"><button>{{ $c->status ? 'Suspend access' : 'Restore access' }}</button>
                </form></td></tr>
        @endforeach
        </tbody></table></div>
    @endif
    {{ $customers->fragment('customers')->links() }}
</div>
</section>
@endif

{{-- ===== Storefront (guest) orders ===== --}}
<section id="orders" class="tenant-section" aria-label="Storefront orders">
@if($panel->allows('accounts'))
@include('rentals.owner-orders')
@endif
<details class="owner-guest-orders"><summary>Guest orders · {{ $requests->total() }}</summary>
@forelse($requests as $item)
    <div class="card"><h3>#{{ $item->id }} · {{ $item->service ? \App\Services\SmartImport::cleanName($item->service->service_title) : 'Service unavailable' }}</h3>
        <p><span class="tag">{{ $statusLabels[$item->status] ?? ucfirst($item->status) }}</span> {{ $item->name }} · {{ $item->email }}{{ $item->phone ? ' · ' . $item->phone : '' }}</p>
        <p>Quantity: {{ number_format($item->quantity) }} · Total: {{ number_format((float) $item->quote, 2) }} {{ $item->currency }}
            @if($item->paid_amount && $item->paid_at) · Paid KES {{ number_format($item->paid_amount) }}@endif
            @if($item->earning !== null) · Your profit: <strong>{{ number_format($item->earning, 2) }} {{ $base }}</strong>@endif</p>
        <p>Link: {{ $item->link }}</p>
        @if($item->note && in_array($item->status, ['failed', 'payment_failed']))<div class="notice error">{{ $item->note }}</div>@endif
        @if($item->provider_order_id)<p>Order #{{ $item->provider_order_id }} · {{ $item->providerOrder->status ?? $item->status }}</p>
        @elseif($item->status === 'requested' && $panel->allows('orders'))
            <a href="{{ route('user.order.create', ['serviceId' => $item->service_id, 'link' => $item->link, 'quantity' => $item->quantity]) }}" target="_blank" rel="noopener">Place matching order with Mass Panel Shop</a>
            <form method="post" action="{{ route('user.panels.link', [$panel->id, $item->id]) }}">@csrf<label>Order ID<input name="provider_order_id" type="number" min="1" required></label><button>Link order for tracking</button></form>
        @endif
    </div>
@empty
    <p>No customer orders yet. Share your panel link to start selling.</p>
@endforelse
{{ $requests->fragment('orders')->links() }}
</details>
</section>
<section id="provider" class="tenant-section card" aria-label="Provider">
    <h2>Your service provider</h2>
<p><strong>Mass Panel Shop</strong> supplies your panel's service catalogue and fulfils orders through the platform.</p>
    <p class="muted">You manage your brand and permitted resale prices. Provider connections and feature access remain controlled by the platform administrator.</p>
</section>

@include('rentals.workspace-icons')
<nav class="owner-bottom-nav" aria-label="Panel navigation">
    @if($panel->allows('accounts'))<a data-control-link href="#customers"><svg aria-hidden="true"><use href="#icon-user"/></svg><span>Users</span></a>@endif
    <a data-control-link href="#orders"><svg aria-hidden="true"><use href="#icon-cart"/></svg><span>Orders</span></a>
    @if($panel->allows('catalogue'))<a data-control-link href="#services"><svg aria-hidden="true"><use href="#icon-layers"/></svg><span>Services</span></a>@endif
    @if($panel->allows('accounts'))<a href="{{ ($ownerPreview ?? false) ? route('rental.preview.alias', ['screen' => 'support']) : route('user.panels.support', $panel->id) }}"><svg aria-hidden="true"><use href="#icon-chat"/></svg><span>Support</span></a>@endif
    <a data-control-link href="#"><svg aria-hidden="true" viewBox="0 0 24 24"><path d="M3 5h18v2H3zm0 6h18v2H3zm0 6h18v2H3z"/></svg><span>Menu</span></a>
</nav>
<script>
    (function () {
        const sections = [...document.querySelectorAll('.tenant-section')];
        const links = [...document.querySelectorAll('[data-control-link]')];
        const home = document.getElementById('tenant-control');
        const tabs = [...document.querySelectorAll('.soc-common-tab')];
        const contents = [...document.querySelectorAll('.soc-tab-content')];
        const activeInput = document.getElementById('soc-active-tab');

        function selectSocTab(tabKey) {
            let found = false;
            tabs.forEach(t => {
                if (t.dataset.tab === tabKey) { t.classList.add('active'); found = true; }
                else { t.classList.remove('active'); }
            });
            contents.forEach(c => {
                if (c.id === 'soc-tab-' + tabKey) { c.classList.add('active'); }
                else { c.classList.remove('active'); }
            });
            if (found && activeInput) activeInput.value = tabKey;
        }

        tabs.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const key = this.dataset.tab;
                selectSocTab(key);
                if (history.replaceState) history.replaceState(null, null, '#common-' + key);
            });
        });

        function navigate() {
            let rawKey = location.hash.slice(1) || 'general';
            let subTab = null;
            if (rawKey.startsWith('common-')) {
                subTab = rawKey.replace('common-', '');
                rawKey = 'branding';
            }
            const selected = sections.find(section => section.id === rawKey) || document.getElementById('general');
            sections.forEach(section => section.hidden = selected ? section !== selected : true);
            home.hidden = false;
            document.getElementById('control-back').hidden = true;
            links.forEach(link => {
                const targetId = (link.hash || '').replace('#common-', '');
                if (link.hash === '#' + selected.id || (selected.id === 'branding' && link.hash.startsWith('#common-'))) {
                    link.setAttribute('aria-current', 'page');
                } else {
                    link.removeAttribute('aria-current');
                }
            });
            if (subTab) selectSocTab(subTab);
        }

        window.addEventListener('hashchange', navigate);
        if (@json($errors->any())) {
            sections.forEach(section => section.hidden = false);
        } else {
            navigate();
        }

        @if(session('active_tab'))
            selectSocTab(@json(session('active_tab')));
        @endif

        const m = document.getElementById('pmethod'), ref = document.getElementById('refWrap');
        m?.addEventListener('change', () => { ref.hidden = m.value !== 'paybill'; });
        const q = document.getElementById('pq');
        q?.addEventListener('input', () => { const v = q.value.toLowerCase(); document.querySelectorAll('#ptable tbody tr').forEach(tr => tr.hidden = v && !tr.dataset.n.includes(v)); });
        const form = document.getElementById('brandForm'), pv = document.getElementById('pv'), url = @json(route('user.panels.preview'));
        let t;
        form?.addEventListener('input', e => {
            if (e.target.type === 'file') return;
            clearTimeout(t);
            t = setTimeout(() => {
                const q = new URLSearchParams({panel: @json($panel->id), name: form.name?.value || '', tagline: form.tagline?.value || '',
                    color: form.color?.value || '', markup: form.markup?.value || '', currency: form.currency?.value || ''});
                pv.src = url + '?' + q.toString();
            }, 400);
        });
    })();
</script>
@endsection
