<!-- How it works section start -->
<section class="how-it-works-section">
    <div class="container">
        <div class="row">
            <div class="section-header text-center">
                <span class="section-subtitle">@lang($how_it_works['single']['title'])</span>
                <h2 class="section-title mx-auto">@lang($how_it_works['single']['heading'])</h2>
                <p class="cmn-para-text mx-auto">@lang(strip_tags($how_it_works['single']['description']))</p>
            </div>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-lg-4 col-md-6">
                <div class="row g-4">
                    @forelse($how_it_works['multiple'] as $key => $item)
                        <div class="col-12">
                            <div class="cmn-box2">
                                <div class="icon-box">
                                    <i class="{{ $item['media']->icon }}"></i>
                                </div>
                                <div class="content-box">
                                    <h5 class="title">@lang($item['title'])</h5>
                                    <span>@lang($item['short_description'])</span>
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
            <div class="col-lg-4 col-md-6 justify-content-center d-flex d-none d-lg-block">
                <img
                    src="{{ getFile(@$how_it_works['single']['media']->image->driver, @$how_it_works['single']['media']->image->path) }}"
                    alt="How it works image">
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="row g-4">
                    @forelse($how_it_works['multiple'] as $key => $item)
                        @if($key <= 1)
                            @continue
                        @endif
                        <div class="col-12">
                            <div class="cmn-box2">
                                <div class="icon-box">
                                    <i class="{{ $item['media']->icon }}"></i>
                                </div>
                                <div class="content-box">
                                    <h5 class="title">@lang($item['title'])</h5>
                                    <span>@lang($item['short_description'])</span>
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
<!-- How it works section end -->
