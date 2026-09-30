<!-- FOOTER -->
<footer id="footer">
    <figure class="footer-shape">
        <svg class="shape-fill" enable-background="new 0 0 1504 110" viewBox="0 0 1504 110"
             xmlns="http://www.w3.org/2000/svg">
            <path
                d="m877.8 85c139.5 24.4 348 33.5 632.2-48.2-.2 32.5-.3 65-.5 97.4-505.9 0-1011.6 0-1517.5 0 0-33.3 0-66.7 0-100.1 54.2-11.4 129.5-23.9 220-28.2 91-4.3 173.6 1 307.4 18.6 183.2 24.2 295.2 49.4 358.4 60.5z"></path>
        </svg>
    </figure>

    <div class="container">
        <div class="row pt-50">
                <div class="col-lg-6">
                    <div class="row footer-address">
                        <div class="col-lg-6">
                            <ul class="icofont-ul">
                                <li class="mb-15"><i class="icofont-iphone"></i>
                                    <span>@lang(@$footer['single']['phone'])</span></li>
                                <li><i class="icofont-envelope-open"></i>
                                    <span>@lang(@$footer['single']['email'])</span></li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <div class="media">
                                <div class="media-icon">
                                    <i class="icofont-google-map"></i>
                                </div>
                                <div class="media-body">
                                    <p class="media-text">@lang(@$footer['single']['address'])</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="col-lg-6">
                <div class="subscribe" id="subscribe">
                    <form class="subscribe-form" action="{{route('subscribe')}}" method="post">
                        @csrf
                        <input class="form-control" name="email" type="email" placeholder="{{trans('Email Address')}}">

                        @error('email')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                        <button class="btn" type="submit">{{trans('Subscribe Now')}}</button>
                    </form>
                </div>
            </div>
        </div>
        <hr>

        <div class="row responsive-footer">
            <div class="col-md-6 col-lg-3">
                <div class="footer-brand">
                    <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                         alt="@lang("Footer Logo")">
                    <p>
                        @lang(@$footer['single']['site_short_description'])
                    </p>
                </div>

                <div class="footer-social">
                    @forelse($footer['multiple'] as $item)
                        <a class="social-icon" target="_blank"
                           href="{{ @$item['media']->link }}"
                           title="{{ @$item['name'] }}"><i
                                    class="{{ @$item['media']->icon }}"></i></a>
                    @empty
                    @endforelse
                </div>

            </div>

            <div class="col-md-6 col-lg-3">
                <div class="footer-links">
                    <h5>{{trans('Quick Links')}}</h5>
                    <ul class="nav flex-column mt-40">
                        @if(getFooterMenuData('useful_link') != null)
                            @foreach(getFooterMenuData('useful_link') as $list)
                                {!! $list !!}
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="footer-links">
                    <h5>{{trans('Support')}}</h5>
                    <ul class="nav flex-column mt-40">
                        @if(getFooterMenuData('support_link') != null)
                            @foreach(getFooterMenuData('support_link') as $list)
                                {!! $list !!}
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="footer-links">
                    <h5>@lang('Language')</h5>
                    <ul class="nav flex-column mt-40">
                        @foreach($languages as $language)
                            <li class="nav-item mb-10">
                                <a class="nav-link {{  session()->get('lang') == $language->short_name ?'active': ''}}"
                                   href="{{ route('language', $language->short_name) }}">{{ $language->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="copy-rights">
        <div class="container">
            <p>{{trans('Copyright')}} &copy; {{date('Y')}} {{trans(basicControl()->site_title)}}
                . {{trans('All Rights Reserved')}}</p>
        </div>
    </div>
</footer>
<!-- /FOOTER -->
