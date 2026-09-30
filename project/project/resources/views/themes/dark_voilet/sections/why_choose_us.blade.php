<!-- Why Choose us section start -->
<section class="why-choose-us-section">
    <div class="container">
        <div class="row">

            <div class="section-header text-center">
                <span
                    class="section-subtitle">@lang($why_choose_us['single']['title'])</span>
                <h2 class="section-title mx-auto"> @lang($why_choose_us['single']['heading']) </h2>
                <p class="cmn-para-text mx-auto">@lang(strip_tags($why_choose_us['single']['short_description']))</p>
            </div>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="row g-4">
                    @forelse($why_choose_us['multiple'] as $key => $item)
                        <div class="col-12">
                            <div class="cmn-box2">
                                <div class="icon-box">
                                    <i class="{{ $item['media']->icon }}"></i>
                                </div>
                                <div class="content-box">
                                    <h5 class="title">@lang($item['title'])</h5>
                                    <span>@lang(strip_tags($item['description']))</span>
                                </div>
                            </div>
                        </div>
                        @if($key == 1)
                            @break;
                        @endif
                    @empty
                    @endforelse
                </div>
            </div>
            @if($why_choose_us['multiple'])
                <div class="col-lg-4 col-md-6 justify-content-center d-flex d-none d-lg-block">
                    <img
                        src="{{ getFile(@$why_choose_us['single']['media']->image->driver, @$why_choose_us['single']['media']->image->path) }}"
                        alt="">
                </div>
            @endif
            <div class="col-lg-4 col-md-6">
                <div class="row g-4">
                    @forelse($why_choose_us['multiple'] as $key => $item)
                        @if($key <= 1)
                            @continue
                        @endif
                        <div class="col-12">
                            <div class="cmn-box2">
                                <div class="icon-box">
                                    <i class="{{ @$item['media']->icon }}"></i>
                                </div>
                                <div class="content-box">
                                    <h5 class="title">@lang($item['title'])</h5>
                                    <span>@lang(strip_tags($item['description']))</span>
                                </div>
                            </div>
                        </div>
                        @if($key == 3)
                            @break;
                        @endif
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Why Choose us section end -->
