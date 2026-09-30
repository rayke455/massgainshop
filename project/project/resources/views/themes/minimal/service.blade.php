@extends(template(). 'layouts.app')
@section('title',trans('Services'))
@section('content')
    <section class="service-list" id="services_app">
        <div class="container-fluid px-3 user-service-list">

            @php
                $currencyRes = request()->cookie('currency');
                $currencyCookie = json_decode($currencyRes);
            @endphp

            <div class="row my-3 justify-content-between mx-lg-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body social-media">
                            <div class="row">
                                @forelse($socialMedia as $item)
                                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                        <button type="button" class="social-btn w-100"
                                                :class="{ activeSocialMedia: '{{ $item->id }}' == isActive }"
                                                @click="toggleActive('{{ $item->id   }}')"
                                                @click.prevent="getServices('{{ $item->id   }}')">
                                            <img class="social-media-img"
                                                 src="{{ getFile($item->icon_driver, $item->icon) }}"
                                                 alt="{{ $item->name }}">
                                            {{ $item->name }}
                                        </button>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-3 justify-content-between mx-lg-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="" method="get">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" v-model="searchQuery" @input="handleInput"
                                                   placeholder="@lang("Search for Services")" autocomplete="off"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="category" id="category" class="form-control">
                                                <option value="">@lang("Select Category")</option>
                                                <option v-for="category in categoriesSearch" :key="category.id"
                                                        :value="category.id">
                                                    @{{ category.category_title }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-3 justify-content-between mx-lg-5">
                <div class="col-md-12">
                    <div id="accordion" class="accordion-service" v-for="(category, key) in categories" :key="key">
                        <div class="card mb-2">
                            <div class="card-header" :id="'heading' + key">
                                <a href="#" class="btn btn-header-link" :class="{ collapsed: key !== 0 }" type="button"
                                   data-toggle="collapse"
                                   :data-target="'#collapse' + key" aria-expanded="true"
                                   :aria-controls="'collapse' + key" v-cloak>
                                    @{{ category.category_title }}
                                </a>
                            </div>
                            <div :id="'collapse' + key"
                                 class="collapse" :class="{ show: key === 0 }"
                                 :aria-labelledby="'heading' + key"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table
                                            class="categories-show-table table  table-striped text-dark">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="text-center">@lang('ID')</th>
                                                <th scope="col" class="text-center">@lang('Name')</th>
                                                <th scope="col"
                                                    class="text-center">@lang('Rate Per 1K')</th>
                                                <th scope="col" class="text-center">@lang('Min')</th>
                                                <th scope="col" class="text-center">@lang('Max')</th>
                                                <th scope="col" class="text-center">@lang('Description')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="service in category.service" :key="service.id">
                                                <td data-label="@lang("ID")" scope="row">@{{ service.id }}</td>
                                                <td data-label="@lang("Name")" width="60%">
                                                    @{{ service.service_title }}
                                                    <br>
                                                    <span>Avg Time</span>@{{ formatAverageTime(service.average_time_minutes) }}
                                                </td>
                                                <td data-label="@lang('Rate Per 1K')">
                                                    <span v-if="service.priceSelectedCurrency">@{{ service.priceSelectedCurrency }}</span>
                                                    <span v-else>@{{ service.price }}</span>
                                                </td>
                                                <td data-label="@lang("Min")">@{{ service.min_amount }}</td>
                                                <td data-label="@lang("Max")">@{{ service.max_amount }}</td>
                                                <td data-label="@lang("Description")" class="td-quick-view">
                                                    <button type="button" data-toggle="modal"
                                                            data-target="#describeModal"
                                                            class="action-btn-primary details border-0"
                                                            :data-id="service.id"
                                                            :data-title="service.service_title"
                                                            :data-description="service.description">
                                                        <i class="fal fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagination-section">
                        <nav v-if="pagination.per_page < pagination.total">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#" :class="{ disabled: pagination.current_page == 1 }"
                                       @click.prevent="updateItems('back')" v-cloak><i class="fas fa-arrow-left"></i></a>
                                </li>
                                <li class="page-item" v-for="(link, index) in links"
                                    :class="{ active: link.label == pagination.current_page }">
                                    <a class="page-link" v-if="link.label >= 1" href="#" @click.prevent="updateItems(link)">@{{link.label}}</a>
                                </li>
                                <li class="page-item" :class="{ disabled: pagination.current_page == pagination.last_page }">
                                    <a class="page-link" href="#" @click.prevent="updateItems('next')" v-cloak>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="empty_state text-center" v-if="categories.length == 0">
                <div class="text-center p-4">
                    <img class="error-image mb-3"
                         src="{{ asset('assets/global/img/oc-error.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <p class="mb-0">@lang("No service available to display.")</p>
                </div>
            </div>
        </div>
    </section>



    <div class="modal fade" id="describeModal" tabindex="-1" role="dialog" aria-labelledby="describeModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header ">
                    <h4 class="modal-title" id="title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                </div>
                <div class="modal-body">
                    <p id="service-description">

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary btn-padding close"
                            data-dismiss="modal">@lang('Close')</button>
                    <a href="" type="submit" class="btn btn-sm btn-primary btn-padding order-now">@lang('Order Now')</a>
                </div>
            </div>
        </div>
    </div>

    @include(template(). 'sections.footer')

@endsection


@push('style')
    <style>
        .user-service-list .card-body thead th {
            background-color: #C1C7D0;
            border-color: #C1C7D0;
            color: #000;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/global/js/vue.global.prod.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/axios.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).on('click', '.details', function () {

            let title = $(this).data('title');
            $('#title').text(title);
            let description = $(this).data('description');
            if (description) {
                $('#service-description').html(description);
            } else {
                $('#service-description').html('The description is not available.');
            }

            let id = $(this).data('id');
            let orderRoute = "{{route('user.order.create')}}" + '?serviceId=' + id;
            $('.order-now').attr('href', orderRoute);
        });
    </script>

    <script>
        const app = Vue.createApp({
            data() {
                return {
                    categories: [],
                    pagination: [],
                    links: [],
                    socialMedia: @json($socialMedia),
                    isActive: 0,
                    categoriesSearch: [],
                    selectedCategory: '',
                    searchQuery: '',
                };
            },
            mounted() {
                $('#category').on('change', (event) => {
                    this.selectedCategory = event.target.value;
                    this.searchCategory();
                });

                this.getServices();
                this.getCategory();

            },
            methods: {
                formatAverageTime(minutes) {
                    if (!minutes) return '~N/A';

                    const min = Number(minutes);

                    if (min >= 1440) {

                        const days = (min / 1440).toFixed(1);
                        return `~${days} Day${days > 1 ? 's' : ''}`;
                    } else if (min >= 60) {

                        const hours = (min / 60).toFixed(1);
                        return `~${hours} Hour${hours > 1 ? 's' : ''}`;
                    } else {
                        return `~${min} Minute${min > 1 ? 's' : ''}`;
                    }
                },
                getCategory(social_media_id) {
                    let app = this;
                    let url = "{{ route('get.category') }}";
                    axios.get(url, {
                        params: {
                            social_media_id: social_media_id,
                        }
                    })
                        .then(function (res) {
                            app.categoriesSearch = res.data;
                        })
                },

                toggleActive(id) {
                    let socialMedia = this.socialMedia.find(media => media.id == id);
                    this.isActive = socialMedia.id;
                    this.getCategory(id);
                },
                fetchData(params) {
                    let app = this;
                    let url = "{{ route('get.services') }}";
                    axios.get(url, {params})
                        .then(function (res) {
                            app.categories = res.data.data;
                            app.pagination = res.data;
                            app.links = res.data.links.slice(1, -1);
                        });
                },
                searchCategory() {
                    this.fetchData({
                        social_media_id: this.isActive,
                        category: this.selectedCategory,
                    });
                },
                handleInput(event) {
                    this.fetchData({
                        social_media_id: this.isActive,
                        category: this.selectedCategory,
                        search: event.target.value,
                    });
                },
                getServices(id) {
                    this.fetchData({
                        social_media_id: id,
                        currency: '{{ $currencyCookie->code ?? null }}',
                    });
                },
                updateItems(page) {
                    let app = this;
                    let url = '';
                    if (page == 'back') {
                        url = this.pagination.prev_page_url;

                    } else if (page == 'next') {
                        url = this.pagination.next_page_url;

                    } else {
                        url = page.url;
                    }
                    axios.get(url)
                        .then(function (res) {
                            app.categories = res.data.data;
                            app.pagination = res.data;
                            app.links = res.data.links;
                        })
                },
            },
        });
        app.mount('#services_app');
    </script>
@endpush
