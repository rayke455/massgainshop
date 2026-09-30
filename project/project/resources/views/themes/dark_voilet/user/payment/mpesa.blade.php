@extends(template().'layouts.user')
@section('title')
    {{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')

    @push('style')
        <style>
            body {
                background: #f5f7fa;
            }

            .payment-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }

            .payment-card {
                background: #ffffff;
                border-radius: 20px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 40px 30px;
                max-width: 450px;
                width: 100%;
                text-align: center;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .payment-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            }

            .gateway-img {
                width: 80px;
                height: 80px;
                border-radius: 15px;
                object-fit: cover;
                margin-bottom: 25px;
            }

            .amount {
                font-size: 2rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 15px;
            }

            .instruction {
                font-size: 1rem;
                color: #555;
                margin-bottom: 25px;
            }

            .phone-input {
                margin-bottom: 25px;
            }

            .phone-input input {
                padding: 12px 15px;
                border-radius: 10px;
                border: 1px solid #ddd;
                width: 100%;
                font-size: 1rem;
                transition: border 0.3s ease;
            }

            .phone-input input:focus {
                border-color: #4a90e2;
                outline: none;
            }

            .pay-btn {
                background: linear-gradient(90deg, #4a90e2, #357abd);
                color: #fff;
                border: none;
                padding: 14px 0;
                font-size: 1.1rem;
                font-weight: 600;
                border-radius: 12px;
                width: 100%;
                transition: background 0.3s ease, transform 0.2s ease;
            }

            .pay-btn:hover {
                background: linear-gradient(90deg, #357abd, #2a5e9c);
                transform: translateY(-2px);
            }

            .small-text {
                font-size: 0.875rem;
                color: #888;
                margin-top: 15px;
            }
        </style>
    @endpush

    <div class="payment-wrapper">
        <div class="payment-card">
            <img src="{{ getFile(optional($deposit->gateway)->driver, optional($deposit->gateway)->image) }}"
                 alt="{{ optional($deposit->gateway)->name }}"
                 class="gateway-img">


            <h2 class="amount">{{ getAmount($deposit->payable_amount) }} {{ $deposit->payment_method_currency }}</h2>
            <p class="instruction">@lang('Please pay using your mobile number linked to your payment method')</p>
            <form action="{{ route('mpesa.stkPush', $deposit->trx_id) }}" method="post">
                @csrf
                <div class="phone-input">
                    <input type="text" id="phoneNumber" name="phone" placeholder="254708374149">
                    @error('phone')
                    <span class="d-block invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" id="pay-button" class="pay-btn">@lang('Pay Now')</button>
            </form>

            <p class="small-text">@lang('You will receive a payment confirmation on your phone')</p>
        </div>
    </div>

@endsection
