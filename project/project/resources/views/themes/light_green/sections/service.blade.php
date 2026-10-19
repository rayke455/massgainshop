<!-- Service section start: platform tabs built from the live catalogue -->
@php
    $zkPlatforms = \App\Models\SocialMedia::where('status', 1)
        ->orderBy('sort_by')
        ->with(['category' => fn($q) => $q->where('status', 1)->orderBy('sort_by')
            ->withCount(['service' => fn($s) => $s->where('service_status', 1)])
            ->withMin(['service as zk_min_price' => fn($s) => $s->where('service_status', 1)], \DB::raw('CAST(price AS DECIMAL(16,6))'))])
        ->get()
        ->filter(fn($p) => $p->category->where('service_count', '>', 0)->isNotEmpty())
        ->values();

    $zkBrand = function ($name) {
        $n = strtolower($name);
        foreach (['instagram', 'tiktok', 'youtube', 'facebook', 'telegram', 'twitter', 'spotify', 'linkedin', 'snapchat', 'discord', 'twitch', 'pinterest', 'reddit', 'soundcloud', 'threads', 'whatsapp'] as $b) {
            if (str_contains($n, $b)) {
                return ['facebook' => 'facebook-f', 'twitter' => 'twitter', 'linkedin' => 'linkedin-in', 'threads' => 'at'][$b] ?? $b;
            }
        }
        return null;
    };
@endphp
<section class="zk-section zk-section--soft" id="services">
    <div class="container">
        <div class="zk-head zk-reveal">
            <span class="zk-eyebrow">@lang('Explore services')</span>
            <h2 class="zk-title">@lang('Your platform.') <span class="zk-hl">@lang('Your next step.')</span></h2>
            <p class="zk-lead">@lang('Choose a platform to compare services, prices, and order limits before you get started.')</p>
        </div>

        @if($zkPlatforms->isNotEmpty())
            <div class="zk-ptabs zk-reveal" role="tablist" aria-label="@lang('Platforms')">
                @foreach($zkPlatforms as $i => $platform)
                    @php $brand = $zkBrand($platform->name); @endphp
                    <button type="button" class="zk-ptab {{ $i === 0 ? 'active' : '' }}" role="tab"
                            id="zk-ptab-{{ $platform->id }}" aria-controls="zk-ppanel-{{ $platform->id }}"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}" data-zk-tab="{{ $platform->id }}">
                        @if($brand)
                            <i class="fa-brands fa-{{ $brand }}"></i>
                        @elseif($platform->icon)
                            <img src="{{ getFile($platform->icon_driver, $platform->icon) }}" alt="">
                        @endif
                        {{ $platform->name }}
                    </button>
                @endforeach
            </div>

            @foreach($zkPlatforms as $i => $platform)
                @php $cats = $platform->category->where('service_count', '>', 0); @endphp
                <div class="zk-ppanel zk-reveal" role="tabpanel" id="zk-ppanel-{{ $platform->id }}"
                     aria-labelledby="zk-ptab-{{ $platform->id }}" @if($i !== 0) hidden @endif>
                    <div class="zk-ppanel__intro">
                        <h3>{!! zkHighlight($platform->name . ' ' . __('SMM services')) !!}</h3>
                        <p>@lang('Everything you need to grow on') {{ $platform->name }} — {{ $cats->sum('service_count') }} @lang('services across') {{ $cats->count() }} @lang('categories, delivered automatically.')</p>
                        <a href="{{ route('services') }}" class="zk-btn zk-btn--primary zk-btn--pill">@lang('View all prices') <i class="fa-regular fa-arrow-right"></i></a>
                    </div>
                    @php
                        $zkTop = \App\Models\Service::where('service_status', 1)
                            ->whereIn('category_id', $cats->pluck('id'))
                            ->orderByRaw('CAST(price AS DECIMAL(16,6)) asc')
                            ->take(6)->get();
                        $zkBuyUrl = auth()->check() ? route('user.order.create') : route('guest.order');
                    @endphp
                    <div class="zk-ppanel__side">
                        <ul class="zk-ppanel__list zk-ppanel__list--services">
                            @foreach($zkTop as $svc)
                                <li>
                                    <i class="fa-solid fa-bolt"></i>
                                    <div class="zk-svc">
                                        <strong title="{{ $svc->service_title }}">{{ \Illuminate\Support\Str::limit($svc->service_title, 70) }}</strong>
                                        <span>
                                            <b><span class="notranslate">{{ zkShowPrice($svc->price) }}</span></b> / 1k
                                            · @lang('Min') {{ number_format($svc->min_amount) }} · @lang('Max') {{ number_format($svc->max_amount) }}
                                            @if($svc->refill) · <em class="zk-svc__tag">@lang('Refill')</em> @endif
                                        </span>
                                    </div>
                                    <a href="{{ $zkBuyUrl }}" class="zk-svc__buy" aria-label="@lang('Buy') {{ $svc->service_title }}">@lang('Buy')</a>
                                </li>
                            @endforeach
                        </ul>
                        @if($cats->count() > 1)
                            <div class="zk-ppanel__cats">
                                @foreach($cats->take(8) as $cat)
                                    <span>{{ \Illuminate\Support\Str::limit($cat->category_title, 40) }} <small>{{ $cat->service_count }}</small></span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="row g-4">
                @forelse($service['multiple'] as $item)
                    <div class="col-lg-4 col-md-6 zk-reveal">
                        <div class="zk-card zk-service">
                            <span class="zk-icon"><i class="{{ @$item['media']->icon }}"></i></span>
                            <h4>@lang(@$item['service_name'])</h4>
                            <p>@lang(strip_tags(@$item['description']))</p>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        @endif
    </div>
</section>

    {{-- Inline: sections are rendered to HTML before the layout, so @push would be lost --}}
    <script>
        "use strict";
        document.querySelectorAll('[data-zk-tab]').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('[data-zk-tab]').forEach(function (t) {
                    const on = t === tab;
                    t.classList.toggle('active', on);
                    t.setAttribute('aria-selected', on ? 'true' : 'false');
                    document.getElementById('zk-ppanel-' + t.dataset.zkTab).hidden = !on;
                });
            });
        });
    </script>
<!-- Service section end -->
