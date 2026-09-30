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
