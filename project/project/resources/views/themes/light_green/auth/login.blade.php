@extends(template().'layouts.app')
@section('title',trans('Login'))
@section('content')
    <section class="login-signup-page mf-login">
        <div class="container">
            <div class="row justify-content-center align-items-center g-5">
                <div class="col-lg-5 mf-login-intro">
                    <span class="mf-login-eyebrow">{{ basicControl()->site_title }}</span>
                    <h2>@lang('Your orders.')<br><span>@lang('One simple place.')</span></h2>
                    <p>@lang('Sign in to manage your orders, check your balance, and pick up where you left off.')</p>
                    <ul>
                        <li><i class="fa-regular fa-list-check" aria-hidden="true"></i><span>@lang('Follow your order progress')</span></li>
                        <li><i class="fa-regular fa-wallet" aria-hidden="true"></i><span>@lang('Manage your wallet and payments')</span></li>
                        <li><i class="fa-regular fa-headset" aria-hidden="true"></i><span>@lang('Get help from your account')</span></li>
                    </ul>
                    <a class="mf-login-guest" href="{{ route('guest.order') }}">@lang('Order without account') <i class="fa-regular fa-arrow-right" aria-hidden="true"></i></a>
                </div>
                <div class="col-lg-5 col-md-10">
                    <div class="login-signup-form">
                        <form action="{{ route('login.submit') }}" method="post" class="php-email-form">
                            @csrf
                            <div class="section-header">
                                <h3>@lang(@$loginContent->description->title)</h3>
                                <div class="description">@lang(@$loginContent->description->sub_title)
                                </div>
                            </div>
                            @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                                <div class="cmn-btn-group">
                                    <div class="row g-2">
                                        @if(config('socialite.google_status'))
                                            <div class="col-sm-4">
                                                <a href="{{route('socialiteLogin','google')}}"
                                                   class="btn cmn-btn3 w-100 social-btn"><img
                                                        src="{{ asset(template(true). 'img/login-signup/google.png') }}"
                                                        alt="Google Icon">@lang('Google')
                                                </a>
                                            </div>
                                        @endif
                                        @if(config('socialite.facebook_status'))
                                            <div class="col-sm-4">
                                                <a href="{{route('socialiteLogin','facebook')}}"
                                                   class="btn cmn-btn3 w-100 social-btn"><img
                                                        src="{{ asset(template(true). 'img/login-signup/facebook.png') }}"
                                                        alt="facebook Icon">@lang('Facebook')
                                                </a>
                                            </div>
                                        @endif
                                        @if(config('socialite.github_status'))
                                            <div class="col-sm-4">
                                                <a href="{{route('socialiteLogin','github')}}"
                                                   class="btn cmn-btn3 w-100 social-btn"><img
                                                        src="{{ asset(template(true). 'img/login-signup/github.png') }}"
                                                        alt="Github Icon">@lang('Github')
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr class="divider">
                            @endif

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label" for="username_or_email">@lang('Username or email')</label>
                                    <input type="text" class="form-control" name="username" id="username_or_email"
                                           value="{{ old('username', config('demo.IS_DEMO') ? (request()->username ?? 'demouser') : '') }}"
                                           placeholder="@lang('Enter your username or email')" autocomplete="username" autocapitalize="none" spellcheck="false" required>
                                    @error('username')
                                    <span class="invalid-feedback d-block">
                                            @lang($message)
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="login_password">@lang('Password')</label>
                                    <div class="password-box">
                                        <input type="password" class="form-control password" name="password" id="login_password"
                                               autocomplete="current-password" required
                                               placeholder="@lang('Enter your password')">
                                        <button type="button" class="mf-password-toggle" aria-label="@lang('Show password')" aria-controls="login_password" aria-pressed="false"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback d-block">
                                            @lang($message)
                                        </span>
                                    @enderror
                                </div>

                                @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                    <div class="col-12 mt-4">
                                        <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                               name="captcha" id="captcha" autocomplete="off"
                                               placeholder="Enter Captcha" required>
                                        @error('captcha')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-12 mt-4">
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image'>
                                            <a class="input-group-append input-group-text"
                                               href='javascript: refreshCaptcha();'>
                                                <i class="fa-light fa-arrows-rotate"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_login == 1)
                                    <div class="row mt-4">
                                        <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                             data-sitekey="{{config('services.recaptcha.site_key')}}"></div>
                                        @error('g-recaptcha-response')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="form-check d-flex justify-content-between">
                                        <div class="check">
                                            <input type="checkbox" class="form-check-input" name="remember_me"
                                                   id="rememberCheck" @checked(old('remember_me'))>
                                            <label class="form-check-label" for="rememberCheck">
                                                @lang("Remember me")</label>
                                        </div>
                                        <div class="forgot highlight">
                                            <a href="{{ route('password.request') }}">@lang("Forgot password?")</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="cmn-btn mt-30 w-100">@lang("Log In")</button>

                            <div class="pt-20 text-center">
                                @lang("Don't have an account?")
                                <p class="mb-0 highlight mt-1"><a
                                        href="{{ route('register') }}">@lang("Create an account")</a>
                                </p>
                            </div>
                            <a class="mf-mobile-guest" href="{{ route('guest.order') }}">@lang('Order without account') <span aria-hidden="true">→</span></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include(template(). 'sections.footer')
