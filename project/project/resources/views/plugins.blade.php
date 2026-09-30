<!--Start of Google analytic Script-->
@if(basicControl()->analytic_status)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{basicControl()->measurement_id}}"></script>
    <script>
        "use strict";
        var MEASUREMENT_ID = "{{ basicControl()->measurement_id }}";
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', MEASUREMENT_ID);
    </script>
@endif
<!--End of Google analytic Script-->


<!--Start of Tawk.to Script-->
@if(basicControl()->tawk_status)
    <script type="text/javascript">
        // $(document).ready(function () {
        var Tawk_SRC = 'https://embed.tawk.to/' + "{{ trim(basicControl()->tawk_id) }}";
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = Tawk_SRC;
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
        // });
    </script>
@endif


<!--start of Facebook Messenger Script-->
@if(basicControl()->fb_messenger_status)
    <div id="fb-root"></div>
    <script>
        "use strict";
        var fb_app_id = "{{ basicControl()->fb_app_id }}";
        window.fbAsyncInit = function () {
            FB.init({
                appId: fb_app_id,
                autoLogAppEvents: true,
                xfbml: true,
                version: 'v10.0'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <div class="fb-customerchat" page_id="{{ basicControl()->fb_page_id }}"></div>
@endif
<!--End of Facebook Messenger Script-->


@if(basicControl()->cookie_status == 1 && auth()->guard('web'))
    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }

        function hasAcceptedCookiePolicy() {
            return document.cookie.indexOf("cookie_policy_accepted=true") !== -1;
        }

        function hasClosedCookiePolicy() {
            return document.cookie.indexOf("cookie_policy_rejected=true") !== -1;
        }

        function acceptCookiePolicy() {
            setCookie("cookie_policy_accepted", "true", 365);
            document.getElementById("cookiesAlert").style.display = "none";
        }

        function closeCookieBanner() {
            setCookie("cookie_policy_rejected", "true", 365);
            document.getElementById("cookiesAlert").style.display = "none";
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (!hasAcceptedCookiePolicy() && !hasClosedCookiePolicy()) {
                document.getElementById("cookiesAlert").style.display = "block";
            }
        });
    </script>

    <div class="cookies-alert" id="cookiesAlert">
        <img src="{{ asset('assets/global/img/cookie.svg') }}" height="50" width="50"
             alt="{{ basicControl()->site_title }} cookies">
        <h4 class="mt-2">@lang(basicControl()->cookie_title)</h4>
        <span class="d-block mt-2">@lang(basicControl()->cookie_sub_title).
            <a href="{{ basicControl()->cookie_url }}" class="link">@lang("see more")</a>
        </span>
        <a href="javascript:void(0);" class="mt-3 cmn-btn justify-content-center cookie-accept-btn" type="button"
           onclick="acceptCookiePolicy()">@lang('Accept')</a>
        <a href="javascript:void(0);" class="mt-2 cmn-btn3 rounded-5 ms-2 cookie-close-btn" type="button"
           onclick="closeCookieBanner()">@lang('Close')</a>

    </div>
@endif



<script>
    var root = document.querySelector(':root');
    @if(request()->has('theme') =='light_green' || getTheme() == 'light_green')

    root.style.setProperty('--primary-color', '{{themeColor()->light_green_primary_color ?? '#706fc7'}}');
    root.style.setProperty('--navbar-active', '{{themeColor()->light_green_primary_color ?? '#706fc7'}}');
    root.style.setProperty('--btn-hover-bg', '{{themeColor()->light_green_primary_color ?? '#0b07cf'}}');
    root.style.setProperty('--secondary-color', '{{themeColor()->light_green_secondary_color ?? '#ffa200'}}');
    root.style.setProperty('--base', '{{themeColor()->light_green_hero_color ?? '#0b07cf'}}');

    const baseColor = getComputedStyle(root).getPropertyValue('--base').trim();
    const baseColorRgb = hexToRGB(baseColor);
    const opacityLow = `rgba(${baseColorRgb.r}, ${baseColorRgb.g}, ${baseColorRgb.b}, 0.1)`;
    const gradientBg = `linear-gradient(188deg, rgba(${baseColorRgb.r}, ${baseColorRgb.g}, ${baseColorRgb.b}, 0.5) 23%, var(--white) 84%)`;
    const gradientBg2 = `linear-gradient(290deg, rgba(${baseColorRgb.r}, ${baseColorRgb.g}, ${baseColorRgb.b}, 0.1) 31%, var(--white) 95%)`;

    root.style.setProperty('--primary-color-opacity-low', opacityLow);
    root.style.setProperty('--gradient-bg', gradientBg);
    root.style.setProperty('--gradient-bg2', gradientBg2);

    function hexToRGB(hex) {
        hex = hex.replace('#', '');
        const r = parseInt(hex.substring(0, 2), 16);
        const g = parseInt(hex.substring(2, 4), 16);
        const b = parseInt(hex.substring(4, 6), 16);
        return {r, g, b};
    }

    @elseif(request()->has('theme') =='dark_voilet' || getTheme() == 'dark_voilet')
    root.style.setProperty('--primary-color', '{{ themeColor()->dark_violet_primary_color ?? '#f2a516'}}');
    root.style.setProperty('--secondary-color', '{{ themeColor()->dark_violet_secondary_color ?? '#03292e'}}');
    root.style.setProperty('--navbar-active', '{{ themeColor()->dark_violet_primary_color ?? '#f2a516'}}');

    @elseif(request()->has('theme') =='minimal' || getTheme() == 'minimal')

    root.style.setProperty('--primary-color', '{{ themeColor()->minimal_bg_right_color ?? '#5900ff'}}');
    root.style.setProperty('--secondary-color', '{{ themeColor()->minimal_secondary_color ?? '#fe5268'}}');
    root.style.setProperty('--navbar-active', '{{ themeColor()->minimal_bg_right_color ?? '##5900ff'}}');
    root.style.setProperty('--btn-hover-bg', '{{themeColor()->minimal_primary_color ?? '#706fc7'}}');

    @elseif(request()->has('theme') =='deep_blue' || getTheme() == 'deep_blue')
    root.style.setProperty('--violet', '{{themeColor()->deep_blue_primary_color ?? '#ff8503'}}');
    root.style.setProperty('--primary-color', '{{ themeColor()->deep_blue_primary_color ?? '#ff8503'}}');
    root.style.setProperty('--secondary-color', '{{ themeColor()->deep_blue_secondary_color ?? '#03292e'}}');
    root.style.setProperty('--navbar-active', '{{ themeColor()->deep_blue_primary_color ?? '#ff8503'}}');
    root.style.setProperty('--btn-hover-bg', '{{themeColor()->deep_blue_primary_color ?? '#ff8503'}}');

    @elseif(request()->has('theme') =='dark_mode' || getTheme() == 'dark_mode')
    root.style.setProperty('--gold', '{{themeColor()->dark_mode_primary_color ?? '#febd00'}}');
    root.style.setProperty('--primary-color', '{{ themeColor()->dark_mode_primary_color ?? '#febd00'}}');
    root.style.setProperty('--secondary-color', '{{ themeColor()->dark_mode_secondary_color ?? '#03292e'}}');
    root.style.setProperty('--navbar-active', '{{ themeColor()->dark_mode_primary_color ?? '#febd00'}}');
    root.style.setProperty('--btn-hover-bg', '{{themeColor()->dark_mode_primary_color ?? '#706fc7'}}');

    @elseif(request()->has('theme') =='light_orange' || getTheme() == 'light_orange')
    root.style.setProperty('--theme_color', '{{themeColor()->light_orange_primary_color ?? '#FA673B'}}');
    root.style.setProperty('--theme_light_color', '{{themeColor()->light_orange_theme_light_color ?? '#fb6738'}}');

    root.style.setProperty('--primary-color', '{{ themeColor()->light_orange_primary_color ?? '#fb6738'}}');
    root.style.setProperty('--secondary-color', '{{ themeColor()->light_orange_secondary_color ?? '#fe5268'}}');
    root.style.setProperty('--navbar-active', '{{ themeColor()->light_orange_primary_color ?? '#fb6738'}}');
    @endif
</script>
