@if(isset($pageSeo['breadcrumb_image']))
    <!-- Banner section start -->
    <div class="banner-section">
        <div class="bg-img-overlay" style="background-image: url({{ $pageSeo['breadcrumb_image'] }}); background-position: center right; background-repeat: no-repeat"></div>
        <div class="banner-section-inner">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="breadcrumb-area">
                            <h3>@lang($pageSeo['page_title'] ?? null)</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i> @lang("Home")</a></li>
                                <li class="breadcrumb-item active" aria-current="page">@lang($pageSeo['page_title'] ?? null)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner section end -->
@endif
