@extends(template(). 'layouts.app')
@section('title',trans('Author'))
@section('content')
    <!-- Author section Start -->
    <section class="author-section">
        <div class="container">
            <div class="author-box">
                <div class="row">
                    <div class="author-box-top">
                        <div class="thumbs-area">
                            <figure>
                                <img src="{{ getFile($author->image_driver, $author->image) }}" alt="Author Image">
                            </figure>
                        </div>
                        <div class="content-area">
                            <h3>{{ optional($author->details)->name }}</h3>
                            <h5>{{ optional($author->details)->address }}</h5>
                            <div class="social-area mt-30">
                                <ul class="d-flex">
                                    @foreach(optional($author->details)->social_media as $item)
                                        <li><a href="{{ $item['link'] }}" target="_blank"><i
                                                    class="{{ $item['icon'] }}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row  mt-50">
                    <div class="author-details">
                        <h4>@lang("About The Author")</h4>
                        <hr class="cmn-hr2">
                        <p>
                            @lang(strip_tags(optional($author->details)->description))
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Author section end -->

    <!-- Blog Section start -->
    <section class="blog-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center mb-50">
                        <span class="section-subtitle">@lang(@$blogContent->description->title)</span>
                        <h3 class="section-title mx-auto">@lang(@$blogContent->description->sub_title)</h3>
                        <p class="cmn-para-text mx-auto mt-20">@lang(@$blogContent->description->short_description)</p>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @forelse($blogs as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-box">
                            <a href="{{ route('blog.details', optional($item->details)->slug) }}">
                                <div class="img-box">
                                    <img src="{{ getFile($item->thumbnail_image_driver, $item->thumbnail_image) }}"
                                         alt="">
                                </div>
                            </a>
                            <div class="content-box">
                                <ul class="meta">
                                    <li class="item">
                                        <a href="{{ route('blog.author', optional(optional($item->author)->details)->slug) }}"><span
                                                class="icon"><i class="fa-regular fa-user"></i></span>
                                            <span>by {{ optional(optional($item->author)->details)->name }}</span></a>
                                    </li>
                                    <li class="item">
                                        <span class="icon"><i class="fa-regular fa-eye"></i></span>
                                        <span>{{ $item->views }} @lang("Views") </span>
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
                @endforelse
            </div>
        </div>
    </section>
    <!-- Blog Section end -->

    @include(template(). 'sections.footer')
@endsection

