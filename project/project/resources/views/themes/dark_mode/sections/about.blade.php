<!-- ABOUT-US -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center gy-5 g-lg-4">
            <div class="col-lg-6">
                <div
                    class="img-box"
                    data-aos="fade-right"
                    data-aos-duration="800"
                    data-aos-anchor-placement="center-bottom">
                    <img class="img-fluid"
                         src="{{ getFile(@$about['single']['media']->image->driver, @$about['single']['media']->image->path) }}"
                         alt="@lang('about image')"/>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-box">
                    <div class="header-text">
                        <h5 class="title">@lang(@$about['single']['title'])</h5>
                        <h3>@lang(@$about['single']['sub_title'])</h3>
                    </div>
                    @forelse(@$about['multiple'] as $item)
                        <div
                                class="info-box"
                                data-aos="fade-left"
                                data-aos-duration="800"
                                data-aos-anchor-placement="center-bottom">
                            <div class="icon-box">
                                <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                     alt="@lang('about image')"/>
                            </div>
                            <div class="text">
                                <h5>@lang(@$item['title'])</h5>
                                <p>@lang(@$item['short_description'])</p>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /ABOUT-US -->
