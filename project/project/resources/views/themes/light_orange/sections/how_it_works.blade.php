<div class="shape3"><img src="{{ asset(template(true).'img/shape3.png') }}" alt="@lang('not found')"></div>

<!-- how_it_work_area_start -->
<section class="how_it_work_area">
    <div class="container">
        <div class="row">
            <div class="section_header mb-50 text-center">
                <div class="section_subtitle">@lang(@$how_it_works['single']['title'])</div>
                <h2>@lang(@$how_it_works['single']['short_title'])</h2>
                <p class="para_text m-auto">@lang(@$how_it_works['single']['short_description'])</p>
            </div>
        </div>
        <div class="row align-items-center">
            @forelse($how_it_works['multiple'] as $key => $item)
                <div class="col-md-6">
                    <div class="cmn_box2 box1 d-flex shadow3 flex-column flex-sm-row">
                        <span class="number">{{ ++$key }}</span>
                        <div class="image_area ">
                            <i class="{{ @$item['media']->icon }}"></i>
                        </div>
                        <div class="text_area">
                            <h5>@lang(@$item['title'])</h5>
                            <p>@lang(@$item['short_description'])</p>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- how_it_work_area_end -->
