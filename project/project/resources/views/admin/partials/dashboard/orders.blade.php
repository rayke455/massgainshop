<div class="card user-card sealsCard mb-3 mb-lg-5" id="order-chart">
    <div class="card-header card-header-content-sm-between">
        <h4 class="card-header-title mb-2 mb-sm-0">@lang('Orders History')
        </h4>

        <div class="d-flex filterOption">
            <select class="js-select form-select" name="category" id="category" autocomplete="off"
                    data-hs-tom-select-options='{
                "dropdownWidth": "100%",
                "dropdownLeft": true
            }'>
                <option value="all" {{ old('category') === null ? 'selected' : '' }}>@lang('All Category')</option>
                @foreach($categories as $item)
                    <option value="{{ $item->id }}"
                            {{ old('category') == $item->id ? 'selected' : '' }}
                            data-item="{{ json_encode($item) }}">
                        {{ $item->category_title }}
                    </option>
                @endforeach
            </select>
            <button id="js-daterangepicker-predefined" class="btn btn-white btn-sm w-100 dropdown-toggle">
                <i class="bi-calendar-week"></i>
                <span class="js-daterangepicker-predefined-preview ms-1"></span>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row col-lg-divider" id="salesRevenueChart" style="max-height: 350px">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <div class="chartjs-custom mb-4" style="max-height: 350px">
                    <canvas id="service-order-statics" style="max-height: 350px"></canvas>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-sm-6 col-lg-12">
                        <div class="d-flex justify-content-center flex-column" style="min-height: 9rem;">
                            <h6 class="card-subtitle">@lang('Total Order Amount')</h6>
                            <span
                                class="d-block display-4 text-dark mb-1 me-3 amountData">{{ currencyPosition(0) }}</span>
                            <span class="d-block text-success" id="revenuePercent"></span>
                        </div>

                        <hr class="d-none d-lg-block my-0">
                    </div>

                    <div class="col-sm-6 col-lg-12">
                        <div class="d-flex justify-content-center flex-column" style="min-height: 9rem;">
                            <h6 class="card-subtitle">@lang('Total Order')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3 orderData">{{ 0 }}</span>
                            <span class="d-block text-danger" id="orderPercent"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/daterangepicker.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).on('ready', function () {

            let baseCurrency = "{{ basicControl()->base_currency }}";

            HSCore.components.HSTomSelect.init('.js-select', {
                placeholder: 'Select one'
            });
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment().subtract(6, 'days');
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
                $('#js-daterangepicker-predefined-country .js-daterangepicker-predefined-preview-country').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            $('#js-daterangepicker-predefined-country').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            var ctx = document.getElementById('service-order-statics').getContext('2d');
            var orderChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'ORDER',
                            data: [],
                            backgroundColor: '#FFA500',
                            borderWidth: 1
                        },
                        {
                            label: 'ORDER AMOUNT',
                            data: [],
                            backgroundColor: '#2196f3',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 100,
                                color: '#97a4af',
                                font: {
                                    size: 12,
                                    family: 'Roboto, Arial, sans-serif'
                                },
                                padding: 10
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0)',
                                borderColor: 'rgba(0, 0, 0, 0)',
                            }
                        },
                        x: {
                            ticks: {
                                color: '#97a4af',
                                font: {
                                    size: 12,
                                    family: 'Roboto, Arial, sans-serif'
                                },
                                padding: 5
                            },
                            categoryPercentage: 0.5,
                            maxBarThickness: 10,
                            grid: {
                                color: 'rgba(0, 0, 0, 0)',
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            prefix: baseCurrency,
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }

                                    if (context.dataset.label === 'ORDER AMOUNT') {
                                        label += `${context.raw}` + ' ' + baseCurrency;
                                    } else {
                                        label += context.raw;
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    }
                }
            });

            Notiflix.Block.standard('#order-chart');

            function fetchDataAndUpdateChart() {
                let dateRange = $('#js-daterangepicker-predefined').data('daterangepicker');
                let startDate = dateRange.startDate.format('YYYY-MM-DD');

                let endDate = dateRange.endDate.format('YYYY-MM-DD');
                let selectedCategory = $('#category').val() ?? 'all';

                let panelType = "{{ $type }}";

                $.ajax({
                    url: '{{ route('admin.monthly.order.show', $type) }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        category: selectedCategory,
                        type:panelType
                    },
                    success: function (data) {

                        orderChart.data.labels = data.labels;
                        orderChart.data.datasets[0].data = data.order;
                        orderChart.data.datasets[1].data = data.amount;

                        $('.amountData').text(data.totalAmountInRange);
                        $('.orderData').text(data.totalOrderInRange);

                        orderChart.update();

                        Notiflix.Block.remove('#order-chart');
                    },
                    error: function (error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            $('.js-select').on('change', fetchDataAndUpdateChart);
            $('#js-daterangepicker-predefined').on('apply.daterangepicker', fetchDataAndUpdateChart);

            $(document).on('change', '#plan', function () {
                fetchDataAndUpdateChart();
            });

            fetchDataAndUpdateChart();
        });


    </script>
@endpush
