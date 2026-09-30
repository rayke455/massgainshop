@extends(template(). 'layouts.app')
@section('title',trans('Blog Details'))
@section('content')
    <!-- Blog details section start -->
    <section class="blog-details-section">
        <div class="container">
            <div class="row gy-5 g-sm-g">
                <div class="col-lg-7 order-2 order-lg-1">
                    <div class="blog-box-large">
                        <div class="thumbs-area">
                            <img
                                src="{{ getFile(optional($blogDetails->blog)->description_image_driver, optional($blogDetails->blog)->description_image) }}"
                                alt="Blog Description Image">
                        </div>
                        <div class="content-area mt-20">
                            <ul class="meta">
                                <li class="item">
                                    <a href="{{ route('blog.author', optional(optional(optional($blogDetails->blog)->author)->details)->slug) }}"><span
                                            class="icon"><i class="fa-regular fa-user"></i></span>
                                        <span>by {{ optional(optional(optional($blogDetails->blog)->author)->details)->name }}</span></a>
                                </li>
                                <li class="item">
                                    <span class="icon"><i class="fa-regular fa-eye"></i></span>
                                    <span>{{ optional($blogDetails->blog)->views ?? 0 }} @lang("Views")</span>
                                </li>
                                <li class="item">
                                    <span class="icon"><i class="fa-regular fa-calendar-days"></i></span>
                                    <span>{{ dateTime(optional($blogDetails->blog)->created_at) }}</span>
                                </li>
                            </ul>
                            <h4 class="blog-title">@lang($blogDetails->title)
                            </h4>

                            <div class="para-text">
                                <p>
                                    {!! $blogDetails->description !!}
                                </p>
                            </div>
                            <div class="blog-quote-box">
                                <div class="icon-area">
                                    <i class="fa-solid fa-quote-right"></i>
                                </div>
                                <div class="content">
                                    <h6>@lang($blogDetails->quote)</h6>
                                    <h5 class="title">{{ $blogDetails->quote_author }}</h5>
                                </div>
                            </div>

                            <div class="share">
                                <h6>@lang("Share:")</h6>
                                <div class="share-media">
                                    <div id="shareBlock">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-1 order-lg-2">
                    <div class="blog-sidebar">
                        <div class="sidebar-widget-area">
                            <form action="{{ route("category.wise.blog") }}" method="GET">
                                <div class="search-box">
                                    <input type="text" class="form-control" name="search" placeholder="Search here..."
                                           autocomplete="off">
                                    <button type="submit" class="search-btn"><i class="far fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="sidebar-widget-area">
                            <div class="widget-title">
                                <h4>@lang("Recent Post")</h4>
                            </div>
                            @forelse($recentPosts as $item)
                                <a href="{{ route('blog.details', optional($item->details)->slug) }}"
                                   class="sidebar-widget-item">
                                    <div class="image-area">
                                        <img
                                            src="{{ getFile($item->thumbnail_image_two_driver, $item->thumbnail_image_two) }}"
                                            alt="">
                                    </div>
                                    <div class="content-area">
                                        <div class="title">@lang(optional($item->details)->title)</div>
                                        <div class="widget-date">
                                            <i class="fa-regular fa-calendar-days"></i> {{ dateTime($item->created_at) }}
                                        </div>
                                    </div>
                                </a>
                            @empty
                            @endforelse
                        </div>

                        <div class="sidebar-widget-area">
                            <div class="sidebar-categories-area">
                                <div class="categories-header">
                                    <div class="widget-title">
                                        <h4>@lang("Categories")</h4>
                                    </div>
                                </div>
                                <ul class="categories-list">
                                    @forelse($blogCategory as $category)
                                        <li>
                                            <a href="{{ route('category.wise.blog', 'category=' . optional($category->details)->slug) }}"
                                               class="{{ optional($blogDetails->blog)->category_id == $category->id ? 'active' : '' }}"><span>@lang(optional($category->details)->name)</span>
                                                <span class="highlight">({{ $category->blog->count() }})</span></a>
                                        </li>
                                    @empty
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-widget-area">
                            <div class="widget-title">
                                <h4>@lang("Tags")</h4>
                            </div>
                            <div class="tag-list">
                                @forelse($blogDetails->tags as $tag)
                                    <a href="javascript:void(0)" class="item">@lang($tag)</a>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog details Section End -->
    @include(template(). 'sections.footer')
@endsection

@push('js-lib')
    <script src="{{ asset('assets/global/js/socialSharing.js') }}"></script>
@endpush

@push('script')
    <script>
        $("#shareBlock").socialSharingPlugin({
            urlShare: window.location.href,
            description: $("meta[name=description]").attr("content"),
            title: $("title").text(),
        });
    </script>
@endpush

