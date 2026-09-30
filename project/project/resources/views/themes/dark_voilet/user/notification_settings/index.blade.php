@extends(template().'layouts.user')
@section('title',trans('Notification Permission'))
@section('content')

    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Notification Permission")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Notification Permission")</li>

                </ol>
            </nav>
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
                            <a class="nav-link" href="{{ route('user.password.setting') }}">
                                <i class="fa-light fa-lock"></i> @lang("Password Setting")</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('user.notification.settings') }}"><i
                                    class="fa-regular fa-link"></i>@lang("Notification")</a>
                        </li>
                    </ul>
                </div>

                <form action="{{ route('user.notification.permission') }}" method="post">
                    @csrf
                    <div class="account-settings-profile-section">
                        <div class="card">
                            <div class="card-header border-0 pb-0">
                                <h5 class="card-title">@lang("Notification Permission")</h5>
                            </div>
                            <div class="card-body pt-0">
                                <p>@lang("We need permission from your browser to show notifications.") <strong>
                                        @lang("Request Permission")</strong>
                                </p>
                                <div class="cmn-table mt-20">
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead>
                                            <tr>
                                                <th style="width: 15%;" scope="col">@lang("Name")</th>
                                                <th style="width: 5%;" scope="col">
                                                    <i class="fa-light fa-envelope"></i> @lang("Email")
                                                </th>
                                                <th style="width: 5%;" scope="col">
                                                    <i class="fa-light fa-message-sms"></i> @lang("SMS")
                                                </th>
                                                <th style="width: 3%;" scope="col">
                                                    <i class="fa-light fa-laptop"></i> @lang("App")
                                                </th>
                                                <th style="width: 5%;" scope="col">
                                                    <i class="fa-light fa-mobile"></i> @lang("Push")
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($notificationTemplates as $key => $template)
                                                <tr>
                                                    <td data-label="Type">
                                                        <div class="d-flex align-items-center">
                                                            <span>@lang($template->name)</span>
                                                        </div>
                                                    </td>
                                                    <td data-label="✉️ Email">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="templates[{{ $template->id }}][mail]"
                                                                   role="switch" id="emailSwitchChecked{{ $key }}"
                                                                   value="1"
                                                                {{ $template->status['mail'] ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td data-label="🖥 Browser">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="templates[{{ $template->id }}][sms]"
                                                                   role="switch" id="smsSwitchChecked{{ $key }}"
                                                                   value="1"
                                                                {{ $template->status['sms'] ? 'checked' : '' }}>
                                                        </div>
                                                    </td>

                                                    <td data-label="👩🏻‍💻 App">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="templates[{{ $template->id }}][in_app]"
                                                                   role="switch" id="appSwitchChecked{{ $key }}"
                                                                   value="1"
                                                                {{ $template->status['in_app'] ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td data-label="👩🏻‍💻 Push">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="templates[{{ $template->id }}][push]"
                                                                   role="switch" id="pushSwitchChecked{{ $key }}"
                                                                   value="1"
                                                                {{ $template->status['push'] ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="cmn-btn w-25">@lang("Save Changes")</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection



