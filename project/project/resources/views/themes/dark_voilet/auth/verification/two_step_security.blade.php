@extends(template().'layouts.app')
@section('title', trans("Two Step Security"))
@section('content')
    <section class="login-signup-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-10">
                    <div class="login-signup-form">
                        <form class="login-form" action="{{route('user.twoFA-Verify')}}" method="post">
                            @csrf
                            <div class="section-header mb-20">
                                <h4>@lang("Two Step Security")</h4>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
