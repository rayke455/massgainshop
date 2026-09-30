<!-- Achievement section start: stat cards (SMMGen-style row under the hero) -->
<section class="zk-section zk-section--tight zk-statcards-wrap">
    <div class="container">
        @if(@$achievement['single']['heading'])
            <div class="zk-head zk-reveal" style="margin-bottom: 36px">
                <h2 class="zk-title">{!! zkHighlight(__(@$achievement['single']['heading'])) !!}</h2>
                @if(@$achievement['single']['description'])
                    <p class="zk-lead">@lang(strip_tags(@$achievement['single']['description']))</p>
                @endif
            </div>
        @endif
        <div class="zk-statcards">
            @forelse($achievement['multiple'] as $item)
                <div class="zk-statcard zk-reveal">
                    <span class="zk-statcard__icon"><i class="{{ @$item['media']->icon }}"></i></span>
                    <strong><span class="achivement-counter">{{ @$item['count'] }}</span>+</strong>
                    <span>@lang(@$item['title'])</span>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- Achievement section end -->
