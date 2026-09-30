<section class="achivement-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 order-2 col-lg-1 achivement-counter-area">
                <div class="row g-4">
                    @forelse($achievement['multiple'] as $item)
                        <div class="col-md-6 cmn-box3-item">
                            <div class="cmn-box3">
                                <div class="icon-area">
                                    <i class="{{ $item['media']->icon }}"></i>
                                </div>
                                <div class="content-area">
                                    <h3><span class="achivement-counter">{{ $item['media']->count }}</span>+</h3>
                                    <h5>@lang($item['title'])</h5>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="col-lg-6 order-1 col-lg-2">
                <div class="content-area">
                    <span
                        class="section-subtitle">@lang(@$achievement['single']['title'])</span>
                    <h3 class="section-title">@lang(@$achievement['single']['heading'])</h3>
                    <p class="cmn-para-text">@lang(strip_tags(@$achievement['single']['description']))</p>
                </div>
            </div>
        </div>
    </div>
</section>
