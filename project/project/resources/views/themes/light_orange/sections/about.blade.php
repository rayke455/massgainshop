<div class="shape3"><img src="{{ asset(template(true).'img/shape3.png') }}" alt=""></div>
<!-- about_area_start -->
<section id="about_area" class="about_area">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 d-flex justify-content-center">
                <div class="image_area">
                    <img class="animation1"
                         src="{{ getFile(@$about['single']['media']->image->driver, @$about['single']['media']->image->path) }}">
                    <a data-fancybox data-width="1000" data-height="600"
                       href="{{@$about['single']['media']->youtube_link}}">
                        <div class="video_play_btn">
                            <i class="fas fa-play"></i>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section_content">
                    <div class="section_header">
                        <div class="section_subtitle">@lang(@$about['single']['title'])</div>
                        <h2>@lang(@$about['single']['sub_title'])</h2>
                        <p>{!! __(@$about['single']['short_description']) !!}</p>
                    </div>
                    <div class="button_area">
                        <a class="custom_btn top-right-radius-0"
                           href="{{@$about['single']['media']->button_link}}">@lang(@$about['single']['button_name'])</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- about_area_end -->
