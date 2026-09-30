@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')
    <section class="login-signup-page">
        <div class="container">
            <div class="login-signup-page-inner">
                <div class="row align-items-center justify-content-center">
                    <div class="col-lg-6">
                        <div class="login-signup-form">
                            <form action="{{ route('register') }}" method="post">
                                @csrf
                                <div class="section-header">
                                    <h3>@lang(@$registerContent->description->title)</h3>
                                    <div class="description">@lang(@$registerContent->description->sub_title)
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
                                    @if(session()->get('sponsor') != null)
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="sponsor"
                                                   placeholder="@lang("Sponsor By")"
                                                   value="{{ session()->get('sponsor') }}">
                                            @error('sponsor')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="first_name" id="first_name"
                                               placeholder="@lang("First Name")" value="{{ old('first_name') }}"
                                               autocomplete="off">
                                        @error('first_name')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="last_name" id="last_name"
                                               placeholder="@lang("Last Name")" value="{{ old('last_name') }}"
                                               autocomplete="off">
                                        @error('last_name')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="username" id="username"
                                               placeholder="@lang("User Name")" value="{{ old('username') }}" autocomplete="off">
                                        @error('username')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email" id="email"
                                               placeholder="@lang("Email")" value="{{ old('email') }}" autocomplete="off">
                                        @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <input type="hidden" id="country_code" name="country_code" value="us">
                                        <input type="hidden" id="phone_code" name="phone_code" value="+880">
                                        <input id="telephone" class="form-control" name="phone" type="tel"
                                               value="{{ old('phone') }}" autocomplete="off">
                                        @error('phone')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="password-box">
                                            <input type="password" class="form-control password" name="password"
                                                   id="password" placeholder="@lang("Password")">
                                            <i class="password-icon fa-regular fa-eye"></i>
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="password-box">
                                            <input type="password" class="form-control confirm_password"
                                                   name="password_confirmation"
                                                   id=confirm_password" placeholder="@lang("Confirm Password")">
                                            <i class="confirm-password-icon fa-regular fa-eye"></i>
                                        </div>
                                        @error('confirm_password')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                        @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                            <div class="col-12 mt-4">
                                                <input type="text"
                                                       class="form-control @error('captcha') is-invalid @enderror"
                                                       name="captcha" id="captcha" autocomplete="off"
                                                       placeholder="@lang("Enter Captcha")" required>
                                                @error('captcha')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="col-12 mt-4">
                                                <div class="input-group input-group-merge"
                                                     data-hs-validation-validate-class>
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
                                                     data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                                @error('g-recaptcha-response')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                </div>
                                <button type="submit" class="btn cmn-btn mt-30 w-100">@lang("signup")</button>
                                <div class="pt-20 text-center">
                                    @lang("Don't have an account?")
                                    <p class="mb-0 highlight mt-1"><a href="{{ route('login') }}">@lang("Sign In")</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
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
    <script async src="https://www.google.com/recaptcha/api.js"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {

            const input = document.querySelector("#telephone");
            window.intlTelInput(input, {
                initialCountry: "us",
                separateDialCode: true,
            });

            let countryCode = $('.iti__country-list li').data('country-code');
            let phoneCode = $('.iti__country-list li').data('dial-code');

            function code(countryCode, phoneCode) {
                $("#country_code").val(countryCode);
                $("#phone_code").val('+' + phoneCode);
            }

            $('.iti__country-list li').on('click', function () {
                let country = $(this).data('country-code');
                let phone = $(this).data('dial-code');

                code(country, phone);
            });

            const confirmPassword = document.querySelector('.confirm_password');
            const confirmPasswordIcon = document.querySelector('.confirm-password-icon');

            confirmPasswordIcon.addEventListener("click", function () {
                if (confirmPassword.type == 'password') {
                    confirmPassword.type = 'text';
                    confirmPasswordIcon.classList.add('fa-eye-slash');
                } else {
                    confirmPassword.type = 'password';
                    confirmPasswordIcon.classList.remove('fa-eye-slash');
                }
            })
        });

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }
    </script>
@endpush

