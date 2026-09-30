@extends(template().'layouts.user')
@section('title',trans('Password Setting'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("Password Setting")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Password Setting")</li>
                </ul>
            </div>
            <div class="section dashboard">
                <div class="row">
                    <div class="account-settings-navbar">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="{{ route('user.profile') }}"><i
                                        class="fa-light fa-user"></i>@lang("profile")</a>
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
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <label for="currentPassword"
                                                           class="form-label">@lang("Current Password")</label>
                                                    <input type="password" class="form-control" id="currentPassword"
                                                           name="current_password" autocomplete="off" required>
                                                    @error('current_password')
                                                    <div class="invalid-feedback d-block"> {{ __($message) }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <label for="newPassword"
                                                           class="form-label">@lang("New Password")</label>
                                                    <input type="password" class="form-control" id="newPassword"
                                                           name="password" required>
                                                    @error('password')
                                                    <div class="invalid-feedback d-block"> {{ __($message) }}</div>
                                                    @enderror
                                                </div>
                                                <div class="btn-area">
                                                    <button type="submit" class="cmn-btn rounded-1">@lang("Submit")</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


