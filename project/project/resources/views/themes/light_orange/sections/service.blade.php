<div class="shape2"><img src="{{ asset(template(true).'img/shape2.png') }}" alt="@lang("Shape")"></div>

<!-- service_area_start -->
<section class="service_area">
    <div class="container">
        <div class="row">
            <div class="section_header text-center">
                <div class="section_subtitle">@lang(@$service['single']['title'])</div>
                <h2>@lang(@$service['single']['sub_title'])</h2>
                <p class="para_text m-auto">@lang(@$service['single']['short_title'])</p>
            </div>
        </div>
        <div class="row g-5 justify-content-center position-relative">
            <img class="shape1" src="{{ asset(template(true).'img/shape1.png') }}" alt="">
            @forelse($service['multiple'] as $item)
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="cmn_box box1 text-center shadow3">
                        <div class="cmn_icon icon1">
                            <img
                                src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                alt="@lang('service image')">
                        </div>
                        <h5 class="pt-30 mb-20">@lang(@$item['title'])</h5>
                        <p>@lang(@$item['short_description'])</p>
                        <a href="{{@$item['media']->button_link}}"
                           class="custom_btn mt-30 top-right-radius-0">@lang(@$item['button_name'])</a>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- service_area_end -->
