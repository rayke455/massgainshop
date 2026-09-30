<!-- TESTIMONIAL -->
<section class="testimonial-section">
    <div class="container">
        <div class="row">

            <div class="col">
                <div class="header-text mb-5 text-center">
                    <h5>@lang(@$testimonial['single']['title'])</h5>
                    <h3>@lang(@$testimonial['single']['short_title'])</h3>
                    <p>@lang(@$testimonial['single']['short_description'])</p>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel testimonials">
                    @forelse($testimonial['multiple'] as $key => $item)
                        <div class="review-box">
                            <p>@lang(strip_tags(@$item['description']))</p>
                            <div
                                class="d-flex align-items-end justify-content-between">
                                <div>
                                    <h5>@lang(@$item['name'])</h5>
                                    <span class="title">@lang(@$item['designation'])</span>
                                </div>
                                <div>
                                    <img
                                        class="img-fluid"
                                        src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                        alt="@lang('client image')"
                                    />
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /TESTIMONIAL -->
