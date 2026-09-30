<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang(basicControl()->site_title) | @if(isset($pageSeo['page_title']))
            @lang($pageSeo['page_title'])
        @else
            @yield('title')
        @endif
    </title>

    <meta name="title" content="{{ isset($pageSeo['meta_title']) ? trans($pageSeo['meta_title']) : ''  }}">
    <meta name="author" content="{{ basicControl()->site_title }}">
    <meta name="description"
          content="{{ isset($pageSeo['meta_description']) ? trans($pageSeo['meta_description']) : '' }}">
    <meta name="keywords" content="{{ isset($pageSeo['meta_keywords']) ? trans($pageSeo['meta_keywords']) : '' }}">
    <meta name="robots" content="{{ isset($pageSeo['meta_robots']) ? trans($pageSeo['meta_robots']) : '' }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ basicControl()->site_title }}">
    <meta property="og:title" content="{{ isset($pageSeo['meta_title']) ? trans($pageSeo['meta_title']) : "" }}">
    <meta property="og:description"
          content="{{ isset($pageSeo['og_description']) ? trans($pageSeo['og_description']) : "" }}">
    <meta property="og:image" content="{{ isset($pageSeo['seo_meta_image']) ? trans($pageSeo['seo_meta_image']) : ""}}">

    <link rel="shortcut icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}"
          type="image/x-icon">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/style.css') }}">

    @stack('css-lib')

    @laravelPWA

</head>

<body class="{{ session()->get('rtl') == 1 ? 'rtl' : '' }}" onload="preloaderFunction()">

<div id="preloader">
    <img class="preloader-image" src="{{ asset(template(true). 'img/preloader/preloader.png') }}" alt="">
</div>


@include(template().'partials.banner')

@include(template().'partials.header')

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

@include(template().'partials.pwa')

</body>

</html>


