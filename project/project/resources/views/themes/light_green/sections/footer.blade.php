@php $zkPanel = \App\Services\PanelRentals::current(); @endphp
@if($zkPanel)
    {{-- A rented panel's domain: a simple branded footer, no platform links. --}}
    <footer class="zk-footer">
        <div class="container zk-footer__main">
            <div class="zk-footer__bottom" style="border-top:0">
                <a href="{{ url('/') }}"><img class="logo" src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}"></a>
                <p class="mb-0">&copy; {{ date('Y') }} {{ $zkPanel->name }}</p>
            </div>
        </div>
    </footer>
@else
<!-- Footer Section start -->
<footer class="zk-footer">
    @guest
        <div class="container">
            <div class="zk-cta zk-reveal">
                <div>
                    <h3>@lang('Ready to grow your audience?')</h3>
                    <p>@lang('Create a free account and place your first order in under a minute.')</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register') }}" class="zk-btn zk-btn--primary">@lang('Create free account')</a>
                    <a href="{{ route('services') }}" class="zk-btn zk-btn--ghost">@lang('View pricing')</a>
                </div>
            </div>
        </div>
    @endguest

    <div class="container zk-footer__main">
        <div class="row gy-5">
            <div class="col-lg-4 col-md-6">
                <a href="{{ url('/') }}">
                    <img class="logo" src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                         alt="{{ basicControl()->site_title }}">
                </a>
                <p class="mt-3 mb-0">@lang(@$footer['single']['short_description'])</p>
                <ul class="zk-social">
                    @forelse($footer['multiple'] as $item)
                        <li>
                            <a href="{{ @$item['media']->link }}" target="_blank" rel="noopener"
                               aria-label="@lang(@$item['name'])"><i class="{{ @$item['media']->icon }}"></i></a>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <h5>@lang("Quick Links")</h5>
                <ul>
                    @foreach(getFooterMenuData('useful_link') ?? [] as $list)
                        {!! $list !!}
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <h5>@lang("Company")</h5>
                <ul>
                    @foreach(getFooterMenuData('support_link') ?? [] as $list)
                        {!! $list !!}
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-4 col-md-12">
                <h5>@lang("Newsletter")</h5>
                <p>@lang(@$footer['single']['news_letter_heading'])</p>
                <form action="{{ route('subscribe') }}" method="post" class="zk-newsletter">
                    @csrf
                    <label for="zk-newsletter-email" class="visually-hidden">@lang("Email address")</label>
                    <input type="email" id="zk-newsletter-email"
                           class="form-control @error('email') is-invalid @enderror" name="email"
                           placeholder="@lang("you@example.com")" autocomplete="email" required>
                    <button type="submit" class="zk-btn zk-btn--primary">@lang("Subscribe")</button>
                </form>
                {{-- Sign-up/login forms also use "email"; don't echo their errors under the newsletter box --}}
                @unless(request()->routeIs('register', 'login', 'password.*'))
                    @error('email')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                @endunless
            </div>
        </div>

        <div class="zk-footer__bottom">
            <p>@lang(@$footer['single']['copyright'])</p>
            <div class="language">
                @foreach($languages as $item)
                    <a href="{{ route('language',$item->short_name) }}"
                       class="{{ session()->get('lang') == $item->short_name ? 'active' : '' }}">@lang($item->name)</a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section end -->
@endif
