<div class="hero-section"
     style="background-image: url({{ getFile(@$hero['single']['media']->background_image->driver, $hero['single']['media']->background_image->path) }}); background-size: cover; background-position: top center">
    <div class="container">
        <div class="hero-section-inner">
            <div class="row g-5 justify-content-between align-items-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="hero-content">
                        <span class="section-subtitle">@lang(@$hero['single']['title'])</span>
                        <h1 class="hero-title">
                            @lang(@$hero['single']['heading'])
                        </h1>
                        <p class="hero-description">
                            @lang(@$hero['single']['short_description'])
                        </p>
                        <div class="btn-area">
                            <a href="{{ @$hero['single']['media']->button_link }}"
                               class="cmn-btn">@lang(@$hero['single']['button_name'])</a>
                            <a data-fancybox
                               href="{{ @$hero['single']['media']->video_link }}"
                               class="video-play-btn">
                                <i class="fa-regular fa-play"></i>
                            </a>
                            <p class="mb-0">@lang(@$hero['single']['video_text'])</p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 col-lg-6 mx-auto">
                    <div class="login-signup-form">
                        <form action="{{ route('login.submit') }}" method="post" class="php-email-form">
                            @csrf
                            <div class="section-header">
                                <h3>@lang(@$hero['single']['login_title'])</h3>
                                <div class="description">@lang(@$hero['single']['login_subtitle'])
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
                                    <input type="text" class="form-control" name="username" id="username_or_email"
                                           placeholder="@lang("Username or Email")">
                                    @error('username')
                                    <span class="invalid-feedback d-block">
                                            @lang($message)
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="password-box">
                                        <input type="password" class="form-control password" name="password"
                                               placeholder="@lang("Password")">
                                        <i class="password-icon fa-regular fa-eye"></i>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback d-block">
                                            @lang($message)
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <div class="form-check d-flex justify-content-between">
                                        <div class="check">
                                            <input type="checkbox" class="form-check-input" id="remember_me"
                                                   name="remember_me">
                                            <label class="form-check-label"
                                                   for="remember_me">@lang("Remember me")</label>
                                        </div>
                                        <div class="forgot highlight">
                                            <a href="{{ route('password.request') }}">@lang("Forgot password?")</a>
                                        </div>
                                    </div>
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


                            </div>
                            <button type="submit" class="cmn-btn mt-30 w-100">@lang("Log In")</button>
                            <div class="pt-20 text-center">
                                @lang(" Don't have an account?")
                                <p class="mb-0 highlight mt-1"><a
                                        href="{{ route('register') }}">@lang("Create an account")</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



