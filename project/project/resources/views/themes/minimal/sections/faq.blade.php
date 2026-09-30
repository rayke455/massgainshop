<!-- FAQ -->
<section id="faq">
    <div class="container">
        <h4 class="h4 wow fadeInUp" data-wow-duration="1s"
            data-wow-delay="0.1s">@lang(@$faq['single']['title'])</h4>
        <p class="p mt-30 mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
            {{ strip_tags(@$faq['single']['short_details']) }}
        </p>
        <div id="faq-wrapper mt-3">
            @forelse($faq['multiple'] as $key => $item)
                <div class="faq-card card mb-30 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
                    <div class="card-header" id="heading{{$key}}">
                        <h5 class="mb-0">
                            <button class="btn-faq" data-toggle="collapse" data-target="#collapse{{$key}}"
                                    aria-expanded="true" aria-controls="collapse{{$key}}">
                                @lang(@$item['title'])
                            </button>
                        </h5>
                    </div>
                    <div id="collapse{{$key}}" class="collapse" aria-labelledby="heading{{$key}}"
                         data-parent="#faq-wrapper">
                        <div class="card-body">
                            @lang(strip_tags(@$item['description']))
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- /FAQ -->
