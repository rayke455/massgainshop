@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')
    <section class="zk-auth zk-signup">
        <div class="container">
            <div class="row justify-content-center align-items-center g-5">
                <div class="col-lg-6 zk-signup__intro">
                    <span class="zk-eyebrow">@lang('Your next chapter starts here')</span>
                    <h2>@lang('Big ideas.')<br><span>@lang('More possibilities.')</span></h2>
                    <p class="zk-signup__lead">@lang('Bring your social media goals together in one place. Create an account, explore services, and grow at your own pace.')</p>
                    <div class="zk-signup__platforms" aria-label="@lang('Supported platforms')">
                        @foreach(['Instagram', 'TikTok', 'YouTube', 'Facebook', 'Telegram', 'WhatsApp'] as $platform)
                            @php($brand = zkBrand($platform))
                            <span title="{{ $platform }}" style="--platform-color: {{ $brand[1] }}"><i class="fa-brands fa-{{ $brand[0] }}" aria-hidden="true"></i><span class="visually-hidden">{{ $platform }}</span></span>
                        @endforeach
                    </div>
                    <ol class="zk-signup__steps">
                        <li><span>01</span><div><strong>@lang('Make it yours')</strong><p>@lang('Set up your free account with a few simple details.')</p></div></li>
                        <li><span>02</span><div><strong>@lang('Find your fit')</strong><p>@lang('Browse platforms, compare services, and see prices upfront.')</p></div></li>
                        <li><span>03</span><div><strong>@lang('Stay in control')</strong><p>@lang('Add funds when you are ready and track orders from your dashboard.')</p></div></li>
                    </ol>
                    <a class="zk-signup__explore" href="{{ route('services') }}">@lang('Explore services first') <i class="fa-regular fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="col-lg-6 col-md-10 zk-signup__form">
                    <div class="zk-auth__card">
                        <div class="zk-auth__head">
                            <span class="zk-eyebrow">@lang('Free account')</span>
                            <h1 class="zk-auth__title">@lang('Create your account')</h1>
                            <p>@lang('A fresh start for your social media journey.')</p>
                            <div class="zk-signup__login">@lang('Already a member?') <a href="{{ route('login') }}">@lang('Log in')</a></div>
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

                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <input type="hidden" name="signup_country" id="signup-country" value="{{ old('signup_country') }}">

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
                                       autocapitalize="none" spellcheck="false" minlength="5" maxlength="60" required>
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
                                       placeholder="@lang('you@example.com')" autocomplete="email" autocapitalize="none" spellcheck="false" maxlength="255" required>
                                @error('email')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="zk-field">
                                <label for="telephone">@lang('WhatsApp number')</label>
                                <input id="telephone" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel"
                                       placeholder="+254712345678" maxlength="25"
                                       aria-describedby="phone-hint" required>
                                <small class="zk-hint" id="phone-hint">@lang('Enter your full WhatsApp number, including the country code (e.g. +254712345678).')</small>
                                @error('phone')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="zk-field">
                                <label for="password">@lang('Password')</label>
                                <div class="password-box">
                                    <input type="password" class="form-control password @error('password') is-invalid @enderror"
                                           name="password" id="password" placeholder="@lang('At least 6 characters')"
                                           autocomplete="new-password" minlength="6" aria-describedby="password-hint" required>
                                    <button type="button" class="zk-auth__eye" aria-controls="password" aria-pressed="false" aria-label="@lang('Show password')">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                                <small class="zk-hint" id="password-hint">
                                    @if($basicControl->strong_password)
                                        @lang('Use at least 6 characters, with uppercase and lowercase letters, a number, and a symbol. Avoid previously exposed passwords.')
                                    @else
                                        @lang('Use at least 6 characters. A longer, unique password is best.')
                                    @endif
                                </small>
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

                            <button type="submit" class="zk-btn zk-btn--primary w-100 mt-2">@lang('Create free account') <i class="fa-regular fa-arrow-right" aria-hidden="true"></i></button>
                            <p class="zk-signup__note"><i class="fa-regular fa-circle-check" aria-hidden="true"></i> @lang('Free to join. No payment details needed.')</p>

                            <p class="zk-auth__terms">
                                @lang('By signing up you agree to our')
                                <a href="{{ url('terms-and-conditions') }}">@lang('Terms')</a>
                                @lang('and')
                                <a href="{{ url('privacy-policy') }}">@lang('Privacy Policy')</a>.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include(template(). 'sections.footer')
@endsection

@push('js-lib')
    @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1)
        <script async src="https://www.google.com/recaptcha/api.js"></script>
    @endif
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            if (window.zkCountry) {
                window.zkCountry().then(country => {
                    if (/^[A-Z]{2}$/.test(country)) document.getElementById('signup-country').value = country;
                }).catch(() => {});
            }
            const eye = document.querySelector('.zk-auth__eye');
            const password = document.querySelector('#password');
            eye.addEventListener('click', function () {
                const show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                eye.querySelector('i').classList.toggle('fa-eye-slash', show);
                eye.setAttribute('aria-pressed', String(show));
                eye.setAttribute('aria-label', show ? @json(__('Hide password')) : @json(__('Show password')));
            });
        });

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
        }
    </script>
@endpush
