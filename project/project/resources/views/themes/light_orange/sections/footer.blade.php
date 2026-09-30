<section class="footer_area">
    <div class="container">
        <div class="row gy-4 gy-sm-5">
            <div class="col-lg-4 col-sm-6">
                <div class="footer_widget">
                    <div class="widget_logo">
                        <h5><a href="{{ url('/') }}" class="site_logo"><img
                                        src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                                        alt="@lang('Logo')"
                                        class="img-fluid" width="220"></a></h5>
                        <p>@lang(@$footer['single']['site_short_description'])</p>
                    </div>

                    <div class="social_area mt-50">
                        <ul>
                            @forelse($footer['multiple'] as $item)
                                <li><a href="{{ @$item['media']->link }}" target="_blank"><i
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
                <div class="footer_widget">
                    <h5>@lang('Quick Links')<span class="highlight"> _</span></h5>
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
                <div class="footer_widget">
                    <h5>@lang('Support') <span class="highlight"> _</span></h5>
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
                <div class="footer_widget">
                    <h5>@lang('Language')<span class="highlight"> _</span></h5>
                    <ul>
                        @foreach($languages as $item)
                            <li>
                                <a href="{{ route('language',$item->short_name) }}"
                                   class="{{  session()->get('lang') == $item->short_name ?'active': ''}}">@lang($item->name) </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- footer_area_end -->

<!-- copy_right_area_start -->
<div class="copy_right_area text-center">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <p>{{trans('Copyright')}} &copy; {{date('Y')}} {{ trans(basicControl()->site_title)  }}
                    . {{trans('All Rights Reserved')}}</p>
            </div>

        </div>
    </div>
</div>
<!-- copy_right_area_end -->

