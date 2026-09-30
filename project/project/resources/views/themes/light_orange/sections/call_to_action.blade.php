<!-- learn_more_area_start -->
<section class="learn_more_area">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="image_area d-flex justify-content-center ">
                    <img class="img-fluid"
                         src="{{ getFile(@$call_to_action['single']['media']->image->driver, @$call_to_action['single']['media']->image->path) }}"
                         alt="@lang('call-to-action image')">
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-start">
                <div class="section_header">
                    <h3>@lang(@$call_to_action['single']['title'])</h3>
                    <h6>@lang(@$call_to_action['single']['sub_title'])</h6>
                </div>
                <div class="btn_area">
                    <a href="{{ @$call_to_action['single']['media']->button_link }}"
                       class="custom_btn top-right-radius-0">@lang(@$call_to_action['single']['button_name'])</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- learn_more_area_end -->
