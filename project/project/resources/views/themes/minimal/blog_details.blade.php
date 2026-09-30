@extends(template(). 'layouts.app')
@section('title',trans('Blog Details'))
@section('content')
    <!-- BLOG -->
    <section id="blog">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card-blog card mb-30 shadow-none wow fadeInUp" data-wow-duration="1s"
                         data-wow-delay="0.35s">
                        <div class="fig-container">
                            <img
                                src="{{ getFile(optional($blogDetails->blog)->description_image_driver, optional($blogDetails->blog)->description_image) }}"
                                alt="">
                            <div class="published-date">
                                <span>{{ optional($blogDetails->blog)->created_at->format('d') }}</span>
                                <span>{{ optional($blogDetails->blog)->created_at->format('M') }}</span>
                            </div>
                        </div>
                        <div class="card-body mt-30 p-0">
                            <h5 class="card-title">
                                @lang($blogDetails->title)
                            </h5>

                            <p class="p mb-0 lineheight-30 text-justify">
                                {!! $blogDetails->description !!}
                            </p>

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


                <div class="col-lg-4">
                    <div class="blog-sidebar wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">

                        <h5 class="h5 mt-40">{{trans('Popular Post')}}</h5>

                        <hr class="mt-20 mb-20 border">

                        <div class="popular-post">
                            @forelse($recentPosts as $item)
                                <div class="media align-items-center">
                                    <div class="media-img">
                                        <img class="br-4 popular-post-img"
                                             src="{{ getFile($item->thumbnail_image_two_driver, $item->thumbnail_image_two) }}"
                                             alt="">
                                    </div>
                                    <div class="media-body ml-20">
                                        <p class="post-date mb-5">{{dateTime($item->created_at)}}</p>
                                        <h6 class="h6">
                                            <a href="{{ route('blog.details', optional($item->details)->slug) }}">
                                                @lang(optional($item->details)->title)
                                            </a>
                                        </h6>

                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /BLOG -->

    @include(template(). 'sections.footer')
@endsection

@push('extra-js')
    <script src="{{ asset(template(true) . 'js/socialSharing.js') }}"></script>
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
