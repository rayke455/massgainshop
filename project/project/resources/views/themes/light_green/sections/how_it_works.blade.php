<!-- How it works section start -->
<section class="zk-section zk-section--soft" id="how-it-works">
    <div class="container">
        <div class="zk-head zk-reveal">
            <span class="zk-eyebrow">@lang(@$how_it_works['single']['title'])</span>
            <h2 class="zk-title">{!! zkHighlight(__(@$how_it_works['single']['heading'])) !!}</h2>
            <p class="zk-lead">@lang(strip_tags(@$how_it_works['single']['description']))</p>
        </div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-2 order-lg-1 zk-reveal">
                <div class="zk-split-visual pe-lg-4">
                    <div class="zk-media zk-media--main">
                        <img src="{{ getFile(@$how_it_works['single']['media']->image_one->driver, @$how_it_works['single']['media']->image_one->path) }}"
                             alt="@lang(@$how_it_works['single']['heading'])" loading="lazy">
                    </div>
                    <div class="zk-media zk-media--inset">
                        <img src="{{ getFile(@$how_it_works['single']['media']->image_two->driver, @$how_it_works['single']['media']->image_two->path) }}"
                             alt="" loading="lazy">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 zk-reveal">
                <div class="zk-grid-2 zk-steps">
                    @forelse($how_it_works['multiple'] as $item)
                        <div class="zk-card zk-tile">
                            <span class="zk-icon"><i class="{{ @$item['media']->icon }}"></i></span>
                            <h5>@lang(@$item['title'])</h5>
                            <span>@lang(@$item['short_description'])</span>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- How it works section end -->
