<!-- Service section start -->
<section class="service-section">
    <div class="bg-img-overlay"
         style="background-image: url({{ getFile(@$service['single']['media']->background_image->driver, @$service['single']['media']->background_image->path) }}); background-position:center right; background-repeat:no-repeat">
    </div>
    <div class="service-section-inner">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <span class="section-subtitle">@lang(@$service['single']['title'])</span>
                    <h2 class="section-title mx-auto">@lang(@$service['single']['heading'])</h2>
                    <p class="cmn-para-text mx-auto">@lang(strip_tags(@$service['single']['description']))</p>
                </div>
            </div>
            <div class="row g-4">
                @forelse($service['multiple'] as $item)
                    <div class="col-lg-4 col-md-6 cmn-box-item">
                        <div class="cmn-box">
                            <div class="icon-area">
                                <i class="{{ @$item['media']->icon }}"></i>
                            </div>
                            <div class="content-area">
                                <h4 class="title">@lang(@$item['service_name'])</h4>
                                <p>
                                    @lang(@$item['description'])
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- Service section end -->
