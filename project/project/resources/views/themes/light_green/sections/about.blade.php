<!-- About section start -->
<section class="zk-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 zk-reveal">
                <div class="zk-split-visual">
                    <div class="zk-media zk-media--main">
                        <img src="{{ getFile(@$about['single']['media']->image_one->driver, @$about['single']['media']->image_one->path) }}"
                             alt="@lang(@$about['single']['heading'])" loading="lazy">
                    </div>
                    <div class="zk-media zk-media--inset">
                        <img src="{{ getFile(@$about['single']['media']->image_two->driver, @$about['single']['media']->image_two->path) }}"
                             alt="" loading="lazy">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 zk-reveal">
                <div class="ps-lg-4">
                    <span class="zk-eyebrow">@lang(@$about['single']['title'])</span>
                    <h2 class="zk-title">{!! zkHighlight(__(@$about['single']['heading'])) !!}</h2>
                    <p class="zk-lead">@lang(strip_tags(@$about['single']['description']))</p>

                    <ul class="zk-checklist">
                        @forelse($about['multiple'] as $item)
                            <li>
                                <span class="zk-icon"><i class="{{ @$item['media']->icon }}"></i></span>
                                <div>
                                    <h5>@lang(@$item['title'])</h5>
                                    <span>@lang(strip_tags(@$item['description']))</span>
                                </div>
                            </li>
                        @empty
                        @endforelse
                    </ul>

                    @if(@$about['single']['button_name'])
                        <a href="{{ @$about['single']['media']->button_link }}" class="zk-btn zk-btn--primary mt-4">
                            @lang(@$about['single']['button_name'])
                            <i class="fa-regular fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About section end -->
