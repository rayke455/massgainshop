@extends(template().'layouts.user')
@section('title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    <div class="dashboard-wrapper d-flex align-items-center">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-7 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <img
                                        src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                                        class="gateway-img">
                                </div>
                                <div class="col-md-6">
                                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                    <button type="button"
                                            class="cmn-btn rounded-1"
                                            id="payment-button">@lang('Pay with Khalti')
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
	<script
		src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
	<script>

		$(document).ready(function () {
			$('body').addClass('antialiased')
		});

		let config = {
			"publicKey": "{{$data->publicKey}}",
			"productIdentity": "{{$data->productIdentity}}",
			"productName": "Payment",
			"productUrl": "{{url('/')}}",
			"paymentPreference": [
				"KHALTI",
				"EBANKING",
				"MOBILE_BANKING",
				"CONNECT_IPS",
				"SCT",
			],
			"eventHandler": {
				onSuccess(payload) {
					$.ajax({
						type: 'POST',
						url: "{{ route('khalti.verifyPayment',[$deposit->trx_id]) }}",
						data: {
							token: payload.token,
							amount: payload.amount,
							"_token": "{{ csrf_token() }}"
						},
						success: function (res) {
							$.ajax({
								type: "POST",
								url: "{{ route('khalti.storePayment') }}",
								data: {
									response: res,
									"_token": "{{ csrf_token() }}"
								},
								success: function (res) {
									window.location.href = "{{route('success')}}"
								}
							});
						}
					});
				},
				onError(error) {
				},
				onClose() {
				}
			}
		};
		let checkout = new KhaltiCheckout(config);
		let btn = document.getElementById("payment-button");
		btn.onclick = function () {
			checkout.show({amount: "{{$data->amount *100}}"});
		}
	</script>
@endpush
