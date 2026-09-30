<div class="col-xl-5">
    <div class="row g-5">
        <div class="col-xl-6 col-sm-6 col-lg-3 mb-5">
            <!-- Card -->
            <a class="card user-card card-hover-shadow" href="{{route('admin.users', 'child_panel')}}"
               id="userRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-user"></i>
                        <h6 class="card-subtitle">@lang('Total Users')</h6>
                    </div>

                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit userRecord-totalUsers"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartUserRecordsGraph">
                                </canvas>
                            </div>

                        </div>
                    </div>
                    <span class="badge userRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="userRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 userRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>
        <div class="col-xl-6 col-sm-6 col-lg-3 mb-5">
            <a class="card user-card card-hover-shadow" href="{{route('admin.order', 'list')}}"
               id="orderRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-cart-shopping"></i>
                        <h6 class="card-subtitle">@lang('Total Orders')</h6>
                    </div>

                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit orderRecord-totalOrders"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartOrderRecordsGraph">
                                </canvas>
                            </div>
                        </div>
                    </div>
                    <span class="badge orderRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="orderRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 orderRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-sm-6 col-lg-3">
            <a class="card user-card card-hover-shadow" href="{{route('admin.order', 'pending')}}"
               id="pendingOrderRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-cart-shopping"></i>
                        <h6 class="card-subtitle">@lang('Pending Orders')</h6>
                    </div>

                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit pendingOrderRecord-totalPendingOrder"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartPendingOrderRecordsGraph">
                                </canvas>
                            </div>
                        </div>
                    </div>
                    <span class="badge pendingOrderRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="pendingOrderRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 pendingOrderRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>


        <div class="col-xl-6 col-sm-6 col-lg-3">
            <a class="card user-card card-hover-shadow h-100" href="{{ route("admin.transaction") }}"
               id="transactionRecord">
                <div class="card-body">
                    <div class="card-title-top">
                        <i class="fa-light fa-credit-card"></i>
                        <h6 class="card-subtitle">@lang("This Month Transactions")</h6>
                    </div>
                    <div class="row align-items-center gx-2 mb-1">
                        <div class="col-6">
                            <h2 class="card-title text-inherit transactionRecord-totalTransaction"></h2>
                        </div>
                        <div class="col-6">
                            <div class="chartjs-custom" style="height: 3rem;">
                                <canvas class="" id="chartTransactionRecordsGraph">
                                </canvas>
                            </div>
                        </div>
                    </div>
                    <span class="badge transactionRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="transactionRecord-followupGrap"></span>%
                      </span>
                    <span
                        class="text-body fs-6 ms-1 transactionRecord-chartPercentageIncDec"></span>
                </div>
            </a>
        </div>
    </div>
</div>

@push('script')
    <script>
        Notiflix.Block.standard('#userRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartUserRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartUserRecordsGraph = HSCore.components.HSChartJS.getItem('chartUserRecordsGraph');

        updateChartUserRecordsGraph();

        async function updateChartUserRecordsGraph() {
            let $url = "{{ route('admin.chartUserRecords') }}"
            await axios.get($url, {
                params: {
                    type: 'child_panel'
                }
            })
                .then(function (res) {
                    $('.userRecord-totalUsers').text(res.data.userRecord.totalUsers);
                    $('.userRecord-followupGrapClass').addClass(res.data.userRecord.followupGrapClass);
                    $('.userRecord-followupGrap').text(res.data.userRecord.followupGrap);
                    $('.userRecord-chartPercentageIncDec').text(`from ${res.data.userRecord.chartPercentageIncDec}`);

                    chartUserRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartUserRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartUserRecordsGraph.update();
                    Notiflix.Block.remove('#userRecord');
                })
                .catch(function (error) {

                });
        }

    </script>

    <script>
        Notiflix.Block.standard('#orderRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartOrderRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartOrderRecordsGraph = HSCore.components.HSChartJS.getItem('chartOrderRecordsGraph');

        updateChartOrderRecordsGraph();

        async function updateChartOrderRecordsGraph() {
            let $url = "{{ route('admin.order.records') }}"
            await axios.get($url, {
                params: {
                    type: 'child_panel'
                }
            })
                .then(function (res) {

                    $('.orderRecord-totalOrders').text(res.data.orderRecord.totalOrders);
                    $('.orderRecord-followupGrapClass').addClass(res.data.orderRecord.followupGrapClass);
                    $('.orderRecord-followupGrap').text(res.data.orderRecord.followupGrap);
                    $('.orderRecord-chartPercentageIncDec').text(`from ${res.data.orderRecord.chartPercentageIncDec}`);

                    chartOrderRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartOrderRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartOrderRecordsGraph.update();
                    Notiflix.Block.remove('#orderRecord');
                })
                .catch(function (error) {

                });
        }
    </script>

    <script>
        Notiflix.Block.standard('#pendingOrderRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartPendingOrderRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartPendingOrderRecordsGraph = HSCore.components.HSChartJS.getItem('chartPendingOrderRecordsGraph');

        updateChartPendingOrderRecordsGraph();

        async function updateChartPendingOrderRecordsGraph() {
            let $url = "{{ route('admin.pending.order.records') }}"
            await axios.get($url, {
                params: {
                    type: 'child_panel'
                }
            })
                .then(function (res) {
                    $('.pendingOrderRecord-totalPendingOrder').text(res.data.pendingOrderRecord.pendingOrderCount);
                    $('.pendingOrderRecord-followupGrapClass').addClass(res.data.pendingOrderRecord.followupGrapClass);
                    $('.pendingOrderRecord-followupGrap').text(res.data.pendingOrderRecord.followupGrap);
                    $('.pendingOrderRecord-chartPercentageIncDec').text(`from ${res.data.pendingOrderRecord.chartPercentageIncDec}`);

                    chartPendingOrderRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartPendingOrderRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartPendingOrderRecordsGraph.update();
                    Notiflix.Block.remove('#pendingOrderRecord');
                })
                .catch(function (error) {

                });
        }
    </script>

    <script>
        Notiflix.Block.standard('#transactionRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartTransactionRecordsGraph'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: ["rgba(55, 125, 255, 0)", "rgba(255, 255, 255, 0)"],
                    borderColor: "#377dff",
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 0
                }]
            },
            options: {
                scales: {
                    y: {
                        display: false
                    },
                    x: {
                        display: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        postfix: "",
                        hasIndicator: true,
                        intersect: false
                    }
                }
            },
        });
        const chartTransactionRecordsGraph = HSCore.components.HSChartJS.getItem('chartTransactionRecordsGraph');

        updateChartTransactionRecordsGraph();

        async function updateChartTransactionRecordsGraph() {
            let $url = "{{ route('admin.chartTransactionRecords') }}"
            await axios.get($url, {
                params: {
                    type: 'child_panel'
                }
            })
                .then(function (res) {
                    $('.transactionRecord-totalTransaction').text(res.data.transactionRecord.totalTransaction);
                    $('.transactionRecord-followupGrapClass').addClass(res.data.transactionRecord.followupGrapClass);
                    $('.transactionRecord-followupGrap').text(res.data.transactionRecord.followupGrap);
                    $('.transactionRecord-chartPercentageIncDec').text(`from ${res.data.transactionRecord.chartPercentageIncDec}`);

                    chartTransactionRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartTransactionRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartTransactionRecordsGraph.update();
                    Notiflix.Block.remove('#transactionRecord');
                })
                .catch(function (error) {

                });
        }

    </script>
@endpush
