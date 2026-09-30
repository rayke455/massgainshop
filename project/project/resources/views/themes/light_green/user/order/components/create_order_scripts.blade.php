@push('script')
    <script>
        const {createApp} = Vue;
        const newOrder = createApp({
            data() {
                return {
                    socialMedia: @json($socialMedia),
                    categories: [],
                    services: [],
                    selectedCategory: '0',
                    selectedService: '0',
                    selectedServiceFromOrder: @json($selectService),
                    display: 'd-none',
                    dripFeedCheck: 'd-none',
                    isChecked: false,
                    quantity: 0,
                    runs: 0,
                    interval: '',
                    serviceName: '',
                    minAmount: 0,
                    maxAmount: 0,
                    pricePer: 0,
                    avgTime: 0,
                    description: '',
                    fractionNumber: '{{ basicControl()->fraction_number }}',
                    link: '',
                    check: '',
                    order_notes: '',
                    errors: [],
                    currency: @json($currency),
                    isActive: 0,
                    orderType: 'default',
                    comments: '',
                    keywords: '',
                    usernames: '',
                    hashtags: '',
                    hashtag: '',
                    username: '',
                    media: '',
                    answer_number: '',
                    groups: '',
                    new_posts: '',
                    old_posts: '',
                    min: '',
                    max: '',
                    delay: '0',
                    expiry_date: '',
                    device: 1,
                    type_of_traffic: 1,
                    google_keyword: '',
                    referring_url: '',

                    onOffGoogleKeyword: true,
                    onOffReferringUrl: false,
                };
            },

            computed: {
                total() {
                    return this.quantity * this.runs;
                },
                totalPrice() {
                    const baseQuantity = this.isChecked
                        ? this.quantity * this.runs
                        : this.quantity;

                    const price = (baseQuantity / 1000) * this.pricePer;
                    return price.toFixed(this.fractionNumber);
                },
            },

            watch: {
                comments(newVal) {
                    const lines = newVal.split(/\r\n|\r|\n/).filter(line => line.trim() !== '');
                    this.quantity = lines.length;
                },
                usernames(newVal) {
                    if (this.orderType == 'Mentions Custom List') {
                        const lines = newVal.split(/\r\n|\r|\n/).filter(line => line.trim() !== '');
                        this.quantity = lines.length;
                    }
                },
                delay(newVal) {
                    $('.cmn-select5').val(newVal).trigger('change.select2');
                },
            },

            mounted() {
                // Restore link/quantity saved when the customer went to add funds (valid for 2 hours).
                try {
                    const draft = JSON.parse(sessionStorage.getItem('zk_order_draft') || 'null');
                    if (draft && Date.now() - draft.t < 2 * 3600 * 1000) {
                        this.link = draft.link || '';
                        setTimeout(() => { if (draft.quantity) this.quantity = draft.quantity; }, 2500);
                        Notiflix.Notify.info(@json(__('We kept your link and quantity. Pick the same service and place the order.')));
                    }
                } catch (e) {}

                if (this.selectedServiceFromOrder) {
                    this.getCategoryAndService(this.selectedServiceFromOrder.category.social_media_id);
                } else {
                    this.getCategoryAndService(0);
                }

                this.$nextTick(() => {
                    const vm = this;
                    const $select = $('.cmn-select5');

                    $select.select2();

                    $select.on('change', function () {
                        vm.delay = $(this).val();
                    });
                });

                $('.cmn-select3').select2();
                $('.cmn-select3').on('change', (event) => {
                    this.selectedCategory = event.target.value;
                    this.handleCategoryChange();
                });

                $('.cmn-select4').select2();
                $('.cmn-select4').on('change', (event) => {
                    this.selectedService = event.target.value;
                    this.handleServiceChange();
                });

                flatpickr(".expiry_date", {
                    dateFormat: "d-m-Y",
                });
            },

            methods: {
                formatAverageTime(minutes) {
                    if (!minutes) return 'N/A';

                    const min = Number(minutes);

                    if (min >= 1440) {

                        const days = (min / 1440).toFixed(1);
                        return `${days} Day${days > 1 ? 's' : ''}`;
                    } else if (min >= 60) {

                        const hours = (min / 60).toFixed(1);
                        return `${hours} Hour${hours > 1 ? 's' : ''}`;
                    } else {
                        return `${min} Minute${min > 1 ? 's' : ''}`;
                    }
                },
                toggleActive(id) {
                    let socialMedia = this.socialMedia.find(media => media.id == id);
                    this.isActive = socialMedia.id;
                },
                handleServiceChange() {
                    let selectedService = this.services.find(service => service.id == this.selectedService);
                    if (this.currency) {
                        let formattedCurrency = parseFloat(selectedService.price) * parseFloat(this.currency.conversion_rate);
                        this.pricePer = formattedCurrency.toFixed(2);
                    } else {
                        let formattedCurrency = parseFloat(selectedService.price);
                        this.pricePer = formattedCurrency.toFixed(2);
                    }
                    this.minAmount = selectedService.min_amount;
                    this.avgTime = this.formatAverageTime(selectedService.average_time_minutes);
                    this.maxAmount = selectedService.max_amount;
                    this.serviceName = selectedService.service_title;
                    this.description = selectedService.description;

                    if (selectedService.drip_feed == 0 || selectedService.drip_feed == null) {
                        this.isChecked = false
                        this.display = 'd-none'
                    } else {
                        this.isChecked = true
                        this.display = 'd-block'
                        this.dripFeedCheck = 'd-block'
                    }

                    this.setServiceTypeFlags(selectedService.service_type, this.pricePer);
                },

                handleCategoryChange() {
                    let selectedCategory = this.categories.find(category => category.id == this.selectedCategory);
                    this.services = selectedCategory.service;

                    if (this.services.length > 0) {
                        this.selectedService = this.services[0].id;
                        this.serviceName = this.services[0].service_title;
                        if (this.currency) {
                            let formattedCurrency = parseFloat(this.services[0].price) * parseFloat(this.currency.conversion_rate);
                            this.pricePer = formattedCurrency;
                        } else {
                            let formattedCurrency = parseFloat(this.services[0].price);
                            this.pricePer = formattedCurrency.toFixed(2);
                        }

                        this.minAmount = this.services[0].min_amount;
                        this.maxAmount = this.services[0].max_amount;
                        this.avgTime = this.formatAverageTime(this.services[0].average_time_minutes);
                        this.description = this.services[0].description;

                        if (this.services[0].drip_feed == 0 || this.services[0].drip_feed == null) {
                            this.isChecked = false
                            this.display = 'd-none'
                        } else {
                            this.isChecked = true
                            this.display = 'd-block'
                            this.dripFeedCheck = 'd-block'
                        }

                        this.setServiceTypeFlags(this.services[0].service_type, this.pricePer);

                    } else {
                        this.selectedService = '0';
                        this.serviceName = '';
                        this.pricePer = 0;
                        this.minAmount = 0;
                        this.maxAmount = 0;
                    }
                },

                getCategoryAndService(id) {
                    let url = "{{ route('user.get.category.service') }}";
                    axios.get(url, {
                        params: {
                            social_media_id: id
                        }
                    })
                        .then(response => {

                            this.categories = response.data;

                            if (this.selectedServiceFromOrder) {
                                this.selectedCategory = this.selectedServiceFromOrder.category_id;
                                this.services.push(this.selectedServiceFromOrder);
                                this.selectedService = this.selectedServiceFromOrder.id;
                                this.serviceName = this.selectedServiceFromOrder.service_title;
                                if (this.currency) {
                                    let formattedCurrency = parseFloat(this.selectedServiceFromOrder.price) * parseFloat(this.currency.conversion_rate);
                                    this.pricePer = formattedCurrency;
                                } else {
                                    let formattedCurrency = parseFloat(this.selectedServiceFromOrder.price);
                                    this.pricePer = formattedCurrency;
                                }
                                this.minAmount = this.selectedServiceFromOrder.min_amount;
                                this.maxAmount = this.selectedServiceFromOrder.max_amount;

                                this.avgTime = this.formatAverageTime(this.selectedServiceFromOrder.average_time_minutes);
                                this.description = this.selectedServiceFromOrder.description;
                            } else {
                                if (this.categories.length > 0) {
                                    this.selectedCategory = this.categories[0].id;
                                    this.services = this.categories[0].service;

                                    if (this.services.length > 0) {
                                        this.selectedService = this.services[0].id;
                                        this.serviceName = this.services[0].service_title;
                                        if (this.currency) {
                                            let formattedCurrency = parseFloat(this.services[0].price) * parseFloat(this.currency.conversion_rate);
                                            this.pricePer = formattedCurrency.toFixed(2);
                                        } else {
                                            let formattedCurrency = parseFloat(this.services[0].price);
                                            this.pricePer = formattedCurrency.toFixed(2);
                                        }
                                        this.minAmount = this.services[0].min_amount;
                                        this.maxAmount = this.services[0].max_amount;
                                        this.avgTime = this.formatAverageTime(this.services[0].average_time_minutes);
                                        this.description = this.services[0].description;

                                        if (this.services[0].drip_feed == 0 || this.services[0].drip_feed == null) {
                                            this.dripFeedCheck = 'd-none'
                                            this.display = 'd-none'
                                            this.isChecked = false
                                        } else {
                                            this.dripFeedCheck = 'd-block'
                                            this.display = 'd-block'
                                            this.isChecked = true
                                        }

                                        this.setServiceTypeFlags(this.services[0].service_type, this.pricePer);

                                    } else {
                                        this.selectedService = '0';
                                    }
                                } else {
                                    this.selectedCategory = '0';
                                    this.services = [];
                                    this.selectedService = '0';
                                    this.serviceName = '';
                                    this.pricePer = 0;
                                    this.minAmount = 0;
                                    this.maxAmount = 0;
                                    this.description = '';
                                }
                            }
                        })
                        .catch(error => {
                        });
                },

                dripFeedChecked() {
                    const isChecked = $('#status').is(":checked");
                    this.dripFeedCheck = isChecked ? 'd-block' : 'd-none'
                },

                typeOfTraffic(type) {
                    if (type == 'Google Keyword') {
                        this.type_of_traffic = 1;
                        this.onOffReferringUrl = false;
                        this.onOffGoogleKeyword = true;
                    } else if (type == 'Custom Referrer') {
                        this.type_of_traffic = 2;
                        this.onOffReferringUrl = true;
                        this.onOffGoogleKeyword = false;
                    } else {
                        this.type_of_traffic = 3;
                        this.onOffReferringUrl = false;
                        this.onOffGoogleKeyword = false;
                    }
                },

                deviceChange(val) {
                    this.device = val;
                },

                setServiceTypeFlags(serviceType, price = 0) {

                    this.orderType = serviceType;
                    this.usernamePlaceholder = '';

                    this.hasQuantity = true;
                    this.withoutQuantityTotalPrice = 0;
                    this.hasKeywords = false;
                    this.hasMentionWithHashTags = false;
                    this.hasMentionCustomList = false;
                    this.hasMentionHashTag = false;
                    this.hasMentionsUserFollowers = false;
                    this.hasCustomComments = false;
                    this.hasMentionsMediaLikers = false;
                    this.hasCustomCommentsPackage = false;
                    this.hasCommentLikes = false;
                    this.hasPoll = false;
                    this.hasCommentReplies = false;
                    this.hasInvitesFromGroups = false;
                    this.hasSubscriptions = false;
                    this.hasWebTraffic = false;

                    switch (serviceType) {
                        case "Package":
                            this.withoutQuantityTotalPrice = price;
                            this.hasQuantity = false;
                            break;
                        case "SEO":
                            this.hasKeywords = true;
                            break;
                        case "Custom Comments":
                            this.hasCustomComments = true;
                            break;
                        case "Mentions with Hashtags":
                            this.hasMentionWithHashTags = true;
                            break;
                        case "Mentions Custom List":
                            this.withoutQuantityTotalPrice = price;
                            this.hasMentionCustomList = true;
                            break;
                        case "Mentions Hashtag":
                            this.hasMentionHashTag = true;
                            break;
                        case "Mentions User Followers":
                            this.usernamePlaceholder = 'URL to scrape followers from';
                            this.hasMentionsUserFollowers = true;
                            break;
                        case "Mentions Media Likers":
                            this.hasMentionsMediaLikers = true;
                            break;
                        case "Custom Comments Package":
                            this.hasCustomCommentsPackage = true;
                            this.withoutQuantityTotalPrice = price;
                            this.hasQuantity = false;
                            break;
                        case "Comment Likes":
                            this.usernamePlaceholder = 'Username of the comment owner';
                            this.hasCommentLikes = true;
                            break;
                        case "Poll":
                            this.hasPoll = true;
                            break;
                        case "Comment Replies":
                            this.hasCommentReplies = true;
                            this.usernamePlaceholder = 'Username';
                            break;
                        case "Invites from Groups":
                            this.hasInvitesFromGroups = true;
                            break;
                        case "Subscriptions":
                            this.$nextTick(() => {
                                const vm = this;
                                const $select = $('.cmn-select5');

                                $select.select2();

                                $select.on('change', function () {
                                    vm.delay = $(this).val();
                                });
                            });

                            this.$nextTick(() => {
                                flatpickr(".expiry_date", {
                                    dateFormat: "d-m-Y",
                                });
                            });
                            this.withoutQuantityTotalPrice = price;
                            this.hasQuantity = false;
                            this.usernamePlaceholder = 'Username';
                            this.hasSubscriptions = true;
                            break;
                        case "Web Traffic":
                            this.hasWebTraffic = true;
                            break;
                    }
                },

                showNeedFunds(missing, url) {
                    Notiflix.Confirm.show(
                        @json(__('Not enough balance')),
                        @json(__('You need')) + ' ' + missing + ' ' + @json(__('more to place this order. Add funds now? Your order details stay here.')),
                        @json(__('Add funds')),
                        @json(__('Not now')),
                        () => { window.location.href = url; },
                        () => {},
                        {titleColor: '#0F172A', okButtonBackground: getComputedStyle(document.documentElement).getPropertyValue('--zk-primary').trim() || '#1D4ED8', borderRadius: '14px', width: '340px'}
                    );
                },

                placeOrder() {
                    // Check the balance before sending anything, so people aren't left guessing.
                    const zkTotal = Number(this.hasQuantity ? this.totalPrice : this.withoutQuantityTotalPrice) || 0;
                    const zkBalance = Number(window.zkOrderCtx?.balance ?? Infinity);
                    if (zkTotal > 0 && zkTotal > zkBalance) {
                        const missing = Math.ceil((zkTotal - zkBalance) * 100) / 100;
                        this.saveDraft();
                        this.showNeedFunds(missing.toFixed(2) + ' ' + window.zkOrderCtx.currency, window.zkOrderCtx.addFundsUrl + '?amount=' + Math.max(1, Math.ceil(missing / window.zkOrderCtx.rate)));
                        return;
                    }

                    Notiflix.Block.standard('.pulse-loader', 'Please wait a few moments.', {
                        backgroundColor: 'rgb(191 255 221 / 20%)',
                        svgColor: '#08cf65',
                        messageColor: '#002138',
                        messageFontSize: '18px',
                        fontFamily: 'Roboto, sans-serif'
                    });

                    let url = "{{ route('user.order.store') }}";

                    let payload = {
                        category: this.selectedCategory,
                        service: this.selectedService,
                        check: this.check,
                        order_notes: this.order_notes,
                        order_type: this.orderType
                    };

                    if (this.orderType === 'Default') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.drip_feed = this.isChecked;
                        payload.runs = this.runs;
                        payload.interval = this.interval;
                    } else if (this.orderType === 'Package') {
                        payload.price = this.withoutQuantityTotalPrice;
                        payload.link = this.link;
                    } else if (this.orderType === 'Custom Comments') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.comments = this.comments;
                    } else if (this.orderType === 'SEO') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.keywords = this.keywords;
                    } else if (this.orderType === 'Mentions with Hashtags') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.usernames = this.usernames;
                        payload.hashtags = this.hashtags;
                    } else if (this.orderType === 'Mentions Custom List') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.usernames = this.usernames;
                    } else if (this.orderType === 'Mentions Hashtag') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.hashtag = this.hashtag;
                    } else if (this.orderType === 'Mentions User Followers') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.username = this.username;
                    } else if (this.orderType === 'Mentions Media Likers') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.media = this.media;
                    } else if (this.orderType === 'Custom Comments Package') {
                        payload.link = this.link;
                        payload.comments = this.comments;
                    } else if (this.orderType === 'Comment Likes') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.username = this.username;
                    } else if (this.orderType === 'Poll') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.answer_number = this.answer_number;
                    } else if (this.orderType === 'Comment Replies') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.username = this.username;
                        payload.comments = this.comments;
                    } else if (this.orderType === 'Invites from Groups') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.groups = this.groups;
                    } else if (this.orderType === 'Subscriptions') {
                        payload.link = this.link;
                        payload.price = this.withoutQuantityTotalPrice;
                        payload.username = this.username;
                        payload.new_posts = this.new_posts;
                        payload.old_posts = this.old_posts;
                        payload.min = this.min;
                        payload.max = this.max;
                        payload.delay = this.delay;
                        payload.expiry_date = this.expiry_date;
                    } else if (this.orderType === 'Web Traffic') {
                        payload.link = this.link;
                        payload.quantity = this.quantity;
                        payload.drip_feed = this.isChecked;
                        payload.runs = this.runs;
                        payload.interval = this.interval;
                        payload.country = this.country;
                        payload.device = this.device;
                        payload.type_of_traffic = this.type_of_traffic;
                        payload.google_keyword = this.google_keyword;
                        payload.referring_url = this.referring_url;
                    }

                    axios.post(url, payload)
                        .then(response => {

                            Notiflix.Block.remove('.pulse-loader');

                            if (response.data.status == 'success') {
                                this.clearDraft();
                                Notiflix.Notify.success(`${response.data.message}`);
                                this.quantity = '';
                                this.check = '';
                                this.link = '';
                                this.runs = '';
                                this.drip_feed = '';
                                this.interval = '';
                                this.order_notes = '';
                                this.comments = '';
                                this.usernames = '';
                                this.hashtags = '';
                                this.hashtag = '';
                                this.username = '';
                                this.media = '';
                                this.answer_number = '';
                                this.groups = '';
                                this.new_posts = '';
                                this.old_posts = '';
                                this.username = '';
                                this.min = '';
                                this.max = '';
                                this.delay = '';
                                this.expiry = '';
                                this.country = '';
                                this.device = '';
                                this.type_of_traffic = '';
                                this.google_keyword = '';
                                this.referring_url = '';

                                this.makeErrorRemove();
                            }
                            if (response.data.status == 'error') {
                                Notiflix.Block.remove('.pulse-loader');
                                if (response.data.code === 'insufficient_balance') {
                                    this.saveDraft();
                                    this.showNeedFunds(response.data.missing + ' ' + window.zkOrderCtx.baseCurrency, response.data.add_funds_url);
                                } else {
                                    Notiflix.Notify.failure(`${response.data.message}`);
                                }
                            }
                        })
                        .catch(error => {
                            Notiflix.Block.remove('.pulse-loader');
                            let _this = this;
                            this.makeErrorRemove();
                            let res = error.response.data
                            _this.errors = res.errors
                            for (let err in _this.errors) {
                                let selector = document.querySelector("." + err);
                                if (selector) {
                                    selector.innerText = `${_this.errors[err]}`;
                                }
                            }
                        });
                },

                // Keep link + quantity while the customer tops up, so they don't retype them.
                saveDraft() {
                    try {
                        sessionStorage.setItem('zk_order_draft', JSON.stringify({
                            link: this.link, quantity: this.quantity, service: this.selectedService, category: this.selectedCategory, t: Date.now()
                        }));
                    } catch (e) {}
                },
                clearDraft() {
                    try { sessionStorage.removeItem('zk_order_draft'); } catch (e) {}
                },

                makeErrorRemove() {
                    $('.link').text('');
                    $('.quantity').text('');
                    $('.check').text('');
                    $('.order_notes').text('');
                    $('.comments').text('');
                    $('.runs').text('');
                    $('.interval').text('');
                    $('.usernames').text('');
                    $('.hashtags').text('');
                    $('.hashtag').text('');
                    $('.username').text('');
                    $('.media').text('');
                    $('.answer_number').text('');
                    $('.groups').text('');
                    $('.new_posts').text('');
                    $('.old_posts').text('');
                    $('.min').text('');
                    $('.max').text('');
                    $('.delay').text('');
                    $('.expiry').text('');
                    $('.country').text('');
                    $('.device').text('');
                    $('.type_of_traffic').text('');
                    $('.google_keyword').text('');
                    $('.referring_url').text('');
                },
            }
        });
        newOrder.mount('#new_order');
    </script>
@endpush
