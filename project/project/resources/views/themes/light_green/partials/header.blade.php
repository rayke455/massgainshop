<nav class="navbar fixed-top navbar-expand-lg {{ url('/') == request()->url() ? 'transparent' : '' }} ">
    <div class="container">
        <a class="navbar-brand logo" href="{{ url('/') }}">
            <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}" alt="@lang("Logo")">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar">
            <i class="fa-light fa-list"></i>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbar">
            <div class="offcanvas-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="logo" src="{{ getFile(basicControl()->admin_logo_driver,basicControl()->admin_logo) }}"
                         alt="@lang("Logo")">
                </a>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                        class="fa-light fa-arrow-right"></i></button>
            </div>
            <div class="offcanvas-body align-items-center justify-content-between">
                {!! renderHeaderMenu(getHeaderMenuData()) !!}
                <ul>
                    <li class="nav-item">
                        <a class="nav-link login-btn" href="{{ route('login') }}"><i
                            class="login-icon fa-light fa-right-to-bracket"></i><span
                            class="d-md-none">@lang("Login")</span>
                        </a>
                    </li>
                </ul>

            </div>
        </div>

        @php
            $currencyRes = request()->cookie('currency');
            $currencyCookie = json_decode($currencyRes);
        @endphp

        <div class="nav-right">
            <ul class="custom-nav">
                @if(count($currencies) > 0)
                <li class="nav-item">
                    <div class="language-box">
                        <i class="fa-regular fa-coins"></i>
                        <select class="form-select select-currency">
                            @forelse($currencies as $currency)
                                <option
                                    value="{{ $currency->code }}" {{ isset($currencyCookie->code) && $currency->code == $currencyCookie->code ? 'selected' : '' }}>
                                    {{ $currency->code }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                </li>
                @endif
                @guest
                    <li class="nav-item">
                        <a class="nav-link login-btn" href="{{ route('login') }}"><i
                                class="login-icon fa-light fa-right-to-bracket"></i><span
                                class="d-none d-md-block">@lang("Login")</span></a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item">
                        <div class="profile-box">
                            <div class="profile">
                                <img
                                    src="{{ getFile(optional(auth()->user())->image_driver, optional(auth()->user())->image) }}"
                                    class="img-fluid" alt="@lang("Profile Image")">
                            </div>
                            <ul class="user-dropdown">
                                <li>
                                    <a href="{{ route('user.dashboard') }}"> <i
                                            class="fal fa-user"></i> @lang("View Profile")
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.ticket.list') }}"> <i
                                            class="fal fa-user-headset"></i> @lang("Support Ticket") </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.profile') }}"> <i
                                            class="fal fa-user-cog"></i> @lang("My Profile")
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fal fa-sign-out-alt"></i> @lang("Sign Out")
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              class="d-none">
                                            @csrf
                                        </form>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('.select-currency').on('change', function () {
                let currency = $('.select-currency').val();
                $.ajax({
                    url: "{{ route('set.currency') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        currency: currency,
                    },
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function (error) {

                    }
                });
            })
        });
    </script>
@endpush
