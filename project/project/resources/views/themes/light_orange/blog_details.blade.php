@extends(template(). 'layouts.app')
@section('title',trans('Blog Details'))

@section('content')

    <!-- blog_details_area_start -->
    <section class="blog_details_area">
        <div class="container">
            <div class="row g-5">
                <div class="col-md-8 order-2 order-md-1">
                    <div class="blog_details">
                        <div class="blog_image">
                            <img
                                src="{{ getFile(optional($blogDetails->blog)->description_image_driver, optional($blogDetails->blog)->description_image) }}"
                                alt="" class="img-fluid">
                        </div>
                        <div class="blog_header py-3">
                            <div class="date_author d-flex">
                                <span><a href=""><i class="far fa-user"></i>@lang('Admin')</a></span>
                                <span><i class="far fa-calendar-alt"></i>{{ dateTime(optional($blogDetails->blog)->created_at) }}</span>
                            </div>
                            <h3 class="mt-30">@lang($blogDetails->title)</h3>
                        </div>
                        <div class="blog_para">
                            <p>{!! __($blogDetails->description) !!}</p>
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

                <div class="col-md-4 order-1 order-md-2">
                    <div class="blog_sidebar">
                        <div class="section_header">
                            <h4>@lang('Recent Post')</h4>
                        </div>
                        @forelse($recentPosts as $item)
                            <div class="blog_widget_area">
                                <ul>
                                    <li>
                                        <a href="{{ route('blog.details', optional($item->details)->slug) }}"
                                           class="d-flex">
                                            <div class="blog_widget_image">
                                                <img
                                                    src="{{ getFile($item->thumbnail_image_two_driver, $item->thumbnail_image_two) }}"
                                                    alt="@lang(@$data->description->title)" class="img-fluid">
                                            </div>
                                            <div class="blog_widget_content">
                                                <h6 class="blog_title">
                                                    @lang(optional($item->details)->title)
                                                </h6>
                                                <div class="blog_date">
                                                    <div
                                                        class="blog_item1">{{ dateTime($item->created_at) }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- blog_details_area_start -->
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
