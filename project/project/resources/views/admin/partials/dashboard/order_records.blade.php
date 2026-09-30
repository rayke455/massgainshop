<div class="row">
    <div class="col-xl-3 col-sm-6 col-lg-3 mb-5">
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
    <div class="col-xl-3 col-sm-6 col-lg-3 mb-5">
        <a class="card user-card card-hover-shadow" href="{{route('admin.order', 'pending')}}"
           id="pendingOrderRecord">
            <div class="card-body">
                <div class="card-title-top">
                    <i class="fa-sharp fa-light fa-spinner"></i>
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
    <div class="col-xl-3 col-sm-6 col-lg-3 mb-5">
        <a class="card user-card card-hover-shadow" href="{{route('admin.order', 'completed')}}"
           id="completedOrderRecord">
            <div class="card-body">
                <div class="card-title-top">
                    <i class="fa-light fa-ballot-check"></i>
                    <h6 class="card-subtitle">@lang('Completed Orders')</h6>
                </div>

                <div class="row align-items-center gx-2 mb-1">
                    <div class="col-6">
                        <h2 class="card-title text-inherit completedOrderRecord-totalCompletedOrder"></h2>
                    </div>
                    <div class="col-6">
                        <div class="chartjs-custom" style="height: 3rem;">
                            <canvas class="" id="chartCompletedOrderRecordsGraph">
                            </canvas>
                        </div>
                    </div>
                </div>
                <span class="badge completedOrderRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="completedOrderRecord-followupGrap"></span>%
                      </span>
                <span
                    class="text-body fs-6 ms-1 completedOrderRecord-chartPercentageIncDec"></span>
            </div>
        </a>
    </div>
    <div class="col-xl-3 col-sm-6 col-lg-3 mb-5">
        <a class="card user-card card-hover-shadow" href="{{route('admin.order', 'canceled')}}"
           id="canceledOrderRecord">
            <div class="card-body">
                <div class="card-title-top">
                    <i class="fa-light fa-ban"></i>
                    <h6 class="card-subtitle">@lang('Cancel Orders')</h6>
                </div>

                <div class="row align-items-center gx-2 mb-1">
                    <div class="col-6">
                        <h2 class="card-title text-inherit canceledOrderRecord-totalCanceledOrderRecord"></h2>
                    </div>
                    <div class="col-6">
                        <div class="chartjs-custom" style="height: 3rem;">
                            <canvas class="" id="chartCanceledOrderRecordsGraph">
                            </canvas>
                        </div>
                    </div>
                </div>
                <span class="badge canceledOrderRecord-followupGrapClass">
                        <i class="bi-graph-up"></i> <span class="canceledOrderRecord-followupGrap"></span>%
                      </span>
                <span
                    class="text-body fs-6 ms-1 canceledOrderRecord-chartPercentageIncDec"></span>
            </div>
        </a>
    </div>
</div>

@push('script')
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
                    type: 'main_panel'
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
                    type: 'main_panel'
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
        Notiflix.Block.standard('#completedOrderRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartCompletedOrderRecordsGraph'), {
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
        const chartCompletedOrderRecordsGraph = HSCore.components.HSChartJS.getItem('chartCompletedOrderRecordsGraph');

        updateChartCompletedOrderRecordsGraph();

        async function updateChartCompletedOrderRecordsGraph() {
            let $url = "{{ route('admin.completed.order.records') }}"
            await axios.get($url)
                .then(function (res) {
                    $('.completedOrderRecord-totalCompletedOrder').text(res.data.completedOrderRecord.completedOrderCount);
                    $('.completedOrderRecord-followupGrapClass').addClass(res.data.completedOrderRecord.followupGrapClass);
                    $('.completedOrderRecord-followupGrap').text(res.data.completedOrderRecord.followupGrap);
                    $('.completedOrderRecord-chartPercentageIncDec').text(`from ${res.data.completedOrderRecord.chartPercentageIncDec}`);

                    chartCompletedOrderRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartCompletedOrderRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartCompletedOrderRecordsGraph.update();
                    Notiflix.Block.remove('#completedOrderRecord');
                })
                .catch(function (error) {
                });
        }
    </script>

    <script>
        Notiflix.Block.standard('#canceledOrderRecord');
        HSCore.components.HSChartJS.init(document.querySelector('#chartCanceledOrderRecordsGraph'), {
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
        const chartCanceledOrderRecordsGraph = HSCore.components.HSChartJS.getItem('chartCanceledOrderRecordsGraph');

        updateChartCompletedOrderRecordsGraph();

        async function updateChartCompletedOrderRecordsGraph() {
            let $url = "{{ route('admin.canceled.order.records') }}"
            await axios.get($url)
                .then(function (res) {
                    $('.canceledOrderRecord-totalCanceledOrderRecord').text(res.data.canceledOrderRecord.canceledOrderCount);
                    $('.canceledOrderRecord-followupGrapClass').addClass(res.data.canceledOrderRecord.followupGrapClass);
                    $('.canceledOrderRecord-followupGrap').text(res.data.canceledOrderRecord.followupGrap);
                    $('.canceledOrderRecord-chartPercentageIncDec').text(`from ${res.data.canceledOrderRecord.chartPercentageIncDec}`);

                    chartCanceledOrderRecordsGraph.data.labels = res.data.current_month_data_dates
                    chartCanceledOrderRecordsGraph.data.datasets[0].data = res.data.current_month_datas
                    chartCanceledOrderRecordsGraph.update();
                    Notiflix.Block.remove('#canceledOrderRecord');
                })
                .catch(function (error) {
                });
        }
    </script>
@endpush
