@extends(template().'layouts.user')
@section('title',trans('Password Setting'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Password Setting")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">@lang("Dashboard")</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Password Setting")</li>

                </ol>
            </nav>
        </div>

        <div class="section dashboard">
            <div class="row">
                <div class="account-settings-navbar">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('user.profile') }}"><i
                                    class="fa-regular fa-user"></i>@lang("profile")</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('user.password.setting') }}">
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
                        <div class="card-header border-0 text-start text-md-center">
                            <h5 class="card-title">@lang("Password Setting")</h5>
                            <p>@lang("Set your password correctly.")</p>
                        </div>
                        <div class="card-body pt-0">
                            <form method="post" action="{{ route('user.update.password') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8 mx-auto">
                                        <div class="col-12">
                                            <label for="currentPassword"
                                                   class="form-label">@lang("Current Password")</label>
                                            <input type="password" class="form-control" id="currentPassword"
                                                   name="current_password" autocomplete="off">
                                            @error('current_password')
                                            <div class="invalid-feedback d-block"> {{ __($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="newPassword"
                                                   class="form-label">@lang("New Password")</label>
                                            <input type="password" class="form-control" id="newPassword"
                                                   name="password">
                                            @error('password')
                                            <div class="invalid-feedback d-block"> {{ __($message) }}</div>
                                            @enderror
                                        </div>
                                        <div class="btn-area mt-3">
                                            <button type="submit" class="cmn-btn">@lang("Submit")</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection


