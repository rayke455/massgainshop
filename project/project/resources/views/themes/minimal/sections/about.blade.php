<!-- ABOUT-US -->
<section id="about-us">
    <div class="shape-circle wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.35s">
        <div class="circle"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6">
                <div class="wrapper wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.35s">
                    <img class="img-br-6 img-w"
                         src="{{ getFile(@$about['single']['media']->image->driver, @$about['single']['media']->image->path) }}"
                         alt="@lang("Image Missing")">
                    <div class="youtube-wrapper">
                        <div class="btn-container">
                            <div class="btn-play grow-play">
                                <i class="icofont-ui-play"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="text-wrapper wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.35s">
                    <h1 class="heading heading-left">@lang(@$about['single']['title'])<span
                                class="sub-heading">@lang(@$about['single']['sub_title'])</span></h1>
                    <div class="text-block">
                        <h3 class="h3 mb-30">@lang(@$about['single']['short_title'])</h3>
                        <p class="text">
                            @lang(strip_tags(@$about['single']['short_description']))
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /ABOUT-US -->


<!-- MODAL-VIDEO -->
<div id="modal-video">
    <div class="modal-wrapper">
        <div class="modal-content">
            <div class="btn-close">&times;</div>
            <div class="modal-container">
                <iframe width="100%" height="100%" src="{{@$about['single']['youtube_link']}}"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
<!-- /MODAL-VIDEO -->



