<!-- Feature section start -->
<section class="zk-section zk-section--tight zk-features">
    <div class="container">
        <div class="row g-4">
            @forelse($feature['multiple'] as $item)
                <div class="col-lg-4 col-md-6 zk-reveal">
                    <div class="zk-card zk-feature">
                        <span class="zk-icon"><i class="{{ @$item['media']->icon }}"></i></span>
                        <div>
                            <h4>@lang(@$item['title'])</h4>
                            <p>@lang(@$item['short_description'])</p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- Feature section end -->
