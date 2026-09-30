@extends(template().'layouts.app')
@section('title', trans($pageSeo['page_title'] ?? 'Verify Phone Number'))
@section('content')

    <section class="login-signup-page" style="background-image: url({{ getFile(@$backgroundImage->content->media->background_image->driver, @$backgroundImage->content->media->background_image->path) }})">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-10">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="login-signup-form">
                        <form class="login-form" action="{{route('user.sms.verify')}}" method="post">
                            @csrf
                            <div class="section-header mb-20">
                                <h4>@lang("Verify Your Phone Number")</h4>
                            </div>
                            <div>
                                <p class="d-flex flex-wrap">@lang("Your phone number is ") <span> {!! maskString(optional(auth()->user())->phone_code . optional(auth()->user())->phone) !!}</span></p>
                            </div>
                            <div class="row g-4">
                                <div class="col-12">
                                    <input type="text" class="form-control" name="code" id="code"
                                           value="{{old('code')}}" placeholder="@lang("Code")" autocomplete="off">
                                    @error('code')
                                    <span class="invalid-feedback d-block">
                                        @lang($message)
                                    </span>
                                    @enderror
                                    @error('error')
                                    <span class="invalid-feedback d-block">
                                        @lang($message)
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit"
                                    class="cmn-btn rounded-1 mt-30 w-100">@lang("Submit")</button>
                        </form>

                        <div class="pt-20 text-center">
                            <p>@lang("Didn't get Code? Click to")
                                <a href="{{route('user.resend.code')}}?type=email"
                                   class="highlight"> @lang('Resend code')</a>
                            </p>
                            @error('resend')
                            <p class="text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include(template(). 'sections.footer')

@endsection
