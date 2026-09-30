<!-- Feature section start -->
<section class="feature-section">
    <div class="container">
        <div class="row g-4">
            @forelse($feature['multiple'] as $item)
                <div class="col-xl-3 col-md-6">
                    <div class="feature-box">
                        <div class="icon-area">
                            <i class="{{ @$item['media']->icon }}"></i>
                        </div>
                        <div class="content-area">
                            <h4 class="title">@lang(@$item['title'])</h4>
                            <p>@lang(@$item['short_description'])
                            </p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- Feature section end -->
