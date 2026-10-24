{{--
    Live activity popup (home page).
    - "Just ordered" cards come only from real orders (country from the buyer's profile, no names or links).
    - "Available in" cards promote real services country by country; they never claim a purchase happened.
--}}
@if(basicControl()->activity_popup_enabled ?? true)
@php
    $zkCountries = [
        'us' => 'United States', 'gb' => 'United Kingdom', 'de' => 'Germany', 'br' => 'Brazil', 'ca' => 'Canada',
        'au' => 'Australia', 'fr' => 'France', 'es' => 'Spain', 'it' => 'Italy', 'nl' => 'Netherlands',
        'se' => 'Sweden', 'no' => 'Norway', 'dk' => 'Denmark', 'fi' => 'Finland', 'ie' => 'Ireland',
        'pt' => 'Portugal', 'pl' => 'Poland', 'ch' => 'Switzerland', 'at' => 'Austria', 'be' => 'Belgium',
        'mx' => 'Mexico', 'ar' => 'Argentina', 'co' => 'Colombia', 'cl' => 'Chile', 'pe' => 'Peru',
        'ke' => 'Kenya', 'ng' => 'Nigeria', 'za' => 'South Africa', 'gh' => 'Ghana', 'ug' => 'Uganda',
        'tz' => 'Tanzania', 'rw' => 'Rwanda', 'eg' => 'Egypt', 'ma' => 'Morocco', 'et' => 'Ethiopia',
        'ae' => 'United Arab Emirates', 'sa' => 'Saudi Arabia', 'qa' => 'Qatar', 'tr' => 'Turkey', 'il' => 'Israel',
        'in' => 'India', 'pk' => 'Pakistan', 'bd' => 'Bangladesh', 'lk' => 'Sri Lanka', 'np' => 'Nepal',
        'id' => 'Indonesia', 'my' => 'Malaysia', 'sg' => 'Singapore', 'ph' => 'Philippines', 'th' => 'Thailand',
        'vn' => 'Vietnam', 'jp' => 'Japan', 'kr' => 'South Korea', 'nz' => 'New Zealand', 'ro' => 'Romania',
        'gr' => 'Greece', 'cz' => 'Czechia', 'hu' => 'Hungary', 'ua' => 'Ukraine', 'jm' => 'Jamaica',
    ];
    $zkFlag = fn($iso) => file_exists(base_path('assets/themes/light_green/img/mini-flag/' . $iso . '.svg'))
        ? asset('assets/themes/light_green/img/mini-flag/' . $iso . '.svg') : null;
    $zkClean = fn($t) => \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) $t))), 48);

    $zkFeed = [];
    try {
        // Real orders from the last 30 days.
        $zkReal = \App\Models\Order::with(['service:id,service_title', 'users:id,country_code,country'])
            ->whereNull('rental_panel_id')
            ->whereIn('status', ['processing', 'completed', 'partial'])
            ->where('created_at', '>=', now()->subDays(30))
            ->latest()->take(15)->get(['id', 'user_id', 'service_id', 'quantity', 'created_at']);
        foreach ($zkReal as $o) {
            $iso = strtolower((string) optional($o->users)->country_code);
            $zkFeed[] = [
                'kind' => 'real',
                'flag' => $iso ? $zkFlag($iso) : null,
                'place' => $zkCountries[$iso] ?? (optional($o->users)->country ?: ''),
                'text' => number_format((int) $o->quantity) . ' × ' . $zkClean(optional($o->service)->service_title ?? __('SMM service')),
                'time' => $o->created_at->diffForHumans(),
            ];
        }

        // Country-by-country promos for the real, active services.
        $zkSvcs = \App\Models\Service::where('service_status', 1)
            ->orderByRaw('CAST(price AS DECIMAL(16,6)) asc')->take(12)->get(['id', 'service_title', 'price']);
        if ($zkSvcs->isNotEmpty()) {
            // Service prices are in the site's base currency; rates convert from that base.
            // Use the promoted country's currency, not the visitor's currency cookie.
            $zkRates = \App\Models\Currency::where('status', 1)->get(['code', 'conversion_rate'])->keyBy('code');
            $zkBaseCurrency = strtoupper((string) basicControl()->base_currency);
            $codes = ['ke', 'ke', 'tz', 'ug', 'ng', 'gh', 'za', 'rw', 'eg', 'ma', 'et'];
            foreach (array_slice($codes, 0, 40) as $i => $iso) {
                $zkCode = config('country_currency.'.strtoupper($iso));
                $zkRate = $zkCode === $zkBaseCurrency ? 1.0 : (float) ($zkRates->get($zkCode)->conversion_rate ?? 0);
                // Do not advertise a misleading price when a local rate is unavailable.
                if (!$zkCode || !is_finite($zkRate) || $zkRate <= 0) continue;
                $s = $zkSvcs[$i % $zkSvcs->count()];
                $zkFeed[] = [
                    'kind' => 'promo',
                    'flag' => $zkFlag($iso),
                    'place' => $zkCountries[$iso],
                    'text' => $zkClean($s->service_title),
                    'price' => zkPerK((float) $s->price * $zkRate, $zkCode) . ' / 1k',
                ];
            }
        }
    } catch (\Throwable $e) {
        $zkFeed = [];
    }
    $zkBuy = auth()->check() ? route('user.order.create') : route('guest.order');
    $zkRealFeed = array_values(array_filter($zkFeed, fn($f) => $f['kind'] === 'real'));
    $zkPromoFeed = array_values(array_filter($zkFeed, fn($f) => $f['kind'] === 'promo'));
