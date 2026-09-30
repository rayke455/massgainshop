$.fn.socialSharingPlugin = function(options) {
    let settings = $.extend({
        urlShare: '',
        btnTarget: '_blank',
        btnTitle: 'Share on',
        title: '',
        description: '',
        via: '',
        hashtags: '',
        img: '',
        isVideo: 'false',
        buttonClass: 'btn btn-light',
        applyDefaultButtonStyle: true
    }, options);

    let urls = {
        facebook: {
            icon: 'fab fa-facebook',
            url: 'https://www.facebook.com/sharer.php?u=[post-url]',
            color: '#1877F2' // Facebook color
        },
        instagram: {
            icon: 'fab fa-instagram-square',
            url: 'https://www.instagram.com/share?url=[post-url]&title=[post-title]',
            color: '#E1306C' // Instagram color
        },
        linkedin: {
            icon: 'fab fa-linkedin',
            url: 'https://www.linkedin.com/shareArticle?url=[post-url]&title=[post-title]',
            color: '#0077B5' // LinkedIn color
        },
        twitter: {
            icon: 'fab fa-twitter',
            url: 'https://twitter.com/intent/tweet?url=[post-url]&text=[post-title]',
            color: '#1DA1F2' // Twitter color
        },

    };

    let build = function (e) {
        if (e.find('a').length > 0) {
            return;
        }

        $.each(urls, function (k, v) {
            let link = v.url
                .replace('[post-title]', encodeURIComponent(settings.title))
                .replace('[post-url]', encodeURIComponent(settings.urlShare))
                .replace('[post-desc]', encodeURIComponent(settings.description))
                .replace('[post-img]', encodeURIComponent(settings.img))
                .replace('[is_video]', encodeURIComponent(settings.isVideo))
                .replace('[hashtags]', encodeURIComponent(settings.hashtags))
                .replace('[via]', encodeURIComponent(settings.via));

            let btn = $('<a></a>');
            btn.attr('class', settings.buttonClass);
            btn.attr('href', link);
            btn.attr('target', settings.btnTarget);
            btn.attr('title', settings.btnTitle + ' ' + k);

            let icon = $('<i></i>');
            icon.attr('class', v.icon);
            if (settings.applyDefaultButtonStyle)
                icon.css({color: v.color});
            btn.append(icon);
            e.append(btn);


            // Add hover effect with JS
            btn.hover(
                function () {
                    // On hover, change background color to platform-specific color
                    $(this).css('background-color', v.color);
                    $(this).css('border-color', v.color);
                    $(this).css('color', '#fff'); // Make sure the text and icons turn white
                    $(this).find('i').css('color', '#fff'); // Make sure the icon turns white

                },
                function () {
                    // On hover out, reset to the original styles
                    $(this).css('background-color', '');
                    $(this).css('border-color', '');
                    $(this).css('color', '');
                    $(this).find('i').css('color', ''); // Reset the icon color

                }
            );

        });
    };

    return this.each(function () {
        return new build($(this));
    });
};
