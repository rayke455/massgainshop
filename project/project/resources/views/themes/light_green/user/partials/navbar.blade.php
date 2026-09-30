<!-- Nav section start -->
<nav class="navbar fixed-top navbar-expand-lg ">
    <div class="container custom-container">
        <a class="navbar-brand logo" href="{{ url('/') }}"><img
                src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}" alt="Logo"></a>
        <button class="navbar-toggler d-none d-lg-block" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <i class="fa-light fa-list"></i>
        </button>
        <div class="offcanvas offcanvas-end mobile-nav-offcanvas" tabindex="-1" id="offcanvasNavbar"
             aria-labelledby="offcanvasNavbar">
            <div class="offcanvas-header">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="logo" src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                         alt="Logo"></a>
                <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                        class="fa-light fa-arrow-right"></i></button>
            </div>
            <div class="offcanvas-body align-items-center justify-content-between">
                <ul class="navbar-nav m-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.dashboard') }}" aria-current="page"
                           href="{{ route('user.dashboard') }}">@lang("Dashboard")</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ menuActive(['user.order.create', 'user.mass.order', 'user.show.draft.order', 'user.order.index', 'user.show.order.refill', 'user.show.drip.feed']) }}"
                           href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            @lang("order")
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.order.create') }}">@lang("New Order")</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.mass.order') }}">@lang("Mass Order")</a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('user.show.draft.order') }}">@lang("Draft Mass Order")</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.order.index') }}">@lang("All Order")</a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('user.show.order.refill') }}">@lang("Refill Order")</a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('user.show.drip.feed') }}">@lang("Drip Feed")</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('services') }}">@lang("services")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.add.fund') }}"
                           href="{{ route('user.add.fund') }}">@lang("add Fund")</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ menuActive(['user.fund.index','user.transaction.history', 'user.kyc.verification.history']) }}"
                           href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            @lang("history")
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('user.fund.index') }}">@lang("deposit")</a></li>
                            <li><a class="dropdown-item"
                                   href="{{ route('user.transaction.history') }}">@lang("transaction")</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('user.kyc.verification.history') }}">
                                    @lang("KYC verification")</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.notice') }}"
                           href="{{ route('user.notice') }}">@lang("notice")</a>
                    </li>

                    @if(Module::has('ChildPanel') && Module::isEnabled('ChildPanel'))
                        @include('childpanel::main_user.light-sidebar-push')
                    @endif

                </ul>
            </div>
        </div>
        <div class="nav-right" id="pushNotificationArea">
            <ul class="custom-nav header-nav">
                <li>
                    <a id="toggle-btnq" class="nav-link d-flex toggle-btn">
                        <i class="fa-light fa-moon" id="moon"></i>
                        <i class="fa-light fa-sun-bright" id="sun"></i>
                    </a>
                </li>

                <li class="nav-item">
                    @if(count($currencies) > 0)
                        <div class="language-box">
                            <i class="fa-light fa-coins"></i>
                            <select class="form-select" @change="selectCurrency($event)">
                                @forelse($currencies as $currency)
                                    <option
                                        value="{{ $currency->code }}" {{ auth()->user()->currency == $currency->code ? 'selected' : '' }}>{{ $currency->code }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    @endif
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="javascript:void(0)"
                       :data-bs-toggle="items.length > 0 ? 'dropdown' : ''">
                        <i class="fa-light fa-bell"></i>
                        <span class="badge badge-number">@{{ items.length }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications"
                         v-if="items.length > 0">
                        <div class="dropdown-header">
                            @lang("You have") @{{ items.length }} @lang("new notifications")
                        </div>
                        <div class="dropdown-body">
                            <div class="notification-item" v-for="(item, index) in items">
                                <a href="javascript:void(0)" @click.prevent="readAt(item.id, item.description.link)">
                                    <i v-if="item.description" :class="item.description.icon"></i>
                                    <div>
                                        <h4 v-if="item.description" v-html="item.description.text"></h4>
                                        <p v-cloak>@{{ item.formatted_date }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <a href="javascript:void(0)" v-if="items.length > 0"
                               @click.prevent="readAll">@lang("Clear All Notification")</a>
                            <a href="javascript:void(0)" v-else>@lang("You have no notifications.")</a>
                        </div>
                    </div>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="javascript:void(0)"
                       data-bs-toggle="dropdown">
                        <img
                            src="{{ getFile(optional(auth()->user())->image_driver, optional(auth()->user())->image) }}"
                            alt="Profile" class="rounded-circle">
                        <span
                            class="d-none d-xl-block dropdown-toggle ps-2">{{ optional(auth()->user())->firstname . ' ' . optional(auth()->user())->lastname }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header d-flex justify-content-center align-items-center text-start">
                            <div class="profile-thumb">
                                <img
                                    src="{{ getFile(optional(auth()->user())->image_driver, optional(auth()->user())->image) }}"
                                    alt="Profile Image">
                            </div>
                            <div class="profile-content">
                                <h6>{{ optional(auth()->user())->firstname . ' ' . optional(auth()->user())->lastname }}</h6>
                                <span>
                                    @if(auth()->user()->currency)
                                        {{ currencyPositionBySelectedCurrency(optional(auth()->user())->balance * ($curr ?$curr->conversion_rate:1), auth()->user()->currency) }}
                                    @else
                                        {{ currencyPosition(optional(auth()->user())->balance) }}
                                    @endif
                                </span>
                            </div>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                                <i class="fa-light fa-user"></i>
                                <span>@lang("My Profile")</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.referral') }}">
                                <i class="fa-light fa-user-group referral-icon"></i>
                                <span>@lang("My Referral")</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                               href="{{ route('user.referral.bonus') }}">
                                <i class="fa-light fa-circle-dollar-to-slot referral-bonus-icon"></i>
                                <span>@lang("Referral Bonus")</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.api.docs') }}">
                                <i class="fa-light fa-link api-setting-icon"></i>
                                <span>@lang("API Settings")</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('user.ticket.list') }}">
                                <i class="fa-light fa-headset create-ticket-icon"></i>
                                <span>@lang("Support Tickets")</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                               href="{{ route('user.two.step.security') }}">
                                <i class="fa-light fa-lock two-fa-icon"></i>
                                <span>@lang("2FA Security")</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center"
                               href="{{ route('user.kyc.verification') }}">
                                <i class="fa-light fa-badge-check verification-icon"></i>
                                <span>@lang("Verification Center")</span>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)"
                               onclick="showPwa()">
                                <i class="fal fa-download need-help-icon"></i>
                                <span>@lang('Install PWA')</span>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ url('faq') }}">
                                <i class="fa-light fa-circle-question need-help-icon"></i>
                                <span>@lang("Need Help?")</span>
                            </a>
                        </li>
                        <li>
                            @auth
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-light fa-right-from-bracket sign-out"></i>
                                    <span>@lang("Sign Out")</span>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </a>
                            @endauth
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


