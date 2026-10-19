@extends(template() . 'layouts.app')
@section('title', $heading)
@section('content')
    @php
        $buyUrl = fn($id) => auth()->check() ? route('user.order.create', ['serviceId' => $id]) : route('register');
        $where = $countryName ? __('in') . ' ' . $countryName : '';
    @endphp
    <section class="zk-land">
        <div class="container">
            <nav class="zk-land__crumbs" aria-label="@lang('Breadcrumb')">
                <a href="{{ url('/') }}">@lang('Home')</a> › <a href="{{ route('landing.index') }}">@lang('Buy')</a> ›
                @if($country)<a href="{{ route('landing.show', $category->slug) }}">{{ $category->category_title }}</a> › <span>{{ $countryName }}</span>
                @else<span>{{ $category->category_title }}</span>@endif
            </nav>

            <div class="zk-land__hero">
                <div>
                    <span class="zk-eyebrow">{{ $platform ?: __('Social media') }}</span>
                    <h1 class="zk-title">{{ $heading }}</h1>
                    <p class="zk-lead">
                        @lang('Grow faster with') {{ strtolower($category->category_title) }} {{ $where }}:
                        @lang('orders start in minutes, you only need your public link (never your password), and you can pay with M-Pesa, card or bank transfer.')
                    </p>
                    <ul class="zk-land__ticks">
                        <li><i class="fa-solid fa-bolt"></i> @lang('Instant start')</li>
                        <li><i class="fa-solid fa-lock"></i> @lang('No password needed')</li>
                        <li><i class="fa-solid fa-rotate"></i> @lang('Refill options')</li>
                        <li><i class="fa-solid fa-headset"></i> @lang('24/7 support')</li>
                    </ul>
                </div>
                <div class="zk-land__cta">
                    <span>@lang('From')</span>
                    <strong class="notranslate">{{ $price(optional($category->service->first())->price) }}</strong>
                    <span>@lang('per 1,000')</span>
                    <a class="zk-btn zk-btn--primary zk-btn--lg w-100 mt-3" href="{{ $buyUrl(optional($category->service->first())->id) }}">@lang('Order now')</a>
                </div>
            </div>

            <h2 class="zk-land__h2">@lang('Choose your package')</h2>
            <div class="zk-land__list">
                @foreach($category->service as $s)
                    <div class="zk-land__item">
                        <div class="zk-land__name">
                            <strong>{{ \App\Services\SmartImport::cleanName($s->service_title) }}</strong>
                            <span>@lang('Min') {{ number_format($s->min_amount) }} · @lang('Max') {{ number_format($s->max_amount) }}
                                @if($s->refill) · <em class="zk-land__refill">@lang('Refill')</em>@endif</span>
                        </div>
                        <div class="zk-land__price notranslate">{{ $price($s->price) }} <small>/ 1k</small></div>
                        <a class="zk-btn zk-btn--primary zk-btn--sm" href="{{ $buyUrl($s->id) }}">@lang('Buy')</a>
                    </div>
                @endforeach
            </div>

            <div class="row g-4 mt-2">
                <div class="col-lg-7">
                    <h2 class="zk-land__h2">@lang('How it works')</h2>
                    <ol class="zk-land__steps">
                        <li><strong>@lang('Create a free account')</strong> @lang('with your username, email and phone.')</li>
                        <li><strong>@lang('Add funds')</strong> @lang('with M-Pesa, card or bank, from a small amount.')</li>
                        <li><strong>@lang('Paste your link')</strong> @lang('and choose the quantity. Delivery starts automatically.')</li>
                    </ol>
                    <h2 class="zk-land__h2">@lang('Questions')</h2>
                    <details class="zk-faq__item" open><summary><span>@lang('Is it safe for my account?')</span><i class="fa-regular fa-plus"></i></summary>
                        <div class="zk-faq__answer"><p>@lang('We never ask for your password. We only need the public link to your post or profile, and delivery is gradual on most services.')</p></div></details>
                    <details class="zk-faq__item"><summary><span>@lang('How fast will it start?')</span><i class="fa-regular fa-plus"></i></summary>
                        <div class="zk-faq__answer"><p>@lang('Most orders start within minutes. Each package shows its speed in the name, for example 10k/day.')</p></div></details>
                    <details class="zk-faq__item"><summary><span>@lang('What if the numbers drop?')</span><i class="fa-regular fa-plus"></i></summary>
                        <div class="zk-faq__answer"><p>@lang('Packages marked Refill are topped up for free during the refill period. Just press Refill in your orders.')</p></div></details>
                </div>
                <div class="col-lg-5">
                    @if($related->isNotEmpty())
                        <h2 class="zk-land__h2">@lang('More for') {{ $platform }}</h2>
                        <div class="zk-land__links">
                            @foreach($related as $r)<a href="{{ route('landing.show', array_filter([$r->slug, $country])) }}">{{ $r->category_title }}</a>@endforeach
                        </div>
                    @endif
                    <h2 class="zk-land__h2 mt-4">@lang('Available in')</h2>
                    <div class="zk-land__links">
                        @foreach($countries as $slugC => [$name])<a href="{{ route('landing.show', [$category->slug, $slugC]) }}">{{ ucfirst(preg_replace('/^the /', '', $name)) }}</a>@endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org', '@type' => 'Product', 'name' => $heading, 'brand' => basicControl()->site_title,
            'offers' => ['@type' => 'AggregateOffer', 'lowPrice' => (float) optional($category->service->first())->price, 'priceCurrency' => basicControl()->base_currency, 'offerCount' => $category->service->count()],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection
