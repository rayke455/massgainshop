<!-- SERVICES -->
<section class="service-section">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="header-text mb-5 text-center">
                    <h5>@lang(@$service['single']['title'])</h5>
                    <h3>@lang(@$service['single']['sub_title'])</h3>
                    <p>@lang(@$service['single']['short_title'])</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse($service['multiple'] as $item)
                <div class="col-lg-4">
                    <div
                        class="service-box"
                        data-aos="fade-up"
                        data-aos-duration="1200"
                        data-aos-anchor-placement="center-bottom"
                    >
                        <div class="icon-box">
                            <img
                                src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                alt="@lang('service image')"/>
                        </div>
                        <h4>@lang(@$item['title'])</h4>
                        <p>@lang(@$item['short_description'])</p>
                        <a href="{{@$item['media']->button_link}}"
                           class="read-more">@lang(@$item['button_name'])</a>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- /SERVICES -->
