<!-- api-docs -->
<section class="api-docs">
    <!--API DETAILS-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details shadow">
                    <div class="card-header">
                        <h5 class="card-title">@lang('API DETAILS')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>@lang('API URL')</h6>
                                <p>{{ route('userApiKey') }}</p>
                            </div>
                            <div class="col-sm-12">
                                <h6>@lang('API KEY')</h6>
                                <p>@lang('Your API Key')</p>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <h6>@lang('HTTP METHOD')</h6>
                                <p>@lang('POST')</p>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <h6>@lang('RESPONSE FORMAT')</h6>
                                <p>@lang('JSON')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--PLACE NEW ORDER-->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">@lang("PLACE NEW ORDER")</h5>
                        <div class="col-md-3 text-end">
                            <select class="custom-select-color form-control" name="type" id="service_type">
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
                        </div>
                    </div>

                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>
                                    @lang('add')
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('service')</h6>
                                <p>@lang('Service ID')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('link')</h6>
                                <p>@lang('Link to page')</p>
                            </div>

                            <div class="col-sm-4 default seo mentions_with_hashtags mentions_hashtag mentions_user_followers mentions_media_likers comment_likes poll invites_from_groups web_traffic">
                                <h6 class="text-lowercase">@lang('quantity')</h6>
                                <p>@lang('Needed quantity')</p>
                            </div>

                            <div class="col-sm-4 default web_traffic">
                                <h6 class="text-lowercase">@lang('runs')
                                    <small class="text-muted">@lang('(optional)')</small>
                                </h6>
                                <p>@lang('Runs to deliver')</p>
                            </div>

                            <div class="col-sm-4 default web_traffic">
                                <h6 class="text-lowercase">@lang('interval')
                                    <small class="text-muted">@lang('(optional)')</small>
                                </h6>
                                <p>@lang('Interval in minutes')</p>
                            </div>

                            <div class="col-sm-4 custom_comments custom_comments_package comment_replies">
                                <h6 class="text-lowercase">@lang('comments')
                                </h6>
                                <p>@lang("Comments list separated by " . '\r\n or \n')</p>
                            </div>

                            <div class="col-sm-4 seo">
                                <h6 class="text-lowercase">@lang('keywords')
                                </h6>
                                <p>@lang("Keywords list separated by" . ' \r\n or \n')</p>
                            </div>

                            <div class="col-sm-4 mentions_with_hashtags mentions_custom_list">
                                <h6 class="text-lowercase">@lang('usernames')
                                </h6>
                                <p>@lang("Usernames list separated by " . ' \r\n or \n')</p>
                            </div>

                            <div class="col-sm-4 mentions_with_hashtags">
                                <h6 class="text-lowercase">@lang('hashtags')
                                </h6>
                                <p>@lang("Hashtags list separated by " . '\r\n or \n')</p>
                            </div>

                            <div class="col-sm-4 mentions_hashtag">
                                <h6><span>@lang("hashtag")</span></h6>
                                <p><span>@lang("Hashtag to scrape usernames from")</span></p>
                            </div>

                            <div class="col-sm-4 mentions_user_followers">
                                <h6><span>@lang("username")</span></h6>
                                <p><span>@lang("URL to scrape followers from")</span></p>
                            </div>

                            <div class="col-sm-4 comment_likes">
                                <h6><span>@lang("username")</span></h6>
                                <p><span>@lang("Username of the comment owner")</span></p>
                            </div>

                            <div class="col-sm-4 comment_replies subscriptions">
                                <h6><span>@lang("username")</span></h6>
                                <p><span>@lang("username")</span></p>
                            </div>

                            <div class="col-sm-4 mentions_media_likers">
                                <h6><span>@lang("media")</span></h6>
                                <p><span>@lang("Media URL to scrape likers from")</span></p>
                            </div>

                            <div class="col-sm-4 poll">
                                <h6><span>@lang("answer_number")</span></h6>
                                <p><span>@lang("Answer number of the poll")</span></p>
                            </div>

                            <div class="col-sm-4 invites_from_groups">
                                <h6><span>@lang("groups")</span></h6>
                                <p><span>@lang("Groups list separated by \\r\\n or \\n")</span></p>
                            </div>

                            <div class="col-sm-4 subscriptions">
                                <h6><span>@lang("min")</span></h6>
                                <p><span>@lang("Quantity min")</span></p>
                            </div>

                            <div class="col-sm-4 subscriptions">
                                <h6><span>@lang("max")</span></h6>
                                <p><span>@lang("Quantity max")</span></p>
                            </div>

                            <div class="col-sm-8 subscriptions">
                                <h6><span>@lang("posts (optional)")</span></h6>
                                <p><span>@lang("Use this parameter if you want to limit the number of new (future) posts that will be parsed and for which orders will be created. If posts parameter is not set, the subscription will be created for an unlimited number of posts.")</span></p>
                            </div>

                            <div class="col-sm-7 subscriptions">
                                <h6><span>@lang("old_posts (optional)")</span></h6>
                                <p><span>@lang("Number of existing posts that will be parsed and for which orders will be created, can be used if this option is available for the service.")</span></p>
                            </div>

                            <div class="col-sm-5 subscriptions">
                                <h6><span>@lang("delay")</span></h6>
                                <p><span>@lang("Delay in minutes. Possible values: 0, 5, 10, 15, 20, 30, 40, 50, 60, 90, 120, 150, 180, 210, 240, 270, 300, 360, 420, 480, 540, 600")</span></p>
                            </div>

                            <div class="col-sm-4 subscriptions">
                                <h6><span>@lang("expiry (optional)")</span></h6>
                                <p><span>@lang("Expiry date. Format d/m/Y.")</span></p>
                            </div>

                            <div class="col-sm-4 web_traffic">
                                <h6><span>@lang("country")</span></h6>
                                <p><span>@lang('Country code or full country name. Format: "US" or "United States"')</span></p>
                            </div>

                            <div class="col-sm-4 web_traffic">
                                <h6><span>@lang("device")</span></h6>
                                <p><span>@lang('Device name. 1 - Desktop, 2 - Mobile (Android), 3 - Mobile (IOS), 4 - Mixed (Mobile), 5 - Mixed (Mobile & Desktop).')</span></p>
                            </div>

                            <div class="col-sm-4 web_traffic">
                                <h6><span>@lang("type_of_traffic")</span></h6>
                                <p><span>@lang('1 - Google Keyword, 2 - Custom Referrer, 3 - Blank Referrer')</span></p>
                            </div>

                            <div class="col-sm-4 web_traffic">
                                <h6><span>@lang("google_keyword")</span></h6>
                                <p><span>@lang('required if type_of_traffic = 1')</span></p>
                            </div>

                            <div class="col-sm-4 web_traffic">
                                <h6><span>@lang("referring_url")</span></h6>
                                <p><span>@lang('required if type_of_traffic = 2')</span></p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "status": "success",
    "order": 116
}
</pre
>
                </div>
            </div>
        </div>
    </div>

    <!--STATUS ORDER-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('STATUS ORDER')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('status')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('order')</h6>
                                <p>@lang('Order ID')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "status": "Processing",
    "charge": "3.60",
    "start_count": 0,
    "remains": 0,
    "currency": "BDT"
}
</pre
>
                </div>
            </div>
        </div>
    </div>

    <!--MULTIPLE STATUS ORDER-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('MULTIPLE STATUS ORDER')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('orders')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('orders')</h6>
                                <p>@lang('Order IDs separated by comma (array data)')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
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
]</pre
                    >
                </div>
            </div>
        </div>
    </div>

    <!--CREATE REFILL-->
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <h5 class="card-title text-white">@lang('PLACE REFILL')</h5>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-3">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>
                                    @lang('refill')
                                </p>
                            </div>
                            <div class="col-sm-3">
                                <h6 class="text-lowercase">@lang('order')</h6>
                                <p>@lang('Order ID')</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="api-code mt-2 mb-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "refill": "1"
}
</pre>
                </div>
            </div>
        </div>
    </div>

    <!--STATUS REFILL-->
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <h5 class="card-title text-white">@lang('STATUS REFILL')</h5>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>
                                    @lang('refill_status')
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('refill')</h6>
                                <p>@lang('Refill ID')</p>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="api-code mt-2 mb-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
    "status": "Completed"
}
</pre>
                </div>

            </div>
        </div>
    </div>

    <!--SERVICE LIST-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('SERVICE LIST')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('services')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
[
    {
        "service": 1,
        "name": "🙋&zwj;♂️ Followers [Ultra-High Quality Profiles]",
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
]</pre
                    >
                </div>
            </div>
        </div>
    </div>

    <!--USER BALANCE-->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card api-details mb-0">
                    <div class="card-header">
                        <h5 class="card-title">@lang('USER BALANCE')</h5>
                    </div>
                    <div class="card-body content">
                        <div class="row">
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('key')</h6>
                                <p>@lang('Your API key')</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-lowercase">@lang('action')</h6>
                                <p>@lang('balance')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="api-code my-5">
                    <p class="text-success">//Example response</p>
                    <pre class="text-white">
{
  "status": "success",
  "balance": "0.03",
  "currency": "USD"
}
</pre>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /api-docs -->
