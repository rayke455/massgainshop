@extends('admin.layouts.app')
@section('page_title', __('Edit Payment Method'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Payment Method')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit ' . $method->name)</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit ' . $method->name)</h1>
                </div>
            </div>
        </div>

        @if($method->code === 'mpesa')
            <div class="card mb-4">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="card-header-title mb-0"><i class="bi-phone me-1"></i> @lang('M-Pesa setup check')
                        <span class="badge {{ $method->environment == 'live' ? 'bg-success' : 'bg-warning text-dark' }} ms-2">{{ $method->environment == 'live' ? __('LIVE') : __('SANDBOX') }}</span></h4>
                    <form action="{{ route('admin.payment.method.mpesa.test', $method->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi-activity me-1"></i> @lang('Test M-Pesa connection')</button>
                    </form>
                </div>
                <div class="card-body">
                    @if(session('mpesa_findings'))
                        <ul class="list-unstyled mb-4">
                            @foreach(session('mpesa_findings') as [$level, $message])
                                <li class="alert alert-soft-{{ $level }} py-2 mb-2">
                                    <i class="bi-{{ ['success' => 'check-circle', 'warning' => 'exclamation-triangle', 'danger' => 'x-circle'][$level] ?? 'info-circle' }} me-1"></i>
                                    {{ $message }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <p class="mb-2"><strong>@lang('To receive real STK pushes:')</strong></p>
                    <ol class="mb-2">
                        <li>@lang('Get a Paybill or Till number from Safaricom (M-Pesa for Business).')</li>
                        <li>@lang('On developer.safaricom.co.ke create an app with "M-Pesa Express", then click "Go Live" and link your Paybill/Till.')</li>
                        <li>@lang('Paste the LIVE Consumer Key, Consumer Secret, your Paybill/Till as Short code and the Passkey Safaricom emails you.')</li>
                        <li>@lang('Set Mode to "Live (production)" below and click Save. Then click "Test M-Pesa connection".')</li>
                    </ol>
                    <p class="mb-0 text-muted small">
                        @lang('Confirmation URL (set automatically):') <code>{{ route('mpesa.webhook') }}</code>.
                        @lang('Sandbox/test mode never sends prompts to real phones.')
                    </p>
                </div>
            </div>
        @endif

        @if($method->code === 'snippe')
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="card-header-title mb-0"><i class="bi-phone me-1"></i> @lang('Snippe setup (Tanzania mobile money)')</h4>
                </div>
                <div class="card-body">
                    <ol class="mb-2">
                        <li>@lang('Create or log in to your account at') <a href="https://snippe.sh/dashboard" target="_blank" rel="noopener">snippe.sh/dashboard</a>.</li>
                        <li>@lang('Copy your API key (starts with snp_) into "Api Key" below.')</li>
                        <li>@lang('Optional but recommended: Settings → Webhook Secret (starts with whsec_) into "Webhook Secret".')</li>
                        <li>@lang('Switch the method on and Save. Customers pay in TZS (minimum 500 TZS) with M-Pesa, Airtel Money, Mixx by Yas or Halotel.')</li>
                    </ol>
                    <p class="mb-0 text-muted small">
                        @lang('Payment notifications are sent to') <code>{{ preg_replace('#^http://(?!localhost|127\.0\.0\.1)#', 'https://', route('snippe.webhook')) }}</code>
                        @lang('automatically with each payment. Every payment is also double-checked with Snippe before the balance is credited, so deposits still confirm on a local test site.')
                    </p>
                </div>
            </div>
        @endif

        <div class="row payment_method">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title mt-2">@lang('Edit ' . $method->name)</h3>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.update.payment.methods', $method->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel"
                                               placeholder="Name" aria-label="Name" autocomplete="off"
                                               value="{{ old('name', $method->name ?? '') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if($method->currencies)
                                        <div class="col-sm-6">
                                            <label for="currencyLabel"
                                                   class="form-label">@lang('Supported Currency')</label>
                                            <div class="tom-select-custom tom-select-custom-with-tags">
                                                <select class="js-select form-select supported_currency"
                                                        name="receivable_currencies[][name]" autocomplete="off"
                                                        multiple
                                                        data-hs-tom-select-options='{
                                                        "placeholder": "Select Currency",
                                                      }' required>
                                                    @php
                                                        $paymentMethodsCurrency = session()->has('selectedCurrencyList')
                                                            ? session('selectedCurrencyList')
                                                            : (isset($method->supported_currency) ? $method->supported_currency : []);
                                                    @endphp
                                                    @forelse($method->currencies as $key => $currency)
                                                        @foreach($currency as $curKey => $singleCurrency)
                                                            @php
                                                                $isSelected = in_array($curKey, (array) $paymentMethodsCurrency);
                                                            @endphp
                                                            <option
                                                                value="{{ $curKey }}" {{ $isSelected ? 'selected' : '' }}>
                                                                {{ trans($curKey) }}
                                                            </option>
                                                        @endforeach
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                            @error('receivable_currencies.0.name')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                </div>


                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-header-title">@lang('Parameters')</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($method->parameters as $key => $parameter)
                                                <div class="col-sm-6 mb-4">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                               for="{{ $key }}">@lang(snake2Title($key))</label>
                                                        <input type="text" name="{{ $key }}"
                                                               value="{{ old($key, $parameter) }}"
                                                               id="{{ $key }}"
                                                               class="form-control @error($key) is-invalid @enderror">
                                                        <div class="invalid-feedback">
                                                            @error($key) @lang($message) @enderror
                                                        </div>
                                                        @if($method->code === 'mpesa')
                                                            @php
                                                                $zkHelp = [
                                                                    'consumer_key' => __('From your Daraja app (use the LIVE app for real payments).'),
                                                                    'consumer_secret' => __('From the same Daraja app.'),
                                                                    'short_code' => __('Paybill number, or for a Till: the Store/Head-office number Safaricom gave you.'),
                                                                    'pass_key' => __('The Lipa na M-Pesa Online passkey Safaricom emails after Go-Live.'),
                                                                    'till_number' => __('Only if customers pay to a Till (Buy Goods). Leave empty for a Paybill.'),
                                                                    'webhook' => __('Leave empty. It is set automatically to') . ' ' . route('mpesa.webhook'),
                                                                ];
                                                            @endphp
                                                            <small class="text-muted">{{ $zkHelp[$key] ?? '' }}</small>
                                                        @endif
                                                        @if($method->code === 'snippe')
                                                            <small class="text-muted">{{ [
                                                                'api_key' => __('From snippe.sh/dashboard. Starts with snp_.'),
                                                                'webhook_secret' => __('Settings → Webhook Secret (whsec_…). Optional: used to verify notifications.'),
                                                            ][$key] ?? '' }}</small>
                                                        @endif
                                                        @if($method->code === 'cryptomus')
                                                            <small class="text-muted">{{ [
                                                                'mercent_id' => __('Your Cryptomus Merchant ID (from Cryptomus Dashboard → Profile/Merchant Settings).'),
                                                                'api_key' => __('Your Cryptomus Payment API Key (Generated under Integration/API in your Cryptomus account).'),
                                                            ][$key] ?? '' }}</small>
                                                        @endif
                                                        @if($method->code === 'payzink')
                                                            <small class="text-muted">{{ [
                                                                'publishable_key' => __('Your Payzink Publishable Key (from Payzink Console console.payzink.com → API Keys).'),
                                                                'secret_key' => __('Your Payzink Secret Key (from Payzink Console console.payzink.com → API Keys).'),
                                                                'environment' => __('Set to "live" for real payments, or "sandbox" for test mode.'),
                                                            ][$key] ?? '' }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($method->extra_parameters)
                                            <div class="row">
                                                @foreach($method->extra_parameters as $key => $param)
                                                    <div class="col-sm-6 mb-5">
                                                        <label class="form-label"
                                                               for="{{ $key }}">@lang(snake2Title($key))</label>
                                                        <div class="input-group input-group-merge">
                                                            <input type="text" id="extraParameters" name="{{ $key }}"
                                                                   class="form-control @error($key) is-invalid @enderror"
                                                                   value="{{ old($key, route($param, $method->code )) }}"
                                                                   readonly>
                                                            <a class="js-clipboard input-group-append input-group-text"
                                                               href="javascript:void(0);" data-bs-toggle="tooltip"
                                                               title="Copy to clipboard!"
                                                               data-hs-clipboard-options='{
                                                               "type": "tooltip",
                                                               "successText": "Copied!",
                                                               "contentTarget": "#extraParameters"
                                                             }'>
                                                                <i class="bi-clipboard"></i>
                                                            </a>
                                                            <span class="invalid-feedback">
                                                    @error($key) @lang($message) @enderror
                                                </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12 mt-4">
                                        <label class="form-label" for="descriptionArea">@lang("Description")</label>
                                        <textarea id="descriptionArea" class="form-control" name="description" placeholder="Description"
                                                  rows="3">{{ old('description', $method->description) }}</textarea>
                                        <span class="invalid-feedback">
                                            @error('description') @lang($message) @enderror
                                        </span>
                                    </div>
                                </div>


                                <div class="row my-lg-5">
                                    <div class="col-md-3">
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 rounded"
                                                 src="{{ getFile($method->driver, $method->image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 rounded"
                                                 src="{{ getFile($method->driver, $method->image, true) }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="image"
                                                   id="logoUploader" data-hs-file-attach-options='{
                                                      "textTarget": "#logoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                   }'>
                                        </label>
                                        <span class="invalid-feedback d-block">
                                            @error('image') @lang($message) @enderror
                                        </span>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span class="d-block text-dark">@lang("Status")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Enable to your gateway status")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="is_active" value="0">
                                                                    <input type="checkbox" class="form-check-input"
                                                                           name="is_active"
                                                                           id="isActiveSwitch"
                                                                           value="1" {{ $method->status == 1 ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                           for="isActiveSwitch"></label>
                                                                </div>
                                                                <span class="invalid-feedback">
                                                                    @error('is_active') @lang($message) @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->

                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <span class="d-block text-dark">@lang("Mode")</span>
                                                                <p class="fs-5 text-body mb-0">@lang("Live takes real payments. Sandbox is only for testing with test keys: no real money, and M-Pesa sends no prompt to real phones.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                @php $zkEnv = old('test_environment', $method->environment == 'test' ? 'test' : 'live'); @endphp
                                                                <div class="btn-group" role="group" aria-label="@lang('Mode')">
                                                                    <input type="radio" class="btn-check" name="test_environment" id="envLive" value="live" {{ $zkEnv == 'live' ? 'checked' : '' }}>
                                                                    <label class="btn btn-outline-success btn-sm" for="envLive"><i class="bi-lightning-charge me-1"></i>@lang('Live (production)')</label>
                                                                    <input type="radio" class="btn-check" name="test_environment" id="envTest" value="test" {{ $zkEnv == 'test' ? 'checked' : '' }}>
                                                                    <label class="btn btn-outline-secondary btn-sm" for="envTest"><i class="bi-bug me-1"></i>@lang('Sandbox (testing)')</label>
                                                                </div>
                                                                <span class="invalid-feedback">
                                                                    @error('test_environment') @lang($message) @enderror
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card supported_currency_card mt-lg-5">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-header-title">@lang('Supported Currencies Configuration')</h4>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-outline-primary auto_update_btn"
                                                    data-meesage="{{ $method->name }}"
                                                    data-bs-toggle="modal" data-bs-target="#autoUpdateCurrencyModal">
                                                @lang('Auto Update Currency')
                                            </button>
                                        </div>
                                    </div>
                                    <div class="table-responsive position-relative">
                                        <table
                                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                            id="supported_currency_table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th scope="col">@lang('Currency Symbol')</th>
                                                <th scope="col">@lang('Conversion Rate')</th>
                                                <th scope="col">@lang('Min Limit')</th>
                                                <th scope="col">@lang('Max Limit')</th>
                                                <th scope="col">@lang('Percentage Charge')</th>
                                                <th scope="col">@lang('Fixed Charge')</th>
                                            </tr>
                                            </thead>
                                            <tbody class="add_table_row">
                                            @php
                                                $oldReceivableCurrency = old('receivable_currencies', $method->receivable_currencies) ? count(old('receivable_currencies', $method->receivable_currencies)) : 0;
                                                $oldSelectedCurrency = session()->get('selectedCurrencyList');
                                            @endphp
                                            @if($oldReceivableCurrency > 0)
                                                @for($i = 0; $i < $oldReceivableCurrency; $i++)
                                                    <tr class="{{ $oldSelectedCurrency[$i] ?? $method->receivable_currencies[$i]->name }}-row">
                                                        <td>
                                                            <div class="mb-1">
                                                                <input type="text" class="form-control"
                                                                       name="receivable_currencies[{{ $i }}][currency_symbol]"
                                                                       placeholder="Currency Symbol"
                                                                       aria-label="Currency Symbol"
                                                                       value="{{ old("receivable_currencies.$i.currency_symbol",  $method->receivable_currencies[$i]->name ?? '') }}"
                                                                       autocomplete="off">
                                                                @error("receivable_currencies.$i.currency_symbol")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <span class="input-group-text"> 1 {{ $basicControl->base_currency ? : 'USD' }} = </span>
                                                                <input type="text"
                                                                       class="form-control @error('conversion_rate') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][conversion_rate]"
                                                                       value="{{ old("receivable_currencies.$i.conversion_rate", $method->receivable_currencies[$i]->conversion_rate ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 && isset($method->receivable_currencies[$i]) ? $method->receivable_currencies[$i]->name  : 'USD') }}</span>

                                                                @error("receivable_currencies.$i.conversion_rate")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('min_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][min_limit]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.min_limit", $method->receivable_currencies[$i]->min_limit ?? '')  }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 && isset($method->receivable_currencies[$i]) ? $method->receivable_currencies[$i]->name : "USD") }}</span>
                                                                @error("receivable_currencies.$i.min_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('max_limit') is-invalid @enderror"
                                                                       name="receivable_currencies[{{$i}}][max_limit]"
                                                                       aria-label="Amount"
                                                                       value="{{ old("receivable_currencies.$i.max_limit", $method->receivable_currencies[$i]->max_limit ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 && isset($method->receivable_currencies[$i])? $method->receivable_currencies[$i]->name : "USD") }}</span>
                                                                @error("receivable_currencies.$i.max_limit")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('percentage_charge') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][percentage_charge]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.percentage_charge", $method->receivable_currencies[$i]->percentage_charge ?? "") }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">%</span>
                                                                @error("receivable_currencies.$i.percentage_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group mb-1">
                                                                <input type="text"
                                                                       class="form-control @error('fixed_charge') is-invalid @enderror"
                                                                       name="receivable_currencies[{{ $i }}][fixed_charge]"
                                                                       aria-label="Amount (to the nearest dollar)"
                                                                       value="{{ old("receivable_currencies.$i.fixed_charge", $method->receivable_currencies[$i]->fixed_charge ?? '') }}"
                                                                       autocomplete="off">
                                                                <span
                                                                    class="input-group-text">{{ old("receivable_currencies.$i.name", $method->currency_type == 1 && isset($method->receivable_currencies[$i]) ? $method->receivable_currencies[$i]->name : "USD") }}</span>
                                                                @error("receivable_currencies.$i.fixed_charge")
                                                                <span
                                                                    class="invalid-feedback d-block mb-1">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endfor
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="autoUpdateCurrencyModal" tabindex="-1" role="dialog"
         aria-labelledby="autoUpdateCurrencyModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="autoUpdateCurrencyModalLabel">@lang('Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payment.method.auto.update.currency', $method->id) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <span>@lang("Do you want to") <span>{{ __($method->name) }}</span> @lang("currency rate auto update?")</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/clipboard.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {

            var eventHandler = function (name) {
                return function () {
                    if (name == 'onItemAdd') {
                        itemAppend(arguments[0]);
                    } else if (name == 'onItemRemove') {
                        itemRemove(arguments[0][0]);
                    }
                };
            };

            var tomSelect = new TomSelect('.js-select', {
                plugins: {
                    remove_button: {
                        title: '',
                    },
                },
                create: true,
                onItemAdd: eventHandler('onItemAdd'),
                onDelete: eventHandler('onItemRemove'),
            });


            let method = @json($method);
            function itemAppend(currency) {
                let rowCount = $('#supported_currency_table tr').length;
                currency = method.currency_type === 1 ? currency : 'USD';
                let markup = "";
                markup += `
                        <tr class="${currency}-row">
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="receivable_currencies[${rowCount - 1}][currency_symbol]"
                                           placeholder="@lang("Currency Symbol")" aria-label="@lang("Currency Symbol")"
                                           autocomplete="off">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">1 {{ $basicControl->base_currency }} = </span>
                                        <input type="text"
                                            class="form-control"
                                            name="receivable_currencies[${rowCount - 1}][conversion_rate]"
                                            autocomplete="off">
                                            <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                          name="receivable_currencies[${rowCount - 1}][min_limit]"
                                          autocomplete="off">
                                          <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                            name="receivable_currencies[${rowCount - 1}][max_limit]"
                                            autocomplete="off">
                                            <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                            name="receivable_currencies[${rowCount - 1}][percentage_charge]"
                                            autocomplete="off">
                                            <span class="input-group-text">%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control"
                                             name="receivable_currencies[${rowCount - 1}][fixed_charge]"
                                             autocomplete="off">
                                             <span class="input-group-text">${currency}</span>
                                    </div>
                                </td>

                        </tr>`;

                $('.add_table_row').append(markup);
            }

            function itemRemove(currency) {
                $(`.${currency}-row`).remove();
                alignArrayIndexForSupportCurrency();
            }


            function alignArrayIndexForSupportCurrency() {
                $('.add_table_row tr').each(function (index) {
                    $(this).find('input[name^="receivable_currencies"]').each(function () {
                        var newName = $(this).attr('name').replace(/\[(\d+)\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    })
                })
            }

            new HSFileAttach('.js-file-attach')
            HSCore.components.HSClipboard.init('.js-clipboard')

        });

    </script>
@endpush

