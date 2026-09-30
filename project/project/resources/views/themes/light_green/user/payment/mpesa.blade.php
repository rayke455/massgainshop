@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    @php
        $zkUser = auth()->user();
        $zkPhone = old('phone') ?: (($zkUser->phone_code === '+254' && $zkUser->phone) ? '0' . ltrim($zkUser->phone, '0') : '');
        $zkPending = session('mpesa_pending') || ($deposit->payment_id && $deposit->status != 1);
    @endphp
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card zk-mpesa">
                        <div class="card-body text-center">
                            <img class="zk-mpesa__logo" src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                 alt="{{ optional($deposit->gateway)->name }}">
                            <p class="zk-mpesa__label">@lang('Amount to pay')</p>
                            <h1 class="zk-mpesa__amount">{{ number_format(ceil($deposit->payable_amount)) }} {{ $deposit->payment_method_currency }}</h1>
                            <p class="zk-mpesa__credit">@lang('Adds') <strong><span class="notranslate">{{ currencyPosition($deposit->payable_amount_in_base_currency) }}</span></strong> @lang('to your balance')</p>

                            @if(optional($deposit->gateway)->environment != 'live')
                                <div class="alert alert-warning text-start small" role="status">
                                    <i class="fa-regular fa-triangle-exclamation"></i>
                                    @lang('M-Pesa is in test mode, so no PIN prompt will reach your phone. Please choose another payment method or contact support.')
                                </div>
                            @endif

                            {{-- Step 1: send the prompt --}}
                            <form action="{{ route('mpesa.stkPush', $deposit->trx_id) }}" method="post" class="zk-mpesa__form {{ $zkPending ? 'd-none' : '' }}" id="zkMpesaForm">
                                @csrf
                                <label for="phoneNumber" class="form-label text-start w-100">@lang('M-Pesa phone number')</label>
                                <input type="tel" id="phoneNumber" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ $zkPhone }}" placeholder="0712 345 678" inputmode="tel" autocomplete="tel" required>
                                @error('phone')
                                <span class="invalid-feedback d-block text-start">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="zk-btn zk-btn--primary zk-btn--lg w-100 mt-3" id="zkMpesaPay">
                                    @lang('Send payment request to my phone')
                                </button>
                                <ol class="zk-mpesa__steps text-start">
                                    <li>@lang('Enter the Safaricom number you want to pay from.')</li>
                                    <li>@lang('A pop-up appears on your phone asking for your M-Pesa PIN.')</li>
                                    <li>@lang('Enter your PIN. Your balance updates here automatically.')</li>
                                </ol>
                            </form>

                            {{-- Step 2: waiting for the PIN --}}
                            <div class="zk-mpesa__wait {{ $zkPending ? '' : 'd-none' }}" id="zkMpesaWait" aria-live="polite">
                                <div class="spinner-border text-success" role="status"></div>
                                <h2>@lang('Check your phone')</h2>
                                <p id="zkMpesaMsg">@lang('Enter your M-Pesa PIN on the pop-up. We\'ll confirm the payment automatically — keep this page open.')</p>
                                <button type="button" class="zk-btn zk-btn--ghost w-100" id="zkMpesaRetry">@lang('Didn\'t get the pop-up? Send it again')</button>
                            </div>

                            <p class="zk-secure mt-3"><i class="fa-regular fa-lock"></i> @lang('We never see your M-Pesa PIN.')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .zk-mpesa { max-width: 460px; margin: 0 auto; }
        .zk-mpesa .card-body { padding: 32px 28px; }
        .zk-mpesa__logo { width: 72px; height: 72px; object-fit: contain; border-radius: 16px; margin-bottom: 14px; }
        .zk-mpesa__label { margin: 0; color: var(--zk-muted); font-size: 14px; font-weight: 600; }
        .zk-mpesa__amount { font-size: 34px; font-weight: 800; margin: 4px 0; text-transform: none; }
        .zk-mpesa__credit { color: var(--zk-muted); font-size: 14px; margin-bottom: 22px; }
        .zk-mpesa__form .form-control { height: 54px; font-size: 18px; font-weight: 700; letter-spacing: .5px; }
        .zk-mpesa__steps { margin: 18px 0 0; padding-left: 18px; color: var(--zk-text); font-size: 14px; display: grid; gap: 6px; }
        .zk-mpesa__wait h2 { font-size: 20px; font-weight: 800; margin: 16px 0 6px; text-transform: none; }
        .zk-mpesa__wait p { color: var(--zk-text); font-size: 15px; }
        .zk-mpesa__wait.is-failed .spinner-border { display: none; }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            const wait = document.getElementById('zkMpesaWait');
            const form = document.getElementById('zkMpesaForm');
            const msg = document.getElementById('zkMpesaMsg');
            let timer = null, tries = 0;

            function poll() {
                tries++;
                fetch(@json(route('mpesa.status', $deposit->trx_id)), {headers: {'Accept': 'application/json'}})
                    .then(r => r.json())
                    .then(res => {
                        if (res.state === 'paid') {
                            msg.textContent = @json(__('Payment received! Taking you to your deposits...'));
                            window.location.href = res.redirect;
                            return;
                        }
                        if (res.state === 'failed') {
                            wait.classList.add('is-failed');
                            msg.textContent = res.message + ' ' + @json(__('You can try again.'));
                            return;
                        }
                        // ~3 minutes of waiting, then stop asking and let the user retry.
                        if (tries < 45) timer = setTimeout(poll, 4000);
                        else { wait.classList.add('is-failed'); msg.textContent = @json(__('We did not get a confirmation yet. If you paid, it will appear in your deposits shortly.')); }
                    })
                    .catch(() => { timer = setTimeout(poll, 6000); });
            }

            document.getElementById('zkMpesaRetry').addEventListener('click', () => {
                clearTimeout(timer);
                wait.classList.add('d-none');
                wait.classList.remove('is-failed');
                form.classList.remove('d-none');
            });
            form.addEventListener('submit', () => {
                const btn = document.getElementById('zkMpesaPay');
                btn.disabled = true;
                btn.textContent = @json(__('Sending...'));
            });

            if (!wait.classList.contains('d-none')) timer = setTimeout(poll, 5000);
        })();
    </script>
@endpush
