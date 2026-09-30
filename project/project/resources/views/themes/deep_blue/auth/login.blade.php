@extends(template().'layouts.app')
@section('title','Login')

@section('content')

    <!-- LOGIN-SIGNUP -->
    <section class="login-section">
        <div class="container">
            <div class="row g-lg-0 gy-5 align-items-center">

                <div class="col-lg-6">
                    <div class="text-box no-wrap">
                        <h4>@lang(@$loginContent->description->title)</h4>
                        <p>{!! __(@$loginContent->description->description) !!}</p>
                    </div>
                </div>


                <div class="col-lg-6">
                    <form method="POST" action="{{ route('login') }}" class="form-content w-100">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h4>@lang('Login here')</h4>
                            </div>
                            <div class="input-box col-12">
                                <input class="form-control" type="text" name="username"
                                       value="{{ old('username', config('demo.IS_DEMO') ? (request()->username ?? 'demouser') : '') }}"
                                       placeholder="@lang('Email Or Username')">
                                @error('username')<p class="text-danger mt-1">@lang($message)</p>@enderror
                                @error('email')<p class="text-danger mt-1">@lang($message)</p>@enderror
                            </div>

                            <div class="input-box col-12">
                                <input class="form-control " type="password" name="password"
                                       value="{{ old('password', config('demo.IS_DEMO') ? (request()->password ?? 'demouser') : '') }}"
                                       placeholder="@lang('Password')">
                                @error('password')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>


                            @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                <div class="col-12 input-box mt-4">
                                    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                           name="captcha" id="captcha" autocomplete="off"
                                           placeholder="Enter Captcha" required>
                                    @error('captcha')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-12 input-box mt-4">
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
                                <div class="row mt-4">
                                    <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                         data-sitekey="{{config('services.recaptcha.site_key')}}"></div>
                                    @error('g-recaptcha-response')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            <div class="col-12">
                                <div class="links">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="flexCheckDefault"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}/>
                                        <label class="form-check-label"
                                               for="flexCheckDefault">@lang('Remember me')</label>
                                    </div>
                                    <a href="{{ route('password.request') }}">@lang("Forgot Password?")</a>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-smm">@lang('sign in')</button>
                        <div class="bottom">@lang('Don\'t have an account?') <br/>
                            <a href="{{ route('register') }}">@lang('Create account')</a>
                        </div>
                    </form>
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
