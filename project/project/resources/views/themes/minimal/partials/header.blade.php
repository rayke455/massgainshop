<header id="hero" class="minimal-header">
    <nav id="navbar">
        <div class="container-fluid px-md-5">
            <div class="navbar navbar-expand-lg mx-lg-5">

                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}" alt="Logo">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#smmnavbar">
                    <div class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>

                @php
                    $currencyRes = request()->cookie('currency');
                    $currencyCookie = json_decode($currencyRes);
                @endphp

                <div class="collapse navbar-collapse" id="smmnavbar">
                    {!! renderHeaderMenu(getHeaderMenuData()) !!}

                    <ul class="navbar-nav nav-registration">
                        @guest
                            @if(count($currencies) > 0)
                                <li class="nav-item">
                                    <div class="language-box">
                                        <i class="fal fa-coins"></i>
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

                            <li class="nav-item mr-5">
                                <a class="{{menuActive('login')}} nav-link"
                                   href="{{route('login')}}"><span>@lang('Sign In')</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="{{menuActive('register')}} nav-link active sign-up"
                                   href="{{route('register')}}"><span>@lang('Sign up')</span></a>
                            </li>
                        @endguest
                        @auth
                            <li class="nav-item mr-5">
                                <a class="{{menuActive('user.dashboard')}} nav-link"
                                   href="{{route('user.dashboard')}}"><span>@lang('Dashboard')</span></a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><span>@lang('Logout')</span></a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

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
