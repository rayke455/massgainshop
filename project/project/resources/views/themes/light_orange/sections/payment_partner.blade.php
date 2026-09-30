<!-- payment_area_start -->
<section class="payment_area">
    <div class="container">
        <div class="row">
            <div class="section_header text-center mb-50">
                <div class="section_subtitle mx-auto">@lang(@$payment_partner['single']['title'])</div>
                <h2>@lang(@$payment_partner['single']['sub_title'])</h2>
            </div>
            <div class="owl-carousel owl-theme payment_slider text-center">
                @forelse($payment_partner['multiple'] as $item)
                    <div class="item">
                        <div class="image_area">
                            <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                 alt="@lang("Gateway Image")">
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- payment_area_end -->

