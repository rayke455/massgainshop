<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <div class="logo-container">
            <a href="{{ url('/') }}" class="logo d-flex align-items-center">
                <img src="{{ getFile(basicControl()->logo_driver,  basicControl()->logo) }}"
                     alt="Logo">
            </a>
        </div>
        <button onclick="toggleSideMenu()" class="toggle-sidebar toggle-sidebar-btn d-none d-lg-block">
            <i class="fa-light fa-list"></i></button>
    </div>

    <nav class="header-nav ms-auto" id="pushNotificationArea">
        <ul class="nav-icons">
            <li class="nav-item">
                <a id="toggle-btn" class="nav-link d-flex toggle-btn">
                    <i class="fa-light fa-moon" id="moon"></i>
                    <i class="fa-light fa-sun-bright" id="sun"></i>
                </a>
            </li>

            <li class="nav-item d-none d-lg-block d-xl-none">
                <a class="nav-link nav-icon search-bar-toggle" href="#">
                    <i class="fa-regular fa-magnifying-glass"></i>
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

            <li class="nav-item dropdown" v-cloak>
                <a class="nav-link nav-icon" href="javascript:void(0)"
                   :data-bs-toggle="items.length > 0 ? 'dropdown' : ''">
                    <i class="fa-light fa-bell"></i>
                    <span class="badge badge-number" v-cloak>@{{ items.length }}</span>
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
                    <img src="{{ getFile(auth()->user()->image_driver,  auth()->user()->image) }}" alt="Profile"
                         class="rounded-circle">
                    <span
                        class="d-none d-xl-block dropdown-toggle ps-2">{{ auth()->user()->firstname . " " . auth()->user()->lastname }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header d-flex justify-content-center align-items-center text-start">
                        <div class="profile-thum">
                            <img src="{{ getFile(auth()->user()->image_driver,  auth()->user()->image) }}"
                                 alt="User Profile">
                        </div>
                        <div class="profile-content">
                            <h6>{{ auth()->user()->firstname . " " . auth()->user()->lastname }}</h6>
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
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('user.profile') }}">
                            <i class="fa-light fa-user"></i>
                            <span>@lang("My Profile")</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ route('user.password.setting') }}">
                            <i class="fa-sharp fa-light fa-gear"></i>
                            <span>@lang("Password Settings")</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('user.kyc.verification') }}">
                            <i class="fa-light fa-badge-check"></i>
                            <span>@lang("verification center")</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('user.two.step.security') }}">
                            <i class="fa-light fa-shield-check"></i>
                            <span>@lang("2FA Security")</span>
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
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-light fa-right-to-bracket"></i>
                            <span>@lang("Sign Out")</span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

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
