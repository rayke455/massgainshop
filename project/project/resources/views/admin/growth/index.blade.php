@extends('admin.layouts.app')
@section('page_title', __('Growth'))
@section('content')
    @php
        $tabs = ['coupons' => ['bi-ticket-perforated', 'Coupons'], 'bonuses' => ['bi-gift', 'Deposit bonuses'], 'vip' => ['bi-award', 'VIP levels'],
                 'reviews' => ['bi-star', 'Reviews'], 'settings' => ['bi-sliders', 'Settings']];
        $fmt = fn($n) => rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.'); // no thousands separator: number inputs can't read "1,000"
    @endphp
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">@lang('Growth')</h1>
            <p class="text-muted mb-0">@lang('Coupons, bonuses, VIP discounts, reviews and the tools that protect your business.')</p>
        </div>

        <ul class="nav nav-segment mb-4 flex-wrap">
            @foreach($tabs as $key => [$icon, $label])
                <li class="nav-item">
                    <a class="nav-link {{ $tab === $key ? 'active' : '' }}" href="{{ route('admin.growth.index', ['tab' => $key]) }}">
                        <i class="{{ $icon }} me-1"></i> @lang($label)
                        @if($key === 'reviews' && $pendingReviews)<span class="badge bg-danger ms-1">{{ $pendingReviews }}</span>@endif
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- ================= COUPONS ================= --}}
        @if($tab === 'coupons')
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header"><h4 class="card-header-title">@lang('New coupon')</h4></div>
                        <div class="card-body">
                            <form action="{{ route('admin.growth.coupon.store') }}" method="post" class="d-grid gap-3">
                                @csrf
                                <div>
                                    <label class="form-label">@lang('Code')</label>
                                    <input class="form-control text-uppercase" name="code" value="{{ old('code') }}" placeholder="WELCOME10" required maxlength="40">
                                    <small class="text-muted">@lang('Letters, numbers, - and _. Customers type it on Add funds.')</small>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">@lang('Bonus type')</label>
                                        <select class="form-select" name="type">
                                            <option value="percent">@lang('% of deposit')</option>
                                            <option value="fixed" @selected(old('type') === 'fixed')>@lang('Fixed amount') ({{ $base }})</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">@lang('Value')</label>
                                        <input class="form-control" type="number" step="0.01" min="0.01" name="value" value="{{ old('value', 10) }}" required>
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">@lang('Min deposit') ({{ $base }})</label>
                                        <input class="form-control" type="number" step="0.01" min="0" name="min_deposit" value="{{ old('min_deposit', 0) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">@lang('Expires')</label>
                                        <input class="form-control" type="datetime-local" name="expires_at" value="{{ old('expires_at') }}">
                                    </div>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label">@lang('Total uses')</label>
                                        <input class="form-control" type="number" min="1" name="max_uses" value="{{ old('max_uses') }}" placeholder="@lang('Unlimited')">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">@lang('Uses per customer')</label>
                                        <input class="form-control" type="number" min="1" name="per_user" value="{{ old('per_user', 1) }}">
                                    </div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="first_deposit_only" value="1" id="cFirst" @checked(old('first_deposit_only'))>
                                    <label class="form-check-label" for="cFirst">@lang('Only for a customer\'s first deposit')</label>
                                </div>
                                <button class="btn btn-primary">@lang('Create coupon')</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header"><h4 class="card-header-title">@lang('Coupons')</h4></div>
                        <div class="table-responsive">
                            <table class="table table-align-middle mb-0">
                                <thead class="thead-light"><tr><th>@lang('Code')</th><th>@lang('Bonus')</th><th>@lang('Rules')</th><th>@lang('Used')</th><th>@lang('Status')</th><th></th></tr></thead>
                                <tbody>
                                @forelse($coupons as $c)
                                    <tr>
                                        <td><strong class="font-monospace">{{ $c->code }}</strong></td>
                                        <td>{{ $c->type === 'percent' ? $fmt($c->value) . '%' : $fmt($c->value) . ' ' . $base }}</td>
                                        <td class="small text-muted">
                                            @if($c->min_deposit > 0) @lang('min') {{ $fmt($c->min_deposit) }} {{ $base }}<br>@endif
                                            @if($c->first_deposit_only) @lang('first deposit only')<br>@endif
                                            @if($c->expires_at) @lang('until') {{ $c->expires_at->format('d M Y H:i') }} @endif
                                        </td>
                                        <td>{{ $c->used_count }}{{ $c->max_uses ? ' / ' . $c->max_uses : '' }}</td>
                                        <td>
                                            @if($c->expires_at && $c->expires_at->isPast())<span class="badge bg-soft-secondary text-secondary">@lang('Expired')</span>
                                            @else<span class="badge {{ $c->status ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }}">{{ $c->status ? __('Active') : __('Off') }}</span>@endif
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <form class="d-inline" method="post" action="{{ route('admin.growth.coupon.toggle', $c) }}">@csrf
                                                <button class="btn btn-white btn-sm">{{ $c->status ? __('Turn off') : __('Turn on') }}</button></form>
                                            <form class="d-inline" method="post" action="{{ route('admin.growth.coupon.delete', $c) }}" onsubmit="return confirm('Delete this coupon?')">@csrf @method('delete')
                                                <button class="btn btn-white btn-sm text-danger"><i class="bi-trash"></i></button></form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">@lang('No coupons yet. Create one on the left, e.g. WELCOME10 = +10% on the first deposit.')</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= DEPOSIT BONUSES ================= --}}
        @if($tab === 'bonuses')
            @php $tiers = array_pad((array) $settings['bonus.tiers'], 4, ['min' => '', 'percent' => '']); @endphp
            <div class="card" style="max-width: 760px">
                <div class="card-header"><h4 class="card-header-title">@lang('Automatic deposit bonuses')</h4></div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.growth.bonus.save') }}" class="d-grid gap-4">
                        @csrf
                        <div>
                            <label class="form-label">@lang('First deposit bonus')</label>
                            <div class="input-group" style="max-width: 220px">
                                <input class="form-control" type="number" step="0.1" min="0" max="100" name="first_deposit_percent" value="{{ $settings['bonus.first_deposit_percent'] }}">
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">@lang('Extra balance on a customer\'s very first deposit. 0 = off.')</small>
                        </div>
                        <div>
                            <label class="form-label">@lang('Bonus by amount')</label>
                            <p class="small text-muted mb-2">@lang('Encourage bigger deposits, e.g. add 50 :base or more → +5%. The customer gets the best matching bonus (not both). Coupons are added on top.', ['base' => $base])</p>
                            @foreach($tiers as $i => $t)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="text-muted small">@lang('From')</span>
                                    <input class="form-control" style="max-width: 140px" type="number" step="0.01" min="0" name="tiers[{{ $i }}][min]" value="{{ $t['min'] }}" placeholder="50">
                                    <span class="text-muted small">{{ $base }} → +</span>
                                    <input class="form-control" style="max-width: 110px" type="number" step="0.1" min="0" max="100" name="tiers[{{ $i }}][percent]" value="{{ $t['percent'] }}" placeholder="5">
                                    <span class="text-muted small">%</span>
                                </div>
                            @endforeach
                        </div>
                        <div><button class="btn btn-primary">@lang('Save bonuses')</button></div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ================= VIP LEVELS ================= --}}
        @if($tab === 'vip')
            @php $rows = $levels->map(fn($l) => $l->only(['name', 'min_spent', 'discount_percent', 'color']))->all(); $rows = array_pad($rows, count($rows) + 2, ['name' => '', 'min_spent' => '', 'discount_percent' => '', 'color' => '#2563eb']); @endphp
            <div class="card" style="max-width: 860px">
                <div class="card-header"><h4 class="card-header-title">@lang('VIP levels')</h4></div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.growth.vip.save') }}">
                        @csrf
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="enabled" value="1" id="vipOn" @checked($settings['vip.enabled'])>
                            <label class="form-check-label" for="vipOn">@lang('VIP discounts are on')</label>
                        </div>
                        <p class="small text-muted">@lang('Customers move up automatically as their total spend grows. The discount comes off every order (website, mass order and API). Leave a name empty to remove a level.')</p>
                        <div class="table-responsive">
                            <table class="table table-align-middle">
                                <thead class="thead-light"><tr><th>@lang('Level name')</th><th>@lang('Total spent from') ({{ $base }})</th><th>@lang('Discount')</th><th>@lang('Colour')</th></tr></thead>
                                <tbody>
                                @foreach($rows as $i => $l)
                                    <tr>
                                        <td><input class="form-control" name="levels[{{ $i }}][name]" value="{{ $l['name'] }}" placeholder="@lang('e.g. Gold')"></td>
                                        <td><input class="form-control" type="number" step="0.01" min="0" name="levels[{{ $i }}][min_spent]" value="{{ $l['min_spent'] !== '' ? $fmt($l['min_spent']) : '' }}"></td>
                                        <td><div class="input-group"><input class="form-control" type="number" step="0.1" min="0" max="90" name="levels[{{ $i }}][discount_percent]" value="{{ $l['discount_percent'] !== '' ? $fmt($l['discount_percent']) : '' }}"><span class="input-group-text">%</span></div></td>
                                        <td><input class="form-control form-control-color" type="color" name="levels[{{ $i }}][color]" value="{{ $l['color'] }}"></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary">@lang('Save VIP levels')</button>
                    </form>
                </div>
            </div>
        @endif

        {{-- ================= REVIEWS ================= --}}
        @if($tab === 'reviews')
            <div class="card">
                <div class="card-header"><h4 class="card-header-title">@lang('Customer reviews')</h4>
                    <p class="small text-muted mb-0">@lang('Customers can review completed orders. Only approved reviews appear on the home page.')</p></div>
                <div class="table-responsive">
                    <table class="table table-align-middle mb-0">
                        <thead class="thead-light"><tr><th>@lang('Customer')</th><th>@lang('Rating')</th><th style="min-width:280px">@lang('Review')</th><th>@lang('Status')</th><th></th></tr></thead>
                        <tbody>
                        @forelse($reviews as $r)
                            <tr>
                                <td><strong>{{ $r->display_name }}</strong>@if($r->country)<br><small class="text-muted">{{ $r->country }}</small>@endif
                                    <br><small class="text-muted">{{ optional($r->user)->username }} · @lang('order') #{{ $r->order_id }}</small></td>
                                <td class="text-warning text-nowrap">{{ str_repeat('★', $r->rating) }}<span class="text-muted">{{ str_repeat('★', 5 - $r->rating) }}</span></td>
                                <td class="small">{{ $r->comment }}</td>
                                <td>{!! [0 => '<span class="badge bg-soft-warning text-warning">' . __('Pending') . '</span>', 1 => '<span class="badge bg-soft-success text-success">' . __('Approved') . '</span>', 2 => '<span class="badge bg-soft-secondary text-secondary">' . __('Rejected') . '</span>'][$r->status] !!}</td>
                                <td class="text-end text-nowrap">
                                    @if($r->status != 1)<form class="d-inline" method="post" action="{{ route('admin.growth.review.status', $r) }}">@csrf<input type="hidden" name="status" value="1"><button class="btn btn-success btn-sm">@lang('Approve')</button></form>@endif
                                    @if($r->status != 2)<form class="d-inline" method="post" action="{{ route('admin.growth.review.status', $r) }}">@csrf<input type="hidden" name="status" value="2"><button class="btn btn-white btn-sm">@lang('Reject')</button></form>@endif
                                    <form class="d-inline" method="post" action="{{ route('admin.growth.review.delete', $r) }}" onsubmit="return confirm('Delete this review?')">@csrf @method('delete')<button class="btn btn-white btn-sm text-danger"><i class="bi-trash"></i></button></form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">@lang('No reviews yet. Customers see a "Rate" button on completed orders.')</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reviews->hasPages())<div class="card-footer">{{ $reviews->appends(['tab' => 'reviews'])->links() }}</div>@endif
            </div>
        @endif

        {{-- ================= SETTINGS ================= --}}
        @if($tab === 'settings')
            <form method="post" action="{{ route('admin.growth.settings.save') }}" style="max-width: 860px" class="d-grid gap-4">
                @csrf
                <div class="card"><div class="card-body">
                    <h4 class="mb-3"><i class="bi-whatsapp text-success me-1"></i> @lang('WhatsApp support button')</h4>
                    <div class="row g-3">
                        <div class="col-md-5"><label class="form-label">@lang('WhatsApp number (with country code)')</label>
                            <input class="form-control" name="whatsapp_number" value="{{ $settings['whatsapp.number'] ? '+' . $settings['whatsapp.number'] : '' }}" placeholder="+254 712 345 678"></div>
                        <div class="col-md-7"><label class="form-label">@lang('Pre-filled message')</label>
                            <input class="form-control" name="whatsapp_message" value="{{ $settings['whatsapp.message'] }}" maxlength="200"></div>
                    </div>
                    <small class="text-muted">@lang('A green chat button appears on the website and the customer dashboard. Leave the number empty to hide it.')</small>
                </div></div>

                <div class="card"><div class="card-body">
                    <h4 class="mb-3"><i class="bi-bell me-1"></i> @lang('Notifications')</h4>
                    <div class="form-check form-switch mb-2"><input class="form-check-input" type="checkbox" name="notify_order_completed" value="1" id="nOrder" @checked($settings['notify.order_completed'])>
                        <label class="form-check-label" for="nOrder">@lang('Tell customers when an order is completed (dashboard bell + email, and SMS if enabled in the "Order Completed" template)')</label></div>
                    <div class="form-check form-switch mb-2"><input class="form-check-input" type="checkbox" name="report_daily" value="1" id="nReport" @checked($settings['report.daily'])>
                        <label class="form-check-label" for="nReport">@lang('Send me a daily report every morning (sales, profit, new customers, failed orders) to the alert email on the dashboard')</label></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="reviews_enabled" value="1" id="nReviews" @checked($settings['reviews.enabled'])>
                        <label class="form-check-label" for="nReviews">@lang('Let customers review completed orders')</label></div>
                </div></div>

                <div class="card"><div class="card-body">
                    <h4 class="mb-3"><i class="bi-gift me-1"></i> @lang('Free trial for new customers')</h4>
                    <div class="form-check form-switch mb-3"><input class="form-check-input" type="checkbox" name="trial_enabled" value="1" id="tOn" @checked($settings['trial.enabled'])>
                        <label class="form-check-label" for="tOn">@lang('Offer one free trial order to each new customer')</label></div>
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label">@lang('Trial service')</label>
                            <select class="form-select" name="trial_service_id"><option value="">@lang('Choose a cheap service, e.g. likes or views')</option>
                                @foreach($services as $s)<option value="{{ $s->id }}" @selected($settings['trial.service_id'] == $s->id)>#{{ $s->id }} · {{ \Illuminate\Support\Str::limit($s->service_title, 70) }} ({{ $s->price }}/1k)</option>@endforeach
                            </select></div>
                        <div class="col-md-4"><label class="form-label">@lang('Quantity')</label>
                            <input class="form-control" type="number" min="1" name="trial_quantity" value="{{ $settings['trial.quantity'] }}"></div>
                    </div>
                    <small class="text-muted">@lang('One trial per account, per phone number and per internet connection. You pay the provider cost.')</small>
                </div></div>

                <div class="card"><div class="card-body">
                    <h4 class="mb-3"><i class="bi-shield-check me-1"></i> @lang('Protection')</h4>
                    <div class="form-check form-switch mb-2"><input class="form-check-input" type="checkbox" name="block_disposable" value="1" id="fDisp" @checked($settings['fraud.block_disposable'])>
                        <label class="form-check-label" for="fDisp">@lang('Block sign-ups with throwaway email addresses (mailinator, 10minutemail, ...)')</label></div>
                    <div class="d-flex align-items-center gap-2 mb-3"><span>@lang('Max new accounts per internet connection per day')</span>
                        <input class="form-control" style="max-width: 90px" type="number" min="0" max="100" name="signups_per_ip_day" value="{{ $settings['fraud.signups_per_ip_day'] }}"><small class="text-muted">(@lang('0 = no limit'))</small></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="loss_auto_disable" value="1" id="fLoss" @checked($settings['loss.auto_disable'])>
                        <label class="form-check-label" for="fLoss">@lang('Loss protection: switch a service off automatically if the provider price rises above your selling price (checked every hour)')</label></div>
                </div></div>

                <div><button class="btn btn-primary">@lang('Save settings')</button></div>
            </form>
        @endif
    </div>
@endsection
