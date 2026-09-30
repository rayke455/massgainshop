<!-- FOOTER -->
<footer class="footer-section">
    <div class="overlay">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-3 col-md-6">
                    <div class="box box1">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="{{ getFile(basicControl()->logo_driver,basicControl()->logo) }}"
                                 alt="@lang('Logo')">
                        </a>

                        <p>@lang(@$footer['single']['site_short_description'])</p>

                        <div class="social-links">
                            @forelse($footer['multiple'] as $item)
                                <a href="{{ @$item['media']->link }}"
                                   title="{{ @$item['name'] }}" target="_blank"><i
                                        class="{{ @$item['media']->icon }}"></i></a>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 ps-lg-5">
                    <div class="box">
                        <h5>{{trans('Quick Links')}}</h5>
                        <ul class="links">
                            @if(getFooterMenuData('useful_link') != null)
                                @foreach(getFooterMenuData('useful_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="box">
                        <h5>{{trans('Support')}}</h5>
                        <ul class="links">
                            @if(getFooterMenuData('support_link') != null)
                                @foreach(getFooterMenuData('support_link') as $list)
                                    {!! $list !!}
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="box">
                        <h5>@lang(@$footer['single']['newsletter_heading'])</h5>
                        <form action="{{route('subscribe')}}" method="post">
                            <div class="input-group">
                                @csrf
                                <input class="form-control" name="email" type="email"
                                       placeholder="{{trans('Enter email')}}">
                                <button type="submit"><i class="fal fa-paper-plane"></i></button>

                            </div>
                            @error('email')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex copyright justify-content-between">
                <div>
                        <span>
                            @lang('All rights reserved') © {{date('Y')}} @lang('by')<a
                                href="javascript:void(0)">{{trans(basicControl()->site_title)}}</a>
                        </span>
                </div>
                <div>
                    @foreach($languages as $language)
                        <a href="{{route('language',[$language->short_name])}}" class="language">
                            {{$language->name}}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- /FOOTER -->

