<!-- Payment section start -->
<section class="payment-section">
    <div class="container">
        <div class="row">
            <div class="section-header text-center">
                <span
                    class="section-subtitle">@lang($payment_partner['single']['title'])</span>
                <h2 class="section-title mx-auto">@lang($payment_partner['single']['heading'])</h2>
                <p class="cmn-para-text mx-auto">@lang(strip_tags($payment_partner['single']['description']))</p>
            </div>
            <div class="owl-carousel owl-theme payment-slider text-center">
                @forelse($payment_partner['multiple'] as $item)
                    <div class="item">
                        <div class="image-area">
                            <img
                                src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                alt="Payment Image"/>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- Payment section end -->
