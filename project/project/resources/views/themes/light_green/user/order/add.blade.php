@extends(template().'layouts.user')
@section('title',trans('New Order'))
@section('content')

    @push('style')
        <style>
            .tooltip-icon {
                position: relative;
                display: inline-block;
                cursor: pointer;
            }

            .tooltip-icon .fal.fa-info-circle {
                font-size: 1rem;
                color: #0d6efd;
            }

            .tooltip-text {
                visibility: hidden;
                opacity: 0;
                width: 250px;
                background-color: #000;
                color: #fff;
                text-align: left;
                border-radius: 0.375rem;
                padding: 8px 10px;
                position: absolute;
                z-index: 10;
                bottom: 125%;
                left: 50%;
                transform: translateX(-50%);
                transition: opacity 0.3s ease;
                font-size: 0.85rem;
                line-height: 1.3;
            }

            .tooltip-text::after {
                content: "";
                position: absolute;
                top: 100%;
                left: 50%;
                margin-left: -6px;
                border-width: 6px;
                border-style: solid;
                border-color: #000 transparent transparent transparent;
            }

            .tooltip-icon:hover .tooltip-text {
                visibility: visible;
                opacity: 1;
            }

        </style>
    @endpush

    <div class="dashboard-wrapper" id="new_order">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("New Order")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i
                                class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">@lang("Order")</li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("New Order")</li>
                </ul>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-30">
                        <div class="card-header">
                            <h4>@lang("choose a social network")</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse($socialMedia as $media)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <button type="button" class="social-btn w-100"
                                                :class="{ activeSocialMedia: '{{ $media->id }}' == isActive }"
                                                @click="toggleActive('{{ $media->id   }}')"
                                                @click.prevent="getCategoryAndService('{{ $media->id   }}')">
                                            <img class="social-media-img"
                                                 src="{{ getFile($media->icon_driver, $media->icon) }}"
                                                 alt="{{ $media->name }}">
                                            {{ $media->name }}
                                        </button>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 pulse-loader">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form @submit.prevent="placeOrder">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">@lang("Category")</label>
                                        <select class="cmn-select3" name="categories" v-model="selectedCategory"
                                                @change="handleCategoryChange()">
                                            <option value="" disabled selected>@lang("Select Category")</option>
                                            <option v-for="category in categories" :key="category.id"
                                                    :value="category.id">
                                                @{{ category.category_title }}
                                            </option>
                                        </select>
                                        <span class="text-danger category"></span>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">@lang("Service")</label>
                                        <select class="cmn-select4" name="service" v-model="selectedService"
                                                @change="handleServiceChange()">
                                            <option value="" disabled selected>@lang("Select Service")</option>
                                            <option v-for="service in services" :key="service.id" :value="service.id"
                                                    :value="service.id">
                                                @{{ service.service_title }}
                                            </option>
                                        </select>
                                        <span class="text-danger service"></span>
                                    </div>
                                    <div class="col-12">
                                        <label for="link" class="form-label">@lang("link")</label>
                                        <input type="text" class="form-control" id="link" v-model="link"
                                               placeholder="@lang("www.example.com/your_profile_identity")"
                                               autocomplete="off">
                                        <span class="text-danger link error-link"></span>
                                    </div>

                                    <div class="col-12" v-if="hasQuantity">
                                        <label for="quantity" class="form-label">@lang("Quantity")</label>
                                        <input type="number" class="form-control" v-model.number="quantity"
                                               id="quantity" :readonly="hasCustomComments"
                                               value="0">
                                        <span class="text-danger quantity"></span>
                                    </div>
                                    <div class="col-12 drip_feed" :class="display">
                                        <label class="form-label">@lang('Drip-Feed')</label>
                                        <div class="custom-switch-btn w-md-25">
                                            <input type="checkbox" name="drip_feed" @click="dripFeedChecked"
                                                   class="form-check-input"
                                                   id="status"
                                                   value="0" v-model="isChecked">
                                            <label class="custom-switch-checkbox-label" for="status">
                                                <span class="custom-switch-checkbox-inner"></span>
                                                <span class="custom-switch-checkbox-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="drip_feed_check" :class="[display, dripFeedCheck]">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="drip_feed">
                                                    <label class="form-label">@lang('Runs')</label>
                                                    <input type="number" id="runs" v-model.number="runs"
                                                           class="form-control"
                                                           value="{{ old('runs') }}">
                                                    <span class="text-danger runs"></span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="drip_feed">
                                                    <label class="form-label">@lang('Interval (in minutes)')</label>
                                                    <input type="number" v-model="interval" class="form-control"
                                                           value="{{ old('interval') }}">
                                                    <span class="text-danger interval"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="drip_feed mt-3">
                                            <label class="form-label">@lang('Total Quantity')</label>
                                            <input type="text" class="form-control total_quantity" name="total_quantity"
                                                   :value="total"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="price" class="form-label">@lang("price")</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control"
                                                   :value="hasQuantity ? totalPrice : withoutQuantityTotalPrice"
                                                   id="price"
                                                   disabled>
                                            <div class="input-group-text">
                                                @if(auth()->user()->currency)
                                                    {{ auth()->user()->currency }}
                                                @else
                                                    {{ basicControl()->base_currency }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12"
                                         v-if="hasCustomComments || hasCustomCommentsPackage || hasCommentReplies">
                                        <label for="Comments" class="form-label">@lang("Comments")</label>
                                        <textarea class="form-control" v-model="comments" id="comments"
                                                  rows="3"
                                                  placeholder="@lang("Enter comments list separated by new lines (one per line)")"></textarea>
                                        <span class="text-danger comments"></span>
                                    </div>

                                    <div class="col-12" v-if="hasKeywords">
                                        <label for="keywords" class="form-label">@lang("Keywords (1 per line)")</label>
                                        <textarea class="form-control" v-model="keywords" id="keywords"
                                                  rows="3"
                                                  placeholder="@lang("Enter keywords separated by new lines (press Enter after each keyword).")"></textarea>
                                        <span class="text-danger keywords"></span>
                                    </div>

                                    <div class="col-12" v-if="hasMentionWithHashTags || hasMentionCustomList">
                                        <label for="usernames"
                                               class="form-label">@lang("Usernames (1 per line)")</label>
                                        <textarea class="form-control" v-model="usernames" id="usernames"
                                                  rows="3"
                                                  placeholder="@lang("Enter usernames separated by new lines (press Enter after each username).")"></textarea>
                                        <span class="text-danger usernames"></span>
                                    </div>

                                    <div class="col-12" v-if="hasMentionWithHashTags">
                                        <label for="Hashtags" class="form-label">@lang("Hashtags (1 per line)")</label>
                                        <textarea class="form-control" v-model="hashtags" id="hashtags"
                                                  rows="3"
                                                  placeholder="@lang("Enter hashtags separated by new lines (press Enter after each hashtag).")"></textarea>
                                        <span class="text-danger hashtags"></span>
                                    </div>

                                    <div class="col-12" v-if="hasMentionHashTag">
                                        <label for="hashtag" class="form-label">@lang("Hashtag")</label>
                                        <input class="form-control" v-model="hashtag" id="hashtag"
                                               rows="3" autocomplete="off"
                                               placeholder="@lang("Hashtag to scrape usernames from.")"/>
                                        <span class="text-danger hashtag"></span>
                                    </div>

                                    <div class="col-12"
                                         v-if="hasMentionsUserFollowers || hasCommentLikes || hasCommentReplies || hasSubscriptions">
                                        <label for="username" class="form-label">@lang("Username")</label>
                                        <input class="form-control" v-model="username" id="username"
                                               rows="3" :placeholder="usernamePlaceholder" autocomplete="off"/>
                                        <span class="text-danger username"></span>
                                    </div>

                                    <div class="col-12" v-if="hasMentionsMediaLikers">
                                        <label for="media" class="form-label">@lang("Media URL")</label>
                                        <input class="form-control" v-model="media" id="media"
                                               rows="3"
                                               placeholder="@lang("Media URL to scrape likers from")"/>
                                        <span class="text-danger media"></span>
                                    </div>

                                    <div class="col-12" v-if="hasPoll">
                                        <label for="answer_number" class="form-label">@lang("Answer Number")</label>
                                        <input class="form-control" v-model="answer_number" id="answer_number"
                                               rows="3"
                                               placeholder="@lang("Answer number of the poll")"/>
                                        <span class="text-danger answer_number"></span>
                                    </div>

                                    <div class="col-12" v-if="hasInvitesFromGroups">
                                        <label for="groups" class="form-label">@lang("Groups")</label>
                                        <textarea class="form-control" v-model="groups" id="groups"
                                                  rows="3"
                                                  placeholder="@lang("Enter groups list separated by new lines (one per line)")"></textarea>
                                        <span class="text-danger groups"></span>
                                    </div>

                                    <!-- subscriptions type -->
                                    <div class="subscriptions" v-if="hasSubscriptions">
                                        <div class="col-12">
                                            <label for="new_posts" class="form-label position-relative">
                                                @lang("New Posts")
                                                <span class="tooltip-icon me-2">
                                                    <i class="fal fa-info-circle text-primary"></i>
                                                    <span class="tooltip-text">
                                                        Use this parameter if you want to limit the number of new (future) posts that will be parsed and
                                                        for which orders will be created. If posts parameter is not set, the subscription will be created
                                                        for an unlimited number of posts.
                                                    </span>
                                                </span>
                                                <sub class="text-muted">(Optional)</sub>
                                            </label>
                                            <input type="text" class="form-control new_posts" v-model="new_posts"
                                                   placeholder="Enter number of new posts">
                                            <span class="text-danger new_posts"></span>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label for="old_posts" class="form-label position-relative">
                                                @lang("Old Posts")
                                                <span class="tooltip-icon me-2">
                                                    <i class="fal fa-info-circle text-primary"></i>
                                                    <span class="tooltip-text">
                                                        Number of existing posts that will be parsed and for which orders will be created, can be used if this option is available for the service.
                                                    </span>
                                                </span>
                                                <sub class="text-muted">(Optional)</sub>
                                            </label>
                                            <input type="text" class="form-control old_posts" v-model="old_posts"
                                                   placeholder="Enter number of old posts">
                                            <span class="text-danger old_posts"></span>
                                        </div>


                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label for="quantity" class="form-label">
                                                    @lang("Quantity")
                                                </label>
                                                <input type="text" class="form-control" v-model="min"
                                                       placeholder="Min">
                                                <span class="text-danger min"></span>
                                            </div>
                                            <div class="col-6 mt-2">
                                                <label for="max" class="form-label">
                                                </label>
                                                <input type="text" class="form-control" v-model="max"
                                                       placeholder="Max">
                                                <span class="text-danger max"></span>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label">@lang("Delay")</label>
                                            <select class="cmn-select5" v-model="delay">
                                                <option value="0" selected>@lang("No delay")</option>
                                                <option value="5">@lang("5 Minutes")</option>
                                                <option value="10">@lang("10 Minutes")</option>
                                                <option value="15">@lang("15 Minutes")</option>
                                                <option value="20">@lang("20 Minutes")</option>
                                                <option value="30">@lang("30 Minutes")</option>
                                                <option value="40">@lang("40 Minutes")</option>
                                                <option value="50">@lang("50 Minutes")</option>
                                                <option value="60">@lang("60 Minutes")</option>
                                                <option value="70">@lang("90 Minutes")</option>
                                                <option value="120">@lang("120 Minutes")</option>
                                                <option value="150">@lang("150 Minutes")</option>
                                                <option value="180">@lang("180 Minutes")</option>
                                                <option value="210">@lang("210 Minutes")</option>
                                                <option value="240">@lang("240 Minutes")</option>
                                                <option value="270">@lang("270 Minutes")</option>
                                                <option value="300">@lang("300 Minutes")</option>
                                                <option value="360">@lang("360 Minutes")</option>
                                                <option value="420">@lang("420 Minutes")</option>
                                                <option value="480">@lang("480 Minutes")</option>
                                                <option value="540">@lang("540 Minutes")</option>
                                                <option value="600">@lang("600 Minutes")</option>
                                            </select>
                                            <span class="text-danger service"></span>
                                        </div>


                                        <div class="col-12 mt-3">
                                            <label for="expiry_date" class="form-label">@lang("Expiry") <sub
                                                    class="text-muted">(Optional)</sub>
                                            </label>
                                            <input type="text" class="form-control expiry_date" v-model="expiry_date"
                                                   placeholder="Expiry date">
                                            <span class="text-danger expiry_date"></span>
                                        </div>
                                    </div>

                                    <!-- web traffic type -->
                                    <div class="web_traffic" v-if="hasWebTraffic">
                                        <div class="col-12">
                                            <label for="country" class="form-label position-relative">
                                                @lang("Country")
                                                <span class="tooltip-icon me-2">
                                                    <i class="fal fa-info-circle text-primary"></i>
                                                    <span class="tooltip-text">
                                                       Country code or full country name. Format: "US" or "United States"
                                                    </span>
                                                </span>
                                            </label>
                                            <input type="text" class="form-control" v-model="country" placeholder="US">
                                            <span class="text-danger country"></span>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label">Device</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="device"
                                                           @change="deviceChange(1)"
                                                           id="deviceDesktop" value="Desktop">
                                                    <label class="form-check-label" for="deviceDesktop">Desktop</label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="device"
                                                           @change="deviceChange(2)"
                                                           id="deviceMobileAndroid" value="Mobile (Android)">
                                                    <label class="form-check-label" for="deviceMobileAndroid">Mobile
                                                        (Android)</label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="device"
                                                           @change="deviceChange(3)"
                                                           id="deviceMobileIOS" value="Mobile (iOS)">
                                                    <label class="form-check-label" for="deviceMobileIOS">Mobile
                                                        (iOS)</label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="device"
                                                           @change="deviceChange(4)"
                                                           id="deviceMixedMobile" value="Mixed (Mobile)">
                                                    <label class="form-check-label" for="deviceMixedMobile">Mixed
                                                        (Mobile)</label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="device"
                                                           @change="deviceChange(5)"
                                                           id="deviceMixedAll" value="Mixed (Mobile & Desktop)">
                                                    <label class="form-check-label" for="deviceMixedAll">Mixed (Mobile &
                                                        Desktop)</label>
                                                </div>
                                            </div>
                                            <span class="text-danger device"></span>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label class="form-label">Type of Traffic</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           @change="typeOfTraffic('Google Keyword')" name="traffic_type"
                                                           id="googleKeyword" value="Google Keyword" checked>
                                                    <label class="form-check-label" for="googleKeyword">Google
                                                        Keyword</label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           @change="typeOfTraffic('Custom Referrer')"
                                                           name="traffic_type" id="customReferrer"
                                                           value="Custom Referrer">
                                                    <label class="form-check-label" for="customReferrer">Custom
                                                        Referrer</label>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           @change="typeOfTraffic('Blank Referrer')" name="traffic_type"
                                                           id="blankReferrer" value="Blank Referrer">
                                                    <label class="form-check-label" for="blankReferrer">Blank
                                                        Referrer</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Conditional inputs v-if="form.type_of_traffic == 2"  v-if="form.type_of_traffic == 1"-->
                                        <div class="mt-3" v-if="onOffGoogleKeyword">
                                            <label for="google_keyword" class="form-label">Google Keyword </label>
                                            <input
                                                type="text"
                                                id="google_keyword"
                                                class="form-control"
                                                v-model="google_keyword"
                                                placeholder="Enter Google keyword">
                                        </div>
                                        <span class="text-danger google_keyword"></span>

                                        <div class="mt-3" v-if="onOffReferringUrl">
                                            <label for="referring_url" class="form-label">Referring URL</label>
                                            <input
                                                type="url"
                                                id="referring_url"
                                                class="form-control"
                                                v-model="referring_url"
                                                placeholder="https://example.com">
                                        </div>
                                        <span class="text-danger referring_url"></span>
                                    </div>

                                    <div class="col-12">
                                        <label for="order_notes" class="form-label">@lang("Order Notes")
                                            <sub class="text-muted">(Optional)</sub>
                                        </label>
                                        <textarea class="form-control" v-model="order_notes" id="order_notes"
                                                  rows="3"></textarea>
                                        <span class="text-danger order_notes"></span>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" v-model="check"
                                                   id="Yes, i have confirmed the order!">
                                            <label class="form-check-label"
                                                   for="Yes, i have confirmed the order!">
                                                @lang("Yes, i have confirmed the order!")
                                            </label>
                                        </div>
                                        <span class="text-danger check"></span>
                                    </div>
                                    <button type="submit" class="cmn-btn mt-30">@lang("place order")</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>@lang("service description")</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="Service-name" class="form-label">@lang("Service name")</label>
                                    <input type="text" class="form-control" id="Service-name"
                                           :value="serviceName" disabled>
                                </div>
                                <div class="col-12">
                                    <div class="row g-3">
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Minimum"
                                                   class="form-label">@lang("Minimum")</label>
                                            <input type="text" class="form-control" v-model.number="minAmount"
                                                   id="Minimum" disabled>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Maximum Amount"
                                                   class="form-label">@lang("Maximum")</label>
                                            <input type="text" class="form-control" v-model.number="maxAmount"
                                                   id="Maximum" disabled>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label for="Price per 1k" class="form-label">@lang("Price per") <span
                                                    v-if="hasQuantity">1k</span></label>
                                            <div class="input-group">
                                                <input type="text" v-model.number="pricePer" class="form-control"
                                                       disabled>
                                                <div class="input-group-text">
                                                    @if(auth()->user()->currency)
                                                        {{ auth()->user()->currency }}
                                                    @else
                                                        {{ basicControl()->base_currency }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6 col-md-6">
                                            <label for="AvgTime"
                                                   class="form-label">@lang("Avg Time")</label>
                                            <input type="text" class="form-control" v-model="avgTime"
                                                   id="Avg Time" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <label for="Description" class="form-label">@lang("Description")</label>
                                    <textarea class="form-control" id="Description" rows="12" disabled>@{{ description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js-lib')
    <script src="{{ asset('assets/global/js/vue.global.prod.min.js') }}"></script>
@endpush

@include(template().'user.order.components.create_order_scripts')

