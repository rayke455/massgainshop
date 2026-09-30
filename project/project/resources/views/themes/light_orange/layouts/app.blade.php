<!DOCTYPE html >
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en" @if(session()->get('rtl') == 1) dir="rtl" @endif >
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'/>
    <![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>@lang(basicControl()->site_title) | @if(isset($pageSeo['page_title']))
            @lang($pageSeo['page_title'])
        @else
            @yield('title')
        @endif
    </title>

    <link rel="shortcut icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}"
          type="image/x-icon">

    <meta name="title" content="{{ isset($pageSeo['meta_title']) ? trans($pageSeo['meta_title']) : ''  }}">
    <meta name="author" content="{{ basicControl()->site_title }}">
    <meta name="description" content="{{ isset($pageSeo['meta_description']) ? trans($pageSeo['meta_description']) : '' }}">
    <meta name="keywords" content="{{ isset($pageSeo['meta_keywords']) ? trans($pageSeo['meta_keywords']) : '' }}">
    <meta name="robots" content="{{ isset($pageSeo['meta_robots']) ? trans($pageSeo['meta_robots']) : '' }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ basicControl()->site_title }}">
    <meta property="og:title" content="{{ isset($pageSeo['meta_title']) ? trans($pageSeo['meta_title']) : "" }}">
    <meta property="og:description" content="{{ isset($pageSeo['og_description']) ? trans($pageSeo['og_description']) : "" }}">
    <meta property="og:image" content="{{ isset($pageSeo['seo_meta_image']) ? trans($pageSeo['seo_meta_image']) : ""}}">

    <link rel="stylesheet" href="{{asset(template(true).'css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.carousel.min.css')}}"/>
    <link rel="stylesheet" href="{{asset(template(true).'css/owl.theme.default.min.css')}}"/>
    <link rel="stylesheet" href="{{asset(template(true).'css/jquery.fancybox.min.css')}}"/>
    @stack('extra-css')
    <link rel="stylesheet" href="{{ asset(template(true).'css/style.css') }}"/>
    <script src="{{asset(template(true).'js/fontawesomepro.js')}}"></script>

    @stack('style')

    <script src="{{asset('assets/global/js/modernizr.custom.js')}}"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @laravelPWA
</head>

<body onload="preloder_function()" class="{{ session()->get('rtl') == 1 ? 'rtl' : '' }}">

<!-- preloader_area_start -->
<div id="preloader">
    <div id="loader"></div>
</div>
<!-- preloader_area_end -->

@include(template().'partials.header')

@include(template().'partials.banner')

@yield('content')

@stack('extra-content')

<!-- arrow up -->
<a href="javascript:void(0)" class="scroll_up">
    <i class="fal fa-chevron-double-up"></i>
</a>


<script src="{{ asset(template(true).'js/jquery-3.6.1.min.js') }}"></script>
<script src="{{ asset(template(true).'js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset(template(true).'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(template(true).'js/owl.carousel.min.js') }}"></script>
<script src="{{ asset(template(true).'js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset(template(true).'js/jquery.fancybox.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
@stack('extra-js')
<script src="{{ asset(template(true).'js/main.js') }}"></script>

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
