<div class="row">

    <div class="col-lg-4 mb-3 mb-lg-5" id="socialMediaBestSaleChart">
        <div class="card">
            <div class="card-header card-header-content-between">
                <h4 class="card-header-title">@lang("Social Media Best Sale")</h4>
                <button id="js-daterangepicker3-predefined" class="btn btn-white btn-sm dropdown-toggle">
                    <i class="bi-calendar-week"></i>
                    <span class="js-daterangepicker3-predefined-preview ms-1"></span>
                </button>
            </div>

            <div class="card-body">
                <div class="chartjs-custom mb-3 mb-sm-5 socialBestSaleChart" style="height: 14rem;">
                    <canvas id="updatingDoughnutChartBestSale"></canvas>
                </div>

                <div class="text-center p-4 error-message-chart-sale">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="No Data"
                         data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                         alt="No Data" data-hs-theme-appearance="dark">
                    <p class="mb-0">@lang("The best sales data is currently unavailable.")</p>
                </div>

                <div class="row justify-content-center bestsale-footer-chart">
                    <div class="col-auto mb-3 mb-sm-0">
                        <h4 class="card-title totalAmountBestSocialMediaSale"></h4>
                        <span class="legend-indicator bg-info"></span> @lang("Total Amount")
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-8 mb-3 mb-lg-5">
        <div class="card h-100">
            <div class="card-header card-header-content-between">
                <h4 class="card-header-title">@lang("Top Service Sale")</h4>
                <a class="btn btn-white btn-sm" href="{{ route('admin.service.index') }}">@lang("View all")</a>
            </div>

            <div class="card-body-height">
                <div class="table-responsive">
                    <table
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col" class="text-left">@lang('Service Name')</th>
                            <th scope="col">@lang('Order')</th>
                            <th scope="col">@lang('Total Qty')</th>
                            <th scope="col">@lang('Provider')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bestSales as $sale)
                            <tr>
                                <td data-label="@lang('Name')" class="text-right text-lg-left">
                                    <div class="flex-grow-1">
                                        <h6 class="text-inherit mb-0">{{\Str::limit(optional($sale->service)->service_title, 30)}}</h6>
                                    </div>
                                </td>
                                <td data-label="@lang('Total Order')">
                                    <span class="badge bg-soft-dark text-dark ms-2">{{$sale->count}}</span>
                                </td>
                                <td data-label="@lang('Total Quantity')">
                                    {{$sale->quantity}}
                                </td>
                                <td data-label="@lang('Provider')">
                                    <span>{{ optional($sale->service->provider)->api_name ?? 'N/A' }}</span>
                                </td>

                                <td data-label="@lang('Action')">
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-white btn-sm"
                                           href="{{route('admin.service.edit',optional($sale->service)->id)}}">
                                            <i class="bi-pencil-fill me-1"></i> @lang("Edit")
                                        </a>
                                        <div class="btn-group">
                                            <button type="button"
                                                    class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                    id="userEditDropdown" data-bs-toggle="dropdown"
                                                    aria-expanded="false"></button>
                                            <div class="dropdown-menu dropdown-menu-end mt-1"
                                                 aria-labelledby="userEditDropdown">
                                                <a href="javascript:void(0)" class="dropdown-item"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#descriptionModal" id="details"
                                                   data-bs-toggle="tooltip" title="Details"
                                                   data-service_title="{{optional($sale->service)->service_title}}"
                                                   data-description="{{optional($sale->service)->description}}">
                                                    <i class="fa fa-info-circle dropdown-item-icon"
                                                       aria-hidden="true"></i>@lang('Details')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title" id="descriptionModalTitle"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="service_description">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

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
        "use strict";

        $(document).on('click', '#details', function () {
            let title = $(this).data('service_title');
            let description = $(this).data('description');

            $('#descriptionModalTitle').text(title);
            if (description) {
                $('#service_description').text(description);
            } else {
                $('#service_description').text(`Description not available`);
            }
        });

        var start = moment().subtract(6, 'days');
        var end = moment();

        function cbBestSale(start, end) {
            $('#js-daterangepicker3-predefined .js-daterangepicker3-predefined-preview').html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
        }

        $('#js-daterangepicker3-predefined').daterangepicker({
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
        }, cbBestSale);


        cbBestSale(start, end);

        Notiflix.Block.standard('#socialMediaBestSaleChart');
        let baseCurrency = "{{  " ".basicControl()->base_currency }}";
        HSCore.components.HSChartJS.init(document.querySelector('#updatingDoughnutChartBestSale'), {
            type: "doughnut",
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [],
                    hoverBorderColor: "#fff",
                    borderWidth: 5,
                }]
            },
            options: {
                cutout: "50%",
                plugins: {
                    tooltip: {
                        postfix: baseCurrency,
                        hasIndicator: true,
                        mode: "index",
                        intersect: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: false
                }
            },
        });

        const updatingDoughnutChartBestSale = HSCore.components.HSChartJS.getItem('updatingDoughnutChartBestSale');

        async function fetchDataAndUpdateDoughnutChart() {
            let dateRange = $('#js-daterangepicker3-predefined').data('daterangepicker');
            let startDate = dateRange.startDate.format('YYYY-MM-DD');
            let endDate = dateRange.endDate.format('YYYY-MM-DD');

            let $url = "{{ route('admin.social.media.best.seller.service') }}";

            const params = {
                startDate: startDate,
                endDate: endDate,
            };

            await axios.get($url, {params})
                .then(function (res) {
                    bestSellerUpdateChart(res.data.socialMediaBestSeller);
                })
                .catch(function (error) {
                    console.error("Error fetching data:", error);
                    Notiflix.Block.remove('#socialMediaBestSaleChart');
                });
        }

        function bestSellerUpdateChart(data) {

            if (data.socialMediaNames.length == 0 || data.socialMediaOrderValue.length == 0) {
                $('.error-message-chart-sale').addClass('d-block')
                $('.socialBestSaleChart').hide()
                $('.bestsale-footer-chart').hide()
            } else {
                $('.error-message-chart-sale').removeClass('d-block')
                $('.socialBestSaleChart').show()
                $('.bestsale-footer-chart').show()
            }

            const colors = data.socialMediaNames.map(name => getSocialMediaColor(name));
            updatingDoughnutChartBestSale.data.labels = data.socialMediaNames;
            updatingDoughnutChartBestSale.data.datasets[0].data = data.socialMediaOrderValue;
            updatingDoughnutChartBestSale.data.datasets[0].backgroundColor = colors;
            $('.totalAmountBestSocialMediaSale').text(data.sumTotalAmount);
            updatingDoughnutChartBestSale.update();

            Notiflix.Block.remove('#socialMediaBestSaleChart');
        }

        function getSocialMediaColor(name) {
            const normalizedName = name.trim().toLowerCase();

            const colorMap = {
                facebook: "#1877F2",
                youtube: "#FF0000",
                instagram: "#E4405F",
                whatsapp: "#25D366",
                twitter: "#1DA1F2",
                linkedin: "#0077B5",
                snapchat: "#FFFC00",
                spotify: "#1DB954",
                tiktok: "#FFFC00",
                google: "#FE2C55",
                telegram: "#0088CC",
                discord: "#57F287",
                twitch: "#6441a5",
                website: "#808080",
                reviews: "#FFFF00",
                others: "#000000",
                everythings: "#800000",
            };
            return colorMap[normalizedName] || getRandomColor();
        }

        function getRandomColor() {
            const letters = "0123456789ABCDEF";
            let color = "#";
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        $('#js-daterangepicker3-predefined').on('apply.daterangepicker', fetchDataAndUpdateDoughnutChart);

        fetchDataAndUpdateDoughnutChart();
    </script>
@endpush
