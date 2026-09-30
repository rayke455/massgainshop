@extends(template().'layouts.app')
@section('title', trans($pageSeo['page_title'] ?? 'Two Step Security'))

@section('content')
    <section class="login-section">
        <div class="container">
            <div class="row g-lg-0 gy-5 align-items-center justify-content-center">
                <div class="col-lg-6">
                    <form method="POST" action="{{route('user.twoFA-Verify')}}" class="form-content w-100">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <h4>@lang($pageSeo['page_title'] ?? 'Verify Phone Number')</h4>
                            </div>

                            <div class="input-box col-12">
                                <input class="form-control" type="text" name="code" value="{{old('code')}}"
                                       placeholder="@lang('Code')" autocomplete="off">
                                @error('code')
                                <p class="text-danger mt-1">{{ trans($message) }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-smm mt-4">@lang('Submit')</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include(template(). 'sections.footer')
@endsection
