@extends(template(). 'layouts.app')
@section('title',trans('Blog Details'))

@section('content')
    <!-- BLOG Details-->
    <section class="blog-section blog-details">
        <div class="container">
            <div class="row gy-5 g-lg-5">
                <div class="col-lg-8">
                    <div class="blog-box details">
                        <div class="img-box">
                            <img
                                src="{{ getFile(optional($blogDetails->blog)->description_image_driver, optional($blogDetails->blog)->description_image) }}"
                                class="img-fluid" alt="@lang("Description Image")"/>
                        </div>
                        <div class="text-box">
                            <div class="date-author">
                                <span class="date">{{ dateTime(optional($blogDetails->blog)->created_at) }}</span>
                                <span class="author">@lang('Admin')</span>
                            </div>
                            <h3>@lang($blogDetails->title)</h3>
                        </div>
                        <div class="description">
                            <p>
                                {!! $blogDetails->description !!}
                            </p>
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

                <div class="col-lg-4">
                    <h4 class="mb-3">@lang('Recent Posts')</h4>
                    @forelse($recentPosts as $item)
                        <a href="{{ route('blog.details', optional($item->details)->slug) }}">
                            <div class="recent-post blog-box">
                                <div class="img-box">
                                    <img
                                        src="{{ getFile($item->thumbnail_image_two_driver, $item->thumbnail_image_two) }}"
                                        alt="" class="img-fluid"/>
                                </div>
                                <div class="text-box">
                                    <h4>@lang(optional($item->details)->title)</h4>
                                    <div class="date-author">
                                        <span class="date">{{dateTime($item->created_at)}}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </section>
    <!-- /BLOG Details -->

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
