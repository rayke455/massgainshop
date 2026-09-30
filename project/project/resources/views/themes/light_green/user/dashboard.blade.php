@extends(template().'layouts.user')
@section('title', trans('Dashboard'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container custom-container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("dashboard")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("dashboard")</li>
                </ul>
            </div>

            <div class="row align-items-end firebase-notify" id="firebase-app">
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

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="row g-4">
                        <div class="col-xl-4 col-sm-6">
                            <div class="box-card grayish-blue-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                        <i class="fa-regular fa-wallet"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Balance')</h5>
                                    </div>
                                </div>
                                <div class="bottom">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Wallet Balance">
                                        <i class="fa-regular fa-money-bill"></i>
                                        @if(optional(auth()->user())->currency && $currency)
                                            <span
                                                class="fw-semibold">{{ currencyPositionBySelectedCurrency($walletBalance * $currency->conversion_rate, auth()->user()->currency) }}</span>
                                        @else
                                            <span class="fw-semibold">  {{ currencyPosition($walletBalance) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="box-card strong-olive-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                        <i class="fa-regular fa-money-check-dollar"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Deposits')</h5>
                                    </div>
                                </div>
                                <div class="bottom fw-semibold">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Last 7 Days">
                                        <i class="fa-regular fa-money-check-dollar"></i>
                                        <span>
                                             @if(auth()->user()->currency && $currency)
                                                {{ currencyPositionBySelectedCurrency($totalLast7DaysDeposits * $currency->conversion_rate, auth()->user()->currency) }}
                                            @else
                                                {{ currencyPosition($totalLast7DaysDeposits) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top" title="Total">
                                        <i class="fa-regular fa-money-check-dollar"></i>
                                        <span>
                                            @if(auth()->user()->currency && $currency)
                                                {{ currencyPositionBySelectedCurrency($totalYearDeposits * $currency->conversion_rate, auth()->user()->currency) }}
                                            @else
                                                {{ currencyPosition($totalYearDeposits) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-6">
                            <div class="box-card strong-orange-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                        <i class="fa-light fa-cart-shopping"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Orders')</h5>
                                    </div>
                                </div>

                                <div class="bottom fw-semibold">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Processing Order">
                                        <i class="fa-regular fa-spinner"></i>
                                        <span class="">{{ number_format($orders['processing'] ?? 0) }}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Confirmed Order">
                                        <i class="fa-regular fa-box-check"></i>
                                        <span class="">{{ number_format($orders['completed'] ?? 0) }}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Cancel Order">
                                        <i class="fa-regular fa-rectangle-xmark"></i>
                                        <span class="">{{ number_format($orders['canceled'] ?? 0) }}</span>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class=" mt-30">
                        <div class="row g-4">
                            <div class="col-xl-12">
                                <h4 class="mb-20">@lang('Deposit Statistics')</h4>
                                <div id="lineChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <h4 class="mb-20">@lang('Transaction Statistics')</h4>
                            <div id="columnChart"></div>
                        </div>
                        <div class="col-md-12">
                            <div class="box-card grayish-green-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                        <i class="fa-regular fa-headset"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Support Tickets')</h5>
                                    </div>
                                </div>
                                <div class="bottom fw-semibold">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Pending Ticket">
                                        <i class="fa-regular fa-spinner"></i>
                                        <span class="">{{ number_format($tickets['pending'] ?? 0) }}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Answer Ticket">
                                        <i class="fa-regular fa-box-check"></i>
                                        <span class="">{{ number_format($tickets['answered'] ?? 0) }}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="Close Ticket">
                                        <i class="fa-light fa-rectangle-xmark"></i>
                                        <span class="">{{ number_format($tickets['closed'] ?? 0) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                    {{ currencyPositionBySelectedCurrency($transaction->amount * $currency->conversion_rate, auth()->user()->currency) }}
                                                @else
                                                    {{ currencyPosition($transaction->amount) }}
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
    <script src="{{ asset('assets/global/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/vue.global.prod.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        const baseCurrSymbol = "{{ $currency->symbol ?? basicControl()->currency_symbol }}";
        const baseCurrency = "{{ $currency->code ?? basicControl()->base_currency }}";
        let monthlyDeposits = @json($monthlyDeposits);

        function calculateMaxValue(data) {
            const maxValue = Math.max(...Object.values(data));
            const increment = 1000;
            return Math.ceil(maxValue / increment) * increment;
        }

        const maxYValue = calculateMaxValue(monthlyDeposits) + 1000;
        if ($('#lineChart').length) {
            let options = {
                theme: {
                    mode: $('.dark-theme').length ? 'dark' : 'light'
                },
                series: [{
                    name: "Deposit",
                    data: Object.values(monthlyDeposits).map(value => parseFloat(value).toFixed(2))
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    background: $('.dark-theme').length ? '#232327' : '',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#0400f599'],
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return baseCurrSymbol + val;
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    align: 'left'
                },
                grid: {
                    borderColor: '',
                    row: {
                        colors: [$('.dark-theme').length ? '#232327' : '#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: Object.keys(monthlyDeposits),
                    title: {
                        text: ''
                    }
                },
                yaxis: {
                    min: 0,
                    max: maxYValue
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return baseCurrSymbol + val;
                        }
                    }
                }
            };

            let chart = new ApexCharts(document.querySelector("#lineChart"), options);
            chart.render();
        }

        if ($('#columnChart').length) {
            let options = {
                theme: {
                    mode: $('.dark-theme').length ? 'dark' : 'light',
                },
                series: [{
                    name: 'Total Deposit',
                    data: @json($totalTransaction)
                }, {
                    name: 'Total Transactions',
                    data: @json($totalSend)
                }, {
                    name: 'Total Received',
                    data: @json($totalReceived)
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    background: $('.dark-theme').length ? '#232327' : ''
                },
                colors: ['#ffc107', '#706fc7', '#3e3e3e'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: @json($months),
                },
                yaxis: {},
                fill: {
                    opacity: 1,
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return baseCurrSymbol + val;
                        }
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#columnChart"), options);
            chart.render();
        }
    </script>
@endpush

@if($firebaseNotify)
    @push('script')

        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
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
        </script>
        <script>
            window.newApp = Vue.createApp({
                data() {
                    return {
                        user_foreground: '',
                        user_background: '',
                        notificationPermission: Notification.permission,
                        is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                    };
                },
                mounted() {
                    sessionStorage.clear();

                    this.user_foreground = "{{$firebaseNotify['user_foreground']}}";
                    this.user_background = "{{$firebaseNotify['user_background']}}";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1');
                        this.is_notification_skipped = true;
                    }
                }
            }).mount('#firebase-app');
        </script>
    @endpush
@endif

