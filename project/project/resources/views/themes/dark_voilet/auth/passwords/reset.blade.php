@extends(template().'layouts.app')
@section('title', trans('Reset password'))
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
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="section-header mb-20">
                                <h4>@lang("Reset Password")</h4>
                            </div>
                            <div class="row g-4">
                                <div class="col-12">
                                    <input type="hidden" class="form-control" name="email" id="email"
                                          value=" {{ $email ?? old('email') }}" placeholder="@lang("Email")" required>
                                    @error('email')
                                    <span class="invalid-feedback d-block">
                                        @lang($message)
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <input id="password" type="password"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           placeholder="@lang("Password")" required autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback">
                                        @lang($message)
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" placeholder="@lang("Confirm Password")" required autocomplete="new-password">
                                </div>
                            </div>
                            <button type="submit"
                                    class="cmn-btn rounded-1 mt-30 w-100">@lang("Reset Password")</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include(template(). 'sections.footer')
@endsection
