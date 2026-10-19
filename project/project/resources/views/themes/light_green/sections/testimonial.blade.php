<!-- Testimonial section start: real, approved customer reviews only (Admin → Growth → Reviews) -->
@php
    $zkReviews = \App\Models\Review::where('status', \App\Models\Review::APPROVED)->latest()->take(12)->get();
@endphp
@if($zkReviews->isNotEmpty())
<section class="zk-section">
    <div class="container">
        <div class="zk-head zk-reveal">
            <span class="zk-eyebrow">@lang(@$testimonial['single']['title'] ?: 'Reviews')</span>
            <h2 class="zk-title">{!! zkHighlight(__(@$testimonial['single']['heading'] ?: 'What our [customers] say')) !!}</h2>
            <p class="zk-lead">
                ★ {{ number_format($zkReviews->avg('rating'), 1) }} / 5 · {{ \App\Models\Review::where('status', 1)->count() }} @lang('verified reviews from customers who completed an order')
            </p>
        </div>
        <div class="owl-carousel owl-theme testimonial-carousel zk-reveal">
            @foreach($zkReviews as $r)
                <div class="item">
                    <figure class="zk-card zk-quote">
                        <div class="zk-quote__stars" aria-label="{{ $r->rating }} / 5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star @if($i <= $r->rating) active @endif"></i>
                            @endfor
                        </div>
                        <blockquote class="m-0"><p>“{{ $r->comment }}”</p></blockquote>
                        <figcaption class="zk-quote__who">
                            <span class="zk-quote__avatar" aria-hidden="true">{{ strtoupper(mb_substr($r->display_name, 0, 1)) }}</span>
                            <div>
                                <h5>{{ $r->display_name }} <i class="fa-solid fa-circle-check text-success" title="@lang('Verified order')"></i></h5>
                                <span>{{ $r->country ?: __('Verified customer') }} · {{ $r->created_at->format('M Y') }}</span>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<!-- Testimonial section end -->
