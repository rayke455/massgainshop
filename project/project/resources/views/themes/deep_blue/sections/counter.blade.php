<!-- COUNTER -->
<section class="achievement-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 w-64">
                <h3>@lang(@$counter['single']['title'])</h3>
            </div>

        </div>

        <div class="row gy-5 g-lg-4">
            @forelse($counter['multiple'] as $item)
                <div class="col-lg-3 col-md-6">
                    <div
                        class="counter-box"
                        data-aos="fade-up"
                        data-aos-duration="800"
                        data-aos-anchor-placement="center-bottom">
                        <div class="icon-box">
                            <img
                                src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                alt="@lang('counter image')"/>
                        </div>
                        <h4><span class="counter">{{trim(@$item['number_of_data'])}}</span> +</h4>
                        <p class="text-capitalize">@lang(@$item['title'])</p>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section>
<!-- /COUNTER -->
