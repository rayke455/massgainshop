@extends('admin.layouts.login')
@section('page_title', __('Two Step Verify'))
@section('content')
    <div class="container py-5 py-sm-7">

        <div class="mx-auto mt-5" style="max-width: 30rem;">
            <div class="card card-lg mb-5">
                <form action="{{route('admin.2step.verify')}}" method="post">
                    @csrf
                    <div class="card-body text-center">
                        <div class="mb-5">
                            <h1 class="display-5">@lang("2-step Verification")</h1>
                            <p class="mb-0">@lang("We sent a verification code to your email.")</p>
                            <p>@lang("Enter the code from the email in the field below.")</p>
                        </div>

                        <div class="row gx-2 gx-sm-3">
                            @for ($i = 1; $i <= 6; $i++)
                                <div class="col">
                                    <div class="mb-4">
                                        <input
                                            type="text"
                                            class="form-control form-control-single-number"
                                            name="code{{ $i }}"
                                            id="twoStepVerificationSrCodeInput{{ $i }}"
                                            maxlength="1"
                                            autocomplete="off"
                                            autocapitalize="off"
                                            spellcheck="false"
                                            @if($i === 1) autofocus @endif
                                        >
                                    </div>
                                </div>
                            @endfor


                                @error('code' . $i)
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror

                            @error('code')
                            <span class="invalid-feedback d-block mb-3">
                                        @lang($message)
                                    </span>
                            @enderror
                            @error('error')
                            <span class="invalid-feedback d-block mb-3">
                                        @lang($message)
                                    </span>
                            @enderror

                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">@lang("Verify my account")</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.querySelectorAll('.form-control-single-number').forEach((input, index, allInputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < allInputs.length - 1) {
                    allInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    allInputs[index - 1].focus();
                }
            });
        });
    </script>

@endpush


