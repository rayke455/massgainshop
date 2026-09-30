{{-- Simple way to import: tick whole categories from the provider instead of single services. --}}
@php
    $zkPlatforms = collect($providerCategories)->groupBy('platform')->map->count()->sortDesc();
@endphp
<div class="card mb-4 zk-catimport">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="card-header-title mb-1"><i class="bi-collection me-1"></i> @lang('Import by category')</h4>
            <p class="text-body mb-0 small">@lang('Tick the categories you want to sell. All their services are imported and appear for customers under the right platform.')</p>
        </div>
        <span class="badge bg-soft-primary text-primary">{{ count($providerCategories) }} @lang('categories') · {{ collect($providerCategories)->sum('count') }} @lang('services')</span>
    </div>

    <form action="{{ route('admin.api.service.import.multiple', ['provider' => $apiProvider->id]) }}" method="post" id="zkCatImport">
        @csrf
        <input type="hidden" name="provider" value="{{ $apiProvider->id }}">
        <input type="hidden" name="import_quantity" value="categories">

        <div class="card-body pb-2">
            <div class="d-flex flex-wrap gap-2 mb-3" role="group" aria-label="@lang('Platforms')">
                <button type="button" class="btn btn-sm btn-soft-primary zk-pf active" data-platform="">@lang('All')</button>
                @foreach($zkPlatforms as $platform => $n)
                    <button type="button" class="btn btn-sm btn-white zk-pf" data-platform="{{ $platform }}">{{ $platform }} <span class="text-muted">{{ $n }}</span></button>
                @endforeach
            </div>
            <input type="search" class="form-control mb-2" id="zkCatSearch" placeholder="@lang('Search categories, e.g. instagram followers')" autocomplete="off">

            <div class="zk-catlist">
                @foreach($providerCategories as $cat)
                    <label class="zk-catrow" data-platform="{{ $cat['platform'] }}" data-search="{{ strtolower($cat['title'] . ' ' . $cat['platform']) }}" data-min="{{ $cat['min_rate'] }}">
                        <input type="checkbox" class="form-check-input" name="categories[]" value="{{ $cat['title'] }}" data-count="{{ $cat['count'] - $cat['imported'] }}"
                            {{ $cat['imported'] >= $cat['count'] ? 'disabled' : '' }}>
                        <span class="zk-catrow__title">{{ $cat['title'] }}</span>
                        <span class="badge bg-soft-secondary text-secondary">{{ $cat['platform'] }}</span>
                        <span class="zk-catrow__meta">{{ $cat['count'] }} @lang('services') · @lang('cost from') {{ zkPerK($cat['min_rate'], $apiProvider->currency ?: 'USD') }}/1k</span>
                        <span class="zk-catrow__earn"></span>
                        @if($cat['imported'])
                            <span class="badge bg-soft-success text-success">{{ $cat['imported'] >= $cat['count'] ? __('All imported') : $cat['imported'] . ' ' . __('imported') }}</span>
                        @endif
                    </label>
                @endforeach
                <p class="text-muted text-center py-4 mb-0 d-none" id="zkCatEmpty">@lang('No categories match your search.')</p>
            </div>
        </div>

        <div class="card-footer d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <label for="zkMarkup" class="mb-0 text-nowrap">@lang('Your profit')</label>
                <div class="input-group input-group-sm" style="width: 120px">
                    <input type="number" class="form-control" id="zkMarkup" name="price_percentage_increase" value="30" min="0" max="1000" step="1">
                    <span class="input-group-text">%</span>
                </div>
                <small class="text-muted d-none d-md-inline">@lang('added on top of the provider price')</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-body small" id="zkCatSummary">@lang('Nothing selected')</span>
                <button type="submit" class="btn btn-primary" id="zkCatSubmit" disabled>
                    <i class="bi-download me-1"></i> @lang('Import')
                </button>
            </div>
        </div>
    </form>
</div>

