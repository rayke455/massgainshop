<!-- Footer Section start -->
<section class="footer-section pb-50">

    <div class="bg-img-overlay"
         style="background-image: url({{ getFile(@$footer['single']['media']->overlay_image->driver, @$footer['single']['media']->overlay_image->path) }}); background-position: center right; background-repeat: no-repeat"></div>
    <div class="footer-section-inner">
        <div class="container">
            <div class="row gy-4 gy-sm-5">
                <div class="col-lg-4 col-sm-6">
                    <div class="footer-widget">
                        <div class="widget-logo mb-30">
                            <a href="{{ url('/') }}">
                                <img class="logo" src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                                     alt="@lang("Logo")">
                            </a>
                        </div>
                        <p>
                            @lang(@$footer['single']['short_description'])
                        </p>
                        <div class="social-area mt-50">
                            <ul class="d-flex">
                                @forelse($footer['multiple'] as $item)
                                    <li>
                                        <a href="{{ @$item['media']->link }}" target="_blank"><i
                                                class="{{ @$item['media']->icon }}"></i>
                                        </a>
                                    </li>
                                @empty
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6">
                    <div class="footer-widget">
                        <h5 class="widget-title">@lang("Quick Links")</h5>
                        <ul>
                            @if(getFooterMenuData('useful_link') != null)
                                @foreach(getFooterMenuData('useful_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif

                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 pt-sm-0 pt-3 ps-lg-5">
                    <div class="footer-widget">
                        <h5 class="widget-title">@lang("Company Policy")</h5>
                        <ul>
                            @if(getFooterMenuData('support_link') != null)
                                @foreach(getFooterMenuData('support_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 pt-sm-0 pt-3">
                    <div class="footer-widget">
                        <h5 class="widget-title">@lang("Newsletter")</h5>
                        <p>@lang(@$footer['single']['news_letter_heading'])</p>
                        <form action="{{ route('subscribe') }}" method="post" class="newsletter-form">
                            @csrf
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                   placeholder="@lang("Your Email Address")" autocomplete="off" required>
                            <button type="submit" class="subscribe-btn">
                                <i class="fa-regular fa-paper-plane"></i>
                            </button>
                        </form>
                        @error('email')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <hr class="cmn-hr">
            <div class="copyright-area">
                <div class="row gy-4">
                    <div class="col-sm-6">
                        <p>
                            @lang(@$footer['single']['copyright'])
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <div class="language">
                            @foreach($languages as $item)
                                <a href="{{ route('language',$item->short_name) }}"
                                   class="{{  session()->get('lang') == $item->short_name ?'active': ''}}">@lang($item->name) </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Footer Section end -->
