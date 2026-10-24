@php
    $currencyRes = request()->cookie('currency');
    $currencyCookie = json_decode($currencyRes);
    $zkPanel = \App\Services\PanelRentals::current();   // a rented panel's own domain
@endphp
<nav class="navbar fixed-top navbar-expand-lg zk-nav">
    <div class="container">
        <a class="navbar-brand logo notranslate" href="{{ url('/') }}">
            <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                 alt="{{ basicControl()->site_title }}">
        </a>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbar">
            <div class="offcanvas-header">
                <a class="navbar-brand logo notranslate" href="{{ url('/') }}">
                    <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                         alt="{{ basicControl()->site_title }}">
                </a>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="@lang('Close')"><i
                        class="fa-light fa-xmark"></i></button>
            </div>
            <div class="offcanvas-body align-items-center justify-content-between">
                @if($zkPanel)
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">@lang('Services & prices')</a></li>
                        @if($zkPanel->whatsapp)
                            <li class="nav-item"><a class="nav-link" href="https://wa.me/{{ $zkPanel->whatsapp }}" target="_blank" rel="noopener">@lang('Contact us')</a></li>
                        @endif
                    </ul>
                @else
                    {!! renderHeaderMenu(getHeaderMenuData()) !!}
                @endif
                <div class="d-flex d-lg-none flex-wrap gap-2 mt-4 zk-mobile-prefs">
                    @include(template().'partials.lang_picker', ['zkLangClass' => 'zk-currency', 'zkLangChevron' => true])
                    @if(count($currencies) > 1)
                        <label class="zk-currency" title="@lang('Currency')">
                            <i class="fa-regular fa-coins" aria-hidden="true"></i>
                            <span class="visually-hidden">@lang('Currency')</span>
                            <select class="select-currency" aria-label="@lang('Currency')">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->code }}" {{ isset($currencyCookie->code) && $currency->code == $currencyCookie->code ? 'selected' : '' }}>{{ $currency->code }}</option>
                                @endforeach
                            </select>
                            <i class="fa-regular fa-chevron-down zk-currency__chev" aria-hidden="true"></i>
                        </label>
                    @endif
                </div>
                @guest
                    <div class="d-flex d-lg-none flex-column gap-2 mt-4">
                        <a class="zk-btn zk-btn--ghost" href="{{ route('login') }}">@lang("Log in")</a>
                        <a class="zk-btn zk-btn--primary" href="{{ route('register') }}">@lang("Get started")</a>
                    </div>
                @endguest
            </div>
        </div>


        <div class="nav-right">
            <ul class="custom-nav">
                @if(basicControl()->auto_translate ?? false)
                <li class="nav-item zk-hide-sm">
                    @include(template().'partials.lang_picker', ['zkLangClass' => 'zk-currency', 'zkLangChevron' => true])
                </li>
                @endif
                @if(count($currencies) > 1)
                <li class="nav-item zk-hide-sm">
                    <label class="zk-currency" title="@lang('Currency')">
                        <i class="fa-regular fa-coins" aria-hidden="true"></i>
                        <span class="visually-hidden">@lang('Currency')</span>
                        <select class="select-currency" aria-label="@lang('Currency')">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->code }}" {{ isset($currencyCookie->code) && $currency->code == $currencyCookie->code ? 'selected' : '' }}>{{ $currency->code }}</option>
                            @endforeach
                        </select>
                        <i class="fa-regular fa-chevron-down zk-currency__chev" aria-hidden="true"></i>
                    </label>
                </li>
                @endif
                @guest
                    <li class="nav-item d-none d-lg-block">
                        <a class="zk-btn zk-btn--ghost zk-btn--sm" href="{{ route('login') }}">@lang("Log in")</a>
                    </li>
                    <li class="nav-item d-none d-lg-block">
                        <a class="zk-btn zk-btn--primary zk-btn--sm" href="{{ route('register') }}">@lang("Get started")</a>
                    </li>
                @endguest
                @auth
                    @php
                        $zkMe = auth()->user();
                        $zkInitial = strtoupper(mb_substr($zkMe->username ?: ($zkMe->firstname ?: 'U'), 0, 1));
                    @endphp
                    <li class="nav-item d-none d-lg-block">
                        <a class="zk-btn zk-btn--primary zk-btn--sm" href="{{ route('user.dashboard') }}"><i class="fa-regular fa-gauge"></i> @lang('Dashboard')</a>
                    </li>
                    <li class="nav-item dropdown">
                        <button type="button" class="zk-avatar" data-bs-toggle="dropdown" aria-expanded="false" aria-label="@lang('Account menu')">
                            @if($zkMe->image)
                                <img src="{{ getFile($zkMe->image_driver, $zkMe->image) }}" alt="">
                            @else
                                <span>{{ $zkInitial }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end zk-menu">
                            <li class="zk-menu__head">
                                <strong>{{ $zkMe->username }}</strong>
                                <small>@lang('Balance'): <span class="notranslate">{{ currencyPosition($zkMe->balance) }}</span></small>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i class="fa-regular fa-gauge"></i> @lang('Dashboard')</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.order.create') }}"><i class="fa-regular fa-cart-shopping"></i> @lang('Buy services')</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.add.fund') }}"><i class="fa-regular fa-wallet"></i> @lang('Add funds')</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.order.index') }}"><i class="fa-regular fa-list-check"></i> @lang('My orders')</a></li>
                            @unless($zkPanel)
                            <li><a class="dropdown-item" href="{{ route('user.ticket.list') }}"><i class="fa-regular fa-headset"></i> @lang('Support')</a></li>
                            @endunless
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item zk-menu__out"><i class="fa-regular fa-right-from-bracket"></i> @lang('Sign out')</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
        @guest
            <a class="zk-btn zk-btn--ghost zk-btn--sm d-inline-flex d-lg-none me-2 py-1 px-3" href="{{ route('login') }}" style="height:38px;font-size:14px;border-radius:8px;">
                <i class="fa-regular fa-arrow-right-to-bracket me-1"></i> @lang("Log in")
            </a>
        @endguest
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="@lang('Open menu')">
            <i class="fa-light fa-bars"></i>
        </button>
    </div>
</nav>

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('.select-currency').on('change', function () {
                let currency = $(this).val();
                $('.select-currency').val(currency);
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
