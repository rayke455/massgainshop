@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')

    <!-- login_signup_area_start -->
    <section class="login_signup_page register-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="contact_area">
                        <div class="section_header mb-0">
                            <h4>@lang(@$registerContent->description->title)</h4>
                        </div>
                        <p class="mt-30">{!! __(@$registerContent->description->description) !!}</p>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="login_signup_form p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="form_title pb-2">
                                    <h4>@lang('Create Account')</h4>
                                </div>

                                @if(session()->get('sponsor') != null)
                                    <div class="col-md-12">
                                        <div class="input-box mb-3">
                                            <label>@lang('Sponsor Name')</label>
                                            <input type="text" name="sponsor" class="form-control" id="sponsor"
                                                   placeholder="{{trans('Sponsor By') }}"
                                                   value="{{session()->get('sponsor')}}" readonly>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-6">
                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="first_name"
                                               value="{{ old('first_name') }}"
                                               placeholder="@lang('First Name')">
                                        @error('first_name')
                                        <p class="text-danger mt-1">@lang($message)</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="last_name"
                                               value="{{old('last_name')}}" placeholder="@lang('Last Name')">
                                        @error('last_name')
                                        <p class="text-danger mt-1">@lang($message)</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-4">
                                        <input type="text" class="form-control" name="username"
                                               value="{{old('username')}}" placeholder="@lang('Username')">
                                        @error('username')
                                        <p class="text-danger mt-1">@lang($message)</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-4">
                                        <input type="email" name="email" class="form-control" value="{{old('email')}}"
                                               placeholder="@lang('Email Address')">
                                        @error('email')
                                        <p class="text-danger  mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-4">
                                        <select class="form-select country_code" aria-label="Default select example"
                                                name="phone_code">
                                            @foreach($countries as $value)
                                                <option value="{{$value['phone_code']}}"
                                                        data-name="{{$value['name']}}"
                                                        data-code="{{$value['code']}}"
                                                        {{$country_code == $value['code'] ? 'selected' : ''}}>{{$value['phone_code']}}
                                                    <strong>({{$value['name']}})</strong></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-4">
                                        <input type="text" name="phone" class="form-control ps-3 phoneField"
                                               value="{{old('phone')}}" placeholder="@lang('Your Phone Number')">
                                        @error('phone')
                                        <p class="text-danger mt-1">@lang($message)</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password"
                                               placeholder="@lang('Password')">
                                        @error('password')
                                        <p class="text-danger mt-1">@lang($message)</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="mb-3">
                                        <input type="password" class="form-control" name="password_confirmation"
                                               placeholder="@lang('Confirm Password')">
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
                                        <div class="form-control">
                                            <div class="input-group input-group-merge"
                                                 data-hs-validation-validate-class>
                                                <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image'>
                                                <a class="input-group-append input-group-text"
                                                   href='javascript: refreshCaptcha();'>
                                                    <i class="fas fa-undo"></i>
                                                </a>
                                            </div>
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


                                <div class="col">
                                    <div class="mb-3 form-check d-flex justify-content-between">
                                        <div class="check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                            <label class="form-check-label"
                                                   for="exampleCheck1">@lang('I Agree with the Terms & conditions')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn custom_btn mt-30 w-100">@lang('Register')</button>
                                    <div class="pt-20 text-center">
                                        @lang('Already have an account?')
                                        <p class="mb-0 highlight"><a href="{{ route('login') }}">@lang('Login Here')</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- contact_area_end -->

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
