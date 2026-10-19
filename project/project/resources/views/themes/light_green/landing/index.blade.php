@extends(template() . 'layouts.app')
@section('title', __('Buy social media services'))
@section('content')
    <section class="zk-land">
        <div class="container">
            <div class="zk-head">
                <span class="zk-eyebrow">@lang('All services')</span>
                <h1 class="zk-title">{!! zkHighlight(__('Buy followers, likes and [views]')) !!}</h1>
                <p class="zk-lead">@lang('Pick a platform and what you need. Every page shows live prices and starts in minutes.')</p>
            </div>
            @foreach($groups as $platform => $cats)
                <h2 class="zk-land__h2">{{ $platform }}</h2>
                <div class="zk-land__links mb-4">
                    @foreach($cats as $c)
                        <a href="{{ route('landing.show', $c->slug) }}">{{ $c->category_title }} <small>{{ $c->service->count() }}</small></a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endsection
