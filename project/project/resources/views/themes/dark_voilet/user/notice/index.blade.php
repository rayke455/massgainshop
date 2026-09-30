@extends(template().'layouts.user')
@section('title',trans('Notice'))
@section('content')
    <main id="main" class="main bg-color2">
        <div class="pagetitle">
            <h3 class="mb-1">@lang("Notice")</h3>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">@lang("Home")</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">
                            @lang("Dashboard")</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang("Notice")</li>
                </ol>
            </nav>
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
                        <h5>@lang("notice details")</h5>
                        <p>
                            {!! trans(optional($notice->details)->description) !!}
                        </p>
                    </div>
                @empty
                @endforelse

                @if(count($notices) < 1)
                    <div class="text-center p-4">
                        <img class="dataTables-image mb-3"
                             src="{{ asset('assets/global/img/oc-error.svg') }}"
                             alt="Image Description" data-hs-theme-appearance="default">
                        <p class="mb-0">@lang("The notice is not available.")</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection


