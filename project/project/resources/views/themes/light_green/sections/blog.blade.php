<!-- Blog Section start -->
<section class="zk-section">
    <div class="container">
        <div class="zk-head zk-reveal">
            <span class="zk-eyebrow">@lang(@$blog['single']['title'])</span>
            <h2 class="zk-title">{!! zkHighlight(__(@$blog['single']['sub_title'])) !!}</h2>
            <p class="zk-lead">@lang(strip_tags(@$blog['single']['short_description']))</p>
        </div>
        <div class="row g-4">
            @forelse($blog['multiple'] as $key => $item)
                @php
                    $blogUrl = route('blog.details', optional($item->details)->slug);
                    $authorDetails = optional(optional($item->author)->details);
                @endphp
                <div class="col-lg-4 col-md-6 zk-reveal">
                    <article class="zk-card zk-post">
                        <a href="{{ $blogUrl }}" class="zk-post__img" tabindex="-1" aria-hidden="true">
                            <img src="{{ getFile($item->thumbnail_image_driver, $item->thumbnail_image) }}" alt=""
                                 loading="lazy">
                        </a>
                        <div class="zk-post__body">
                            <div class="zk-post__meta">
                                @if($authorDetails->slug)
                                    <a href="{{ route('blog.author', $authorDetails->slug) }}"><i
                                            class="fa-regular fa-user"></i>{{ $authorDetails->name }}</a>
                                @endif
                                <span><i class="fa-regular fa-calendar"></i>{{ dateTime($item->created_at) }}</span>
                                <span><i class="fa-regular fa-eye"></i>{{ $item->views ?? 0 }}</span>
                            </div>
                            <h4><a href="{{ $blogUrl }}">@lang(optional($item->details)->title)</a></h4>
                            <p>@lang(strip_tags(optional($item->details)->description))</p>
                            <a href="{{ $blogUrl }}" class="zk-post__more">@lang("Read more") <i
                                    class="fa-regular fa-arrow-right"></i></a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="text-center p-4">
                    <img class="error-image mb-3" src="{{ asset('assets/global/img/oc-error.svg') }}" alt="">
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
