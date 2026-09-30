{{--
    Shared Add / Edit service form, so both pages always look and behave the same.
    Expects: $categories, $apiProviders, optional $service (edit mode).
--}}
@php
    $svc = $service ?? null;
    $isApi = (int) old('manual_api', $svc && $svc->api_provider_id && $svc->api_service_id ? 1 : 0) === 1;
    // Refill select values: 1 = manual, 2 = automatic (API only), 3 = no refill.
    $refillNow = old('refill', $svc ? ($svc->refill ? ($svc->is_refill_automatic ? 2 : 1) : 3) : 3);
    $base = basicControl()->base_currency;
    $costNow = old('cost', $svc && $svc->original_price !== null && $svc->original_price !== '' ? (float) $svc->original_price : null);
@endphp

<div class="row mb-4 g-3">
    <div class="col-md-6">
        <label for="nameLabel" class="form-label">@lang('Name')</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               name="name" id="nameLabel" placeholder="@lang('e.g. Instagram Followers · Real · 10k/day')"
               autocomplete="off" value="{{ old('name', $svc->service_title ?? '') }}">
        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">@lang('Category')</label>
        <div class="tom-select-custom">
            <select class="js-select form-select" name="category" autocomplete="off"
                    data-hs-tom-select-options='{"placeholder": "Select category"}'>
                <option value="">@lang('Select category')</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category', $svc->category_id ?? null) == $category->id ? 'selected' : '' }}>{{ $category->category_title }}</option>
                @endforeach
            </select>
        </div>
        @error('category')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-6">
        <label for="minAmountLabel" class="form-label">@lang('Min Amount')</label>
        <input type="number" class="form-control @error('min_amount') is-invalid @enderror"
               name="min_amount" id="minAmountLabel" autocomplete="off"
               value="{{ old('min_amount', $svc->min_amount ?? 500) }}">
        @error('min_amount')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
    <div class="col-md-6">
        <label for="maxAmountLabel" class="form-label">@lang('Max Amount')</label>
        <input type="number" class="form-control @error('max_amount') is-invalid @enderror"
               name="max_amount" id="maxAmountLabel" autocomplete="off"
               value="{{ old('max_amount', $svc->max_amount ?? 5000) }}">
        @error('max_amount')<span class="invalid-feedback">{{ $message }}</span>@enderror
    </div>
</div>

<div class="row g-3 align-items-start">
    <div class="col-md-6">
        <label for="priceLabel" class="form-label">@lang('Price per 1,000')
            <small class="text-muted">(@lang('what customers pay'))</small></label>
        <div class="input-group">
            <input type="text" class="form-control @error('price') is-invalid @enderror"
                   name="price" id="priceLabel" placeholder="1.50" autocomplete="off" inputmode="decimal"
                   value="{{ old('price', $svc->price ?? '') }}">
            <span class="input-group-text">{{ $base }}</span>
        </div>
        <small class="text-muted">@lang('Example: 1.50 means 1,000 followers cost 1.50') {{ $base }}</small>
        @error('price')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-md-3">
        <label class="row form-check form-switch mt-md-4" for="serviceStatus">
            <span class="col-8 ms-0"><span class="d-block text-dark">@lang('Active')</span>
                <small class="text-muted">@lang('Visible to customers')</small></span>
            <span class="col-4 text-end">
                <input type="hidden" value="0" name="status">
                <input class="form-check-input" type="checkbox" name="status" id="serviceStatus" value="1"
                    {{ (int) old('status', $svc->service_status ?? 1) === 1 ? 'checked' : '' }}>
            </span>
        </label>
    </div>
    <div class="col-md-3">
        <label class="row form-check form-switch mt-md-4" for="dripFeed">
            <span class="col-8 ms-0"><span class="d-block text-dark">@lang('Drip Feed')</span>
                <small class="text-muted">@lang('Deliver in runs')</small></span>
            <span class="col-4 text-end">
                <input type="hidden" value="0" name="drip_feed">
                <input class="form-check-input" type="checkbox" name="drip_feed" id="dripFeed" value="1"
                    {{ (int) old('drip_feed', $svc->drip_feed ?? 0) === 1 ? 'checked' : '' }}>
            </span>
        </label>
    </div>
</div>

<div class="text-start mt-5">
    <ul class="nav nav-segment mb-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $isApi ? '' : 'active' }}" id="nav-one-eg1-tab" href="#" data-zk-mode="0"
               data-bs-toggle="pill" role="tab">@lang('Manual')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $isApi ? 'active' : '' }}" id="nav-two-eg2-tab" href="#" data-zk-mode="1"
               data-bs-toggle="pill" role="tab">@lang('From API provider')</a>
        </li>
    </ul>
</div>
<input class="manual_api" type="hidden" name="manual_api" value="{{ $isApi ? 1 : 0 }}">

