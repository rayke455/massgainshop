<!-- BLOG -->
<section class="blog-section">
    <div class="container">
        <div class="row g-4">
            <div class="col">
                <div class="header-text text-center">
                    <h5>@lang(@$blog['single']['title'])</h5>
                    <h3>@lang(@$blog['single']['short_title'])</h3>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @forelse(@$blog['multiple'] as $key => $item)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-box">
                        <div class="img-box">
                            <img
                                    src="{{ getFile($item->thumbnail_image_driver, $item->thumbnail_image) }}"
                                    alt="@lang("thumbnail image")"
                                    class="img-fluid"
                            />
                        </div>
                        <div class="text-box">
                            <h4>{{Str::limit(strip_tags(optional($item->details)->description),40)}}</h4>
                            <div class="date-author">
                                <span class="date">{{dateTime($item->created_at,'d M Y')}}</span>
                                <span class="author">@lang('Admin')</span>
                            </div>
                            <p>{{Str::limit(strip_tags(optional($item->details)->description),40)}}</p>
                            <a href="{{ route('blog.details', optional($item->details)->slug) }}"
                               class="read-more">
                                @lang('Read More')...
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-4">
                    <img class="error-image mb-3"
                         src="{{ asset('assets/global/img/oc-error.svg') }}"
                         alt="@lang("Image Description")" data-hs-theme-appearance="default">
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
