{{-- Visitor country, looked up once and shared by auto-translate and auto-currency. --}}
@php
    $zkCfCountry = strtoupper((string) request()->header('CF-IPCountry', ''));
    $zkCfCountry = preg_match('/^[A-Z]{2}$/', $zkCfCountry) && !in_array($zkCfCountry, ['XX', 'T1']) ? $zkCfCountry : '';
@endphp
<script>
    "use strict";
    (function () {
        if (window.zkCountry) return;
        let pending = null;
        const server = @json($zkCfCountry);
        const read = () => { try { return Date.now() - Number(localStorage.getItem('zkCountryAt') || 0) < 86400000 ? localStorage.getItem('zkCountry') || '' : ''; } catch (e) { return ''; } };
        const save = c => { try { localStorage.setItem('zkCountry', c); localStorage.setItem('zkCountryAt', String(Date.now())); } catch (e) {} };

        /** Resolves to a 2-letter country code, or '' if unknown. Behind Cloudflare it's instant. */
        window.zkCountry = function () {
            if (server) { save(server); return Promise.resolve(server); }
            const known = read();
            if (known) return Promise.resolve(known);
            return pending ??= fetch('https://get.geojs.io/v1/ip/country.json', {cache: 'no-store'})
                .then(r => r.ok ? r.json() : null)
                .then(d => {
                    const c = d && /^[A-Z]{2}$/.test(d.country) ? d.country : '';
                    if (c) save(c);
                    return c;
                })
                .catch(() => '');
        };
    })();
</script>
