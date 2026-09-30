<div id="hero-banner">
    <div class="shape-rectangle wow fadeIn">
        <div class="rectangle-lg"></div>
        <div class="rectangle-sm"></div>
    </div>
    <div class="hero-fig">
        <img class="wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s"
             src="{{asset(template(true).'images/welcome_bg.jpg')}}" alt="First Layer">
        <div class="hero-fig-overlay wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
        </div>
        <div class="hero-fig-img wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.7s">
            <img src="{{ getFile(@$hero['single']['media']->image->driver, @$hero['single']['media']->image->path) }}" alt="Hero Img">
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="h1 wow fadeInUp" data-wow-duration="1s"
                        data-wow-delay="0.1s">@lang(@$hero['single']['title'])</h1>
                    <p class="p mt-30 mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                        @lang(@strip_tags($hero['single']['short_description']))
                    </p>
                    <a class="btn btn-hero wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s"
                       href="{{ @$hero['single']['media']->button_link }}"> @lang(@$hero['single']['button_name'])</a>
                </div>
            </div>
        </div>
    </div>
</div>

