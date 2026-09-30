<!-- SERVICES -->
<section id="services">
    <div class="container">
        <div class="heading-container">
            <h1 class="heading">@lang(@$service['single']['title']) <span
                    class="sub-heading">@lang(@$service['single']['sub_title'])</span></h1>
            <h3 class="slogan">@lang(@$service['single']['short_title'])</h3>
        </div>

        <div class="row">
            @forelse($service['multiple'] as $item)
                <div class="col-lg-4">
                    <div class="card-type-1 card wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
                        <div class="card-icon">
                            <img class="card-img-top"
                                 src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                 alt="Icon Missing">
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">@lang(@$item['title']) </h4>
                            <p class="card-text">@lang(@$item['short_description'])</p>
                            <a class="btn-readmore"
                               href="{{@$item['media']->button_link}}"><i
                                    class="icofont-long-arrow-right"></i>@lang(@$item['button_name'])</a>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- /SERVICES -->
