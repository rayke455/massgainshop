@extends(template().'layouts.user')
@section('title', trans('Dashboard'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container custom-container">
            <div class="zk-welcome">
                <div>
                    <h1 class="zk-welcome__title">@lang('Welcome back'), {{ auth()->user()->username }}</h1>
                    <p class="zk-welcome__sub">@lang('What would you like to grow today?')</p>
                </div>
                <div class="zk-welcome__actions">
                    <a href="{{ route('user.order.create') }}" class="zk-btn zk-btn--primary"><i class="fa-regular fa-cart-shopping"></i> @lang('Buy services')</a>
                </div>
            </div>

            <div class="zk-dashboard-guide mb-4">
                <span class="zk-dashboard-guide__icon"><i class="fa-solid fa-sparkles"></i></span>
                <div class="zk-dashboard-guide__copy">
                    <strong>@lang('A faster way to get started')</strong>
                    <span>@lang('Choose a platform, paste your link and let') {{ basicControl()->site_title }} @lang('handle the delivery.')</span>
                </div>
                <a href="{{ route('services') }}" class="zk-dashboard-guide__link">@lang('Browse services') <i class="fa-regular fa-arrow-right"></i></a>
            </div>

            {{-- Only when push notifications (Firebase) are configured; otherwise the prompt can never work. --}}
            @if(!empty($firebaseNotify['apiKey']))
            <div class="row align-items-end firebase-notify" id="firebase-app" translate="no">
                <div v-if="user_foreground == '1' || user_background == '1'">
                    <div class="col-lg-12">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert"
                             v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
                            <div class="d-flex align-items-center">
                                <h4 class="text-primary mb-0 mr-3">
                                    <i class="fa-light fa-bullhorn text-warning"></i></h4>

                                <div class="fire-base d-flex align-items-center">
                                    <p class="mb-0 ms-2">@lang("Please allow your browser to receive instant
                                                        push notifications. Enable it in your notification settings.")</p>
                                    <button type="button" class="cmn-btn rounded-1 ml-auto ms-2"
                                            id="allow-notification">@lang("Allow")
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close" @click.prevent="skipNotification"><i class="fal fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12"
                     v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <h4 class="text-warning mb-0 mr-3">@lang("Attention!")</h4>
                            <div class="fire-base">
                                <p class="mb-0 ms-2">@lang("Please allow your browser to receive instant push
                                                    notifications. Enable it in your notification settings.")</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close" @click.prevent="skipNotification"><i class="fal fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            @php
                $zkRate = (auth()->user()->currency && $currency) ? $currency->conversion_rate : 1;
                $zkMoney = fn($amount) => (auth()->user()->currency && $currency)
                    ? currencyPositionBySelectedCurrency($amount * $zkRate, auth()->user()->currency)
                    : currencyPosition($amount);
                $zkStatus = [
                    'awaiting' => ['Awaiting', 'wait'], 'pending' => ['Pending', 'wait'],
                    'processing' => ['Processing', 'run'], 'progress' => ['In progress', 'run'],
                    'completed' => ['Completed', 'ok'], 'partial' => ['Partial', 'warn'],
                    'canceled' => ['Canceled', 'bad'], 'refunded' => ['Refunded', 'bad'],
                ];
            @endphp

            <div class="zk-stats-row">
                {{-- "Add funds" already lives in the top bar; the card just shows the balance. --}}
                <div class="zk-stat zk-stat--primary">
                    <span class="zk-stat__icon"><i class="fa-regular fa-wallet"></i></span>
                    <span class="zk-stat__label">@lang('Balance')</span>
                    <strong class="zk-stat__value"><span class="notranslate">{{ $zkMoney($walletBalance) }}</span></strong>
                    <span class="zk-stat__link"><span class="notranslate">{{ $zkMoney($totalYearDeposits) }}</span> @lang('deposited this year')</span>
                </div>
                <div class="zk-stat">
                    <span class="zk-stat__icon"><i class="fa-regular fa-chart-line-up"></i></span>
                    <span class="zk-stat__label">@lang('Total spent')</span>
                    <strong class="zk-stat__value"><span class="notranslate">{{ $zkMoney($orders['spent'] ?? 0) }}</span></strong>
                    <span class="zk-stat__meta">@lang('on completed and running orders')</span>
                </div>
                <a href="{{ route('user.order.index') }}" class="zk-stat">
                    <span class="zk-stat__icon"><i class="fa-regular fa-spinner"></i></span>
                    <span class="zk-stat__label">@lang('Active orders')</span>
                    <strong class="zk-stat__value">{{ number_format($orders['active'] ?? 0) }}</strong>
                    <span class="zk-stat__meta">{{ number_format($orders['total'] ?? 0) }} @lang('orders in total')</span>
                </a>
                @unless(\App\Services\PanelRentals::current())
                <a href="{{ route('user.ticket.list') }}" class="zk-stat">
                    <span class="zk-stat__icon"><i class="fa-regular fa-headset"></i></span>
                    <span class="zk-stat__label">@lang('Open tickets')</span>
                    <strong class="zk-stat__value">{{ number_format($tickets['pending'] ?? 0) }}</strong>
                    <span class="zk-stat__meta">@lang('We usually reply within hours')</span>
                </a>
                @endunless
            </div>

            @unless(auth()->user()->rental_panel_id)
            @include(template().'user.partials.vip_card')
            @includeIf(template().'user.partials.free_trial_card')
            @endunless

            <div class="row g-4 mb-4">
                <div class="col-xl-8">
                    <div class="zk-panel h-100">
                        <div class="zk-panel__head">
                            <h2>@lang('Recent orders')</h2>
                            <a href="{{ route('user.order.index') }}">@lang('View all') <i class="fa-regular fa-arrow-right"></i></a>
                        </div>
                        @forelse($recentOrders as $order)
                            @php [$zkLabel, $zkTone] = $zkStatus[$order->status] ?? [ucfirst($order->status), 'wait']; @endphp
                            <div class="zk-order-row">
                                <div class="zk-order-row__main">
                                    <strong>{{ optional($order->service)->service_title ?? __('Service') }}</strong>
                                    <span>#{{ $order->id }} · {{ number_format($order->quantity) }} · {{ \Illuminate\Support\Str::limit($order->link, 38) }}</span>
                                </div>
                                <div class="zk-order-row__side">
                                    <span class="zk-pill zk-pill--{{ $zkTone }}">@lang($zkLabel)</span>
                                    <span class="zk-order-row__price"><span class="notranslate">{{ $zkMoney($order->price) }}</span></span>
                                </div>
                            </div>
                        @empty
                            <div class="zk-empty">
                                <span class="zk-empty__icon"><i class="fa-regular fa-cart-shopping"></i></span>
                                <h3>@lang('No orders yet')</h3>
                                <p>@lang('Pick a service, paste your link and choose a quantity — it takes under a minute.')</p>
                                <a href="{{ route('user.order.create') }}" class="zk-btn zk-btn--primary">@lang('Place your first order')</a>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="zk-panel h-100">
                        <div class="zk-panel__head"><h2>@lang('How ordering works')</h2></div>
                        <ol class="zk-steps-list">
                            <li><span>1</span><div><strong>@lang('Add funds')</strong><p>@lang('Top up your balance with any payment method.')</p></div></li>
                            <li><span>2</span><div><strong>@lang('Choose a service')</strong><p>@lang('Pick the platform, category and service.')</p></div></li>
                            <li><span>3</span><div><strong>@lang('Paste link & quantity')</strong><p>@lang('We start delivering automatically.')</p></div></li>
                        </ol>
                        <a href="{{ route('services') }}" class="zk-btn zk-btn--ghost w-100 mt-2">@lang('See services & prices')</a>
                    </div>
                </div>
            </div>


            <div class="card mt-30">
                <div class="card-header d-flex justify-content-between align-items-center border-0">
                    <h4 class="mb-0">@lang("Latest Transaction")</h4>
                </div>
                <div class="card-body">
                    @if(count($transactions) > 0)
                        <div class="cmn-table">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Transaction ID")</th>
                                        <th scope="col">@lang("Amount")</th>
                                        <th scope="col">@lang("Remarks")</th>
                                        <th scope="col">@lang("Time & Date")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td data-label="@lang("Transaction ID")">
                                                <span>{{ '#' . $transaction->trx_id }}</span>
                                            </td>
                                            <td data-label="@lang("Amount")">
                                            <span
                                                class="text-{{ $transaction->trx_type == "+" ? 'success'  : 'danger' }}">
                                                {{($transaction->trx_type == "+") ? '+': '-'}}
                                                @if(auth()->user()->currency && $currency)
                                                    <span class="notranslate">{{ currencyPositionBySelectedCurrency($transaction->amount * $currency->conversion_rate, auth()->user()->currency) }}</span>
                                                @else
                                                    <span class="notranslate">{{ currencyPosition($transaction->amount) }}</span>
                                                @endif

                                            </span>
                                            </td>
                                            <td data-label="@lang("Remarks")">
                                                <span>@lang($transaction->remarks)</span>
                                            </td>
                                            <td data-label="@lang("Time & Date")">
                                            <span>
                                                {{ dateTime($transaction->created_at) }}
                                            </span>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center p-4">
                            <img class="error-image mb-3"
                                 src="{{ asset('assets/global/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-0">@lang("No transactions available to display.")</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js-lib')
    <script src="{{ asset('assets/global/js/vue.global.prod.min.js') }}"></script>
@endpush


@if(!empty($firebaseNotify['apiKey']))
    @push('script')

        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage,
                isSupported
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "{{$firebaseNotify['apiKey']}}",
                authDomain: "{{$firebaseNotify['authDomain']}}",
                projectId: "{{$firebaseNotify['projectId']}}",
                storageBucket: "{{$firebaseNotify['storageBucket']}}",
                messagingSenderId: "{{$firebaseNotify['messagingSenderId']}}",
                appId: "{{$firebaseNotify['appId']}}",
                measurementId: "{{$firebaseNotify['measurementId']}}"
            };

            if ('Notification' in window && await isSupported()) {
            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
                        requestPermissionAndGenerateToken(registration);
                    }
                ).catch(function (error) {
                });
            } else {
            }

            onMessage(messaging, (payload) => {
                if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                }
            });

            function requestPermissionAndGenerateToken(registration) {
                document.addEventListener("click", function (event) {
                    if (event.target.id == 'allow-notification') {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, {
                                    serviceWorkerRegistration: registration,
                                    vapidKey: "{{$firebaseNotify['vapidKey']}}"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "{{ route('user.save.token') }}",
                                            method: "post",
                                            data: {
                                                token: token,
                                            },
                                            success: function (res) {
                                            }
                                        });
                                        window.newApp.notificationPermission = 'granted';
                                    });
                            } else {
                                window.newApp.notificationPermission = 'denied';
                            }
                        });
                    }
                });
            }
            }
        </script>
        <script>
            window.newApp = Vue.createApp({
                data() {
                    return {
                        user_foreground: '',
                        user_background: '',
                        notificationPermission: 'Notification' in window ? Notification.permission : 'denied',
                        is_notification_skipped: (() => {
                            try { return sessionStorage.getItem('is_notification_skipped') === '1'; }
                            catch (e) { return false; }
                        })()
                    };
                },
                mounted() {
                    this.user_foreground = "{{$firebaseNotify['user_foreground']}}";
                    this.user_background = "{{$firebaseNotify['user_background']}}";
                },
                methods: {
                    skipNotification() {
                        try { sessionStorage.setItem('is_notification_skipped', '1'); } catch (e) {}
                        this.is_notification_skipped = true;
                    }
                }
            }).mount('#firebase-app');
        </script>
    @endpush
@endif

