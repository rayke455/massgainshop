<!-- TESTIMONIAL -->
<section id="testimonial">
    <div class="container">
        <div class="testimonial-slider carousel slide" data-ride="testimonial-slider" data-interval="false"
             data-pause="hover">
            <div class="row">

                <div class="col-xl-6">
                    <div class="circle-layout wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.35s">
                        <ul class="circle-indicators carousel-indicators">
                            @php  $i = 1; @endphp
                            @forelse($testimonial['multiple'] as $key => $item)
                                <li class="{{($key == 0) ? 'active' : ''}} nav-place-{{$i}}"
                                    data-target=".testimonial-slider" data-slide-to="{{$key}}">
                                    <img
                                            src="{{ getFile(@$item['media']->image->driver, @$item['media']->image->path) }}"
                                            @if($key == 0) class="testimonial-img-150" @endif alt="Nav Img Missing">
                                </li>
                                @php  $i++; @endphp
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>


                <div class="col-xl-6">
                    <div class="text-wrapper pl-0 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.35s">
                        <h1 class="heading heading-left">@lang(@$testimonial['single']['title']) <span
                                    class="sub-heading">@lang(@$testimonial['single']['sub_title'])</span></h1>
                        <div class="text-block">
                            <h3 class="h3 mb-30">@lang(@$testimonial['single']['short_title'])</h3>
                            <div class="carousel-inner">
                                @forelse($testimonial['multiple'] as $key => $item)
                                    <div class="carousel-item {{($key == 0) ? 'active' : ''}}">
                                        <div class="item-icon"><i class="icofont-quote-left"></i></div>
                                        <p class="text mb-30">
                                            @lang(strip_tags(@$item['description']))
                                        </p>
                                        <div class="clients-title">
                                            <h6>@lang(@$item['designation'])</h6>
                                            <p>@lang(@$item['name'])</p>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            <div class="carousel-control">
                                <a class="carousel-control-prev" href=".testimonial-slider" data-slide="prev">
                                    <i class="icofont-long-arrow-left"></i>
                                </a>
                                <a class="carousel-control-next" href=".testimonial-slider" data-slide="next">
                                    <i class="icofont-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /TESTIMONIAL -->
