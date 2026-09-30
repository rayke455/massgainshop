{{-- Site health: cron heartbeat + provider balances. Shown at the top of the admin dashboard. --}}
@php
    $zkCronLast = \Illuminate\Support\Facades\Cache::get('zk_cron_last_run');
    $zkCronOk = $zkCronLast && (now()->timestamp - $zkCronLast) < 15 * 60;
    $zkProviders = \App\Models\ApiProvider::where('status', 1)->get();
    $zkLow = $zkProviders->filter(fn($p) => $p->low_balance_alert > 0 && (float) $p->balance < (float) $p->low_balance_alert);
    $zkPhp = PHP_OS_FAMILY === 'Windows' ? 'php' : '/usr/local/bin/php';
    $zkCronCmd = '* * * * * cd ' . base_path() . ' && ' . $zkPhp . ' artisan schedule:run >> /dev/null 2>&1';
@endphp

@if($zkLow->isNotEmpty())
    <div class="alert alert-soft-danger d-flex flex-wrap align-items-center gap-3 mb-4" role="alert">
        <i class="bi-exclamation-octagon-fill fs-2"></i>
        <div class="flex-grow-1">
            <h4 class="alert-heading mb-1">@lang('Provider balance is running low')</h4>
            @foreach($zkLow as $p)
                <div>
                    <strong>{{ $p->api_name }}</strong>: {{ rtrim(rtrim(number_format((float) $p->balance, 2), '0'), '.') }} {{ $p->currency }} @lang('left')
                    <span class="text-muted">(@lang('alert below') {{ rtrim(rtrim(number_format((float) $p->low_balance_alert, 2), '0'), '.') }})</span>
                </div>
            @endforeach
            <small>@lang('Top up now, otherwise new customer orders will fail.')</small>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @foreach($zkLow as $p)
                @php $zkHost = parse_url($p->url, PHP_URL_SCHEME) . '://' . parse_url($p->url, PHP_URL_HOST); @endphp
                <a href="{{ $zkHost }}" target="_blank" rel="noopener" class="btn btn-sm btn-danger"><i class="bi-box-arrow-up-right"></i> @lang('Top up') {{ $p->api_name }}</a>
            @endforeach
        </div>
    </div>
@endif

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="d-flex gap-3">
                    <span class="avatar avatar-sm avatar-circle {{ $zkCronOk ? 'avatar-soft-success' : 'avatar-soft-warning' }}">
                        <span class="avatar-initials"><i class="{{ $zkCronOk ? 'bi-check-lg' : 'bi-clock-history' }}"></i></span>
                    </span>
                    <div class="flex-grow-1">
                        <h5 class="mb-1">@lang('Automatic jobs (cron)')</h5>
                        @if($zkCronOk)
                            <p class="mb-0 small text-success">@lang('Running'). @lang('Last run') {{ \Carbon\Carbon::createFromTimestamp($zkCronLast)->diffForHumans() }}.</p>
                            <p class="mb-0 small text-body">@lang('Order statuses, refunds, refills, currency rates and balance checks update automatically.')</p>
                        @else
                            <p class="mb-1 small text-warning fw-semibold">
                                {{ $zkCronLast ? __('Stopped. Last run') . ' ' . \Carbon\Carbon::createFromTimestamp($zkCronLast)->diffForHumans() . '.' : __('Not set up yet.') }}
                            </p>
                            <p class="mb-2 small text-body">@lang('Without it, order statuses, refunds and currency rates never update. In cPanel → Cron Jobs → "Once per minute", add:')</p>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control font-monospace" readonly value="{{ $zkCronCmd }}" id="zkCronCmd">
                                <button class="btn btn-white" type="button" onclick="navigator.clipboard.writeText(document.getElementById('zkCronCmd').value); this.textContent='@lang('Copied')';">@lang('Copy')</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <h5 class="mb-0">@lang('Provider balances')</h5>
                    <form action="{{ route('admin.system.check.balances') }}" method="post"
                          onsubmit="this.querySelector('button').disabled = true;">
                        @csrf
                        <button class="btn btn-xs btn-white" type="submit"><i class="bi-arrow-clockwise"></i> @lang('Check now')</button>
                    </form>
                </div>
                @forelse($zkProviders as $p)
                    @php $isLow = $zkLow->contains('id', $p->id); @endphp
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom small">
                        <span><strong>{{ $p->api_name }}</strong>
                            <span class="text-muted">· @lang('checked') {{ $p->balance_checked_at ? $p->balance_checked_at->diffForHumans() : __('never') }}</span></span>
                        <span class="{{ $isLow ? 'text-danger fw-semibold' : 'text-success fw-semibold' }}">
                            {{ rtrim(rtrim(number_format((float) $p->balance, 2), '0'), '.') ?: '0' }} {{ $p->currency }}
                            <a href="{{ route('admin.api-provider.edit', $p->id) }}" class="text-muted ms-1" title="@lang('Alert below') {{ $p->low_balance_alert }}"><i class="bi-bell"></i> {{ rtrim(rtrim(number_format((float) $p->low_balance_alert, 2), '0'), '.') }}</a>
                        </span>
                    </div>
                @empty
                    <p class="small text-muted mb-0">@lang('No active providers yet.')</p>
                @endforelse
                <form action="{{ route('admin.system.alert.email') }}" method="post" class="d-flex gap-2 mt-3">
                    @csrf
                    <input type="email" name="alert_email" class="form-control form-control-sm" placeholder="@lang('Email for low-balance alerts')"
                           value="{{ basicControl()->alert_email ?? '' }}">
                    <button class="btn btn-sm btn-white text-nowrap" type="submit">@lang('Save')</button>
                </form>
            </div>
        </div>
    </div>
</div>
