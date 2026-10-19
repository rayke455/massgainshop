<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">

    <title>@yield('title') | {{ basicControl()->site_title }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="{{ asset(template(true). "css/bootstrap.min.css") }}"/>
    <link rel="stylesheet" href="{{ asset(template(true). 'css/all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/user-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/zirka-app.css') }}">

    @stack('css-lib')

    @stack('style')

    @unless(app()->bound('rental.panel'))
        @laravelPWA
    @endunless

</head>

<body class="zk-app">


@if(env('IS_DEMO') == true)
    <div class="">
        <a class="cmn-btn sticky-btn" href="<?php echo url()->current() ?>?user_dashboard={{ session('user_dashboard') == 'user_dashboard_style_one' ? 'user_dashboard_style_two' : 'user_dashboard_style_one' }}"><i class="fa-regular fa-rotate"></i> Swap Dashboard</a>
    </div>
@endif


@include(template().'user.partials.sidebar')

<div class="zk-shell">
    @include(template().'user.partials.navbar')

    <main class="zk-main">
        @yield('content')
    </main>

    @include(template().'user.partials.footer')
</div>

@include(template().'user.partials.mobile_nav')

<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
<script src="{{ asset(template(true). 'js/user-dashboard.js') }}"></script>

<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

@stack('js-lib')

@stack('script')

@unless(app()->bound('rental.panel'))
    @include(template().'user.partials.pwa')
@endunless
@include(template().'partials.whatsapp_button')
@include(template().'partials.geo')
@include(template().'partials.auto_translate')
@include(template().'partials.auto_currency')

@include('plugins')
@include(template().'partials.rental_brand')

<script>
    "use strict";
    const toggleBtn = document.getElementById("toggle-btnq");
    const body = document.querySelector("body");
    toggleBtn.addEventListener("click", function () {

        document.body.classList.toggle("dark-theme");
        if (document.body.classList.contains("dark-theme")) {
            localStorage.setItem("dark-theme", 1);
        } else {
            localStorage.setItem("dark-theme", 0);
        }
        setTheme();
    });

    function setTheme() {
        const isDarkTheme = localStorage.getItem("dark-theme");
        if (isDarkTheme == 1) {
            document.querySelector('body').classList.add('dark-theme');
            document.getElementById("moon").style.display = "none";
            document.getElementById("sun").style.display = "block";
        } else {
            document.querySelector('body').classList.remove('dark-theme');
            document.getElementById("moon").style.display = "block";
            document.getElementById("sun").style.display = "none";
        }
    }
    setTheme();
</script>

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

</body>
</html>





