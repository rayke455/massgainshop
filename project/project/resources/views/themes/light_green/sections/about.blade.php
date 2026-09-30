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
                        <br>
                        <ul class="item-list-container">
                            @forelse($about['multiple'] as $item)
                                <li class="cmn-box4">
                                    <div class="icon-box">
                                        <i class="{{ @$item['media']->icon }}"></i>
                                    </div>
                                    <div class="item-content">
                                        <h5>@lang(@$item['title'])</h5>
                                        <span>@lang(@$item['description'])</span>
                                    </div>
                                </li>
                            @empty
                            @endforelse
                        </ul>
                        <div class="btn-area mt-30">
                            <a href="{{ @$about['single']['media']->button_link }}"
                               class="cmn-btn">@lang(@$about['single']['button_name'])</a>
                        </div>
                    </div>

                </div>
                <div class="col-lg-6 order-1 order-lg-1">
                    <div class="about-image-area rotate-group-images">
                        <figure class="img1">
                            <img
                                    src="{{ getFile(@$about['single']['media']->image_one->driver, @$about['single']['media']->image_one->path) }}"
                                    alt="@lang("About Image One")">
                        </figure>
                        <figure class="img2">
                            <img
                                    src="{{ getFile(@$about['single']['media']->image_two->driver, @$about['single']['media']->image_two->path) }}"
                                alt="@lang("About Image Two")">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About section end -->