@endsection

@push('js-lib')
    <script async src="https://www.google.com/recaptcha/api.js"></script>
@endpush

@push('script')
    <script>
        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }

        $(document).ready(function () {
            const password = document.querySelector('.password');
            const passwordToggle = document.querySelector('.mf-password-toggle');
            const passwordIcon = passwordToggle.querySelector('i');

            passwordToggle.addEventListener("click", function () {
                if (password.type == 'password') {
                    password.type = 'text';
                    passwordIcon.classList.add('fa-eye-slash');


                } else {
                    password.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                }
                passwordToggle.setAttribute('aria-pressed', String(password.type === 'text'));
                passwordToggle.setAttribute('aria-label', password.type === 'text' ? @json(__('Hide password')) : @json(__('Show password')));
            })
        });
    </script>
@endpush

@push('css-lib')
<style>
.mf-login .section-header h3{font-family:inherit;font-weight:700}.mf-login .section-header .description{max-width:100%}
.mf-login{background:linear-gradient(135deg,#f5f7ff,#fafbfe);padding:70px 0!important}.mf-login-intro{padding-right:48px}.mf-login-eyebrow{color:#64748b;font-size:12px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase}.mf-login-intro h2{font-size:clamp(32px,3.4vw,46px);line-height:1.15;letter-spacing:-1.5px;color:#15213c;margin:22px 0;text-transform:none}.mf-login-intro h2 span{color:#315afb}.mf-login-intro>p{font-size:16px;line-height:1.8;color:#66748d;max-width:380px}.mf-login-intro ul{list-style:none;padding:0;margin:28px 0;display:grid;gap:18px}.mf-login-intro li{display:flex;align-items:center;gap:14px;font-size:14px;color:#42516b}.mf-login-intro li i{display:grid;place-items:center;width:38px;height:38px;border-radius:11px;background:#e9eeff;color:#315afb;font-size:17px}.mf-login-guest{display:inline-flex;align-items:center;gap:16px;font-weight:600;color:#315afb;font-size:14px}.mf-login .login-signup-form{background:#fff;padding:38px;border:1px solid #e2e7f0;border-radius:22px;box-shadow:0 15px 50px #172c5c0b}.mf-login .section-header{text-align:left;margin-bottom:28px}.mf-login .section-header h3{text-transform:none;font-size:28px;letter-spacing:-.8px;line-height:1.2;color:#17213b}.mf-login .section-header .description{font-size:14px;line-height:1.7;color:#748096;margin-top:10px}.mf-login .form-label{font-size:13px;font-weight:600;color:#3d4b64;margin-bottom:9px}.mf-login .form-control{height:52px;border:1px solid #dce2ed;border-radius:10px;background:#fbfcff;color:#17213b;font-size:15px;padding:12px 14px}.mf-login .form-control:focus{border-color:#315afb;box-shadow:0 0 0 3px #315afb18;background:white}.mf-login .password{padding-right:52px}.mf-login .password-box{position:relative}.mf-password-toggle{position:absolute;right:5px;top:5px;width:42px;height:42px;display:grid;place-items:center;border:0;background:transparent;color:#67758f;border-radius:7px}.mf-password-toggle:focus-visible{outline:2px solid #315afb}.mf-login .cmn-btn{min-height:52px;border-radius:10px;background:#315afb;color:#fff}.mf-login .form-check{padding-left:0;gap:10px;font-size:12px;flex-wrap:wrap}.mf-login .check{display:flex;align-items:center;gap:8px}.mf-login .check input{float:none;margin:0}.mf-login .pt-20{font-size:13px;color:#748096}.mf-login .highlight a{color:#315afb;font-weight:600}.mf-mobile-guest{display:none}@media(max-width:991px){.mf-login{padding:38px 0!important}.mf-login-intro{display:none}.mf-mobile-guest{display:flex;justify-content:center;gap:12px;border-top:1px solid #e7eaf1;padding-top:20px;margin-top:22px;color:#315afb;font-size:13px;font-weight:600}}@media(max-width:480px){.mf-login .login-signup-form{padding:26px 20px;border-radius:16px}.mf-login .container{padding-inline:16px}.mf-login .form-control{font-size:16px}}
</style>
@endpush