@push('js-lib')
    <script src="{{ asset('assets/global/js/vue.global.prod.min.js') }}"></script>
@endpush

@push('script')
    @auth
        <script>
            'use strict';
            let vueApp = Vue.createApp({
                data() {
                    return {
                        items: [],
                    }
                },
                beforeMount() {
                    this.getNotifications();
                    this.pushNewItem();
                },
                methods: {
                    getNotifications() {
                        let app = this;
                        axios.get("{{ route('user.push.notification.show') }}")
                            .then(function (res) {
                                app.items = res.data;
                            })
                    },
                    readAt(id, link) {
                        let app = this;
                        let url = "{{ route('user.push.notification.readAt', 0) }}";
                        url = url.replace(/.$/, id);
                        axios.get(url)
                            .then(function (res) {
                                if (res.status) {
                                    app.getNotifications();
                                    if (link !== '#') {
                                        window.location.href = link
                                    }
                                }
                            })
                    },
                    readAll() {
                        let app = this;
                        let url = "{{ route('user.push.notification.readAll') }}";
                        axios.get(url)
                            .then(function (res) {
                                if (res.status) {
                                    app.items = [];
                                }
                            })
                    },
                    pushNewItem() {
                        let app = this;
                        Pusher.logToConsole = false;
                        let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                            encrypted: true,
                            cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                        });
                        let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                        channel.bind('App\\Events\\UserNotification', function (data) {
                            app.items.unshift(data.message);
                        });
                        channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                            app.getNotifications();
                        });
                    },
                    selectCurrency(event) {
                        let route = "{{ route('user.selected.currency') }}";
                        let selectedCurrency = event.target.value;

                        axios.get(route, {params: {currency: selectedCurrency}})
                            .then(function (res) {
                                window.location.reload();
                            })
                            .catch(function (error) {
                                console.error(error);
                            });
                    }
                },
            }).mount('#pushNotificationArea');
        </script>
    @endauth
@endpush
