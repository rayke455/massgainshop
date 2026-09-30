@extends('admin.layouts.app')
@section('page_title', __('Order Details'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.order', 'list') }}">
                                    @lang('Manage Orders')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Order Details')</li>
                        </ol>
                    </nav>
                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">@lang("Order") #{{ $order->id }}</h1>
                        <span class="badge bg-soft-success text-success ms-sm-3">
                            <span class="legend-indicator bg-success"></span>@lang("Paid")
                          </span>
                        <span
                            class="badge bg-soft-{{$order->getStatusClass($order->status)}} text-{{$order->getStatusClass($order->status)}} ms-2 ms-sm-3">
                            <span class="legend-indicator bg-{{$order->getStatusClass($order->status)}}"></span>{{ ucfirst($order->status) }}
                          </span>
                        <span class="ms-2 ms-sm-3">
                            <i class="bi-calendar-week"></i> {{ dateTime($order->created_at) }}
                          </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-between">
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang("Detailed Order Overview")
                                <span
                                    class="badge bg-soft-secondary text-secondary ms-1">{{ optional($order->service)->service_type }}</span>
                            </h4>
                            <a class="btn btn-sm btn-white orderResendBtn" href="javascript:void(0)"
                               data-bs-toggle="modal" data-bs-target="#orderResendModal"
                               data-route="{{ route('admin.order.resend', $order->id) }}"><i
                                    class="fa-light fa-paper-plane-top dropdown-item-icon"></i>{{ trans("Resend Order") }}
                            </a>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.order.update', $order->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-3 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="categoryLabel" class="form-label">@lang('Category')</label>
                                        <input type="text" class="form-control  @error('category') is-invalid @enderror"
                                               name="category" id="categoryLabel" placeholder="@lang("Category")"
                                               aria-label="@lang("Category")"
                                               autocomplete="off"
                                               value="{{ old('category',  optional(optional($order->service)->category)->category_title) }}"
                                               disabled>
                                        @error('category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="serviceLabel" class="form-label">@lang('Service')</label>
                                        <input type="text" class="form-control  @error('service') is-invalid @enderror"
                                               name="service" id="serviceLabel" placeholder="@lang("Service")"
                                               aria-label="@lang("Service")"
                                               autocomplete="off"
                                               value="{{ old('service', optional($order->service)->service_title ) }}"
                                               disabled>
                                        @error('service')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row d-flex align-items-center">

                                    <div class="col-md-12">
                                        <label for="urlLabel" class="form-label">@lang('Price')</label>
                                        <div class="input-group">
                                            <input type="number" name="conversion_rate" step="0.000000000000000001"
                                                   class="form-control @error('conversion_rate') is-invalid @enderror"
                                                   value="{{ $order->price }}"
                                                   aria-label="@lang("Conversion Rate")" autocomplete="off" disabled>
                                            <span
                                                class="input-group-text">{{ basicControl()->base_currency ?? 'USD' }}</span>
                                        </div>
                                        @error('conversion_rate')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if(optional($order->service)->service_type != 'Package' && optional($order->service)->service_type != 'Subscriptions')
                                        <div class="col-md-6 mt-3 mb-3">
                                            <label for="quantityLabel" class="form-label">@lang('Quantity')</label>
                                            <input type="number"
                                                   class="form-control @error('quantity') is-invalid @enderror"
                                                   name="quantity" id="quantityLabel" placeholder="@lang('Quantity')"
                                                   aria-label="@lang('Quantity')"
                                                   autocomplete="off"
                                                   value="{{ old('quantity', $order->quantity) }}" disabled>
                                            @error('quantity')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-3 mb-3">
                                            <label for="startCounter" class="form-label">@lang('Start Counter')</label>
                                            <input type="text"
                                                   class="form-control  @error('start_counter') is-invalid @enderror"
                                                   name="start_counter" id="startCounterLabel"
                                                   placeholder="@lang("Start Counter")"
                                                   aria-label="@lang("Start Counter")"
                                                   autocomplete="off"
                                                   value="{{ old('start_counter', $order->start_counter ?? 'N/A') }}"
                                                   disabled>
                                            @error('start_counter')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>

                                @if(isset($order->interval) && $order->runs)
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-6">
                                            <label for="intervalLabel" class="form-label">@lang('Interval')</label>
                                            <input type="text"
                                                   class="form-control  @error('interval') is-invalid @enderror"
                                                   name="interval" id="intervalLabel" placeholder="@lang("Interval")"
                                                   aria-label="@lang("interval")"
                                                   autocomplete="off"
                                                   value="{{ old('interval', $order->interval) }}" disabled>
                                            @error('interval')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="runsLabel" class="form-label">@lang('Runs')</label>
                                            <input type="text" class="form-control  @error('runs') is-invalid @enderror"
                                                   name="runs" id="intervalLabel" placeholder="@lang("Runs")"
                                                   aria-label="@lang("Runs")"
                                                   autocomplete="off"
                                                   value="{{ old('runs', $order->runs) }}" disabled>
                                            @error('runs')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if($order->custom_comments)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="">
                                                <label class="form-label"
                                                       for="api_response">@lang("Comments")</label>
                                                <textarea id="custom_comments" class="form-control"
                                                          name="custom_comments"
                                                          placeholder="@lang("Comments")"
                                                          rows="3"
                                                          disabled>{{ old('custom_comments', $order->custom_comments) }}</textarea>
                                                @error('custom_comments')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'SEO' && optional($order->order_type_info)->keywords)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-3">
                                                <label class="form-label" for="keywords">@lang("Keywords")</label>
                                                <textarea id="keywords" class="form-control" name="keywords"
                                                          placeholder="@lang("Keywords")"
                                                          rows="3"
                                                          disabled>{{ old('keywords', optional($order->order_type_info)->keywords) }}</textarea>
                                                @error('keywords')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'SEO')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-3">
                                                <label class="form-label" for="keywords">@lang("Keywords")</label>
                                                <textarea id="keywords" class="form-control" name="keywords"
                                                          placeholder="@lang("Keywords")"
                                                          rows="3"
                                                          disabled>{{ old('keywords', optional($order->order_type_info)->keywords) }}</textarea>
                                                @error('keywords')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'Mentions with Hashtags' || optional($order->service)->service_type == 'Mentions Custom List')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-3">
                                                <label class="form-label" for="usernames">@lang("Usernames")</label>
                                                <textarea id="usernames" class="form-control" name="usernames"
                                                          placeholder="@lang("Usernames")"
                                                          rows="3"
                                                          disabled>{{ old('Usernames', optional($order->order_type_info)->usernames) }}</textarea>
                                                @error('usernames')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                @if(optional($order->service)->service_type == 'Mentions with Hashtags')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-3">
                                                <label class="form-label" for="hashtags">@lang("Hashtags")</label>
                                                <textarea id="hashtags" class="form-control" name="hashtags"
                                                          placeholder="@lang("hashtags")"
                                                          rows="3"
                                                          disabled>{{ old('hashtags', optional($order->order_type_info)->hashtags) }}</textarea>
                                                @error('hashtags')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'Mentions Hashtag')
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="hashtagLabel" class="form-label">@lang('Hashtag')</label>
                                            <input type="text"
                                                   class="form-control  @error('hashtag') is-invalid @enderror"
                                                   name="hashtag" id="hashtagLabel" placeholder="@lang("Hashtag")"
                                                   aria-label="@lang("Hashtag")"
                                                   autocomplete="off"
                                                   value="{{ old('hashtag', optional($order->order_type_info)->hashtag) }}"
                                                   disabled>
                                            @error('hashtag')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif


                                @if(optional($order->service)->service_type == 'Mentions User Followers' || optional($order->service)->service_type == 'Comment Likes' || optional($order->service)->service_type == 'Comment Replies')
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="usernameLabel" class="form-label">@lang('Username')</label>
                                            <input type="text"
                                                   class="form-control  @error('username') is-invalid @enderror"
                                                   name="username" id="usernameLabel" placeholder="@lang("Username")"
                                                   aria-label="@lang("Username")"
                                                   autocomplete="off"
                                                   value="{{ old('username', optional($order->order_type_info)->username) }}"
                                                   disabled>
                                            @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'Mentions Media Likers')
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label for="mediaLabel" class="form-label">@lang('Media URL')</label>
                                            <input type="text"
                                                   class="form-control  @error('media') is-invalid @enderror"
                                                   name="media" id="mediaLabel" placeholder="@lang("media")"
                                                   aria-label="@lang("media")"
                                                   autocomplete="off"
                                                   value="{{ old('media', optional($order->order_type_info)->media) }}"
                                                   disabled>
                                            @error('media')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'Poll')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="answer_number" class="form-label">@lang('Answer Number')</label>
                                            <input type="text"
                                                   class="form-control  @error('answer_number') is-invalid @enderror"
                                                   name="answer_number" id="answer_number"
                                                   placeholder="@lang("Answer Number")"
                                                   aria-label="@lang("Answer Number")"
                                                   autocomplete="off"
                                                   value="{{ old('answer_number', optional($order->order_type_info)->answer_number) }}"
                                                   disabled>
                                            @error('answer_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'Invites from Groups')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="groups" class="form-label">@lang('Groups')</label>
                                            <input type="text"
                                                   class="form-control  @error('groups') is-invalid @enderror"
                                                   name="groups" id="groups" placeholder="@lang("Groups")"
                                                   aria-label="@lang("Groups")"
                                                   autocomplete="off"
                                                   value="{{ old('groups', optional($order->order_type_info)->groups) }}"
                                                   disabled>
                                            @error('groups')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if(optional($order->service)->service_type == 'Subscriptions')
                                    <div class="subscriptions mt-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="new_posts"
                                                       class="form-label position-relative">@lang("New Posts")
                                                </label>
                                                <input type="text" class="form-control new_posts" name="new_posts"
                                                       value="{{ optional($order->order_type_info)->new_posts }}"
                                                       placeholder="Enter number of new posts" disabled>
                                                @error('new_posts')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-6">
                                                <label for="old_posts" class="form-label position-relative">
                                                    @lang("Old Posts")
                                                </label>
                                                <input type="text" class="form-control old_posts" name="old_posts"
                                                       value="{{ optional($order->order_type_info)->old_posts }}"
                                                       placeholder="Enter number of old posts" disabled>
                                                @error('old_posts')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label for="quantity" class="form-label">
                                                    @lang("Quantity Min")
                                                </label>
                                                <input type="text" class="form-control" name="min"
                                                       value="{{ optional($order->order_type_info)->min }}"
                                                       placeholder="Min" disabled>
                                                @error('min')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-6">
                                                <label for="max" class="form-label">
                                                    @lang("Quantity Min")
                                                </label>
                                                <input type="text" class="form-control" name="max"
                                                       value="{{ optional($order->order_type_info)->max }}"
                                                       placeholder="Max" disabled>
                                                @error('max')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label for="delay" class="form-label">
                                                    @lang("Delay")
                                                </label>
                                                <input type="text" class="form-control" name="delay"
                                                       value="{{ optional($order->order_type_info)->delay }} Minutes"
                                                       placeholder="delay" disabled>
                                                @error('delay')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-6">
                                                <label for="expiry_date" class="form-label">@lang("Expiry")</label>
                                                <input type="text" class="form-control" name="expiry_date"
                                                       value="{{ optional($order->order_type_info)->expiry }}"
                                                       placeholder="Expiry Date" disabled>
                                                @error('expiry_date')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                @if(optional($order->service)->service_type == 'Web Traffic')
                                    <div class="subscriptions mt-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <label for="country"
                                                       class="form-label position-relative">@lang("Country")
                                                </label>
                                                <input type="text" class="form-control" name="country"
                                                       value="{{ optional($order->order_type_info)->country }}"
                                                       placeholder="Country" disabled>
                                                @error('country')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-6">
                                                <label for="old_posts" class="form-label">
                                                    @lang("Device")
                                                </label>
                                                <input type="text" class="form-control" name="device"
                                                       value="{{ optional($order->order_type_info)->device == '1' ? 'Desktop' :
                                                        (optional($order->order_type_info)->device == '2' ? 'Mobile (Android)' :
                                                        (optional($order->order_type_info)->device == '3' ? 'Mobile (iOS)' :
                                                        (optional($order->order_type_info)->device == '4' ? 'Mixed (Mobile)' :
                                                        (optional($order->order_type_info)->device == '5' ? 'Mixed (Mobile & Desktop)' :
                                                        'Unknown')))) }}"
                                                       placeholder="Device" disabled>

                                                @error('device')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <label for="type_of_traffic" class="form-label">
                                                    @lang("Type Of Traffic")
                                                </label>
                                                <input type="text" class="form-control" name="type_of_traffic"
                                                       value="{{ optional($order->order_type_info)->type_of_traffic == '1' ? 'Google Keyword' :
                                                        (optional($order->order_type_info)->type_of_traffic == '2' ? 'Custom Referrer' :
                                                        (optional($order->order_type_info)->type_of_traffic == '3' ? 'Blank Referrer' :
                                                        'Unknown')) }}"
                                                       placeholder="Type Of Traffic" disabled>

                                                @error('type_of_traffic')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            @if(optional($order->order_type_info)->type_of_traffic == '1')
                                                <div class="col-6">
                                                    <label for="google_keyword" class="form-label">
                                                        @lang("Google Keyword")
                                                    </label>
                                                    <input type="text" class="form-control" name="google_keyword"
                                                           value="{{ optional($order->order_type_info)->google_keyword }}"
                                                           placeholder="google_keyword" disabled>
                                                    @error('google_keyword')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @elseif(optional($order->order_type_info)->type_of_traffic == '2')
                                                <div class="col-6">
                                                    <label for="referring_url" class="form-label">
                                                        @lang("Referring URL")
                                                    </label>
                                                    <input type="text" class="form-control" name="referring_url"
                                                           value="{{ optional($order->order_type_info)->referring_url }}"
                                                           placeholder="Referring Url" disabled>
                                                    @error('referring_url')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="row align-items-center mt-3">
                                    <div class="col-md-6">
                                        <label for="apiProviderLabel" class="form-label">@lang('API Provider')</label>
                                        <input type="text" class="form-control  @error('interval') is-invalid @enderror"
                                               name="api_provider" id="apiProviderLabel"
                                               placeholder="@lang("Api Provider")"
                                               aria-label="@lang("Api Provider")"
                                               autocomplete="off"
                                               value="{{ old('api_provider', optional(optional($order->service)->provider)->api_name) }}"
                                               disabled>
                                        @error('api_provider')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ApiOrderIdLabel" class="form-label">@lang('API Order ID')</label>
                                        <input type="text" class="form-control  @error('runs') is-invalid @enderror"
                                               name="api_order_id" id="ApiOrderIdLabel"
                                               placeholder="@lang("Api Order Id")"
                                               aria-label="@lang("Api Order Id")"
                                               autocomplete="off"
                                               value="{{ old('api_order_id', $order->api_order_id) }}" disabled>
                                        @error('api_order_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                @if(!empty($order->comments))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-3">
                                                <label class="form-label" for="order_note">@lang("Order Note")</label>
                                                <textarea id="order_note" class="form-control" name="order_note"
                                                          placeholder="@lang("Order Note")"
                                                          rows="3"
                                                          disabled>{{ old('order_note', $order->comments) }}</textarea>
                                                @error('order_note')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <label class="form-label" for="api_response">@lang("API Response")</label>
                                            <textarea id="api_response" class="form-control" name="api_response"
                                                      placeholder="@lang("API Response")"
                                                      rows="3"
                                                      disabled>{{ old('api_response', $order->status_description) }}</textarea>
                                            @error('api_response')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang("Modify Order Information")</h4>
                            <a class="link" href="javascript:void(0)"></a>
                            <span
                                class="badge bg-soft-{{$order->getStatusClass($order->status)}} text-{{$order->getStatusClass($order->status)}} ms-2 ms-sm-3">
                            <span class="legend-indicator bg-{{$order->getStatusClass($order->status)}}"></span>{{ ucfirst($order->status) }}
                          </span>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.order.update', $order->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-12">
                                        <label for="linkLabel" class="form-label">@lang('Link')</label>
                                        <input type="text" class="form-control  @error('link') is-invalid @enderror"
                                               name="link" id="linkLabel"
                                               placeholder="@lang("www.example.com/your_profile_identity")"
                                               aria-label="@lang("Link")"
                                               autocomplete="off"
                                               value="{{ old('link', $order->link)}}">
                                        @error('link')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="remainsLabel" class="form-label d-flex align-items-center">
                                            @lang('Remains')
                                            <span
                                                data-bs-toggle="tooltip"
                                                title="@lang('Number of units (e.g. followers, views) not delivered. This is used for partial/refunded orders.')"
                                                class="ms-1"
                                                style="cursor: pointer; color: #0d6efd;"><i class="bi bi-info-circle-fill"></i>
                                            </span>
                                        </label>
                                        <input type="number"
                                               class="form-control @error('remains') is-invalid @enderror"
                                               name="remains" id="remainsLabel" placeholder="@lang('Remains')"
                                               aria-label="@lang('Remains')"
                                               autocomplete="off"
                                               value="{{ old('remains', $order->remains) }}">
                                        @error('remains')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="startCounter" class="form-label d-flex align-items-center">
                                            @lang('Start Counter')
                                            <span
                                                data-bs-toggle="tooltip"
                                                title="@lang('Initial count at the time the order was started. Used for tracking progress.')"
                                                class="ms-1"
                                                style="cursor: pointer; color: #0d6efd;">
                                            <i class="bi bi-info-circle-fill"></i>
                                        </span>
                                        </label>
                                        <input type="number"
                                               class="form-control  @error('start_counter') is-invalid @enderror"
                                               name="start_counter" id="startCounterLabel"
                                               placeholder="@lang("Start Counter")"
                                               aria-label="@lang("Start Counter")"
                                               autocomplete="off"
                                               value="{{ old('start_counter', $order->start_counter) }}">
                                        @error('start_counter')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center">
                                    <div class="{{ isset($order->refilled_at) ? 'col-md-6' : 'col-md-12' }}">
                                        <label for="apiProviderLabel" class="form-label">@lang('Order Status')</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="status" autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                  "placeholder": "Change Status",
                                                  "hideSearch": true
                                                }'>
                                                <option value="" selected disabled>@lang("Change Status")</option>
                                                <option
                                                    value="awaiting" {{ $order->status == 'awaiting' ? 'selected' : '' }}>@lang('Awaiting')</option>
                                                <option
                                                    value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>@lang('Pending')</option>
                                                <option
                                                    value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>@lang('Processing')</option>
                                                <option
                                                    value="progress" {{ $order->status == 'progress' ? 'selected' : '' }}>@lang('In Progress')</option>
                                                <option
                                                    value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
                                                <option
                                                    value="partial" {{ $order->status == 'partial' ? 'selected' : '' }}>@lang('Partial')</option>
                                                <option
                                                    value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>@lang('Canceled')</option>
                                                <option
                                                    value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>@lang('Refunded')</option>
                                            </select>
                                        </div>
                                        @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if($order->status == 'completed'
                                          && $order->remains > 0
                                          && optional($order->service)->refill == 1
                                          && isset($order->refilled_at))
                                        <div class="col-md-6">
                                            <label for="apiProviderLabel"
                                                   class="form-label">@lang('Refill Status')</label>
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select" name="status" autocomplete="off"
                                                        data-hs-tom-select-options='{
                                                          "placeholder": "Refill Status",
                                                          "hideSearch": true
                                                        }'>
                                                    <option value="" selected disabled>@lang("Refill Status")</option>
                                                    <option
                                                        value="pending" {{ $order->refill_status == 'pending' ? 'selected' : '' }}>@lang('Pending')</option>
                                                    <option
                                                        value="processing" {{ $order->refill_status == 'processing' ? 'selected' : '' }}>@lang('Processing')</option>
                                                    <option
                                                        value="completed" {{ $order->refill_status == 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
                                                    <option
                                                        value="canceled" {{ $order->refill_status == 'canceled' ? 'selected' : '' }}>@lang('Canceled')</option>
                                                    <option
                                                        value="refunded" {{ $order->refill_status == 'refunded' ? 'selected' : '' }}>@lang('Refunded')</option>
                                                </select>
                                            </div>
                                            @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mt-3">
                                            <label class="form-label" for="note">@lang("Note")</label>
                                            <textarea id="note" class="form-control" name="reason"
                                                      placeholder="@lang("Note")"
                                                      rows="3">{{ old('reason', $order->reason) }}</textarea>
                                            @error('reason')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start mt-5">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.orders.components.order_resend_modal')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset("assets/admin/js/tom-select.complete.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select');

            $(document).on('click', '.orderResendBtn', function () {
                let route = $(this).data('route');
                $('.setResendRoute').attr('action', route);
            });
        });
    </script>
@endpush
