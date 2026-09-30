@extends(template(). 'layouts.app')
@section('title',trans('Services'))

@section('content')

    <section class="service-page" id="services_app">
        <div class="container">

            @php
                $currencyRes = request()->cookie('currency');
                $currencyCookie = json_decode($currencyRes);
            @endphp


            <div class="row">
                <div class="col">
                    <div class="search-area">
                        <form action="" method="get">
                            <div class="row g-3">
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
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="search-area">
                        <form action="" method="get">
                            <div class="row g-3">
                                <div class="col-md-6 input-box">
                                    <input class="form-control" v-model="searchQuery" @input="handleInput"
                                           placeholder="@lang("Search for Services")" autocomplete="off"/>
                                </div>
                                <div class="input-box col-md-6">
                                    <select name="category" id="category" class="form-control">
                                        <option value="">@lang("Select Category")</option>
                                        <option v-for="category in categoriesSearch" :key="category.id"
                                                :value="category.id">
                                            @{{ category.category_title }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item" v-for="(category, key) in categories" :key="key">
                            <h5 class="accordion-header" :id="'heading' + key">
                                <button class="accordion-button" :class="{ collapsed: key !== 0 }" type="button" type="button"
                                        data-bs-toggle="collapse"
                                        :data-bs-target="'#collapse' + key" aria-expanded="true"
                                        :aria-controls="'collapse' + key" v-cloak>
                                    @{{ category.category_title }}
                                </button>
                            </h5>
                            <div :id="'collapse' + key"
                                 class="accordion-collapse collapse" :class="{ show: key === 0 }"
                                 :aria-labelledby="'heading' + key"
                                 data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="table-parent table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th scope="col">@lang('ID')</th>
                                                <th scope="col">@lang('Name')</th>
                                                <th scope="col">@lang('Rate Per 1K')</th>
                                                <th scope="col">@lang('Min')</th>
                                                <th scope="col">@lang('Max')</th>
                                                <th scope="col">@lang('Description')</th>
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
                                                    <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#describeModal"
                                                            class="action-btn-primary details border-0 text-white"
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
                </div>
            </div>

            <div class="pagination-section">
                <nav v-if="pagination.per_page < pagination.total">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" :class="{ disabled: pagination.current_page == 1 }"
                               @click.prevent="updateItems('back')" v-cloak><i class="fal fa-long-arrow-left"></i></a>
                        </li>
                        <li class="page-item" v-for="(link, index) in links"
                            :class="{ active: link.label == pagination.current_page }">
                            <a class="page-link" v-if="link.label >= 1" href="#" @click.prevent="updateItems(link)">@{{link.label}}</a>
                        </li>
                        <li class="page-item" :class="{ disabled: pagination.current_page == pagination.last_page }">
                            <a class="page-link" href="#" @click.prevent="updateItems('next')" v-cloak><i
                                    class="fal fa-long-arrow-right"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="empty_state text-center" v-if="categories.length == 0">
                <div class="text-center p-4">
                    <img class="error-image mb-3"
                         src="{{ asset('assets/global/img/oc-error-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <p class="mb-0">@lang("No service available to display.")</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Describe Modal -->
    <div class="modal fade" id="describeModal" tabindex="-1" aria-labelledby="describeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title"></h5>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="service-description">

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-smm btn2" data-bs-dismiss="modal">@lang('Close')</button>
                    <a href="" type="submit" class="btn-smm order-now">@lang('Order Now')</a>
                </div>
            </div>
        </div>
    </div>

    @include(template(). 'sections.footer')

@endsection


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
