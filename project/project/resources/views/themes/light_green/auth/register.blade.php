@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')
    <section class="zk-auth">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-lg-6 col-md-8">
                    <div class="zk-auth__card">
                        <div class="zk-auth__head">
                            <span class="zk-eyebrow">@lang('Free account')</span>
                            <h1 class="zk-auth__title">@lang('Create your account')</h1>
                            <p>@lang('Start growing in less than a minute. No credit card required.')</p>
                        </div>

                        @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                            <div class="zk-auth__social">
                                @if(config('socialite.google_status'))
                                    <a href="{{ route('socialiteLogin','google') }}" class="zk-btn zk-btn--ghost">
                                        <img src="{{ asset(template(true). 'img/login-signup/google.png') }}" alt="">@lang('Google')
                                    </a>
                                @endif
                                @if(config('socialite.facebook_status'))
                                    <a href="{{ route('socialiteLogin','facebook') }}" class="zk-btn zk-btn--ghost">
                                        <img src="{{ asset(template(true). 'img/login-signup/facebook.png') }}" alt="">@lang('Facebook')
                                    </a>
                                @endif
                                @if(config('socialite.github_status'))
                                    <a href="{{ route('socialiteLogin','github') }}" class="zk-btn zk-btn--ghost">
                                        <img src="{{ asset(template(true). 'img/login-signup/github.png') }}" alt="">@lang('Github')
                                    </a>
                                @endif
                            </div>
                            <div class="zk-auth__divider"><span>@lang('or sign up with email')</span></div>
                        @endif

                        <form action="{{ route('register') }}" method="post" novalidate>
                            @csrf

                            @if(session()->get('sponsor') != null)
                                <div class="zk-field">
                                    <label for="sponsor">@lang('Referred by')</label>
                                    <input type="text" class="form-control" name="sponsor" id="sponsor"
                                           value="{{ session()->get('sponsor') }}" readonly>
                                </div>
                            @endif

                            <div class="zk-field">
                                <label for="username">@lang('Username')</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror"
                                       name="username" id="username" value="{{ old('username') }}"
                                       placeholder="@lang('e.g. zirka_fan')" autocomplete="username"
                                       autocapitalize="none" spellcheck="false" minlength="5" required>
                                @error('username')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @else
                                    <small class="zk-hint">@lang('At least 5 characters: letters, numbers, - or _')</small>
                                    @enderror
                            </div>

                            <div class="zk-field">
                                <label for="email">@lang('Email')</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" id="email" value="{{ old('email') }}"
                                       placeholder="@lang('you@example.com')" autocomplete="email" required>
                                @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="zk-field">
                                <label for="telephone">@lang('Phone number')</label>
                                <input type="hidden" id="country_code" name="country_code"
                                       value="{{ old('country_code', $country_code ? strtolower($country_code) : 'us') }}">
                                <input type="hidden" id="phone_code" name="phone_code" value="{{ old('phone_code') }}">
                                <input id="telephone" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel-national"
                                       required>
                                @error('phone')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="zk-field">
                                <label for="password">@lang('Password')</label>
                                <div class="password-box">
                                    <input type="password" class="form-control password @error('password') is-invalid @enderror"
                                           name="password" id="password" placeholder="@lang('At least 6 characters')"
                                           autocomplete="new-password" minlength="6" required>
                                    <button type="button" class="zk-auth__eye" aria-label="@lang('Show password')">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_register == 1)
                                <div class="zk-field">
                                    <label for="captcha">@lang('Captcha')</label>
                                    <div class="d-flex gap-2 align-items-center mb-2">
                                        <img src="{{ route('captcha').'?rand='. rand() }}" id="captcha_image" alt="@lang('Captcha')">
                                        <a class="zk-btn zk-btn--ghost zk-btn--sm" href="javascript:refreshCaptcha();"
                                           aria-label="@lang('New captcha')"><i class="fa-light fa-arrows-rotate"></i></a>
                                    </div>
                                    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                           name="captcha" id="captcha" autocomplete="off" required>
                                    @error('captcha')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1)
                                <div class="zk-field">
                                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                    @error('g-recaptcha-response')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <button type="submit" class="zk-btn zk-btn--primary w-100 mt-2">@lang('Create account')</button>

                            <p class="zk-auth__terms">
                                @lang('By signing up you agree to our')
                                <a href="{{ url('terms-and-conditions') }}">@lang('Terms')</a>
                                @lang('and')
                                <a href="{{ url('privacy-policy') }}">@lang('Privacy Policy')</a>.
                            </p>
                        </form>
                    </div>
                    <p class="zk-auth__switch">
                        @lang('Already have an account?') <a href="{{ route('login') }}">@lang('Log in')</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include(template(). 'sections.footer')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset(template(true). 'css/intlTelInput.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset(template(true). 'js/intlTelInput.min.js') }}"></script>
    @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1)
        <script async src="https://www.google.com/recaptcha/api.js"></script>
    @endif
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            const input = document.querySelector("#telephone");
            const countryField = document.querySelector("#country_code");
            const phoneCodeField = document.querySelector("#phone_code");

            const iti = window.intlTelInput(input, {
                initialCountry: countryField.value || "us",
                separateDialCode: true,
            });

            // Keep the hidden fields in sync with the flag the user picks.
            const syncCountry = () => {
                const data = iti.getSelectedCountryData();
                if (data && data.iso2) {
                    countryField.value = data.iso2;
                    phoneCodeField.value = '+' + data.dialCode;
                }
            };
            input.addEventListener('countrychange', syncCountry);
            syncCountry();

            const eye = document.querySelector('.zk-auth__eye');
            const password = document.querySelector('#password');
            eye.addEventListener('click', function () {
                const show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                eye.querySelector('i').classList.toggle('fa-eye-slash', show);
                eye.setAttribute('aria-label', show ? '@lang('Hide password')' : '@lang('Show password')');
            });
        });

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
        }
    </script>
@endpush
