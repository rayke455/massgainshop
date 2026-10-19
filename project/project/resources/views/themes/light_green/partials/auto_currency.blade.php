{{--
    First visit: switch prices to the visitor's local currency (by country), once.
    - Guests: only when they have never picked a currency (no cookie).
    - Customers: only when their account has no currency yet.
    A currency the visitor picked themselves is never changed.
--}}
@php
    $zkNeedsCurrency = auth()->check() ? empty(auth()->user()->getRawOriginal('currency')) : !request()->cookie('currency');
    $zkActiveCodes = $zkNeedsCurrency ? \App\Models\Currency::where('status', 1)->pluck('code')->values() : collect();
@endphp
@if($zkNeedsCurrency && $zkActiveCodes->count() > 1)
    <script>
        "use strict";
        (function () {
            const map = @json(config('country_currency'));
            const active = @json($zkActiveCodes);
            const base = @json(basicControl()->base_currency);
            const loggedIn = @json(auth()->check());
            const guestUrl = @json(route('set.currency'));
            const userUrl = @json(auth()->check() ? route('user.selected.currency') : null);
            const token = @json(csrf_token());
            const attemptKey = @json(auth()->check() ? 'zkCurAuto:user:'.auth()->id() : 'zkCurAuto:guest');
            try { if (sessionStorage.getItem(attemptKey)) return; } catch (e) {}

            (window.zkCountry ? window.zkCountry() : Promise.resolve('')).then(function (country) {
                const code = map[country];
                if (!code || !active.includes(code)) return;
                if (!loggedIn && code === base) return; // already showing base prices

                const req = loggedIn
                    ? fetch(userUrl + '?automatic=1&currency=' + encodeURIComponent(code), {headers: {'X-Requested-With': 'XMLHttpRequest'}})
                    : fetch(guestUrl, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest'},
                        body: '_token=' + encodeURIComponent(token) + '&automatic=1&currency=' + encodeURIComponent(code),
                    });
                req.then(r => r.ok ? r.json() : null).then(result => {
                    if (!result || !result.success) return;
                    try { sessionStorage.setItem(attemptKey, '1'); } catch (e) {}
                    location.reload();
                }).catch(() => {});
            });
        })();
    </script>
@endif