@endphp

@if(!empty($zkFeed))
    <div class="zk-live" id="zkLive" role="status" aria-live="polite" hidden>
        <img class="zk-live__flag" alt="" width="36" height="27">
        <div class="zk-live__body">
            <small class="zk-live__kicker"></small>
            <strong class="zk-live__text"></strong>
            <span class="zk-live__meta notranslate"></span>
        </div>
        <a class="zk-live__cta" href="{{ $zkBuy }}">@lang('Buy')</a>
        <button type="button" class="zk-live__close" aria-label="@lang('Hide')"><i class="fa-regular fa-xmark"></i></button>
    </div>
    <script>
        "use strict";
        (function () {
            const box = document.getElementById('zkLive');
            if (!box) return;
            try { if (sessionStorage.getItem('zkLiveOff') === '1') return; } catch (e) {}
            const real = @json($zkRealFeed);
            const promo = @json($zkPromoFeed);
            const t = {
                real: @json(__('Recent order from')),
                promo: @json(__('Now available in')),
                from: @json(__('from')),
                justNow: @json(__('Just ordered')),
            };
            // Mix: real orders first and more often, promos fill the gaps.
            const queue = [];
            const max = Math.max(real.length, promo.length);
            for (let i = 0; i < max; i++) {
                if (real[i]) queue.push(real[i]);
                if (promo[i]) queue.push(promo[i]);
            }
            if (!queue.length) return;

            const flag = box.querySelector('.zk-live__flag');
            const kicker = box.querySelector('.zk-live__kicker');
            const text = box.querySelector('.zk-live__text');
            const meta = box.querySelector('.zk-live__meta');
            let i = 0, hideTimer, nextTimer, paused = false;

            function render(item) {
                if (item.flag) { flag.src = item.flag; flag.hidden = false; } else { flag.hidden = true; }
                kicker.textContent = item.kind === 'real' ? (item.place ? t.real + ' ' + item.place : t.justNow) : t.promo + ' ' + item.place;
                text.textContent = item.text;
                meta.textContent = item.kind === 'real' ? item.time : t.from + ' ' + item.price;
                box.classList.toggle('zk-live--real', item.kind === 'real');
            }
            function show() {
                if (paused) { nextTimer = setTimeout(show, 2000); return; }
                render(queue[i++ % queue.length]);
                box.hidden = false;
                requestAnimationFrame(() => box.classList.add('is-on'));
                hideTimer = setTimeout(hide, 6000);
            }
            function hide() {
                box.classList.remove('is-on');
                setTimeout(() => { box.hidden = true; }, 350);
                nextTimer = setTimeout(show, 9000 + Math.random() * 6000);
            }
            box.addEventListener('mouseenter', () => { paused = true; clearTimeout(hideTimer); });
            box.addEventListener('mouseleave', () => { paused = false; hideTimer = setTimeout(hide, 2500); });
            box.querySelector('.zk-live__close').addEventListener('click', function () {
                clearTimeout(hideTimer); clearTimeout(nextTimer);
                box.classList.remove('is-on'); box.hidden = true;
                try { sessionStorage.setItem('zkLiveOff', '1'); } catch (e) {}
            });
            nextTimer = setTimeout(show, 5000);
        })();
    </script>
@endif
@endif
