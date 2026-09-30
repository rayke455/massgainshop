<!-- Testimonial section start -->
<section class="testimonial-section">
    <div class="container">
        <div class="row">
            <div class="section-header text-center">
                <span class="section-subtitle">@lang($testimonial['single']['title'])</span>
                <h2 class="section-title mx-auto">@lang($testimonial['single']['heading'])</h2>
                <p class="cmn-para-text m-auto">@lang(strip_tags($testimonial['single']['short_description']))</p>
            </div>
        </div>
        <div class="row">
            <div class="owl-carousel owl-theme testimonial-carousel">
                @forelse($testimonial['multiple'] as $key => $item)
                    <div class="item">
                        <div class="testimonial-box">
                            <div class="testimonial-header">
                                <div class="testimonial-title-area">
                                    <div class="testimonial-thumbs">
                                        <img src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                             alt=""/>
                                    </div>
                                    <div class="testimonial-title">
                                        <h5 class="mb-0">{{ $item['name'] }}</h5>
                                        <p>{{ $item['location'] }}</p>
                                    </div>
                                </div>

                                <ul class="ratings">
                                    <li>
                                        @php
                                            $maxRating = 5;
                                        @endphp

                                        @for($i = 1; $i <= $maxRating; $i++)
                                            <i class="fa-solid fa-star @if($i <= $item['rating']) active @endif"></i>
                                        @endfor
                                    </li>
                                </ul>
                            </div>
                            <div class="quote-area">
                                <p>@lang($item['review'])</p>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- Testimonial section end -->
