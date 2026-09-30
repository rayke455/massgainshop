<!-- Header_area_start -->
<div class="header_area position-fixed light-orange-header">
    <!-- Nav_area_start -->
    <div class="nav_area">
        <nav class="navbar navbar-expand-lg">
            <div class="container custom_nav">
                <a class="logo" href="{{ url('/') }}"><img
                        src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                        alt="@lang('Logo')"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="bars"><i class="fal fa-bars"></i></span>
                </button>

                @php
                    $currencyRes = request()->cookie('currency');
                    $currencyCookie = json_decode($currencyRes);
                @endphp

                <div class="collapse navbar-collapse" id="navbarNavDropdown">

                    <ul class="navbar-nav ms-auto text-center align-items-center">
                        {!! renderHeaderMenu(getHeaderMenuData()) !!}
                        @if(count($currencies) > 0)
                            <li class="nav-item">
                                <div class="language-box d-flex align-items-center">
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
                        @guest
                            <li class="nav-item">
                                <a class="nav-link login_btn top-right-radius-0 {{menuActive('login')}}"
                                   href="{{route('login')}}">@lang('login')</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><span>@lang('Logout')</span></a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link login_btn top-right-radius-0"
                                   href="{{route('user.dashboard')}}">@lang('Dashboard')</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <!-- Nav_area_end -->
</div>
<!-- Header_area_end -->


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
