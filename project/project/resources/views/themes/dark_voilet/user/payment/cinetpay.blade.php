@extends(template().'layouts.user')
@section('title')
    {{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
@endsection
@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.cinetpay.com/seamless/main.js"></script>
    <style>
        .sdk {
            display: block;
            position: absolute;
            background-position: center;
            text-align: center;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

    <div class="dashboard-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-7 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <img
                                        src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                                        class="card-img-top gateway-img">
                                </div>
                                <div class="col-md-6">
                                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                    <button type="button"
                                            class="cmn-btn rounded-1"
                                            id="pay-button">@lang('Pay Now')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.getElementById("pay-button").addEventListener("click", function () {
            checkout();
        });

        function checkout() {

            CinetPay.setConfig({
                apikey: '{{ optional($deposit->gateway)->parameters->apiKey }}',
                site_id: '{{ optional($deposit->gateway)->parameters->site_id }}',
                notify_url: '{{ route('ipn', [optional($deposit->gateway)->code, $deposit->trx_id]) ?? 'https://bugfinder.net/' }}',
                return_url: '{{ route('success') ?? 'https://bugfinder.net/' }}',
                mode: '{{ optional($deposit->gateway)->environment === 'live' ? 'PRODUCTION' : 'SANDBOX' }}'
            });

            CinetPay.getCheckout({
                transaction_id: '{{ $deposit->trx_id }}',
                amount: 1000,
                currency: '{{ $deposit->payment_method_currency ?? 'XAF' }}',
                channels: 'ALL',
                description: 'Payment for Order #{{ $deposit->trx_id }}',
                customer_name: "{{ optional($deposit->user)->username ?? 'Guest' }}",
                customer_surname: "{{ optional($deposit->user)->username ?? 'Guest' }}",
                customer_email: "{{ optional($deposit->user)->email ?? 'guest@example.com' }}",
                customer_phone_number: "{{ optional($deposit->user)->phone ?? '0000000000' }}",
                customer_address: "{{ optional($deposit->user)->address_one ?? 'Unknown Address' }}",
                customer_city: "{{ optional($deposit->user)->city ?? 'City' }}",
                customer_country: "{{ optional($deposit->user)->city ?? 'CM' }}", // Change to your ISO country code (e.g., 'BJ', 'SN', 'CI')
                customer_state: "{{ optional($deposit->user)->state ?? 'CM' }}",
                customer_zip_code: "{{ optional($deposit->user)->zip_code ?? '00000' }}",
            });

            CinetPay.waitResponse(function (data) {
                if (data.status === "REFUSED") {
                    alert("Payment failed or refused.");
                    window.location.href = "{{ route('failed') }}";
                } else if (data.status === "ACCEPTED") {
                    alert("Payment successful!");
                    window.location.href = "{{ route('success') }}";
                } else {
                }
            });

            CinetPay.onError(function (data) {
                console.error("CinetPay error:", data);
                alert("An error occurred while processing your payment. Please try again.");
            });
        }
    </script>
@endpush



