@php
    $brand = $panel->allows('branding') ? $panel->name : __('Social services');
    $logo = \App\Services\PanelRentals::logoUrl($panel);
    $color = $panel->color ?: '#2563eb';
    $orderStatus = optional($item->providerOrder)->status;
    $labels = [
        'awaiting_payment' => [__('Waiting for your M-Pesa payment'), __('Check your phone and enter your M-Pesa PIN. This page updates by itself.'), 'wait'],
        'payment_failed' => [__('Payment not completed'), $item->note ?: __('The M-Pesa payment was not completed. You can place the order again.'), 'bad'],
        'requested' => [__('Request received'), __('We will contact you to confirm and collect payment.'), 'wait'],
        'failed' => [__('We could not start this order'), __('Your payment was received. We will contact you to place it again or refund you.'), 'bad'],
        'submitted' => [ucfirst($orderStatus ?: 'processing'), __('Your order is in progress. Keep this link to follow it.'), $orderStatus === 'completed' ? 'ok' : 'run'],
    ];
    [$title, $hint, $tone] = $labels[$item->status] ?? [ucfirst($item->status), '', 'wait'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
    <title>{{ __('Order') }} #{{ $item->id }} · {{ $brand }}</title>
    <style>
        :root { --brand: {{ $color }}; }
        * { box-sizing: border-box; } body { margin: 0; font: 15px/1.6 system-ui, sans-serif; background: #f6f8fc; color: #0f172a; }
        .wrap { max-width: 560px; margin: 0 auto; padding: 24px 16px; }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 18px; color: #0f172a; text-decoration: none; margin-bottom: 18px; }
        .brand img { height: 36px; max-width: 160px; object-fit: contain; }
        .card { background: #fff; border: 1px solid #e5e9f2; border-radius: 18px; padding: 24px; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 999px; font-weight: 700; font-size: 13px; }
        .ok { background: #dcfce7; color: #047857; } .run { background: #dbeafe; color: #1d4ed8; } .wait { background: #fef3c7; color: #b45309; } .bad { background: #fee2e2; color: #b91c1c; }
        dl { display: grid; grid-template-columns: auto 1fr; gap: 6px 16px; margin: 16px 0 0; } dt { color: #64748b; } dd { margin: 0; font-weight: 600; overflow-wrap: anywhere; }
        .spin { width: 18px; height: 18px; border: 3px solid #e5e9f2; border-top-color: var(--brand); border-radius: 50%; display: inline-block; animation: s 1s linear infinite; vertical-align: -3px; margin-right: 6px; }
        @keyframes s { to { transform: rotate(360deg); } }
        a.btn { display: inline-block; margin-top: 16px; background: var(--brand); color: #fff; padding: 10px 16px; border-radius: 12px; text-decoration: none; font-weight: 700; }
    </style>
</head>
<body>
<div class="wrap">
    <a class="brand" href="{{ $base ?: '/' }}">@if($logo)<img src="{{ $logo }}" alt="">@endif{{ $logo ? '' : $brand }}</a>
    <div class="card">
        <p style="margin:0;color:#64748b">{{ __('Order') }} #{{ $item->id }}</p>
        <h1 style="margin:4px 0 8px;font-size:22px">@if($item->status === 'awaiting_payment')<span class="spin" aria-hidden="true"></span>@endif{{ $title }}</h1>
        <span class="badge {{ $tone }}">{{ $title }}</span>
        <p id="hint" style="color:#475569">{{ $hint }}</p>
        <dl>
            <dt>{{ __('Service') }}</dt><dd>{{ $item->service ? \App\Services\SmartImport::cleanName($item->service->service_title) : '—' }}</dd>
            <dt>{{ __('Quantity') }}</dt><dd>{{ number_format($item->quantity) }}</dd>
            <dt>{{ __('Total') }}</dt><dd>{{ number_format((float) $item->quote, 2) }} {{ $item->currency }}</dd>
            @if($item->providerOrder)
                @if($item->providerOrder->start_counter !== null)<dt>{{ __('Start count') }}</dt><dd>{{ number_format($item->providerOrder->start_counter) }}</dd>@endif
                @if($item->providerOrder->remains !== null && $orderStatus !== 'completed')<dt>{{ __('Remaining') }}</dt><dd>{{ number_format($item->providerOrder->remains) }}</dd>@endif
            @endif
        </dl>
        @if(in_array($item->status, ['payment_failed']))<a class="btn" href="{{ $base ?: '/' }}">{{ __('Try again') }}</a>@endif
        <p style="font-size:13px;color:#64748b;margin:16px 0 0">{{ __('Save this page link to check your order any time.') }}</p>
    </div>
</div>
@if($item->status === 'awaiting_payment')
<script>
    (function () {
        let tries = 0;
        function poll() {
            fetch(@json(($base ?: '') . '/pay-status/' . $item->token), {headers: {'Accept': 'application/json'}})
                .then(r => r.json()).then(d => {
                    if (d.status !== 'awaiting_payment') { location.reload(); return; }
                    if (++tries < 45) setTimeout(poll, 4000);
                    else document.getElementById('hint').textContent = @json(__('We have not received a confirmation yet. If you paid, refresh this page in a minute.'));
                }).catch(() => setTimeout(poll, 6000));
        }
        setTimeout(poll, 5000);
    })();
</script>
@endif
</body>
</html>