@push('style')
    <style>
        .zk-catlist { max-height: 420px; overflow-y: auto; border: 1px solid rgba(231, 234, 243, .9); border-radius: 12px; }
        .zk-catrow { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 12px; padding: 10px 14px; margin: 0; border-bottom: 1px solid rgba(231, 234, 243, .7); cursor: pointer; }
        .zk-catrow:last-of-type { border-bottom: 0; }
        .zk-catrow:hover { background: rgba(55, 125, 255, .04); }
        .zk-catrow:has(input:checked) { background: rgba(55, 125, 255, .08); }
        .zk-catrow:has(input:disabled) { opacity: .6; cursor: default; }
        .zk-catrow__title { flex: 1 1 280px; font-weight: 600; color: #1e2022; min-width: 0; }
        .zk-catrow__meta { color: #677788; font-size: 12.5px; white-space: nowrap; }
        .zk-catrow__earn { color: #00a86b; font-size: 12.5px; font-weight: 600; white-space: nowrap; }
        .zk-pf.active { background: #377dff; color: #fff; border-color: #377dff; }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            const form = document.getElementById('zkCatImport');
            const rows = [...form.querySelectorAll('.zk-catrow')];
            const search = document.getElementById('zkCatSearch');
            const summary = document.getElementById('zkCatSummary');
            const submit = document.getElementById('zkCatSubmit');
            let platform = '';

            function filter() {
                const q = search.value.trim().toLowerCase().split(/\s+/).filter(Boolean);
                let shown = 0;
                rows.forEach(r => {
                    const ok = (!platform || r.dataset.platform === platform) && q.every(w => r.dataset.search.includes(w));
                    r.classList.toggle('d-none', !ok);
                    if (ok) shown++;
                });
                document.getElementById('zkCatEmpty').classList.toggle('d-none', shown > 0);
            }

            const cur = @json($apiProvider->currency ?: 'USD');
            const fx = {{ (float) ($apiProvider->conversion_rate ?: 1) }};
            const markup = document.getElementById('zkMarkup');
            const fmt = n => { const v = Math.round(n * 10000) / 10000; let d = 4; while (d > 2 && Math.round(v * 10 ** (d - 1)) / 10 ** (d - 1) === v) d--; return v.toFixed(d); };
            function earnings() {
                const pct = parseFloat(markup.value) || 0;
                rows.forEach(r => {
                    const cost = parseFloat(r.dataset.min) || 0;
                    r.querySelector('.zk-catrow__earn').textContent =
                        `${@json(__('you sell from'))} ${fmt(cost * (1 + pct / 100) * fx)} · +${fmt(cost * pct / 100 * fx)} ${cur}`;
                });
            }
            markup.addEventListener('input', () => { earnings(); update(); });
            earnings();

            function update() {
                const picked = form.querySelectorAll('input[name="categories[]"]:checked');
                const services = [...picked].reduce((n, c) => n + (+c.dataset.count || 0), 0);
                const pct = parseFloat(markup.value) || 0;
                const costs = [...picked].map(c => parseFloat(c.closest('.zk-catrow').dataset.min) || 0).sort((a, b) => a - b);
                const typical = costs.length ? costs[Math.floor(costs.length / 2)] : 0;
                summary.innerHTML = picked.length
                    ? `${picked.length} ${@json(__('categories'))} · ${services} ${@json(__('services to import'))}<br><span class="text-success fw-semibold">${@json(__('You earn'))} ${pct}% · ${@json(__('e.g.'))} +${fmt(typical * pct / 100 * fx)} ${cur} ${@json(__('per 1000 on a'))} ${fmt(typical * fx)} ${cur} ${@json(__('service'))}</span>`
                    : @json(__('Nothing selected'));
                submit.disabled = !picked.length;
                submit.innerHTML = '<i class="bi-download me-1"></i> ' + (picked.length ? `${@json(__('Import'))} ${services}` : @json(__('Import')));
            }

            form.querySelectorAll('.zk-pf').forEach(b => b.addEventListener('click', () => {
                form.querySelectorAll('.zk-pf').forEach(x => x.classList.toggle('active', x === b));
                platform = b.dataset.platform;
                filter();
            }));
            search.addEventListener('input', filter);
            form.addEventListener('change', update);
            form.addEventListener('submit', () => {
                submit.disabled = true;
                submit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + @json(__('Importing...'));
            });
        })();
    </script>
@endpush
