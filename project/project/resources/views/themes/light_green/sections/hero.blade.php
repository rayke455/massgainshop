<!-- Hero section start -->
@php
    $heroMedia = @$hero['single']['media'];
    // Real numbers only — nothing invented.
    $zkServiceCount = \App\Models\Service::where('service_status', 1)->count();
    $zkPlatformCount = \App\Models\SocialMedia::where('status', 1)->whereHas('category', fn($q) => $q->where('status', 1))->count();
    $zkRibbon = [
        ['instagram', 'Instagram', '#E1306C'], ['tiktok', 'TikTok', '#111111'], ['youtube', 'YouTube', '#FF0000'],
        ['facebook-f', 'Facebook', '#1877F2'], ['telegram', 'Telegram', '#229ED9'], ['twitter', 'X (Twitter)', '#0F172A'],
        ['spotify', 'Spotify', '#1DB954'], ['linkedin-in', 'LinkedIn', '#0A66C2'], ['twitch', 'Twitch', '#9146FF'],
        ['snapchat', 'Snapchat', '#E6C200'], ['discord', 'Discord', '#5865F2'], ['whatsapp', 'WhatsApp', '#25D366'],
    ];
@endphp
<section class="zk-hero zk-hero--v3">
    <div class="zk-hero__glow" aria-hidden="true"><span></span><span></span><span></span></div>
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="zk-hero__content">
                    <div class="zk-trust">
                        <span class="zk-trust__stars" aria-hidden="true">
                            @for($i = 0; $i < 5; $i++)<i class="fa-solid fa-star"></i>@endfor
                        </span>
                        <span class="zk-trust__text">@lang(@$hero['single']['title'] ?: 'Loved by creators, brands & resellers')</span>
                    </div>

                    <h1 class="zk-hero__title">{!! zkHighlight(__(@$hero['single']['heading'])) !!}</h1>
                    <p class="zk-hero__desc">@lang(@$hero['single']['short_description'])</p>

                    <div class="zk-hero__actions">
                        @guest
                            <a href="{{ route('register') }}" class="zk-btn zk-btn--primary zk-btn--pill zk-btn--glow">
                                @lang(@$hero['single']['button_name'] ?: 'Sign up free') <i class="fa-regular fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('services') }}" class="zk-btn zk-btn--outline zk-btn--pill">@lang('See prices')</a>
                        @else
                            <a href="{{ route('user.order.create') }}" class="zk-btn zk-btn--primary zk-btn--pill zk-btn--glow">
                                @lang('Buy services') <i class="fa-regular fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('user.dashboard') }}" class="zk-btn zk-btn--outline zk-btn--pill">@lang('Dashboard')</a>
                        @endguest
                    </div>

                    <ul class="zk-hero__facts">
                        {{-- Real counts, shown once they're big enough to impress (small numbers look weak on a sales page). --}}
                        @if($zkServiceCount >= 50)
                            <li><strong>{{ number_format($zkServiceCount) }}+</strong><span>@lang('services')</span></li>
                        @endif
                        @if($zkPlatformCount >= 5)
                            <li><strong>{{ $zkPlatformCount }}</strong><span>@lang('platforms')</span></li>
                        @endif
                        @if($zkServiceCount < 50 || $zkPlatformCount < 5)
                            <li><strong>@lang('No')</strong><span>@lang('password needed')</span></li>
                        @endif
                        <li><strong>@lang('Minutes')</strong><span>@lang('to start')</span></li>
                        <li><strong>24/7</strong><span>@lang('support')</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zk-hero__visual">
                    <div class="zk-hero__frame">
                        <div class="zk-media">
                            <img src="{{ getFile(@$heroMedia->image->driver, @$heroMedia->image->path) }}"
                                 alt="@lang(strip_tags(str_replace(['[', ']'], '', @$hero['single']['heading'])))" fetchpriority="high">
                        </div>
                    </div>
                    <div class="zk-chip zk-chip--a" aria-hidden="true">
                        <span class="zk-chip__icon"><i class="fa-solid fa-bolt"></i></span>
                        <span><strong>@lang('Instant start')</strong><small>@lang('Orders begin automatically')</small></span>
                    </div>
                    <div class="zk-chip zk-chip--b" aria-hidden="true">
                        <span class="zk-chip__icon zk-chip__icon--green"><i class="fa-solid fa-shield-check"></i></span>
                        <span><strong>@lang('No password needed')</strong><small>@lang('Just paste your link')</small></span>
                    </div>
                    <div class="zk-chip zk-chip--c" aria-hidden="true">
                        <span class="zk-chip__icon zk-chip__icon--gold"><i class="fa-solid fa-rotate"></i></span>
                        <span><strong>@lang('Refill guarantee')</strong><small>@lang('On eligible services')</small></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scrolling ribbon of platforms we cover --}}
    <div class="zk-ribbon" aria-label="@lang('Platforms we support')">
        <div class="zk-ribbon__track">
            @foreach([0, 1] as $copy)
                @foreach($zkRibbon as [$icon, $name, $color])
                    <span class="zk-ribbon__item" style="--c: {{ $color }}" @if($copy) aria-hidden="true" @endif>
                        <i class="fa-brands fa-{{ $icon }}"></i>{{ $name }}
                    </span>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
<!-- Hero section end -->
