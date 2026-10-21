<!-- Top bar start -->
@php
    $zkUser = auth()->user();
    $zkBalance = $zkUser->currency
        ? currencyPositionBySelectedCurrency($zkUser->balance * ($curr ? $curr->conversion_rate : 1), $zkUser->currency)
        : currencyPosition($zkUser->balance);
@endphp
<header class="zk-topbar">
        <button class="zk-icon-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#zkSidebar"
                aria-controls="zkSidebar" aria-label="@lang('Open menu')">
            <i class="fa-regular fa-bars"></i>
        </button>
        <a class="zk-topbar__logo d-lg-none" href="{{ route('user.dashboard') }}">
            <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}" alt="{{ basicControl()->site_title }}">
        </a>

        <a href="{{ route('user.add.fund') }}" class="zk-balance" title="@lang('Add funds')">
            <span class="zk-balance__label">@lang('Balance')</span>
            <strong class="zk-balance__amount notranslate">{{ $zkBalance }}</strong>
            <span class="zk-balance__add"><i class="fa-regular fa-plus"></i><span class="d-none d-sm-inline"> @lang('Add funds')</span></span>
        </a>

        <div class="nav-right" id="pushNotificationArea" translate="no">
            <ul class="custom-nav header-nav">
                <li>
                    <a id="toggle-btnq" class="nav-link d-flex toggle-btn">
                        <i class="fa-light fa-moon" id="moon"></i>
                        <i class="fa-light fa-sun-bright" id="sun"></i>
                    </a>
                </li>

                @if(basicControl()->auto_translate ?? false)
                <li class="nav-item">
                    @include(template().'partials.lang_picker', ['zkLangClass' => 'language-box', 'zkLangSelectClass' => 'form-select'])
                </li>
                @endif
                <li class="nav-item d-none d-md-block">
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
                        <span class="badge badge-number" v-cloak>@{{ items.length }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications"
                         v-if="items.length > 0">
                        <div class="dropdown-header" v-cloak>
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
                            class="d-none d-xl-block dropdown-toggle ps-2">{{ trim(optional(auth()->user())->firstname . ' ' . optional(auth()->user())->lastname) ?: optional(auth()->user())->username }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header d-flex justify-content-center align-items-center text-start">
                            <div class="profile-thumb">
                                <img
                                    src="{{ getFile(optional(auth()->user())->image_driver, optional(auth()->user())->image) }}"
                                    alt="Profile Image">
                            </div>
                            <div class="profile-content">
                                <h6>{{ trim(optional(auth()->user())->firstname . ' ' . optional(auth()->user())->lastname) ?: optional(auth()->user())->username }}</h6>
                                <span>
                                    @if(auth()->user()->currency)
                                        <span class="notranslate">{{ currencyPositionBySelectedCurrency(optional(auth()->user())->balance * ($curr ?$curr->conversion_rate:1), auth()->user()->currency) }}</span>
                                    @else
                                        <span class="notranslate">{{ currencyPosition(optional(auth()->user())->balance) }}</span>
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
                        @unless(\App\Services\PanelRentals::current())
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
                        @endunless
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

                        @unless(\App\Services\PanelRentals::current())
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)"
                               onclick="showPwa()">
                                <i class="fal fa-download need-help-icon"></i>
                                <span>@lang('Install PWA')</span>
                            </a>
                        </li>
                        @endunless

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ \App\Services\PanelRentals::current() ? route('rental.support') : url('faq') }}">
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
</header>
<!-- Top bar end -->


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
