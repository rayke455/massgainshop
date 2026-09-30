@extends(template().'layouts.app')
@section('title', trans($pageSeo['page_title'] ?? 'Two Step Security'))

@section('content')
    <section id="login-signup">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center justify-content-start">
                        <div class="wrapper">
                            <div class="login-info-wrapper">
                                <img src="{{asset(template(true).'images/verification.jpg')}}" alt="" class="w-100">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="form-block py-3">
                        <form class="login-form shadow" action="{{route('user.twoFA-Verify')}}"  method="post">
                            @csrf
                            <div class="signin">
                                <h1 class="title greenColor mb-30 text-center">@lang($pageSeo['page_title'] ?? 'Two Step Security')</h1>
                                <div class="form-group mb-30">
                                    <input class="form-control" type="text" name="code" value="{{old('code')}}" placeholder="@lang('Code')" autocomplete="off">

                                    @error('code')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                    @error('error')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>

                                <div class="btn-area mb-3">
                                    <button class="btn send-massage-button anim-button" type="submit"><span>@lang('Submit')</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>



        </div>
    </section>


@endsection
