<!-- FEATURE -->
<section id="feature">
    <div class="container">
        <div class="row">
            @forelse($feature['multiple'] as $item)
                <div class="col-lg-4">
                    <div class="card-type-1 card wow fadeInUp" data-wow-duration="1s" data-wow-dealy="0.1s">
                        <div class="card-icon">
                            <img class="card-img-top"
                                 src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                 alt="Feature Image">
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">@lang(@$item['title'])</h4>
                            <p class="card-text">
                                @lang(@$item['short_description'])
                            </p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>

