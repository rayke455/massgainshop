@extends(template().'layouts.user')
@section('title',trans('Profile'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Profile")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Profile")</li>
                </ol>
            </nav>
        </div>

        <div class="section dashboard">
            <div class="row">
                <div class="account-settings-navbar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('user.profile') }}"><i
                                    class="fa-regular fa-user"></i>@lang("profile")</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.password.setting') }}">
                                <i class="fa-light fa-lock"></i> @lang("Password Setting")</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.notification.settings') }}"><i
                                    class="fa-regular fa-link"></i>@lang("Notification")</a>
                        </li>
                    </ul>
                </div>

                <div class="account-settings-profile-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">@lang("Profile Information")
                            </h5>
                            <div class="profile-details-section">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="image-area">
                                        <img id="profile-img"
                                             src="{{ getFile(optional(auth()->user())->image_driver, optional(auth()->user())->image) }}"
                                             alt="Profile">
                                    </div>
                                    <div class="btn-area">
                                        <div class="btn-area-inner d-flex">
                                            <div class="cmn-file-input">
                                                <label for="formFile"
                                                       class="form-label">@lang("Upload New Photo")</label>
                                                <input class="form-control" type="file" id="formFile"
                                                       onchange="previewImage('profile-img')" name="image">
                                            </div>
                                        </div>
                                        <small>@lang("Allowed JPG, GIF or PNG. Max size of 5MB")</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <form action="{{ route('user.profile.update') }}" method="POST">
                                @csrf
                                <div class="profile-form-section">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="firstname" class="form-label">@lang("First Name")</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname"
                                                   value="{{ old('firstname', auth()->user()->firstname) }}"
                                                   autocomplete="off">
                                            @error('firstname')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastname" class="form-label">@lang("last name")</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname"
                                                   value="{{ old('lastname', auth()->user()->lastname) }}"
                                                   autocomplete="off">
                                            @error('lastname')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="e-mail" class="form-label">@lang("e-mail")</label>
                                            <input type="email" class="form-control" id="e-mail" name="email"
                                                   value="{{ old('email', auth()->user()->email) }}"
                                                   autocomplete="off" readonly>
                                            @error('email')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone_number"
                                                   class="form-label">@lang("phone number")</label><br>
                                            <input type="hidden" id="phone_number" name="phone_code" value="+880">
                                            <input id="telephone" class="form-control" name="phone" type="tel"
                                                   value="{{ old('phone', auth()->user()->phone) }}">
                                            @error('phone')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="address" class="form-label">@lang("address")</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                   value="{{ old('address', auth()->user()->address_one) }}"
                                                   autocomplete="off">
                                            @error('address')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="state" class="form-label">@lang("state")</label>
                                            <input type="text" class="form-control" id="state" name="state"
                                                   value="{{ old('state', auth()->user()->state) }}"
                                                   autocomplete="off">
                                            @error('state')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="zipcode" class="form-label">@lang("zip code")</label>
                                            <input type="text" class="form-control" id="zipcode" name="zipcode"
                                                   value="{{ old('zipcode', auth()->user()->zip_code) }}"
                                                   autocomplete="off">
                                            @error('zipcode')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">@lang("country")</label>
                                            <select class="cmn-select2" name="country" id="country_name">
                                                <option value="" disabled selected>@lang("Select Country")</option>
                                                @forelse($countries as $country)
                                                    <option
                                                        value="{{ $country['name'] }}" {{ old('country', $country['name']) == auth()->user()->country ? 'selected' : '' }}>
                                                        {{ $country['name'] }}
                                                    </option>
                                                @empty
                                                @endforelse
                                            </select>
                                            <input type="hidden" name="country_code" id="country_code">
                                            @error('country')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">@lang("language")</label>
                                            <select class="cmn-select2" name="language">
                                                <option value="" selected disabled>@lang("Select Language")</option>
                                                @forelse($languages as $lang)
                                                    <option
                                                        value="{{ $lang->id }}" {{ old('language', $lang->id) == auth()->user()->language_id ? 'selected' : '' }}>@lang($lang->name)</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('language')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">@lang("Time zone")</label>
                                            <select class="cmn-select2" name="time_zone">
                                                <option value="">@lang("Select Timezone")</option>
                                                @foreach(timezone_identifiers_list() as $key => $value)
                                                    <option
                                                        value="{{$value}}" {{  (old('time_zone', auth()->user()->time_zone) == $value ? ' selected' : '') }}>{{ __($value) }}</option>
                                                @endforeach
                                            </select>
                                            @error('time_zone')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="btn-area d-flex g-3">
                                        <button type="submit" class="cmn-btn">@lang("save changes")</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </main><!-- End #main -->
@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset(template(true). 'css/intlTelInput.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset(template(true). 'js/intlTelInput.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        function previewFile() {
            const preview = document.getElementById('previewImage');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }

        $(document).ready(function () {

            $('#formFile').on('change', function () {

                let formData = new FormData();
                formData.append('file', $(this)[0].files[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('user.profile.image.update') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            Notiflix.Notify.success("Profile image updated.");
                        }
                    },
                    error: function (xhr, status, error) {
                    }
                });
            });

            const input = document.querySelector("#telephone");

            window.intlTelInput(input, {
                initialCountry: "{{ auth()->user()->country_code }}",
                separateDialCode: true,
            });

            $('.iti__country-list li').on('click', function () {
                $("#country").val('+' + $(this).data('dial-code'));
            })

            const country_name = $('#country_name').val();
            selectedCountry(country_name);

            function selectedCountry(country_name) {
                let countries = @json(config('country'));
                let selectedCountry = countries.find(country => country.name === country_name);
                $('#country_code').val(selectedCountry.code);
            }
        });
    </script>
@endpush


