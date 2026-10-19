<!-- Hero section start -->
@php
    $zkFeaturedPlatforms = \App\Models\SocialMedia::where('status', 1)
        ->whereHas('category', fn($q) => $q->where('status', 1)->whereHas('service', fn($s) => $s->where('service_status', 1)))
        ->orderBy('sort_by')->take(6)->get();
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
<section class="zk-hero zk-hero--v3 zk-hero--discovery">
    <div class="zk-hero__glow" aria-hidden="true"><span></span><span></span><span></span></div>
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="zk-hero__content">
                    <div class="zk-trust">
                        <span class="zk-trust__stars" aria-hidden="true"><i class="fa-solid fa-sparkles"></i></span>
                        <span class="zk-trust__text">@lang('For creators, brands & resellers')</span>
                    </div>

                    <h1 class="zk-hero__title">@lang('Your next move.')<br><span class="zk-hl">@lang('More reach.')</span></h1>
                    <p class="zk-hero__desc">@lang('Discover social media services for your favourite platforms. Compare clear prices, place an order, and follow its progress in one simple dashboard.')</p>

                    <div class="zk-hero__actions">
                        @guest
                            <a href="{{ route('guest.order') }}" class="zk-btn zk-btn--primary zk-btn--pill zk-btn--glow">
                                @lang('Order without account') <i class="fa-regular fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('register') }}" class="zk-btn zk-btn--outline zk-btn--pill">@lang('Create account')</a>
                        @else
                            <a href="{{ route('user.order.create') }}" class="zk-btn zk-btn--primary zk-btn--pill zk-btn--glow">
                                @lang('Buy services') <i class="fa-regular fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('user.dashboard') }}" class="zk-btn zk-btn--outline zk-btn--pill">@lang('Dashboard')</a>
                        @endguest
                    </div>

                    <a class="zk-rental-invite" href="{{ route('user.panels.index') }}"><span class="zk-rental-invite__icon" aria-hidden="true"><i class="fa-regular fa-store"></i></span><span><strong>@lang('Rent your own panel')</strong><small>@lang('Your brand, your prices. Powered by us.')</small></span><i class="fa-regular fa-arrow-up-right" aria-hidden="true"></i></a>
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
                    <p class="zk-hero__microcopy"><i class="fa-solid fa-lock-keyhole"></i> @lang('Transparent pricing') <span></span> @lang('No subscriptions') <span></span> @lang('Secure checkout')</p>
                    <a class="zk-hero__how" href="#how-it-works">@lang('New here? See how it works') <i class="fa-regular fa-arrow-down" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zk-discover">
                    <div class="zk-discover__top"><span><i class="fa-solid fa-sparkles" aria-hidden="true"></i> @lang('Find your next opportunity')</span><span class="zk-discover__dot" aria-hidden="true"></span></div>
                    <h2>@lang('Where do you want to grow?')</h2>
                    <p>@lang('Explore the platforms in our service catalogue.')</p>
                    <div class="zk-discover__grid">
                        @foreach($zkFeaturedPlatforms as $platform)
                            @php($brand = zkBrand($platform->name))
                            <a href="#zk-ptab-{{ $platform->id }}" data-zk-discover="{{ $platform->id }}">
                                <i class="{{ $brand ? 'fa-brands fa-'.$brand[0] : 'fa-regular fa-globe' }}" style="color: {{ $brand[1] ?? '#2563eb' }}" aria-hidden="true"></i>
                                <span>{{ $platform->name }}</span><i class="fa-regular fa-arrow-up-right" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                    <a class="zk-discover__all" href="{{ route('services') }}">@lang('Explore all services') <i class="fa-regular fa-arrow-right" aria-hidden="true"></i></a>
                    <div class="zk-discover__foot"><i class="fa-regular fa-shield-check" aria-hidden="true"></i> @lang('Your social account password stays yours.')</div>
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
<script>
    document.querySelectorAll('[data-zk-discover]').forEach(link => {
        link.addEventListener('click', event => {
            const tab = document.getElementById('zk-ptab-' + link.dataset.zkDiscover);
            if (!tab) return;
            event.preventDefault();
            tab.click();
            tab.scrollIntoView({behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'center'});
            tab.focus({preventScroll: true});
        });
    });
</script>
