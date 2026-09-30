<!-- FEATURE -->
<section class="feature-section">
    <div class="container">
        <div class="row gy-5 g-lg-4">
            @forelse($feature['multiple'] as $item)
                <div class="col-lg-4">
                    <div
                            class="feature-box"
                            data-aos="fade-up"
                            data-aos-duration="800"
                            data-aos-anchor-placement="center-bottom">
                        <div class="icon-box">
                            <img
                                    src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                    alt="@lang('feature icon')"/>
                        </div>
                        <h4>@lang(@$item['title'])</h4>
                        <p>@lang(@$item['short_description'])</p>
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</section> <!-- /FEATURE -->

