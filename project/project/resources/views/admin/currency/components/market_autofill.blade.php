{{-- Code suggestions + "Use market rate" for the add/edit currency forms. --}}
<datalist id="zkCurrencyCodes">
    @foreach(config('market_currencies.fiat') + config('market_currencies.crypto') as $code => $info)
        <option value="{{ $code }}">{{ $info[0] }}</option>
    @endforeach
</datalist>

@push('script')
    <script>
        'use strict';
        (function () {
            const code = document.getElementById('codeLabel');
            const name = document.getElementById('nameLabel');
            const symbol = document.querySelector('input[name="symbol"]');
            const rate = document.querySelector('input[name="conversion_rate"]');
            const btn = document.getElementById('zkUseMarketRate');
            const note = document.getElementById('zkMarketNote');
            if (!code || !btn) return;

            code.setAttribute('list', 'zkCurrencyCodes');
            code.addEventListener('input', () => code.value = code.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 6));

            function fetchQuote(fillRate, fillMeta) {
                const c = code.value.trim();
                if (c.length < 2) { note.textContent = @json(__('Enter a currency code first (e.g. EUR, KES, BTC).')); return; }
                btn.disabled = true;
                note.textContent = @json(__('Fetching live rate...'));
                fetch(@json(route('admin.currency.market.quote')) + '?code=' + encodeURIComponent(c), {headers: {'Accept': 'application/json'}})
                    .then(r => r.ok ? r.json() : Promise.reject())
                    .then(q => {
                        if (fillMeta) {
                            if (q.name && !name.value) name.value = q.name;
                            if (q.symbol && !symbol.value) symbol.value = q.symbol;
                        }
                        if (q.rate === null) {
                            note.textContent = @json(__('No market rate found for this code. Enter the rate manually.'));
                            return;
                        }
                        const value = q.rate >= 1 ? Number(q.rate).toFixed(4).replace(/\.?0+$/, '') : Number(q.rate).toPrecision(6);
                        if (fillRate) rate.value = value;
                        note.textContent = `1 ${q.base} = ${value} ${q.code}` + (q.source ? ` · ${q.source}` : '');
                    })
                    .catch(() => note.textContent = @json(__('Could not reach the market data source. Try again or enter the rate manually.')))
                    .finally(() => btn.disabled = false);
            }

            btn.addEventListener('click', () => fetchQuote(true, true));
            // Picking a code from the list fills name/symbol/rate straight away on the add form.
            code.addEventListener('change', () => { if (!rate.value || rate.dataset.autofill !== 'off') fetchQuote(!rate.value, true); });
            rate.addEventListener('input', () => rate.dataset.autofill = 'off');
        })();
    </script>
@endpush
