@extends('admin.layouts.app')
@section('page_title', __('Manage Currency'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang("Dashboard")</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Manage Currency")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Manage Currency")</h1>
                </div>
                <div class="col-sm-auto d-flex flex-wrap gap-2">
                    <form action="{{ route('admin.currency.add.all') }}" method="post"
                          onsubmit="this.querySelector('button').disabled = true; this.querySelector('span').textContent = '@lang('Adding…')';">
                        @csrf
                        <button type="submit" class="btn btn-white" title="@lang('Adds every world currency (about 155) that is missing, priced at the live market rate. Existing ones are not changed.')">
                            <i class="bi-globe2 me-1"></i> <span>@lang('Add all world currencies')</span>
                        </button>
                    </form>
                    <a href="{{ route('admin.currency.create') }}" class="btn btn-primary">
                        <i class="bi-plus me-1"></i> @lang("Add Currency")
                    </a>
                </div>
            </div>
        </div>


        <div class="alert alert-soft-dark" role="alert">
            <div class="d-flex align-items-baseline">
                <div class="flex-shrink-0">
                    <i class="bi-info-circle me-1"></i>
                </div>

                <div class="flex-grow-1 ms-2">
                    @lang("To get started, please add your base currency. This is a required step, as the base currency acts as the default reference point for all exchange rate conversions across the multi-currency system.")
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="mb-1">@lang("Live market rates")</h5>
                    <p class="mb-0 text-body">@lang("Rates come from free market sources (ExchangeRate-API for money, CoinGecko for crypto). No API key needed.")</p>
                </div>
                <form action="{{ route('admin.currency.auto.toggle') }}" method="post" class="d-flex align-items-center gap-2">
                    @csrf
                    <input type="hidden" name="enabled" value="{{ basicControl()->auto_currency_update ? 0 : 1 }}">
                    <span class="badge {{ basicControl()->auto_currency_update ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }}">
                        @lang("Daily auto-update"): {{ basicControl()->auto_currency_update ? __('On') : __('Off') }}
                    </span>
                    <button type="submit" class="btn btn-sm btn-white">
                        {{ basicControl()->auto_currency_update ? __('Turn off') : __('Turn on') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-sm-0">
                    <h4 class="card-header-title">@lang("Currencies")</h4>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-primary" id="checkMarketRates"
                            data-bs-toggle="modal" data-bs-target="#marketRatesModal">
                        <i class="bi-graph-up-arrow me-1"></i> @lang("Check market rates")
                    </button>
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm w-100"
                                id="dropdownMenuClickable" data-bs-auto-close="false"
                                id="usersFilterDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <i class="bi-filter me-1"></i> @lang('Filter')
                        </button>
                        <div
                            class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered filter_dropdown"
                            aria-labelledby="dropdownMenuClickable">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h5 class="card-header-title">@lang('Filter Category')</h5>
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                            id="filter_close_btn">
                                        <i class="bi-x-lg"></i>
                                    </button>
                                </div>

                                <div class="card-body">
                                    <form action="" method="get">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <span class="text-cap text-body">@lang("Search")</span>
                                                <input type="text" class="form-control" name="search"
                                                       id="category_filter_input" value="{{ request()->search }}"
                                                       autocomplete="off">
                                            </div>

                                            <div class="col-sm-12 mb-5">
                                                <small class="text-cap text-body">@lang("Status")</small>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select"   name="status" autocomplete="off"
                                                            data-hs-tom-select-options='{
                                                              "placeholder": "Any status",
                                                              "hideSearch": true
                                                            }'>
                                                        <option value="">@lang("Any status")</option>
                                                        <option value="1"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                            @lang("Active")
                                                        </option>
                                                        <option value="0"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Inactive</span>'>
                                                            @lang("Inactive")
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" id="filter_button"
                                                    class="btn btn-primary">@lang('Apply')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="table-column-pe-0">
                            @lang("Sl")
                        </th>
                        <th>@lang('Name')</th>
                        <th>@lang('Conversion Rate')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody class="js-sortable">
                    @forelse($currencies as $key => $currency)
                        <tr>
                            <td class="table-column-pe-0">
                                {{ loopIndex($currencies) }}
                            </td>
                            <td>
                                <a class="d-flex align-items-center tr-href text-secondary" data-code="{{ $currency->id }}" href="javascript:void(0)">
                                    <div class="list-group-item">
                                        <i class="sortablejs-custom-handle bi-grip-horizontal list-group-icon"></i>
                                    </div>
                                    @lang($currency->name)
                                </a>
                            </td>
                            <td>{{ rtrim(rtrim(number_format((float) $currency->conversion_rate, $currency->conversion_rate >= 1 ? 4 : 10, '.', ','), '0'), '.') . ' ' . $currency->code }}</td>
                            <td>
                                <span
                                    class="badge bg-soft-{{ $currency->status == 1 ? 'success' : 'danger' }} text-{{ $currency->status == 1 ? 'success' : 'danger' }}">
                                    <span
                                        class="legend-indicator bg-{{ $currency->status == 1 ? 'success' : 'danger' }}">
                                    </span>
                                    {{ $currency->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.currency.edit', $currency->id) }}"
                                       class="btn btn-white btn-sm edit_user_btn">
                                        <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                    </a>
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                id="userEditDropdown" data-bs-toggle="dropdown"
                                                aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-end mt-1"
                                             aria-labelledby="userEditDropdown">
                                            <a class="dropdown-item status-change"
                                               href="javascript:void(0)" data-bs-toggle="modal"
                                               data-bs-target="#activateDeactivateModal"
                                               data-route="{{ route('admin.currency.status.change', $currency->id) }}"
                                               data-text="{{ $currency->status == 0 ? 'activate' : 'deactivate' }}"
                                               data-status="{{ $currency->status }}">
                                                <i class="fa-light {{ $currency->status == 1 ? 'fa-ban' : 'fa-square-check'}} dropdown-item-icon"></i>
                                                @lang("Mark As") {{ $currency->status == 0 ? 'Activate' : 'Deactivate' }}
                                            </a>
                                            <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                               data-bs-toggle="modal" data-bs-target="#deleteModal"
                                               data-route="{{ route('admin.currency.destroy', $currency->id) }}">
                                                <i class="fa-light fa-trash-can dropdown-item-icon"></i>
                                                @lang("Delete")
                                            </a>

                                            <a class="dropdown-item singleAutoUpdateBtn" href="javascript:void(0)"
                                               data-bs-toggle="modal" data-bs-target="#autoUpdateCurrencyModal"
                                               data-route="{{ route('admin.currency.single.auto.update') }}"
                                               data-currency="{{ $currency->code }}">
                                                <i class="fa-light fa-square-dollar dropdown-item-icon"></i>
                                                @lang("Auto Update Currency")
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="odd">
                            <td valign="top" colspan="8" class="dataTables_empty">
                                <div class="text-center p-4">
                                    <img class="mb-3 dataTables-image"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img class="mb-3 dataTables-image"
                                         src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang("No data to show")</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($currencies->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        <nav id="datatableWithFilterPagination" aria-label="Activity pagination">
                            {{ $currencies->appends($_GET)->links('admin.partials.pagination') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('admin.currency.components.delete_modal')
    @include('admin.currency.components.activate_deactivate')
    <div class="modal fade" id="marketRatesModal" tabindex="-1" aria-labelledby="marketRatesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form class="modal-content" action="{{ route('admin.currency.market.apply') }}" method="post" id="marketRatesForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="marketRatesLabel">@lang("Market rates") <small class="text-muted">(1 <span class="mr-base"></span> = ...)</small></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <div class="mr-loading text-center py-5"><div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 mb-0">@lang("Fetching live rates...")</p></div>
                    <div class="mr-error alert alert-soft-danger d-none"></div>
                    <div class="mr-content d-none">
                        <div class="table-responsive">
                            <table class="table table-sm table-align-middle mb-2">
                                <thead class="thead-light">
                                <tr>
                                    <th><input type="checkbox" class="form-check-input mr-all" checked aria-label="@lang('Select all')"></th>
                                    <th>@lang("Currency")</th>
                                    <th class="text-end">@lang("Your rate")</th>
                                    <th class="text-end">@lang("Market rate")</th>
                                    <th class="text-end">@lang("Change")</th>
                                </tr>
                                </thead>
                                <tbody class="mr-rows"></tbody>
                            </table>
                        </div>
                        <p class="small text-muted mb-0 mr-meta"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white mr-refresh">@lang("Refresh")</button>
                    <button type="submit" class="btn btn-primary mr-apply" disabled>@lang("Apply selected rates")</button>
                </div>
            </form>
        </div>
    </div>

    @include('admin.currency.components.auto_currency_update')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sortable.min.js') }}"></script>
@endpush


@push('script')
    <script>
        'use strict';
        (function () {
            const modal = document.getElementById('marketRatesModal');
            const fmt = n => n === null ? '—' : (n >= 1 ? Number(n).toLocaleString(undefined, {maximumFractionDigits: 4}) : Number(n).toPrecision(4));
            const esc = s => String(s).replace(/[&<>"]/g, c => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;'}[c]));
            const t = {
                base: @json(__('base')), notFound: @json(__('not found')), source: @json(__('Source')),
                updated: @json(__('Updated')), unreachable: @json(__('The market data source could not be reached. Please try again in a minute.')),
                failed: @json(__('Could not load market rates. Please try again.')), off: @json(__('off')),
            };

            function load(fresh) {
                modal.querySelector('.mr-loading').classList.remove('d-none');
                modal.querySelector('.mr-content').classList.add('d-none');
                modal.querySelector('.mr-error').classList.add('d-none');
                modal.querySelector('.mr-apply').disabled = true;

                fetch(@json(route('admin.currency.market.rates')) + (fresh ? '?fresh=1' : ''), {headers: {'Accept': 'application/json'}})
                    .then(r => r.ok ? r.json() : Promise.reject(r.status))
                    .then(data => {
                        modal.querySelector('.mr-base').textContent = data.base;
                        let rows = '';
                        let available = 0;
                        data.rows.forEach(row => {
                            const isBase = row.code.toUpperCase() === data.base;
                            const has = row.market !== null && !isBase;
                            if (has) available++;
                            const change = row.change === null ? '—'
                                : `<span class="${Math.abs(row.change) >= 5 ? 'text-danger fw-semibold' : 'text-muted'}">${row.change > 0 ? '+' : ''}${row.change}%</span>`;
                            rows += `<tr>
                                <td><input type="checkbox" class="form-check-input mr-pick" name="ids[]" value="${row.id}" ${has ? 'checked' : 'disabled'}></td>
                                <td><strong>${esc(row.code)}</strong> <span class="text-muted small">${esc(row.name || '')}</span>${row.active ? '' : ` <span class="badge bg-soft-secondary text-secondary">${t.off}</span>`}</td>
                                <td class="text-end">${fmt(row.current)}</td>
                                <td class="text-end fw-semibold">${has ? fmt(row.market) : (isBase ? t.base : t.notFound)}</td>
                                <td class="text-end">${has ? change : '—'}</td>
                            </tr>`;
                        });
                        modal.querySelector('.mr-rows').innerHTML = rows;
                        modal.querySelector('.mr-meta').textContent =
                            (data.sources.length ? t.source + ': ' + data.sources.join(' + ') : '') +
                            (data.updated_at ? ' · ' + t.updated + ': ' + data.updated_at : '');
                        modal.querySelector('.mr-loading').classList.add('d-none');
                        modal.querySelector('.mr-content').classList.remove('d-none');
                        modal.querySelector('.mr-apply').disabled = available === 0;
                        if (!data.sources.length) showError(t.unreachable);
                    })
                    .catch(() => showError(t.failed));
            }

            function showError(msg) {
                modal.querySelector('.mr-loading').classList.add('d-none');
                const box = modal.querySelector('.mr-error');
                box.textContent = msg;
                box.classList.remove('d-none');
            }

            modal.addEventListener('show.bs.modal', () => load(false));
            modal.querySelector('.mr-refresh').addEventListener('click', () => load(true));
            modal.addEventListener('change', e => {
                if (e.target.classList.contains('mr-all')) {
                    modal.querySelectorAll('.mr-pick:not(:disabled)').forEach(cb => cb.checked = e.target.checked);
                }
                modal.querySelector('.mr-apply').disabled = !modal.querySelector('.mr-pick:checked');
            });
        })();
    </script>

    <script>
        'use strict';
        $(document).on('click', '.status-change', function () {
            let route = $(this).data('route');
            $('.setRoute').attr('action', route);
            $('.modal-status-text').text($(this).data('text'));
            $('.status').val($(this).data('status'));
        });

        $(document).on('click', '.deleteBtn', function () {
            let route = $(this).data('route');
            $('.deleteRoute').attr('action', route);
        })

        $(document).on('click', '.all-auto-update-currency', function () {

            let route = $(this).data('route');
            $('.autoUpdateCurrency').attr('action', route);

            let text = $(this).data('text');
            $('.modal-text').text(text);
        })

        $(document).on('click', '.singleAutoUpdateBtn', function () {

            let route = $(this).data('route');
            $('.autoUpdateCurrency').attr('action', route);

            let currency = $(this).data('currency');
            $('#currency_code').val(currency);
        })

        HSCore.components.HSTomSelect.init('.js-select', {
            maxOptions: 250,
        })


        HSCore.components.HSSortable.init('.js-sortable')

        $(document).on('change', '.js-sortable', function () {

            let methods = [];
            $('.js-sortable tr td .tr-href').each(function (key, val) {
                let methodCode = $(val).data('code');
                methods.push(methodCode);
            });
            $.ajax({
                'url': "{{ route('admin.currency.sort.by') }}",
                'method': "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                'data': {sort: methods}
            })
        })
    </script>
@endpush


