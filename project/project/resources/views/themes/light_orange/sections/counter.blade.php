<!-- achievement_area_start -->
<section class="achivement_area">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="section_header mb-0 text-center text-lg-start">
                    <h3 class="mb-0">@lang(@$counter['single']['title'])</h3>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="row">
                    @forelse($counter['multiple'] as $item)
                        <div class="col-md-6">
                            <div class="cmn_box text-center">
                                <div class="image_area">
                                    <img
                                        src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                        alt="@lang('counter image')">
                                </div>
                                <div class="text_area">
                                    <h4><span
                                            class="achivement_counter">{{trim(@$item['number_of_data'])}}</span>
                                        +</h4>
                                    <h5>@lang(@$item['title'])</h5>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- achievement_area_end -->
