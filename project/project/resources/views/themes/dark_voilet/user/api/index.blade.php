@extends(template().'layouts.user')
@section('title',trans('API Docs'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("api")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active">@lang("api")</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-50">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">@lang("API Key")</h5>
                        <button class="cmn-btn" data-bs-toggle="modal"
                                data-bs-target="#apiKeyGenerateModal">@lang("Generate Key")</button>
                    </div>
                    <div class="card-body p-sm-5">
                        <h6>@lang("API KEY")</h6>
                        <div class="input-group mb-3">
                            <input id="api_key" type="{{ session()->get('type') == 'text' ? 'text' : 'password' }}"
                                   class="form-control api_token"
                                   value="{{ $api_token ?? 'Generate Your API Key' }}"
                                   aria-label="@lang("Api Key")" aria-describedby="basic-addon2" readonly>
                            @if(session()->get('type'))
                                <div class="input-group-text" id="copyBtn">
                                    <i class="fa-light fa-copy"></i>@lang("Copy")</div>
                            @else
                                <div class="input-group-text" data-bs-toggle="modal"
                                     data-bs-target="#apiKeyViewModal">
                                    <i class="fa-light fa-eye"></i>@lang("View")</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("API Documentation")</h5>
                    </div>
                    <div class="card-body">
                        <small>@lang("Note: Please read the API intructions carefully. Its your solo responsability what
                            you add by
                            our API.")</small>
                        <div class="cmn-table mt-20">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("HTTP Method")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("POST")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("Response format")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Json")</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("API URL")</span></td>
                                        <td data-label="Description">
                                            <span class="text-break">{{ route('userApiKey') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("API Key")</span></td>
                                        <td data-label="Description">
                                            <span class="text-break">demoQmuMPsR1ybOF9rmVa</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">@lang("PLACE NEW ORDER")</h5>
                        <div class="col-md-3">
                            <select class="cmn-select2" name="type" id="service_type">
                                <option value="default">@lang("Default")</option>
                                <option value="package">@lang('Package')</option>
                                <option value="seo">@lang('SEO')</option>
                                <option value="custom_comments">@lang('Custom Comments')</option>
                                <option value="mentions_with_hashtags">@lang('Mentions with Hashtags')</option>
                                <option value="mentions_custom_list">@lang('Mentions Custom List')</option>
                                <option value="mentions_hashtag">@lang('Mentions Hashtag')</option>
                                <option value="mentions_user_followers">@lang('Mentions User Followers')</option>
                                <option value="mentions_media_likers">@lang('Mentions Media Likers')</option>
                                <option value="custom_comments_package">@lang('Custom Comments Package')</option>
                                <option value="comment_likes">@lang('Comment Likes')</option>
                                <option value="poll">@lang('Poll')</option>
                                <option value="comment_replies">@lang('Comment Replies')</option>
                                <option value="invites_from_groups">@lang('Invites from Groups')</option>
                                <option value="subscriptions">@lang('Subscriptions')</option>
                                <option value="web_traffic">@lang('Web Traffic')</option>
                            </select>
                            @error('time_zone')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <td data-label="@lang("Parameter")"><span>@lang("key")</span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Your API key")</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td data-label="@lang("Parameter")"><span>@lang("action")</span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("add")</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td data-label="@lang("Parameter")"><span>@lang("service")</span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Service ID")</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td data-label="@lang("Parameter")"><span>@lang("link")</span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Link to page")</span>
                                        </td>
                                    </tr>

                                    <tr class="default seo mentions_with_hashtags mentions_hashtag mentions_user_followers mentions_media_likers comment_likes poll invites_from_groups web_traffic">
                                        <td data-label="@lang("Parameter")"><span>@lang("quantity")</span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Needed quantity")</span>
                                        </td>
                                    </tr>

                                    <tr class="default web_traffic">
                                        <td data-label="@lang("Parameter")">
                                            <span>@lang("runs") <small>(optional)</small></span>
                                        </td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Runs to deliver")</span>
                                        </td>
                                    </tr>

                                    <tr class="default web_traffic">
                                        <td data-label="@lang("Parameter")"><span>@lang("interval")
                                                    <small>@lang("(optional)")</small></span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Interval in minutes")</span>
                                        </td>
                                    </tr>

                                    <tr class="custom_comments custom_comments_package comment_replies">
                                        <td data-label="@lang("Parameter")"><span>@lang("comments")</span></td>
                                        <td data-label="@lang("Description")">
                                            <span>@lang("Comments list separated by") \r\n or \n</span>
                                        </td>
                                    </tr>

                                    <tr class="seo">
                                        <td><span>@lang("keywords")</span></td>
                                        <td><span>@lang("Keywords list separated by \\r\\n or \\n")</span></td>
                                    </tr>

                                    <tr class="mentions_with_hashtags mentions_custom_list">
                                        <td><span>@lang("usernames")</span></td>
                                        <td><span>@lang("Usernames list separated by \\r\\n or \\n")</span></td>
                                    </tr>

                                    <tr class="mentions_with_hashtags">
                                        <td><span>@lang("Hashtags")</span></td>
                                        <td><span>@lang("Hashtags list separated by \\r\\n or \\n")</span></td>
                                    </tr>

                                    <tr class="mentions_hashtag">
                                        <td><span>@lang("hashtag")</span></td>
                                        <td><span>@lang("Hashtag to scrape usernames from")</span></td>
                                    </tr>

                                    <tr class="mentions_user_followers">
                                        <td><span>@lang("username")</span></td>
                                        <td><span>@lang("URL to scrape followers from")</span></td>
                                    </tr>

                                    <tr class="comment_likes">
                                        <td><span>@lang("username")</span></td>
                                        <td><span>@lang("Username of the comment owner")</span></td>
                                    </tr>

                                    <tr class="comment_replies subscriptions">
                                        <td><span>@lang("username")</span></td>
                                        <td><span>@lang("username")</span></td>
                                    </tr>

                                    <tr class="mentions_media_likers">
                                        <td><span>@lang("media")</span></td>
                                        <td><span>@lang("Media URL to scrape likers from")</span></td>
                                    </tr>

                                    <tr class="poll">
                                        <td><span>@lang("answer_number")</span></td>
                                        <td><span>@lang("Answer number of the poll")</span></td>
                                    </tr>

                                    <tr class="invites_from_groups">
                                        <td><span>@lang("groups")</span></td>
                                        <td><span>@lang("Groups list separated by \\r\\n or \\n")</span></td>
                                    </tr>

                                    <tr class="subscriptions">
                                        <td><span>@lang("min")</span></td>
                                        <td>
                                            <span>@lang("Quantity min.")</span>
                                        </td>
                                    </tr>

                                    <tr class="subscriptions">
                                        <td><span>@lang("max")</span></td>
                                        <td>
                                            <span>@lang("Quantity max.")</span>
                                        </td>
                                    </tr>

                                    <tr class="subscriptions">
                                        <td><span>@lang("posts (optional)")</span></td>
                                        <td>
                                            <span>@lang("Use this parameter if you want to limit the number of new (future) posts that will be parsed and for which orders will be created. If posts parameter is not set, the subscription will be created for an unlimited number of posts..")</span>
                                        </td>
                                    </tr>

                                    <tr class="subscriptions">
                                        <td><span>@lang("old_posts (optional)")</span></td>
                                        <td>
                                            <span>@lang("Number of existing posts that will be parsed and for which orders will be created, can be used if this option is available for the service.")</span>
                                        </td>
                                    </tr>

                                    <tr class="subscriptions">
                                        <td><span>@lang("delay")</span></td>
                                        <td>
                                            <span>@lang("Delay in minutes. Possible values: 0, 5, 10, 15, 20, 30, 40, 50, 60, 90, 120, 150, 180, 210, 240, 270, 300, 360, 420, 480, 540, 600.")</span>
                                        </td>
                                    </tr>

                                    <tr class="subscriptions">
                                        <td><span>@lang("expiry (optional)")</span></td>
                                        <td>
                                            <span>@lang("Expiry date. Format d/m/Y.")</span>
                                        </td>
                                    </tr>

                                    <tr class="web_traffic">
                                        <td><span>@lang("country")</span></td>
                                        <td>
                                            <span>@lang('Country code or full country name. Format: "US" or "United States".')</span>
                                        </td>
                                    </tr>

                                    <tr class="web_traffic">
                                        <td><span>@lang("device")</span></td>
                                        <td>
                                            <span>@lang('Device name. 1 - Desktop, 2 - Mobile (Android), 3 - Mobile (IOS), 4 - Mixed (Mobile), 5 - Mixed (Mobile & Desktop).')</span>
                                        </td>
                                    </tr>

                                    <tr class="web_traffic">
                                        <td><span>@lang("type_of_traffic")</span></td>
                                        <td>
                                            <span>@lang('1 - Google Keyword, 2 - Custom Referrer, 3 - Blank Referrer')</span>
                                        </td>
                                    </tr>

                                    <tr class="web_traffic">
                                        <td><span>@lang("google_keyword")</span></td>
                                        <td>
                                            <span>@lang('required if type_of_traffic = 1')</span>
                                        </td>
                                    </tr>

                                    <tr class="web_traffic">
                                        <td><span>@lang("referring_url")</span></td>
                                        <td>
                                            <span>@lang('required if type_of_traffic = 2')</span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "status": "success",
   "order": 116
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("STATUS ORDER")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("status")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("order")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Order ID")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "status": "Processing",
   "charge": "3.60",
   "start_count": 0,
   "remains": 0,
   "currency": "BDT"
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("MULTIPLE STATUS ORDER")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("orders")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("orders")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Order IDs separated by comma (array data)")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
[
   {
      "order": 116,
      "status": "Processing",
      "charge": "3.60",
      "start_count": 10,
      "remains": 0
   },
   {
      "order": 117,
      "status": "Completed",
      "charge": null,
      "start_count": 0,
      "remains": 0
   }
]
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("PLACE REFILL")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("refill")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("order")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Order ID")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "refill": "1"
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("STATUS REFILL")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("refill_status")</span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("refill")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Refill ID")</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
{
   "status": "Completed"
}
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-50">
                    <div class="card-header">
                        <h5 class="mb-0">@lang("SERVICE LIST")</h5>
                    </div>
                    <div class="card-body">
                        <div class="cmn-table">
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang("Parameter")</th>
                                        <th scope="col">@lang("Description")</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td data-label="Parameter"><span>@lang("key")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("Your API key")</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td data-label="Parameter"><span>@lang("action")</span></td>
                                        <td data-label="Description">
                                            <span>@lang("services")</span>
                                        </td>

                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="mb-10">@lang("Example response:")</h5>
                            <div class="api-code-box">
                                <pre>
[
   {
      "service": 1,
      "name": "🙋‍♂️ Followers [Ultra-High Quality Profiles]",
      "category": "🥇 [VIP]\r\n",
      "rate": "4.80",
      "min": 100,
      "max": 10000
   },
   {
      "service": 11,
      "name": "🧨 Instagram Power Comments (100k+ Accounts) ➡️ [3 Comments]",
      "category": "💬 Instagram - Verified / Power Comments [ Own Service ]",
      "rate": "0.60",
      "min": 500,
      "max": 5000
   },
   {
      "service": 52,
      "name": "🎙️ Facebook Live Stream Views ➡️ [ 120 Min ]",
      "category": "🔵 Facebook - Live Stream Views\r\n",
      "rate": "57.60",
      "min": 50,
      "max": 2000
   }
]
                                </pre>
                                <div class="editor-dots">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    @include(template(). 'user.api.components.api_key_generate_modal')
    @include(template(). 'user.api.components.api_key_view_modal')
@endsection


@push('script')
    <script>
        "use strict";
        document.getElementById("copyBtn").addEventListener("click", () => {
            let copyText = document.getElementById("api_key");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        })
    </script>

    <script>
        'use strict';
        const orderTypeConfig = {
            default: ['.default'],
            custom_comments: ['.custom_comments'],
            package: ['.package'],
            seo: ['.seo'],
            mentions_with_hashtags: ['.mentions_with_hashtags'],
            mentions_custom_list: ['.mentions_custom_list'],
            mentions_hashtag: ['.mentions_hashtag'],
            mentions_user_followers: ['.mentions_user_followers'],
            mentions_media_likers: ['.mentions_media_likers'],
            custom_comments_package: ['.custom_comments_package'],
            comment_likes: ['.comment_likes'],
            poll: ['.poll'],
            comment_replies: ['.comment_replies'],
            invites_from_groups: ['.invites_from_groups'],
            subscriptions: ['.subscriptions'],
            web_traffic: ['.web_traffic'],
        };

        function hideAllRows() {
            $('.default, .custom_comments, .package, .seo, .mentions_with_hashtags, .mentions_custom_list, .mentions_hashtag, .mentions_user_followers, .mentions_media_likers, .custom_comments_package, .comment_likes, .poll, .comment_replies, .invites_from_groups, .subscriptions, .web_traffic').hide();
        }

        function showFields(type) {
            hideAllRows();
            const visibleSelectors = orderTypeConfig[type] || ['.default'];
            visibleSelectors.forEach(selector => $(selector).show());
        }

        const $serviceType = $('#service_type');
        if ($serviceType.length) {
            $serviceType.select2({
                theme: 'bootstrap-5',
                width: '100%',
            });

            showFields($serviceType.val());

            $serviceType.on('change', function () {
                showFields($(this).val());
            });
        }
    </script>
@endpush




