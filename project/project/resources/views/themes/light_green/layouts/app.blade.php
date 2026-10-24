<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteTitle = basicControl()->site_title ?: 'Mass Fame Shop';
        $defaultPageTitle = 'Best SMM Panel in Africa & Kenya | ' . $siteTitle . ' - Cheapest SMM Provider';
        $defaultMetaDesc = $siteTitle . ' is Africa and Kenya\'s #1 best and cheapest SMM panel. Buy real Instagram followers, likes, TikTok views, YouTube watch hours and Facebook likes with instant automated M-Pesa, card or crypto checkout 24/7.';
        $defaultKeywords = 'best smm in africa, best smm panel kenya, cheapest smm panel kenya, smm panel mpesa, smm provider africa, buy instagram followers kenya, buy tiktok views kenya, youtube subscribers kenya, smm reseller panel africa, cheap smm panel worldwide, mass fame shop';
        
        $currentTitle = (isset($pageSeo['meta_title']) && !empty($pageSeo['meta_title'])) 
            ? trans($pageSeo['meta_title']) 
            : ((isset($pageSeo['page_title']) && !empty($pageSeo['page_title'])) 
                ? trans($pageSeo['page_title']) . ' | ' . $siteTitle 
                : $defaultPageTitle);
            
        $currentDesc = (isset($pageSeo['meta_description']) && !empty($pageSeo['meta_description'])) 
            ? trans($pageSeo['meta_description']) 
            : $defaultMetaDesc;
            
        $currentKeywords = (isset($pageSeo['meta_keywords']) && !empty($pageSeo['meta_keywords'])) 
            ? trans($pageSeo['meta_keywords']) 
            : $defaultKeywords;
            
        $currentRobots = (isset($pageSeo['meta_robots']) && !empty($pageSeo['meta_robots'])) 
            ? trans($pageSeo['meta_robots']) 
            : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
            
        $ogImage = (isset($pageSeo['meta_image']) && !empty($pageSeo['meta_image']))
            ? $pageSeo['meta_image']
            : ((isset($pageSeo['seo_meta_image']) && !empty($pageSeo['seo_meta_image']))
                ? trans($pageSeo['seo_meta_image'])
                : getFile(basicControl()->logo_driver, basicControl()->logo));
    @endphp

    <title>{{ $currentTitle }}</title>
    <meta name="title" content="{{ $currentTitle }}">
    <meta name="author" content="{{ $siteTitle }}">
    <meta name="description" content="{{ $currentDesc }}">
    <meta name="keywords" content="{{ $currentKeywords }}">
    <meta name="robots" content="{{ $currentRobots }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="google-site-verification" content="uSOPqQv5Gt-8m_dQ0ynKkvBZyJJXHFAq9xEKvNEf1pk">

    <!-- Regional & Geo-targeting for Kenya, Africa, and Global Search -->
    <meta name="geo.region" content="KE">
    <meta name="geo.placename" content="Nairobi, Kenya, Africa">
    <meta name="geo.position" content="-1.286389;36.817223">
    <meta name="ICBM" content="-1.286389, 36.817223">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteTitle }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $currentTitle }}">
    <meta property="og:description" content="{{ $currentDesc }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $currentTitle }}">
    <meta name="twitter:description" content="{{ $currentDesc }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <!-- Schema.org JSON-LD Structured Data for Google SERP Rich Results & SMM Rankings -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebSite",
                "@id": "{{ url('/') }}/#website",
                "url": "{{ url('/') }}",
                "name": "{{ $siteTitle }}",
                "description": "Africa and Kenya's #1 Best and Cheapest SMM Panel with instant M-Pesa automated delivery.",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ route('services') }}?search={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "Organization",
                "@id": "{{ url('/') }}/#organization",
                "name": "{{ $siteTitle }}",
                "alternateName": [
                    "Best SMM Panel in Africa",
                    "Best SMM Panel Kenya",
                    "MassFameShop"
                ],
                "url": "{{ url('/') }}",
                "logo": "{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}",
                "description": "Leading SMM panel provider in Africa & Kenya. Buy cheap, real social media growth services with automated M-Pesa, card, and crypto payment.",
                "areaServed": [
                    {"@type": "Country", "name": "Kenya"},
                    {"@type": "Continent", "name": "Africa"},
                    {"@type": "Country", "name": "Nigeria"},
                    {"@type": "Country", "name": "Tanzania"},
                    {"@type": "Country", "name": "Uganda"},
                    {"@type": "Country", "name": "South Africa"},
                    {"@type": "Country", "name": "Ghana"},
                    {"@type": "Country", "name": "Rwanda"},
                    {"@type": "Place", "name": "Worldwide"}
                ],
                "paymentAccepted": [
                    "M-Pesa",
                    "Lipa Na M-Pesa",
                    "Credit Card",
                    "Debit Card",
                    "Cryptocurrency",
                    "Airtel Money",
                    "Bank Transfer"
                ],
                "currenciesAccepted": "KES, USD, NGN, TZS, UGX, ZAR, GHS, EUR",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "4.9",
                    "reviewCount": "1950",
                    "bestRating": "5",
                    "worstRating": "1"
                }
            },
            {
                "@type": "FAQPage",
                "@id": "{{ url()->current() }}/#faq",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "What is the best SMM panel in Africa and Kenya?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Mass Fame Shop (massfameshop.com) is the #1 best and cheapest SMM panel in Africa and Kenya, providing automated instant delivery for Instagram, TikTok, YouTube, Telegram, and Facebook services with 24/7 customer support."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Can I pay for SMM services using M-Pesa in Kenya?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Yes! Mass Fame Shop supports instant automated Lipa Na M-Pesa STK push and Paybill. Your payment is verified instantly and your order begins delivery within minutes."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "How fast are social media orders delivered?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Most orders on Mass Fame Shop start within 1 to 5 minutes of payment confirmation. Full delivery completes smoothly based on your selected package speed."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Do I need to share my social media password?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "No! We never ask for your password or sensitive account access. Only your public username or post link is required."
                        }
                    }
                ]
            }
        ]
    }
    </script>

    <link rel="shortcut icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}"
          type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/style.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/zirka.css') }}?v={{ filemtime(base_path(template(true). 'css/zirka.css')) }}">
    <script>document.documentElement.classList.add('zk-js');</script>

    @stack('css-lib')

    @unless(app()->bound('rental.panel'))
        @laravelPWA
    @endunless

