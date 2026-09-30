<!-- How it works section start -->
<section class="how-it-works-section">
    <div class="container">
        <div class="row">
            <div class="section-header text-center">
                <span class="section-subtitle">@lang(@$how_it_works['single']['title']) </span>
                <h2 class="section-title mx-auto">@lang(@$how_it_works['single']['heading'])</h2>
                <p class="cmn-para-text mx-auto">@lang(strip_tags(@$how_it_works['single']['description']))</p>
            </div>
        </div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="how-it-works-content">
                    <div class="row g-0">
                        @forelse($how_it_works['multiple'] as $item)
                            <div class="col-md-6 cmn-box2-item">
                                <div class="cmn-box2">
                                    <div class="icon-box">
                                        <i class="{{ @$item['media']->icon }}"></i>
                                    </div>
                                    <div class="content-box">
                                        <h5>@lang(@$item['title'])</h5>
                                        <span>@lang(@$item['short_description'])</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="how-it-works-thumbs rotate-group-images">
                    <figure class="img1">
                        <img
                            src="{{ @getFile(@$how_it_works['single']['media']->image_one->driver, @$how_it_works['single']['media']->image_one->path) }}"
                            alt="Image One">
                    </figure>
                    <figure class="img2">
                        <img
                            src="{{ getFile(@$how_it_works['single']['media']->image_two->driver, @$how_it_works['single']['media']->image_two->path) }}"
                            alt="Image Two">
                    </figure>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- How it works section end -->
