<!-- Feature_area_start -->
<section class="feature_area mt-5 mt-md-0 ">
    <div class="container">
        <div class="row g-lg-5 justify-content-center position-relative">
            <img class="shape1" src="{{ asset(template(true).'img/shape1.png') }}" alt="">
            @forelse($feature['multiple'] as $item)
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="cmn_box box1 text-center shadow3">
                        <div class="cmn_icon icon1">
                            <img
                                    src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                    alt="">
                        </div>
                        <h5 class="pt-30 mb-20">@lang(@$item['title'])</h5>
                        <p>@lang(@$item['short_description'])</p>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- Feature_area_end -->