</head>

<body class="{{ session()->get('rtl') == 1 ? 'rtl' : '' }}">


@include(template().'partials.header')

@include(template().'partials.banner')

@yield('content')

<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/owl.carousel.min.js') }}"></script>

<script src="{{ asset(template(true). 'js/select2.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/fancybox.umd.js') }}"></script>
<script src="{{ asset(template(true). 'js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>

@stack('js-lib')

<script src="{{ asset(template(true). 'js/main.js') }}"></script>
<script>
    "use strict";
    (function () {
        const items = document.querySelectorAll('.zk-reveal');
        if (!('IntersectionObserver' in window)) {
            items.forEach(el => el.classList.add('is-in'));
            return;
        }
        const io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-in');
                    io.unobserve(entry.target);
                }
            });
        }, {rootMargin: '0px 0px -8% 0px'});
        items.forEach(el => io.observe(el));
    })();
</script>

@stack('script')

@include(template().'partials.api_docs_script')

@if (session()->has('success'))
    <script>
        Notiflix.Notify.success("@lang(session('success'))");
        let svgPath = document.querySelector('.nx-message-icon g path');
        if (svgPath) {
            svgPath.setAttribute('fill', '#fff');
        }
    </script>
@endif

@if (session()->has('error'))
    <script>
        Notiflix.Notify.failure("@lang(session('error'))");
        let svgPath = document.querySelector('.nx-message-icon g path');
        if (svgPath) {
            svgPath.setAttribute('fill', '#fff');
        }
    </script>
@endif

@if (session()->has('warning'))
    <script>
        Notiflix.Notify.warning("@lang(session('warning'))");
        let svgPath = document.querySelector('.nx-message-icon g path');
        if (svgPath) {
            svgPath.setAttribute('fill', '#fff');
        }
    </script>
@endif

@include('plugins')
@include(template().'partials.rental_brand')

@unless(app()->bound('rental.panel'))
    @include(template().'partials.pwa')
@endunless
@include(template().'partials.whatsapp_button')
@include(template().'partials.geo')
@include(template().'partials.auto_translate')
@include(template().'partials.auto_currency')
@if(request()->is('/'))
    @include(template().'partials.activity_popup')
@endif

</body>

</html>


