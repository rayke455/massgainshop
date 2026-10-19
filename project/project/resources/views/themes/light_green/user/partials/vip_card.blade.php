{{-- VIP level: current discount and progress to the next level. --}}
@php
    $zkVip = \App\Services\Growth::vip(auth()->user());
    $zkLevel = $zkVip['level'];
    $zkNext = $zkVip['next'];
    $zkSpent = $zkVip['spent'];
    $zkFrom = $zkLevel ? (float) $zkLevel->min_spent : 0;
    $zkPct = $zkNext ? max(3, min(100, ($zkSpent - $zkFrom) / max(0.0001, (float) $zkNext->min_spent - $zkFrom) * 100)) : 100;
@endphp
@if($zkLevel || $zkNext)
    <div class="zk-vip mb-4" style="--vip: {{ $zkLevel->color ?? '#2563eb' }}">
        <div class="zk-vip__badge"><i class="fa-solid fa-crown"></i></div>
        <div class="zk-vip__body">
            <div class="zk-vip__top">
                <strong>{{ $zkLevel->name ?? __('Member') }} @lang('member')</strong>
                @if($zkLevel && $zkLevel->discount_percent > 0)
                    <span class="zk-vip__off">{{ rtrim(rtrim(number_format($zkLevel->discount_percent, 2), '0'), '.') }}% @lang('off every order')</span>
                @endif
            </div>
            @if($zkNext)
                <div class="zk-vip__bar" role="progressbar" aria-valuenow="{{ round($zkPct) }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $zkPct }}%"></span></div>
                <small>
                    @lang('Spend') <b class="notranslate">{{ zkShowMoney(max(0, (float) $zkNext->min_spent - $zkSpent)) }}</b> @lang('more to reach')
                    <b>{{ $zkNext->name }}</b> ({{ rtrim(rtrim(number_format($zkNext->discount_percent, 2), '0'), '.') }}% @lang('off')).
                </small>
            @else
                <small>@lang('You have reached the top level. Thank you for being a loyal customer!')</small>
            @endif
        </div>
    </div>
@endif
