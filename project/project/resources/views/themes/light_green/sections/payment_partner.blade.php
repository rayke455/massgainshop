<!-- Payment section start -->
<section class="zk-section zk-section--tight zk-section--soft">
    <div class="container">
        <div class="zk-head zk-reveal" style="margin-bottom: 36px">
            <span class="zk-eyebrow">@lang(@$payment_partner['single']['title'])</span>
            <h2 class="zk-title">{!! zkHighlight(__(@$payment_partner['single']['heading'])) !!}</h2>
            <p class="zk-lead">@lang(strip_tags(@$payment_partner['single']['description']))</p>
        </div>
        @php
            $zkGateways = \App\Models\Gateway::where('status', 1)->orderBy('sort_by')->get(['id', 'name', 'image', 'driver']);
        @endphp
        @if($zkGateways->isNotEmpty())
            <div class="zk-paylist">
                @foreach($zkGateways as $gw)
                    <div class="zk-paylist__item">
                        <img src="{{ getFile($gw->driver, $gw->image) }}" alt="" loading="lazy">
                        <span>{{ trim($gw->name) }}</span>
                    </div>
                @endforeach
            </div>
            <p class="zk-paylist__note"><i class="fa-regular fa-shield-check"></i> @lang('Funds reach your balance automatically once the payment is confirmed.')</p>
        @else
        <div class="owl-carousel owl-theme payment-slider zk-logos">
            @forelse($payment_partner['multiple'] as $item)
                <div class="item">
                    <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                         alt="@lang('Payment method')" loading="lazy">
                </div>
            @empty
            @endforelse
        </div>
        @endif
        </div>
    </div>
</section>
<!-- Payment section end -->
