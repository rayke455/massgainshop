<section class="payment-gateway">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="header-text text-center">
                    <h3>@lang(@$payment_partner['single']['title'])</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="gateways owl-carousel">
                    @forelse($payment_partner['multiple'] as $item)
                        <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}" class="m-2"
                             alt="Partner Img">
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
