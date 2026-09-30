{{-- Quick import: pick platforms + what to sell, we choose the best services and file them into clean categories. --}}
@php
    $zqTypeIcons = [
        'Followers' => 'bi-people', 'Likes' => 'bi-heart', 'Views' => 'bi-eye', 'Comments' => 'bi-chat-dots',
        'Shares' => 'bi-share', 'Subscribers' => 'bi-person-plus', 'Members' => 'bi-people-fill', 'Reactions' => 'bi-emoji-smile',
        'Story Views' => 'bi-circle', 'Live Stream' => 'bi-broadcast', 'Saves' => 'bi-bookmark', 'Votes' => 'bi-check2-square',
        'Traffic' => 'bi-globe2', 'Other' => 'bi-three-dots',
    ];
    $zqDefaultPlatforms = array_slice(array_keys(array_diff_key($smartMatrix, ['Other' => 1])), 0, 3);
@endphp

<div class="card mb-4 zq" id="zqCard">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="card-title mb-1"><i class="bi-magic text-primary"></i> @lang('Quick import') <span class="badge bg-soft-success text-success ms-1">@lang('Recommended')</span></h4>
            <p class="card-text text-muted small mb-0">@lang('Pick platforms and what to sell. We choose the best-value services, clean up their names and put them in simple categories like') “Instagram Followers”.</p>
        </div>
    </div>
    <div class="card-body">
        <form id="zqForm" action="{{ route('admin.api.service.smart.import') }}" method="post">
            @csrf
            <input type="hidden" name="provider" value="{{ $apiProvider->id }}">

            <div class="zq-step">
                <span class="zq-step__n">1</span>
                <div class="flex-grow-1">
                    <h5 class="mb-2">@lang('Platforms')</h5>
                    <div class="zq-chips" id="zqPlatforms">
                        @foreach($smartMatrix as $platform => $types)
                            <label class="zq-chip">
                                <input type="checkbox" name="platforms[]" value="{{ $platform }}" @checked(in_array($platform, $zqDefaultPlatforms, true))>
                                <span>{{ $platform }} <small>{{ number_format(array_sum($types)) }}</small></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="zq-step">
                <span class="zq-step__n">2</span>
                <div class="flex-grow-1">
                    <h5 class="mb-2">@lang('What do you want to sell?')</h5>
                    <div class="zq-chips" id="zqTypes"></div>
                    <small class="text-muted">@lang('Numbers show how many provider services match your platforms.')</small>
                </div>
            </div>

            <div class="zq-step">
                <span class="zq-step__n">3</span>
                <div class="flex-grow-1">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="zqPer">@lang('Services per type')</label>
                            <select class="form-select" name="per_type" id="zqPer">
                                @foreach([1, 2, 3, 5, 10] as $n)
                                    <option value="{{ $n }}" @selected($n === 3)>{{ $n }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">@lang('3 gives customers a cheap, a mid and a refill option.')</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="zqStrategy">@lang('Choose by')</label>
                            <select class="form-select" name="strategy" id="zqStrategy">
                                <option value="best" selected>@lang('Best mix: cheapest + a refill option')</option>
                                <option value="cheapest">@lang('Cheapest only')</option>
                                <option value="refill">@lang('Refill only (fewer complaints)')</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="zqMarkup">@lang('Your profit')</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="markup" id="zqMarkup" value="50" min="0" max="1000" step="1">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="d-flex gap-1 mt-1">
                                @foreach([30, 50, 100, 200] as $m)
                                    <button type="button" class="btn btn-xs btn-white zq-mk" data-mk="{{ $m }}">{{ $m }}%</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="clean" value="1" id="zqClean" checked>
                        <label class="form-check-label" for="zqClean">@lang('Clean service names') <small class="text-muted">(@lang('removes emojis and fancy letters, e.g.') “𝗥𝗘𝗙𝗜𝗟𝗟 👍” → “REFILL”)</small></label>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                <button type="button" class="btn btn-primary" id="zqPreviewBtn"><i class="bi-eye"></i> @lang('Preview services')</button>
                <span class="text-muted small" id="zqHint"></span>
            </div>

            <div id="zqPreview" class="mt-4" hidden>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                    <h5 class="mb-0" id="zqSummary"></h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="zqAll" checked>
                        <label class="form-check-label" for="zqAll">@lang('Select all')</label>
                    </div>
                </div>
                <div class="table-responsive zq-table">
                    <table class="table table-sm table-align-middle mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th></th>
                            <th>@lang('Service (as customers will see it)')</th>
                            <th class="text-end">@lang('Min / Max')</th>
                            <th class="text-end">@lang('Your cost')</th>
                            <th class="text-end">@lang('Customer pays')</th>
                            <th class="text-end">@lang('Profit / 1k')</th>
                        </tr>
                        </thead>
                        <tbody id="zqRows"></tbody>
                    </table>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                    <button type="submit" class="btn btn-success" id="zqImportBtn"><i class="bi-cloud-download"></i> <span>@lang('Import selected')</span></button>
                    <small class="text-muted">@lang('Already imported services are skipped automatically. You can edit prices later.')</small>
                </div>
            </div>
        </form>
    </div>
</div>

@push('css')
    <style>
        .zq-step { display: flex; gap: 14px; padding: 16px 0; border-bottom: 1px dashed var(--bs-border-color, #e7eaf3); }
        .zq-step:last-of-type { border-bottom: 0; }
        .zq-step__n { flex: none; width: 28px; height: 28px; border-radius: 50%; display: grid; place-items: center; background: var(--bs-primary, #377dff); color: #fff; font-weight: 700; font-size: 14px; }
        .zq-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 6px; }
        .zq-chip { position: relative; margin: 0; cursor: pointer; }
        .zq-chip input { position: absolute; opacity: 0; pointer-events: none; }
        .zq-chip span { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 999px; border: 1px solid var(--bs-border-color, #e7eaf3); font-weight: 600; font-size: 14px; transition: .15s; user-select: none; }
        .zq-chip span small { font-weight: 600; opacity: .6; }
        .zq-chip input:checked + span { background: var(--bs-primary, #377dff); border-color: var(--bs-primary, #377dff); color: #fff; }
        .zq-chip input:focus-visible + span { outline: 3px solid rgba(55, 125, 255, .35); outline-offset: 2px; }
        .zq-chip.is-empty { display: none; }
        .zq-table { max-height: 520px; overflow: auto; border: 1px solid var(--bs-border-color, #e7eaf3); border-radius: 10px; }
        .zq-table thead th { position: sticky; top: 0; z-index: 1; }
        .zq-table td.zq-name { min-width: 280px; white-space: normal; }
        .zq-table td.zq-name small { display: block; color: #8c98a4; max-width: 520px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .btn-xs { padding: 2px 8px; font-size: 12px; }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            const matrix = @json($smartMatrix);
            const icons = @json($zqTypeIcons);
            const form = document.getElementById('zqForm');
            const typesBox = document.getElementById('zqTypes');
            const previewUrl = @json(route('admin.api.service.smart.preview'));
            const defaultTypes = ['Followers', 'Likes', 'Views'];
            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]));
            let firstRender = true;

            function chosenPlatforms() {
                return [...form.querySelectorAll('input[name="platforms[]"]:checked')].map(i => i.value);
            }

            function renderTypes() {
                const keep = new Set([...typesBox.querySelectorAll('input:checked')].map(i => i.value));
                const counts = {};
                chosenPlatforms().forEach(p => Object.entries(matrix[p] || {}).forEach(([t, n]) => counts[t] = (counts[t] || 0) + n));
                const order = Object.keys(counts).sort((a, b) => counts[b] - counts[a]);
                typesBox.innerHTML = order.length ? order.map(t => {
                    const on = firstRender ? defaultTypes.includes(t) : keep.has(t);
                    return '<label class="zq-chip"><input type="checkbox" name="types[]" value="' + esc(t) + '"' + (on ? ' checked' : '') + '>' +
                        '<span><i class="' + esc(icons[t] || 'bi-tag') + '"></i> ' + esc(t) + ' <small>' + counts[t].toLocaleString() + '</small></span></label>';
                }).join('') : '<span class="text-muted small">' + esc(@json(__('Pick a platform first.'))) + '</span>';
                firstRender = false;
                hidePreview();
            }

            function hidePreview() {
                document.getElementById('zqPreview').hidden = true;
                document.getElementById('zqHint').textContent = '';
            }

            async function preview() {
                const btn = document.getElementById('zqPreviewBtn');
                const hint = document.getElementById('zqHint');
                const fd = new FormData(form);
                fd.delete('ids[]');
                if (!fd.getAll('platforms[]').length) { hint.textContent = @json(__('Pick at least one platform.')); return; }
                if (!fd.getAll('types[]').length) { hint.textContent = @json(__('Pick at least one thing to sell.')); return; }
                btn.disabled = true;
                hint.innerHTML = '<span class="spinner-border spinner-border-sm"></span> ' + esc(@json(__('Choosing the best services… (the first time can take up to a minute)')));
                try {
                    const res = await fetch(previewUrl, {method: 'POST', body: fd, headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
                    const d = await res.json();
                    if (!res.ok) throw new Error(d.message || Object.values(d.errors || {}).flat()[0] || 'Error');
                    showRows(d);
                    hint.textContent = '';
                } catch (e) {
                    hint.textContent = e.message;
                } finally {
                    btn.disabled = false;
                }
            }

            function showRows(d) {
                const tbody = document.getElementById('zqRows');
                if (!d.rows.length) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">' + esc(@json(__('Nothing new to import: everything matching is already imported.'))) + '</td></tr>';
                } else {
                    let last = '';
                    tbody.innerHTML = d.rows.map(r => {
                        const head = r.category !== last ? '<tr class="table-light"><td colspan="6" class="fw-semibold">' + esc(r.category) + '</td></tr>' : '';
                        last = r.category;
                        return head + '<tr>' +
                            '<td><input class="form-check-input zq-pick" type="checkbox" name="ids[]" value="' + esc(r.id) + '" checked></td>' +
                            '<td class="zq-name">' + esc(r.name) + (r.refill ? ' <span class="badge bg-soft-success text-success">Refill</span>' : '') +
                                '<small title="' + esc(r.original) + '">#' + esc(r.id) + ' · ' + esc(r.original) + '</small></td>' +
                            '<td class="text-end text-nowrap">' + esc(r.min) + ' / ' + esc(r.max) + '</td>' +
                            '<td class="text-end text-muted text-nowrap">' + esc(r.cost) + '</td>' +
                            '<td class="text-end fw-semibold text-nowrap">' + esc(r.sell) + '</td>' +
                            '<td class="text-end text-success text-nowrap">+' + esc(r.profit) + '</td></tr>';
                    }).join('');
                }
                document.getElementById('zqPreview').hidden = false;
                document.getElementById('zqAll').checked = true;
                updateCount(d.categories);
                document.getElementById('zqPreview').scrollIntoView({behavior: 'smooth', block: 'start'});
            }

            let catCount = 0;
            function updateCount(cats) {
                if (cats !== undefined) catCount = cats;
                const n = form.querySelectorAll('.zq-pick:checked').length;
                document.getElementById('zqSummary').textContent = n + ' ' + @json(__('services ready')) + ' · ' + catCount + ' ' + @json(__('categories'));
                const btn = document.getElementById('zqImportBtn');
                btn.disabled = n === 0;
                btn.querySelector('span').textContent = @json(__('Import')) + ' ' + n + ' ' + @json(__('services'));
            }

            document.getElementById('zqPlatforms').addEventListener('change', renderTypes);
            typesBox.addEventListener('change', hidePreview);
            ['zqPer', 'zqStrategy', 'zqMarkup'].forEach(id => document.getElementById(id).addEventListener('change', hidePreview));
            document.querySelectorAll('.zq-mk').forEach(b => b.addEventListener('click', () => { document.getElementById('zqMarkup').value = b.dataset.mk; hidePreview(); }));
            document.getElementById('zqPreviewBtn').addEventListener('click', preview);
            document.getElementById('zqAll').addEventListener('change', function () {
                form.querySelectorAll('.zq-pick').forEach(c => c.checked = this.checked);
                updateCount();
            });
            document.getElementById('zqRows').addEventListener('change', () => updateCount());
            form.addEventListener('submit', function () {
                const btn = document.getElementById('zqImportBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> ' + esc(@json(__('Importing…')));
            });
            renderTypes();

            // The advanced table starts collapsed; fix its column widths when opened.
            document.querySelector('.zq-advanced')?.addEventListener('toggle', function () {
                if (this.open && window.jQuery && $.fn.dataTable && $.fn.dataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable().columns.adjust();
                }
            });
        })();
    </script>
@endpush
