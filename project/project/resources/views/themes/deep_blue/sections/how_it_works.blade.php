<!-- HOW-IT-WORKS -->
<section class="how-it-works">
    <div class="container">
        <div class="row align-items-center gy-5 g-lg-4">
            <div class="col-lg-6">
                <div class="text-box">
                    <div class="header-text">
                        <h5 class="title">@lang(@$how_it_works['single']['title'])</h5>
                        <h3>@lang(@$how_it_works['single']['short_title'])</h3>
                        <p>@lang(@$how_it_works['single']['short_description'])</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="work-box-wrapper">
                    <div class="shape"></div>
                    <div class="row g-4">
                        @forelse($how_it_works['multiple'] as $item)
                            <div class="col-md-6">
                                <div
                                    class="work-box"
                                    data-aos="fade-up"
                                    data-aos-duration="800"
                                    data-aos-anchor-placement="center-bottom">
                                    <div class="icon-box">
                                        <i class="{{ @$item['media']->icon }}"></i>
                                    </div>
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
    </div>
</section>
<!-- /HOW-IT-WORKS -->
