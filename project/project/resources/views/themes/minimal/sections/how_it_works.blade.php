<!-- HOW-IT-WORKS -->
<section id="how-it-works">
    <div class="container">
            <div class="heading-container">
                <h1 class="heading">@lang(@$how_it_works['single']['title']) <span
                            class="sub-heading">@lang(@$how_it_works['single']['sub_title'])</span></h1>
                <h3 class="slogan">@lang(strip_tags(@$how_it_works['single']['short_title'])) </h3>
            </div>

        <div class="how-it-works">
            @forelse($how_it_works['multiple'] as $item)
                <div class="content-wrapper">
                    <div class="icon">
                        <i class="{{ @$item['media']->icon }}"></i>
                    </div>
                    <h6 class="h6 mt-20 mb-20">@lang(@$item['title'])</h6>
                    <p>@lang(@$item['short_description'])</p>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- /HOW-IT-WORKS -->

