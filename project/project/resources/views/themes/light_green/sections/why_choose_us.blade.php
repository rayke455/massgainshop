<!-- Why Choose us section start -->
<section class="why-choose-us-section">
    <div class="container">
        <div class="row">
            <div class="section-header text-center">
                <span class="section-subtitle">@lang(@$why_choose_us['single']['title'])</span>
                <h2 class="section-title mx-auto"> @lang(@$why_choose_us['single']['heading']) </h2>
                <p class="cmn-para-text mx-auto">@lang(@$why_choose_us['single']['description'])</p>
            </div>
        </div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="why-choose-us-thumbs">
                    <div class="image-area rotate-group-images">
                        <figure class="img1">
                            <img
                                    src="{{ getFile(@$why_choose_us['single']['media']->image_one->driver, @$why_choose_us['single']['media']->image_one->path) }}"
                                    alt="Image One">
                        </figure>
                        <figure class="img2">
                            <img
                                    src="{{ getFile(@$why_choose_us['single']['media']->image_two->driver, @$why_choose_us['single']['media']->image_two->path) }}"
                                    alt="Image Two">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="why-choose-us-content">
                    <div class="row g-0">
                        @forelse($why_choose_us['multiple'] as $item)
                            <div class="col-md-6 cmn-box2-item">
                                <div class="cmn-box2">
                                    <div class="icon-box">
                                        <i class="{{ @$item['media']->icon }}"></i>
                                    </div>
                                    <div class="content-box">
                                        <h5>@lang(@$item['title'])</h5>
                                        <span>@lang(strip_tags(@$item['description']))</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Choose us section end -->
