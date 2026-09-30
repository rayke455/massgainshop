@extends(template().'layouts.app')
@section('title',trans('Login'))

@section('content')

    <!-- login_signup_area_start -->
    <section class="login_signup_page">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="contact_area">
                        <div class="section_header mb-0">
                            <h4>@lang(@$loginContent->description->title)</h4>
                        </div>
                        <p class="mt-30">{!! __(@$loginContent->description->description) !!}</p>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="login_signup_form p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form_title pb-2">
                                <h4>@lang('Login Here')</h4>
                            </div>
                            <div class="mb-4">
                                <input class="form-control" type="text" name="username"
                                       value="{{ old('username', config('demo.IS_DEMO') ? (request()->username ?? 'demouser') : '') }}"
                                       placeholder="@lang('Email Or Username')">
                                @error('username')<p class="text-danger mt-1">@lang($message)</p>@enderror
                                @error('email')<p class="text-danger mt-1">@lang($message)</p>@enderror
                            </div>

                            <div class="mb-3">
                                <input class="form-control " type="password" name="password"
                                       value="{{ old('password', config('demo.IS_DEMO') ? (request()->password ?? 'demouser') : '') }}"
                                       placeholder="@lang('Password')">
                                @error('password')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>

                            @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                <div class=" mt-4">
                                    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                           name="captcha" id="captcha" autocomplete="off"
                                           placeholder="Enter Captcha" required>
                                    @error('captcha')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-control mt-4">
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
                                <div class="row mt-4 mb-3">
                                    <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                         data-sitekey="{{config('services.recaptcha.site_key')}}"></div>
                                    @error('g-recaptcha-response')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-3 form-check d-flex justify-content-between">
                                <div class="check">
                                    <input type="checkbox" name="remember" class="form-check-input"
                                           id="exampleCheck1" {{ old('remember') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="exampleCheck1">@lang('Remember me')</label>
                                </div>
                                <div class="forgot highlight">
                                    <a href="{{ route('password.request') }}">@lang("Forgot Password?")</a>
                                </div>
                            </div>

                            <button type="submit" class="btn custom_btn mt-30 w-100">@lang('Log In')</button>
                            <div class="pt-20 text-center">
                                @lang("Don't have an account?")
                                <p class="mb-0 highlight"><a
                                            href="{{ route('register') }}">@lang('Create an account')</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- login_signup_area_start -->

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
