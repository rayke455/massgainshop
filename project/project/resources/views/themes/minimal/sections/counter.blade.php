<section id="counter">
    <div class="left-shape shape-circle wow fadeIn" data-wow-duration="1s" data-wow-delay="0.35s">
        <div class="circle"></div>
    </div>
    <div class="container">
        <div class="counter-wrap wow fadeIn" data-wow-duration="1s" data-wow-delay="0.35s">
            <div class="row">
                @forelse($counter['multiple'] as $item)
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="counting">
                            <div class="counter-heading">
                                <h2 class="h2 counter"
                                    data-TargetNum="{{trim(@$item['number_of_data'])}}"
                                    data-Speed="7000"></h2>
                                <h2 class="h2 ml-5">+</h2>
                            </div>
                            <p>@lang(@$item['title'])</p>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</section>
<!-- /COUNTER -->

