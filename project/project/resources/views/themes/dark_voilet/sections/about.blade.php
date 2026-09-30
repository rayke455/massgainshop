<!-- About section start -->
<section class="about-section">
    <div class="container">
        <div class="about-section-inner">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="about-content">
                        <span class="section-subtitle">@lang(@$about['single']['title'])</span>
                        <h2 class="section-title">@lang(@$about['single']['heading'])</h2>
                        <p>
                            @lang(strip_tags(@$about['single']['description']))
                        </p>

                        <div class="btn-area mt-30">
                            <a href="{{ @$about['single']['media']->button_link }}"
                               class="cmn-btn">@lang(@$about['single']['button_name'])</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-1">
                    <div class="about-image-area">
                        <img
                            src="{{ getFile(@$about['single']['media']->image->driver, @$about['single']['media']->image->path) }}"
                            alt="About Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About section end -->
