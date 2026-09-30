@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')

    <!-- Register-SIGNUP -->
    <section class="login-section">
        <div class="container">
            <div class="row g-lg-0 gy-5 align-items-center">
                <div class="col-lg-6">
                    <div class="text-box">
                        <h4>@lang(@$registerContent->description->title)</h4>
                        <p>{!! __(@$registerContent->description->description) !!}</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form method="POST" action="{{ route('register') }}" class="form-content w-100">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h4>@lang('create account')</h4>
                            </div>

                            @if(session()->get('sponsor') != null)
                                <div class="col-md-12">
                                    <div class="input-box mb-3 col-12">
                                        <label>@lang('Sponsor Name')</label>
                                        <input type="text" name="sponsor" class="form-control" id="sponsor"
                                               placeholder="{{trans('Sponsor By') }}"
                                               value="{{session()->get('sponsor')}}" readonly>
                                    </div>
                                </div>
                            @endif
                            <div class="input-box col-md-6">
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}"
                                       placeholder="@lang('First Name')"/>
                                @error('first_name')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>
                            <div class="input-box col-md-6">
                                <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}"
                                       placeholder="@lang('Last Name')"/>
                                @error('last_name')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>
                            <div class="input-box col-md-6">
                                <input type="text" class="form-control" name="username" value="{{old('username')}}"
                                       placeholder="@lang('Username')"/>
                                @error('username')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>
                            <div class="input-box col-md-6">
                                <input type="email" name="email" class="form-control" value="{{old('email')}}"
                                       placeholder="@lang('Email Address')"/>
                                @error('email')
                                <p class="text-danger  mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="input-box col-12">
                                <div class="input-group">
                                    <div class="input-group-prepend w-50">
                                        <select name="phone_code" class="form-control country_code">
                                            @foreach($countries as $value)
                                                <option value="{{$value['phone_code']}}"
                                                        data-name="{{$value['name']}}"
                                                        data-code="{{$value['code']}}"
                                                    {{$country_code == $value['code'] ? 'selected' : ''}}
                                                > {{$value['phone_code']}} <strong>({{$value['name']}})</strong>
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <input type="text" name="phone" class="form-control ps-3 phoneField"
                                           value="{{old('phone')}}" placeholder="@lang('Your Phone Number')">
                                </div>

                                @error('phone')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>

                            <div class="input-box col-md-6">
                                <input type="password" class="form-control" name="password"
                                       placeholder="@lang('Password')"/>
                                @error('password')
                                <p class="text-danger mt-1">@lang($message)</p>
                                @enderror
                            </div>
                            <div class="input-box col-md-6">
                                <input type="password" class="form-control" name="password_confirmation"
                                       placeholder="@lang('Confirm Password')"/>
                            </div>

                            @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                <div class="col-12 input-box mt-4">
                                    <input type="text" class="form-control @error('captcha') is-invalid @enderror"
                                           name="captcha" id="captcha" autocomplete="off"
                                           placeholder="@lang("Enter Captcha")" required>
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
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"/>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            @lang('I Agree with the Terms & conditions')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-smm">@lang('sign up')</button>

                        <div class="bottom">
                            @lang('Already have an account?') <br/>
                            <a href="{{ route('login') }}">@lang('Login here')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /Register-SIGNUP -->

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
