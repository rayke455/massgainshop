<!-- Why Choose us section start -->
<section class="zk-section">
    <div class="container">
        <div class="zk-head zk-reveal">
            <span class="zk-eyebrow">@lang(@$why_choose_us['single']['title'])</span>
            <h2 class="zk-title">{!! zkHighlight(__(@$why_choose_us['single']['heading'])) !!}</h2>
            <p class="zk-lead">@lang(strip_tags(@$why_choose_us['single']['description']))</p>
        </div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 zk-reveal">
                <div class="zk-grid-2">
                    @forelse($why_choose_us['multiple'] as $item)
                        <div class="zk-card zk-tile">
                            <span class="zk-icon"><i class="{{ @$item['media']->icon }}"></i></span>
                            <h5>@lang(@$item['title'])</h5>
                            <span>@lang(strip_tags(@$item['description']))</span>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="col-lg-6 zk-reveal">
                <div class="zk-split-visual ps-lg-4">
                    <div class="zk-media zk-media--main">
                        <img src="{{ getFile(@$why_choose_us['single']['media']->image_one->driver, @$why_choose_us['single']['media']->image_one->path) }}"
                             alt="@lang(@$why_choose_us['single']['heading'])" loading="lazy">
                    </div>
                    <div class="zk-media zk-media--inset">
                        <img src="{{ getFile(@$why_choose_us['single']['media']->image_two->driver, @$why_choose_us['single']['media']->image_two->path) }}"
                             alt="" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Choose us section end -->
