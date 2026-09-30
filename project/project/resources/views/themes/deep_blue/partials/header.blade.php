<nav class="navbar navbar-expand-lg fixed-top deep-blue-header">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}" alt="@lang('Logo')">
        </a>
        <button
            class="navbar-toggler p-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <i class="fal fa-bars"></i>
        </button>

        @php
            $currencyRes = request()->cookie('currency');
            $currencyCookie = json_decode($currencyRes);
        @endphp

        <div class="collapse navbar-collapse" id="navbarNav">
            {!! renderHeaderMenu(getHeaderMenuData()) !!}

            @guest
                <li class="nav-item d-block d-sm-none">
                    <a class="nav-link {{menuActive('login')}}" href="{{route('login')}}">@lang('Login')</a>
                </li>
            @else
                <li class="nav-item d-block d-sm-none">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">@lang('Logout')</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endguest

            <ul class="currency">
                @if(count($currencies) > 0)
                    <li class="nav-item">
                        <div class="language-box d-flex align-items-center">
                            <i class="fal fa-coins" aria-hidden="true"></i>
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
            </ul>
        </div>
        @guest
            <span class="navbar-text ps-4 d-none d-sm-block">
                <a href="{{route('login')}}">
                    <button class="btn-smm">@lang('Login')</button>
                </a>
            </span>
        @else
            <span class="navbar-text ps-4 d-md-block d-none">
                     <a class="nav-link text-white" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><span>@lang('Logout')</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </span>

            <span class="navbar-text ps-4  d-md-block d-none ">
                    <a href="{{route('user.dashboard')}}">
                        <button class="btn-smm">@lang('Dashboard')</button>
                    </a>
                </span>

            <span class="navbar-text ps-4 d-md-none">
                    <a href="{{route('user.dashboard')}}">
                        <button class="btn-smm icon-width"><i class="fa fa-home"></i></button>
                    </a>
            </span>
        @endguest
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
