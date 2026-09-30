@extends(template().'layouts.app')
@section('title', trans('Forgot Password'))
@section('content')
    <section class="login-signup-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-10">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="login-signup-form">
                        <form method="post" action="{{ route('user.password.email') }}">
                            @csrf
                            <div class="section-header mb-20">
                                <h4>@lang("Forgot Password")</h4>
                            </div>
                            <div class="row g-4">
                                <div class="col-12">
                                    <input type="text" class="form-control" name="email" id="email"
                                           placeholder="@lang("Email")">
                                    @error('email')
                                    <span class="invalid-feedback d-block">
                                        @lang($message)
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="cmn-btn rounded-1 mt-30 w-100">@lang("Send Password Reset Link")</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include(template(). 'sections.footer')
@endsection
