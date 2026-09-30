@extends(template().'layouts.user')
@section('title',trans('Notice'))
@section('content')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="breadcrumb-area">
                <h4 class="title">@lang("notice")</h4>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-light fa-house"></i>
                            @lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("notice")</li>
                </ul>
            </div>
            <div class="row">
                <div class="col-lg-8">
                    @forelse($notices as $notice)
                        <div class="notice-card">
                            <div class="cmn-box4">
                                <div class="icon-box">
                                    <i class="fa-regular fa-bullhorn"></i>
                                </div>
                                <div class="content-area">
                                    <h5 class="title">@lang(optional($notice->details)->title)</h5>
                                    <div class="date-box ">{{ dateTime($notice->created_at) }}</div>
                                </div>
                            </div>

                            <hr class="cmn-hr2">
                            <h5>@lang("notice description")</h5>
                            <p>
                                {!! trans(optional($notice->details)->description) !!}
                            </p>
                            @if($notice->image)
                                <figure class="image-area">
                                    <img src="{{ getFile($notice->image_driver, $notice->image) }}" alt="">
                                </figure>
                            @endif
                        </div>
                    @empty
                        <div class="text-center p-4 mt-5">
                            <img class="error-image mb-3"
                                 src="{{ asset('assets/global/img/oc-error.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <p class="mb-0">@lang("No notice available to display.")</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection


