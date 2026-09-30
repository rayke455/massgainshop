<section id="banner-wrap" style="background-image: linear-gradient(109deg, var(--bggrdleft3) 0%, var(--bggrdright3) 100%), url({{ asset(template(true).'/images/customer_banner.jpg') }});
background-size: cover; background-position: center center; background-repeat: no-repeat">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="content wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.35s">
                    <h3 class="h3">@lang(@$call_to_action['single']['title'])</h3>
                    <p>@lang(@$call_to_action['single']['sub_title'])</p>
                    <a class="btn"
                       href="{{ @$call_to_action['single']['media']->button_link }}">@lang(@$call_to_action['single']['button_name'])</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img-container wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.35s">
                    <img src="{{ getFile(@$call_to_action['single']['media']->image->driver, @$call_to_action['single']['media']->image->path) }}"
                         alt="Image Missing">
                </div>
            </div>
        </div>
    </div>
</section>





