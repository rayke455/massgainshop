<!-- Blog Section start -->
<section class="blog-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header text-center mb-50">
                    <span class="section-subtitle">@lang(@$blog['single']['title'])</span>
                    <h3 class="section-title mx-auto">@lang(@$blog['single']['sub_title'])</h3>
                    <p class="cmn-para-text mx-auto mt-20">@lang(@$blog['single']['short_description'])</p>
                </div>
            </div>
        </div>
        <div class="row g-4">
            @forelse($blog['multiple'] as $key => $item)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-box">
                        <a href="{{ route('blog.details', optional($item->details)->slug) }}">
                            <div class="img-box">
                                <img src="{{ getFile($item->thumbnail_image_driver, $item->thumbnail_image) }}" alt="">
                            </div>
                        </a>
                        <div class="content-box">
                            <ul class="meta">
                                <li class="cmn-box4">
                                    <a href="{{ route('blog.author', optional(optional($item->author)->details)->slug) }}"><span
                                            class="icon"><i class="fa-regular fa-user"></i></span>
                                        <span>by {{ optional(optional($item->author)->details)->name }}</span></a>
                                </li>
                                <li class="item">
                                    <span class="icon"><i class="fa-regular fa-eye"></i></span>
                                    <span>{{ $item->views ?? 0 }} @lang("Views") </span>
                                </li>
                                <li class="item">
                                    <span class="icon"><i class="fa-regular fa-calendar-days"></i></span>
                                    <span>{{ dateTime($item->created_at) }}</span>
                                </li>

                            </ul>
                            <h4 class="blog-title"><a
                                    href="{{ route('blog.details', optional($item->details)->slug) }}">@lang(optional($item->details)->title)</a>
                            </h4>
                            <div class="para-text">
                                <p>
                                    @lang(strip_tags(optional($item->details)->description))
                                </p>
                            </div>
                            <a href="{{ route('blog.details', optional($item->details)->slug) }}"
                               class="blog-btn">@lang("read more")<i
                                    class="fa-regular fa-arrow-right"></i></a>
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
<!-- Blog Section end -->
