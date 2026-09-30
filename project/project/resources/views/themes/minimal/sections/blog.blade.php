<!-- BLOG -->
<section id="blog">
    <div class="shape-circle wow fadeIn" data-wow-duration="1s" data-wow-delay="0..5s">
        <div class="circle"></div>
    </div>
    <div class="container">
        <div class="heading-container">
            <h1 class="heading">@lang(@$blog['single']['title']) <span
                    class="sub-heading">@lang(@$blog['single']['sub_title'])</span></h1>
            <h3 class="slogan">@lang(@$blog['single']['short_title'])</h3>
        </div>

        <div class="row">
            @forelse($blog['multiple'] as $key => $item)
                <div class="col-lg-4 col-md-6">
                    <div class="card-blog card wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.35s">
                        <div class="fig-container">
                            <img
                                src="{{ getFile($item->thumbnail_image_driver, $item->thumbnail_image) }}"
                                alt="">
                            <div class="published-date">
                                <span>{{ $item->created_at->format('d') }}</span>
                                <span>{{ $item->created_at->format('M') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{Str::limit(strip_tags(optional($item->details)->description),40)}}</h5>
                            <a class="btn-readmore"
                               href="{{ route('blog.details', optional($item->details)->slug) }}"><i
                                    class="icofont-long-arrow-right"></i> @lang('Read More')</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-4">
                    <img class="error-image mb-3"
                         src="{{ asset('assets/global/img/oc-error.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <p class="mb-0">@lang("No blogs available to display.")</p>
                </div>
            @endforelse
        </div>

        @if (request()->is('blogs'))
            <div class="pagination-section">
                <nav>
                    <ul class="pagination">
                        {{ $blog['multiple']->appends($_GET)->links(template().'partials.pagination') }}
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</section>
<!-- /BLOG -->

