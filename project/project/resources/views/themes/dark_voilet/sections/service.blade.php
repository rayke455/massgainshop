<section class="service-section">
    <div class="service-section-inner">
        <div class="container">
            <div class="row">
                <div class="section-header text-center">
                    <span class="section-subtitle">@lang(@$service['single']['title'])</span>
                    <h2 class="section-title mx-auto">@lang(@$service['single']['heading'])</h2>
                    <p class="cmn-para-text mx-auto">
                        @lang(strip_tags(@$service['single']['short_description']))
                    </p>
                </div>
            </div>
            <div class="row g-4">
                @forelse($service['multiple'] as $key => $item)

                    @php
                        $colClass = ($key == 0 || $key == 3 || $key == 4) ? 'col-lg-7 col-md-6' : 'col-lg-5 col-md-6';
                    @endphp

                    <div class="{{ $colClass }}">
                        <div class="cmn-box">
                            <div class="icon-area">
                                <i class="{{ $item['media']->icon }}"></i>
                            </div>
                            <div class="content-area">
                                <h4 class="title">@lang($item['name'])</h4>
                                <p>{!! $item['short_description'] !!}
                                </p>
                                <a class="link" href="{{ $item['media']->button_link }}">{{ $item['button_name'] }} <i
                                            class="fa-sharp fa-regular fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>
