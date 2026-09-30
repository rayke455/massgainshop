<!-- Feature section start -->
<section class="feature-section">
    <div class="container">
        <div class="feature-section-inner">
            @forelse($feature['multiple'] as $item)
                <div class="item">
                    <div class="icon-area">
                        <i class="{{ @$item['media']->icon }}"></i>
                    </div>
                    <div class="content-area">
                        <h4 class="title">@lang(@$item['title'])</h4>
                        <p>@lang(@$item['short_description'])</p>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- Feature section end -->


