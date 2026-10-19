{{--
    Auto-translate by country (Google's page translator, no API key).
    Order of choice: the visitor's own pick (globe menu) → their country → their browser language → English.
    Parts with live prices (Vue apps) are marked "notranslate" so numbers never go stale.
--}}
@if(basicControl()->auto_translate ?? false)
    <div id="zk_gt" class="notranslate" aria-hidden="true"></div>
    <style>
        #zk_gt, .skiptranslate > iframe, iframe.skiptranslate, .goog-te-banner-frame, #goog-gt-tt, .goog-te-balloon-frame,
        .VIpgJd-ZVi9od-ORHb-OEVmcd, .VIpgJd-ZVi9od-aZ2wEe-wOHMyf, .VIpgJd-ZVi9od-l4eHX-hSRGPd, .VIpgJd-yAWNEb-L7lbkb { display: none !important; }
        html, body { top: 0 !important; position: static; }
        .goog-text-highlight { background: none !important; box-shadow: none !important; }
        .zk-lang { position: relative; display: inline-flex; align-items: center; gap: 6px; }
        .zk-lang select { max-width: 150px; }
    </style>
    <script>
        "use strict";
        (function () {
            const COUNTRY_LANG = {
                // Europe
                DE: 'de', AT: 'de', CH: 'de', LI: 'de', FR: 'fr', BE: 'fr', LU: 'fr', MC: 'fr', ES: 'es', PT: 'pt', IT: 'it', SM: 'it',
                NL: 'nl', SE: 'sv', NO: 'no', DK: 'da', FI: 'fi', IS: 'is', PL: 'pl', CZ: 'cs', SK: 'sk', HU: 'hu', RO: 'ro', MD: 'ro',
                BG: 'bg', GR: 'el', CY: 'el', HR: 'hr', RS: 'sr', BA: 'bs', SI: 'sl', MK: 'mk', AL: 'sq', EE: 'et', LV: 'lv', LT: 'lt',
                UA: 'uk', RU: 'ru', BY: 'ru', TR: 'tr', GE: 'ka', AM: 'hy', AZ: 'az',
                // Americas
                BR: 'pt', MX: 'es', AR: 'es', CO: 'es', CL: 'es', PE: 'es', VE: 'es', EC: 'es', BO: 'es', PY: 'es', UY: 'es',
                CR: 'es', PA: 'es', GT: 'es', HN: 'es', SV: 'es', NI: 'es', DO: 'es', CU: 'es', PR: 'es', HT: 'ht',
                // Middle East & Asia
                SA: 'ar', AE: 'ar', EG: 'ar', IQ: 'ar', JO: 'ar', KW: 'ar', QA: 'ar', OM: 'ar', BH: 'ar', LB: 'ar', SY: 'ar', YE: 'ar',
                LY: 'ar', DZ: 'ar', TN: 'ar', MA: 'ar', SD: 'ar', PS: 'ar', IR: 'fa', AF: 'fa', IL: 'iw',
                CN: 'zh-CN', TW: 'zh-TW', HK: 'zh-TW', MO: 'zh-TW', JP: 'ja', KR: 'ko', VN: 'vi', TH: 'th', ID: 'id', MY: 'ms',
                BD: 'bn', NP: 'ne', LK: 'si', MM: 'my', KH: 'km', LA: 'lo', MN: 'mn', KZ: 'kk', UZ: 'uz', KG: 'ky', TJ: 'tg',
                // Africa (English-speaking countries such as Kenya, Nigeria, Ghana, Uganda and South Africa stay in English)
                TZ: 'sw', ET: 'am', SO: 'so', RW: 'rw', AO: 'pt', MZ: 'pt', CV: 'pt', GW: 'pt', ST: 'pt', MG: 'mg',
                SN: 'fr', CI: 'fr', CM: 'fr', ML: 'fr', BF: 'fr', NE: 'fr', TG: 'fr', BJ: 'fr', GN: 'fr', GA: 'fr', CG: 'fr',
                CD: 'fr', TD: 'fr', CF: 'fr', BI: 'fr', DJ: 'fr', KM: 'fr', MR: 'ar',
            };
            const SUPPORTED = @json(array_keys(config('zirka_languages')));
            const store = {
                get: k => { try { return localStorage.getItem(k); } catch (e) { return null; } },
                set: (k, v) => { try { localStorage.setItem(k, v); } catch (e) {} },
            };

            function setCookie(value) {
                const host = location.hostname;
                const expire = value ? '' : ';expires=Thu, 01 Jan 1970 00:00:00 GMT';
                const v = value ? '/en/' + value : '';
                document.cookie = 'googtrans=' + v + ';path=/' + expire;
                if (host.indexOf('.') > 0 && !/^\d+\.\d+\.\d+\.\d+$/.test(host)) {
                    document.cookie = 'googtrans=' + v + ';path=/;domain=.' + host.replace(/^www\./, '') + expire;
                }
            }

            let loading = false;
            // Start Google's translator only after the page's own apps (Vue) have started,
            // so it can never rewrite a Vue template before Vue reads it.
            function whenPageReady(fn) {
                if (document.readyState === 'complete') return setTimeout(fn, 0);
                let done = false;
                const go = () => { if (!done) { done = true; fn(); } };
                window.addEventListener('load', go, {once: true});
                setTimeout(go, 4000); // don't wait forever for a slow image
            }

            function loadTranslator() {
                if (loading) return;
                loading = true;
                window.zkGtInit = function () {
                    new google.translate.TranslateElement({pageLanguage: 'en', autoDisplay: false}, 'zk_gt');
                };
                whenPageReady(function () {
                    const s = document.createElement('script');
                    s.src = 'https://translate.google.com/translate_a/element.js?cb=zkGtInit';
                    s.async = true;
                    document.head.appendChild(s);
                });
            }

            // Select the language in Google's hidden menu once it exists (the cookie alone is only read on page load).
            function selectInTranslator(lang, tries) {
                const combo = document.querySelector('.goog-te-combo');
                if (combo && combo.options.length > 1) {
                    if (combo.value !== lang) {
                        combo.value = lang;
                        combo.dispatchEvent(new Event('change'));
                    }
                    return;
                }
                if ((tries || 0) < 60) setTimeout(() => selectInTranslator(lang, (tries || 0) + 1), 250);
            }

            function switchTo(lang) {
                if (lang === 'en') {
                    setCookie('');
                    location.reload(); // the original English text is restored on reload
                    return;
                }
                setCookie(lang);
                loadTranslator();
                selectInTranslator(lang);
            }

            function syncPickers(lang) {
                document.querySelectorAll('.zk-lang-select').forEach(sel => { sel.value = SUPPORTED.includes(lang) ? lang : 'en'; });
            }

            function pickFor(country) {
                const byCountry = COUNTRY_LANG[country];
                if (byCountry && SUPPORTED.includes(byCountry)) return byCountry;
                if (country) return 'en'; // known country without its own language rule (e.g. Kenya, USA)
                const nav = (navigator.language || 'en').toLowerCase();
                const exact = SUPPORTED.find(l => l.toLowerCase() === nav);
                const base = nav.split('-')[0];
                return exact || (SUPPORTED.includes(base) ? base : 'en');
            }

            function apply(lang) {
                syncPickers(lang);
                if (lang && lang !== 'en') {
                    setCookie(lang);
                    loadTranslator();
                    selectInTranslator(lang);
                } else {
                    setCookie('');
                }
            }

            document.addEventListener('change', function (e) {
                if (!e.target.classList || !e.target.classList.contains('zk-lang-select')) return;
                const lang = e.target.value;
                store.set('zkLang', lang);       // an explicit choice always wins over the country
                syncPickers(lang);
                switchTo(lang);
            });

            const chosen = store.get('zkLang');
            if (chosen) {
                apply(chosen);
            } else {
                (window.zkCountry ? window.zkCountry() : Promise.resolve(''))
                    .then(c => apply(pickFor(c)))
                    .catch(() => apply(pickFor('')));
            }
        })();
    </script>
@endif
