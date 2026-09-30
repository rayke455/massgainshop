<!-- Testimonial section start -->
<section class="zk-section">
    <div class="container">
        <div class="zk-head zk-reveal">
            <span class="zk-eyebrow">@lang(@$testimonial['single']['title'])</span>
            <h2 class="zk-title">{!! zkHighlight(__(@$testimonial['single']['heading'])) !!}</h2>
            <p class="zk-lead">@lang(strip_tags(@$testimonial['single']['description']))</p>
        </div>
        <div class="owl-carousel owl-theme testimonial-carousel zk-reveal">
            @forelse($testimonial['multiple'] as $item)
                <div class="item">
                    <figure class="zk-card zk-quote">
                        <div class="zk-quote__stars" aria-label="{{ (int) @$item['rating'] }} / 5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star @if($i <= (int) @$item['rating']) active @endif"></i>
                            @endfor
                        </div>
                        <blockquote class="m-0"><p>“@lang(@$item['review'])”</p></blockquote>
                        <figcaption class="zk-quote__who">
                            <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                 alt="{{ @$item['name'] }}" loading="lazy">
                            <div>
                                <h5>{{ @$item['name'] }}</h5>
                                <span>{{ @$item['address'] }}</span>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- Testimonial section end -->
