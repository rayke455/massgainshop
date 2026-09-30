@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')
    <!-- LOGIN-SIGNUP -->
    <section id="login-signup">
        <div class="container">
            <div class="row">

                <div class="col-lg-5">
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="wrapper">
                            <div class="login-info-wrapper">
                                <h5 class="h5 mb-30">@lang(@$registerContent->description->title)</h5>
                                <p>@lang(@$registerContent->description->description)</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-7">
                    <div
                        class="form-wrapper w-100 h-100 d-flex flex-column align-items-start justify-content-center pl-65">
                        <h4 class="h4 text-uppercase mb-30">@lang('REGISTER')</h4>


                        <form method="POST" action="{{ route('register') }}" class="form-content w-100">
                            @csrf
                            <div class="signup">
                                <div class="row">
                                    @if(session()->get('sponsor') != null)
                                        <div class="col-md-12">
                                            <div class="form-group mb-30">
                                                <label>@lang('Sponsor Name')</label>
                                                <input type="text" name="sponsor" class="form-control" id="sponsor"
                                                       placeholder="{{trans('Sponsor By') }}"
                                                       value="{{session()->get('sponsor')}}" readonly>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control " type="text" name="first_name"
                                                   value="{{old('first_name')}}" placeholder="@lang('First Name')">
                                            @error('first_name')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control " type="text" name="last_name"
                                                   value="{{old('last_name')}}" placeholder="@lang('Last Name')">
                                            @error('last_name')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control " type="text" name="username"
                                                   value="{{old('username')}}" placeholder="@lang('Username')">
                                            @error('username')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="email"
                                                   value="{{old('email')}}"
                                                   placeholder="@lang('Email Address')">
                                            @error('email')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group ">
                                                <div class="input-group-prepend w-30">
                                                    <select name="phone_code" class="form-control country_code">
                                                        @foreach($countries as $value)
                                                            <option value="{{$value['phone_code']}}"
                                                                    data-name="{{$value['name']}}"
                                                                    data-code="{{$value['code']}}"
                                                                {{$country_code == $value['code'] ? 'selected' : ''}}
                                                            > {{$value['phone_code']}} <strong>({{$value['name']}}
                                                                    )</strong>
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <input type="text" name="phone" class="form-control pl-3"
                                                       value="{{old('phone')}}"
                                                       placeholder="Your Phone Number">
                                            </div>

                                            @error('phone')
                                            <p class="text-danger  mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control " type="password" name="password"
                                                   placeholder="@lang('Password')">
                                            @error('password')
                                            <p class="text-danger mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control " type="password" name="password_confirmation"
                                                   placeholder="@lang('Confirm Password')">
                                        </div>
                                    </div>
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

                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <a class="btn-forgetpass"
                                           href="{{ route('login') }}">@lang("Already have account?")</a>
                                    </div>
                                </div>

                            </div>

                            <button class="btn mt-20" type="submit">@lang('Submit')</button>
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
