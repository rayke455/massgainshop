@php
    $pageHeading = $countryName 
        ? __('Best SMM Panel in') . ' ' . $countryName 
        : __('Best SMM Panel in Africa & Kenya');
    $pageLead = $country === 'kenya'
        ? __('Africa & Kenya\'s #1 cheapest automated SMM panel. Buy real Instagram followers, likes, TikTok views, YouTube watch hours with instant Lipa Na M-Pesa STK push.')
        : ($country === 'africa'
            ? __('The leading social media marketing provider across Africa. Instant delivery for Kenya, Nigeria, South Africa, Ghana, Tanzania, Uganda & worldwide.')
            : __('Pick your platform and package. Every service includes live transparent pricing, gradual natural delivery, and automated 24/7 start.'));
@endphp

@extends(template() . 'layouts.app')
@section('title', $pageHeading)
@section('content')
    <section class="zk-land">
        <div class="container">
            <nav class="zk-land__crumbs" aria-label="@lang('Breadcrumb')">
                <a href="{{ url('/') }}">@lang('Home')</a> › 
                @if($countryName)
                    <a href="{{ route('landing.index') }}">@lang('All Services')</a> › <span>{{ $countryName }}</span>
                @else
                    <span>@lang('All Services')</span>
                @endif
            </nav>

            <div class="zk-head">
                <span class="zk-eyebrow">{{ $countryName ? __('Regional Hub') . ' · ' . $countryName : __('Africa & Global SMM Hub') }}</span>
                <h1 class="zk-title">{!! zkHighlight($countryName ? __('Best SMM in [') . $countryName . __(']') : __('Best SMM in [Africa, Kenya] & Worldwide')) !!}</h1>
                <p class="zk-lead">{{ $pageLead }}</p>

                @if($country === 'kenya' || !$country)
                    <div class="d-inline-flex flex-wrap gap-2 mt-3">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill"><i class="fa-solid fa-bolt me-1"></i> @lang('Instant M-Pesa STK Push')</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill"><i class="fa-solid fa-shield-check me-1"></i> @lang('100% Safe (No Password)')</span>
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-2 rounded-pill"><i class="fa-solid fa-headset me-1"></i> @lang('24/7 WhatsApp & Ticket Support')</span>
                    </div>
                @endif
            </div>

            <!-- Regional Market Switcher Pills for Instant Crawling & High SERP Visibility -->
            <div class="mb-5 pb-3 border-bottom">
                <h3 class="h6 text-muted mb-3 text-uppercase fw-bold"><i class="fa-regular fa-globe me-1"></i> @lang('Select Regional Market / Country')</h3>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('landing.index') }}" class="btn btn-sm {{ !$country ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">@lang('All (Global)')</a>
                    @foreach($countries as $cSlug => [$cTitle])
                        <a href="{{ url('/buy/' . $cSlug) }}" class="btn btn-sm {{ $country === $cSlug ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-3">
                            {{ ucfirst(preg_replace('/^the /i', '', $cTitle)) }}
                        </a>
                    @endforeach
                </div>
            </div>

            @foreach($groups as $platform => $cats)
                <h2 class="zk-land__h2">{{ $platform }}</h2>
                <div class="zk-land__links mb-4">
                    @foreach($cats as $c)
                        <a href="{{ $country ? route('landing.show', [$c->slug, $country]) : route('landing.show', $c->slug) }}">
                            {{ $c->category_title }} <small>{{ $c->service->count() }}</small>
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <!-- BreadcrumbList Schema for Google Search Engine -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "{{ url('/') }}"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $pageHeading }}",
                "item": "{{ url()->current() }}"
            }
        ]
    }
    </script>
@endsection
