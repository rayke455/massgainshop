@extends(template().'layouts.user')
@section('title', trans($kyc->name) ?? 'Kyc Form')
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("KYC Verification")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">@lang("Home")</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a></li>
                    <li class="breadcrumb-item active">@lang("KYC Verification")</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="account-settings-profile-section">
                <div class="card">
                    <div class="card-header border-0 text-start text-md-center">
                        <h5 class="card-title">@lang($kyc->name)</h5>
                        <p>@lang("Verify your process instantly.")</p>
                    </div>
                    <div class="card-body pt-0">
                        @if($userKyc && $userKyc->status == 0)
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="custom-alert alert alert-warning">
                                        @lang("The verification of your $kyc->name is currently pending." )
                                    </div>
                                </div>
                            </div>
                        @elseif($userKyc && $userKyc->status == 1)
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="custom-alert alert alert-warning">
                                        @lang('The verification of your :name has been approved.', ['name' => strtolower($kyc->name)])
                                    </div>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('user.kyc.verification.submit') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="type" value="{{ $kyc->id }}">
                                <div class="row">
                                    <div class="col-md-8 mx-auto">
                                        <div class="row g-4">
                                            @forelse($kyc->input_form as $key => $value)
                                                @if($value->type == "text")
                                                    <div class="col-12">
                                                        <label for="{{ trans($value->field_label) }}"
                                                               class="form-label">@lang($value->field_label)
                                                            <span
                                                                class="text-danger">{{ $value->validation == 'required' ? '*' : '' }}</span>
                                                        </label>
                                                        <input type="text" class="form-control"
                                                               name="{{ $value->field_name }}" value="{{ old($value->field_name) }}"
                                                               autocomplete="off"/>
                                                        @error($value->field_name)
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                                @if($value->type == "number")
                                                    <div class="col-12">
                                                        <label for="{{ trans($value->field_label) }}"
                                                               class="form-label">@lang($value->field_label)
                                                            <span
                                                                class="text-danger">{{ $value->validation == 'required' ? '*' : '' }}</span>
                                                        </label>
                                                        <input type="number" class="form-control"
                                                               name="{{ $value->field_name }}" value="{{ old($value->field_name) }}"
                                                               autocomplete="off"/>
                                                        @error($value->field_name)
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                                @if($value->type == "date")
                                                    <div class="col-12">
                                                        <label for="{{ trans($value->field_label) }}"
                                                               class="form-label">@lang($value->field_label)
                                                            <span
                                                                class="text-danger">{{ $value->validation == 'required' ? '*' : '' }}</span>
                                                        </label>
                                                        <input type="number" class="form-control flatpickr"
                                                               name="{{ $value->field_name }}" value="{{ old($value->field_name) }}"
                                                               autocomplete="off"/>
                                                    </div>
                                                    @error($value->field_name)
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                                @if($value->type == "textarea")
                                                    <div class="col-12">
                                                        <label for="{{ trans($value->field_label) }}"
                                                               class="form-label">@lang($value->field_label)
                                                            <span class="text-danger">
                                                                    {{ $value->validation == 'required' ? '*' : '' }}
                                                                </span>
                                                        </label>
                                                        <textarea class="form-control" rows="5"
                                                                  name="{{ $value->field_name }}">{{old($value->field_name)}}</textarea>
                                                    </div>
                                                    @error($value->field_name)
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                                @if($value->type == "file")
                                                    <div class="col-12">
                                                        <label for="{{ trans($value->field_label) }}"
                                                               class="form-label">@lang($value->field_label)
                                                            <span
                                                                class="text-danger">{{ $value->validation == 'required' ? '*' : '' }}</span>
                                                        </label>

                                                        <div class="input-group custom-file-button">
                                                            <label class="input-group-text" for="inputGroupFile">@lang("Upload")</label>
                                                            <input type="file" class="form-control" id="inputGroupFile" name="{{ $value->field_name }}">
                                                        </div>
                                                        @error($value->field_name)
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endif
                                            @empty
                                            @endforelse
                                        </div>
                                        <button type="submit" class="cmn-btn mt-3">@lang("Save Changes")</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $('.flatpickr').flatpickr()
    </script>
@endpush
