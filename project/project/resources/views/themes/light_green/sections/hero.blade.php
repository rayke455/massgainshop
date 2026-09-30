<!-- Hero section start -->
<div class="hero-section">
    <div class="container">
        <div class="hero-section-inner">
            <div class="row g-xl-4 g-5 justify-content-between align-items-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="hero-content">
                        <span class="section-subtitle">@lang(@$hero['single']['title'])</span>
                        <h1 class="hero-title">
                            @lang(@$hero['single']['heading'])
                        </h1>
                        <p class="hero-description">
                            @lang(@$hero['single']['short_description'])
                        </p>

                        <div class="btn-area">
                            <a href="{{ @$hero['single']['media']->button_link }}"
                               class="cmn-btn">@lang(@$hero['single']['button_name'])</a>
                            <a data-fancybox
                               href="{{ @$hero['single']['media']->video_link }}"
                               class="video-play-btn">
                                <i class="fa-regular fa-play"></i>
                            </a>
                            <p class="mb-0">@lang(@$hero['single']['video_text'])</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mx-auto order-1 order-lg-2">
                    <div class="hero-thumbs mx-auto"
                         style="mask-image: url({{ getFile(@$hero['single']['media']->background_image->driver, @$hero['single']['media']->background_image->path) }}); -webkit-mask-image:url({{ getFile($hero['single']['media']->background_image->driver,$hero['single']['media']->background_image->path) }})">
                        <img
                            src="{{ getFile(@$hero['single']['media']->image->driver, @$hero['single']['media']->image->path) }}"
                            alt="Hero Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero section end -->