<div class="row g-3 moreField {{ $isApi ? '' : 'd-none' }}">
    <div class="col-md-6">
        <label class="form-label">@lang('API provider')</label>
        <div class="tom-select-custom">
            <select class="js-select form-select" name="api_provider_id" autocomplete="off"
                    data-hs-tom-select-options='{"placeholder": "Select API Provider"}'>
                <option value="">@lang('Select API Provider')</option>
                @foreach($apiProviders as $apiProvider)
                    <option value="{{ $apiProvider->id }}" {{ old('api_provider_id', $svc->api_provider_id ?? null) == $apiProvider->id ? 'selected' : '' }}>{{ $apiProvider->api_name }}</option>
                @endforeach
            </select>
        </div>
        @error('api_provider_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-md-6">
        <label for="apiServiceIdLabel" class="form-label">@lang('API Service ID')</label>
        <input type="text" class="form-control @error('api_service_id') is-invalid @enderror"
               name="api_service_id" id="apiServiceIdLabel" placeholder="@lang('The service number on the provider, e.g. 1024')"
               autocomplete="off" value="{{ old('api_service_id', $svc->api_service_id ?? '') }}">
        @error('api_service_id')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-12">
        <small class="text-muted"><i class="bi-info-circle"></i>
            @lang('Your cost is read from the provider automatically when you save.')
            @if($svc && $isApi && $costNow !== null)
                @lang('Current provider cost'): <strong>{{ zkPerK($costNow) }}</strong> / 1k
            @endif
        </small>
    </div>
</div>

<div class="row g-3 mt-1 manualCost {{ $isApi ? 'd-none' : '' }}">
    <div class="col-md-6">
        <label for="costLabel" class="form-label">@lang('Your cost per 1,000') <small class="text-muted">(@lang('optional'))</small></label>
        <div class="input-group">
            <input type="text" class="form-control @error('cost') is-invalid @enderror" name="cost" id="costLabel"
                   placeholder="0.80" autocomplete="off" inputmode="decimal" value="{{ $costNow }}">
            <span class="input-group-text">{{ $base }}</span>
        </div>
        <small class="text-muted">@lang('What this service costs you. Used only to show your profit.')</small>
        @error('cost')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

<div class="alert alert-soft-secondary align-items-center gap-2 mt-3 mb-0 py-2 d-none" id="zkProfit">
    <i class="bi-graph-up-arrow"></i><span></span>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-6">
        <label for="refill" class="form-label">@lang('Refill')</label>
        <select class="form-select" name="refill" id="refill" autocomplete="off">
            <option value="1" {{ $refillNow == 1 ? 'selected' : '' }}>@lang('Manual: customers request, you handle it')</option>
            <option value="2" class="automatic" {{ $refillNow == 2 ? 'selected' : '' }}>@lang('Automatic: the provider refills')</option>
            <option value="3" {{ $refillNow == 3 ? 'selected' : '' }}>@lang('No refill')</option>
        </select>
        @error('refill')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
    <div class="col-12">
        <label class="form-label" for="description">@lang('Description') <small class="text-muted">(@lang('optional'))</small></label>
        <textarea id="description" class="form-control" name="description" rows="3"
                  placeholder="@lang('e.g. Start: 0-1h · Speed: 5k/day · Refill: 30 days')">{{ old('description', $svc->description ?? '') }}</textarea>
        @error('description')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
    </div>
</div>

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(function () {
            HSCore.components.HSTomSelect.init('.js-select', {maxOptions: 1000});

            const $refill = $('#refill');
            const autoOpt = $refill.find('option.automatic');
            if ($('.manual_api').val() !== '1') autoOpt.detach();
            const base = @json($base);
            const apiCost = @json($isApi ? $costNow : null);

            function setMode(api) {
                $('.manual_api').val(api ? 1 : 0);
                $('.moreField').toggleClass('d-none', !api);
                $('.manualCost').toggleClass('d-none', api);
                // "Automatic" refill only makes sense for provider services.
                if (api) {
                    if (!$refill.find('option.automatic').length) autoOpt.insertAfter($refill.find('option[value=1]'));
                } else {
                    if ($refill.val() === '2') $refill.val('3');
                    $refill.find('option.automatic').detach();
                }
                profit();
            }

            function profit() {
                const api = $('.manual_api').val() === '1';
                const sell = parseFloat($('#priceLabel').val());
                const cost = api ? parseFloat(apiCost) : parseFloat($('#costLabel').val());
                const box = $('#zkProfit');
                if (!(sell > 0) || !(cost > 0)) { box.addClass('d-none').removeClass('d-flex'); return; }
                const p = sell - cost, m = p / cost * 100;
                box.removeClass('d-none').addClass('d-flex')
                    .toggleClass('alert-soft-success', p >= 0).toggleClass('alert-soft-danger', p < 0)
                    .find('span').text(p >= 0
                        ? '@lang('Profit')' + ': ' + p.toFixed(4).replace(/\.?0+$/, '') + ' ' + base + ' / 1k (' + m.toFixed(0) + '% @lang('margin'))'
                        : '@lang('Loss')' + ': ' + p.toFixed(4).replace(/\.?0+$/, '') + ' ' + base + ' / 1k. @lang('Raise the price')');
            }

            $('[data-zk-mode]').on('click', function (e) { e.preventDefault(); setMode($(this).data('zk-mode') == 1); });
            $('#priceLabel, #costLabel').on('input', profit);
            profit();
        });
    </script>
@endpush
