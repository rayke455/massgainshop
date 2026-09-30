@extends(template().'layouts.app')
@section('title', trans('Login'))
@section('content')
    <!-- LOGIN-SIGNUP -->
    <section id="login-signup">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="wrapper">
                            <div class="login-info-wrapper">
                                <h5 class="h5 mb-30">@lang(@$loginContent->description->title)</h5>
                                <p>@lang(@$loginContent->description->description)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div
                        class="form-wrapper w-100 h-100 d-flex flex-column align-items-start justify-content-center pl-65">
                        <h4 class="h4 text-uppercase mb-30">@lang('Login')</h4>

                        <form method="POST" action="{{ route('login') }}" class="form-content w-100">
                            @csrf
                            <div class="login">

                                <div class="form-group">
                                    <input class="form-control" type="text" name="username"
                                           value="{{ old('username', config('demo.IS_DEMO') ? (request()->username ?? 'demouser') : '') }}"
                                           placeholder="@lang('Email Or Username')">

                                    @error('username')<p class="text-danger  mt-1">{{ $message }}</p>@enderror
                                    @error('email')<p class="text-danger  mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="form-group">
                                    <input class="form-control " type="password" name="password"
                                           value="{{ old('password', config('demo.IS_DEMO') ? (request()->password ?? 'demouser') : '') }}"
                                           placeholder="@lang('Password')">
                                    @error('password')
                                    <p class="text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                    <div class="form-group mt-4">
                                        <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                               name="captcha" id="captcha" autocomplete="off"
                                               placeholder="Enter Captcha" required>
                                        @error('captcha')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mt-4">
                                        <div class="input-group input-group-merge" data-hs-validation-validate-class>
                                            <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image'>
                                            <a class="input-group-append input-group-text"
                                               href='javascript: refreshCaptcha();'>
                                                <i class="fas fa-undo"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_login == 1)
                                    <div class="form-group mt-4">
                                        <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                             data-sitekey="{{config('services.recaptcha.site_key')}}"></div>
                                        @error('g-recaptcha-response')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <div class="d-flex align-items-center justify-content-between mt-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="remember"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="remember">@lang('Remember me')</label>
                                    </div>
                                    <div>
                                        <a class="btn-forgetpass"
                                           href="{{ route('password.request') }}">@lang("Forgot Your Password?")</a>
                                    </div>
                                </div>
                                <button class="btn mt-20" type="submit">@lang('Login')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /LOGIN-SIGNUP -->

    @include(template(). 'sections.footer')
@endsection

@push('extra-js')
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
    </script>
@endpush
