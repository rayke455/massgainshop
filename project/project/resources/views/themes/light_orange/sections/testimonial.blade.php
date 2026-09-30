<div class="shape3"><img src="{{ asset(template(true).'img/shape3.png') }}"></div>
<!-- testimonial_area_start -->
<section class="testimonial_area">
    <div class="container">
        <div class="row">
            <div class="section_header mb-50 text-center">
                <div class="section_subtitle">@lang(@$testimonial['single']['title'])</div>
                <h2 class="">@lang(@$testimonial['single']['short_title'])</h2>
                <p class="para_text m-auto">@lang(@$testimonial['single']['short_description'])</p>
            </div>
        </div>

        <div class="row">
            <div class="owl-carousel owl-theme testimonial_carousel">
                @forelse($testimonial['multiple'] as $key => $item)
                    <div class="item">
                        <div class="cmn_box box1 custom_zindex shadow2">
                            <div class="cmn_icon icon1">
                                <img
                                    src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                    alt="@lang('client image')" class="img-fluid">
                            </div>
                            <div class="text_area text-center ">
                                <h4 class="mt-20">@lang(@$item['name'])</h4>
                                <h6>@lang(@$item['designation'])</h6>
                                <div class="quote_area"><img src="{{ asset(template(true).'img/quote.png') }}"
                                                             alt="@lang("Quote")"></div>
                                <p>@lang(strip_tags(@$item['description']))</p>
                                <div class="quote_area ms-auto"><img src="{{ asset(template(true).'img/quote2.png') }}"
                                                                     alt="@lang("Quote")">
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- testimonial_area_end -->

