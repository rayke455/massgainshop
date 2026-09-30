<div class="shape2"><img src="{{ asset(template(true).'img/shape2.png') }}" alt=""></div>

<!-- blog_area_start -->
<section class="blog_area">
    <div class="container">
        <div class="row">
            <div class="section_header mb-50 text-center">
                <div class="section_subtitle">@lang(@$blog['single']['title'])</div>
                <h2>@lang(@$blog['single']['short_title'])</h2>
            </div>
        </div>
        <div class="row justify-content-center g-lg-4 gy-5">
            @forelse($blog['multiple'] as $key => $item)
                <div class="col-lg-4 col-sm-6">
                    <div class="blog_box box1">
                        <div class="image_area">
                            <img
                                src="{{ getFile($item->thumbnail_image_driver, $item->thumbnail_image) }}"
                                alt="@lang("Thumbnail Image")"
                                class="img-fluid">
                        </div>

                        <div class="text_area">
                            <div class="date_author d-flex justify-content-between">
                                <span><a href=""><i class="far fa-user"></i>@lang('Admin')</a></span>
                                <span><i class="far fa-calendar-alt"></i>{{dateTime($item->created_at)}}</span>
                            </div>
                            <h5 class="pt-3"><a
                                    href="{{ route('blog.details', optional($item->details)->slug) }}">@lang(Str::limit(optional($item->details)->title, 40))</a>
                            </h5>
                            <p class=" pb-20"> @lang(strip_tags(Str::limit(optional($item->details)->description), 40))</p>
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
            <div class="pagination-section mt-3">
                <nav>
                    <ul class="pagination">
                        {{ $blog['multiple']->appends($_GET)->links(template().'partials.pagination') }}
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</section>
<!-- blog_area_end -->
