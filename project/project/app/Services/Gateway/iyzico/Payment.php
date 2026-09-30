<?php

namespace App\Services\Gateway\iyzico;


use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Currency;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Request\CreatePayWithIyzicoInitializeRequest;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\PayWithIyzicoInitialize;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\CheckoutForm;


class Payment
{

    public static function prepareData($deposit, $gateway)
    {
        //=============================================
        $options = new Options();
        $options->setApiKey(optional($gateway->parameters)->api_key);
        $options->setSecretKey(optional($gateway->parameters)->secrest_key);
        $options->setBaseUrl(optional($gateway->parameters)->base_url);

        $request = new CreateCheckoutFormInitializeRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($deposit->trx_id);
        $request->setPrice($deposit->payable_amount);
        $request->setPaidPrice($deposit->payable_amount);
        $request->setCurrency(Currency::TL);
        $request->setBasketId("B67832");
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('ipn', [$deposit->child_panel_id ? $gateway->gateway->code : $gateway->code, $deposit->trx_id]));
        $request->setEnabledInstallments([2, 3, 6, 9]);

        // Buyer
        $buyer = new Buyer();
        $buyer->setId("BY" . optional($deposit->user)->id);
        $buyer->setName(optional($deposit->user)->firstname ?? "John");
        $buyer->setSurname(optional($deposit->user)->lastname ?? "Doe");
        $buyer->setGsmNumber(optional($deposit->user)->phone_code . optional($deposit->user)->phone ?? "+905350000000");
        $buyer->setEmail(optional($deposit->user)->email ?? "email@email.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setLastLoginDate(Carbon::parse(optional($deposit->user)->last_login)->format('Y-m-d H:i:s') ?? "2015-10-05 12:43:35");
        $buyer->setRegistrationDate(Carbon::parse(optional($deposit->user)->created_at)->format('Y-m-d H:i:s') ?? "2013-04-21 15:12:09");
        $buyer->setRegistrationAddress(optional($deposit->user)->address_one ?? "Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setIp(request()->ip());
        $buyer->setCity(optional($deposit->user)->city ?? "Istanbul");
        $buyer->setCountry(optional($deposit->user)->country ?? "Turkey");
        $buyer->setZipCode(optional($deposit->user)->zip_code ?? "34732");
        $request->setBuyer($buyer);

        // Shipping & Billing
        $address = new Address();
        $address->setContactName(optional($deposit->user)->firstname . optional($deposit->user)->lastname ?? "Jane Doe");
        $address->setCity(optional($deposit->user)->city ?? "Istanbul");
        $address->setCountry(optional($deposit->user)->country ?? "Turkey");
        $address->setAddress(optional($deposit->user)->address_one ?? "Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $address->setZipCode(optional($deposit->user)->zip_code ?? "34742");
        $request->setShippingAddress($address);
        $request->setBillingAddress($address);

        // Basket items
        $items = [];

        $item1 = new BasketItem();
        $item1->setId("BI" . $deposit->id);
        $item1->setName("User Add Fund");
        $item1->setCategory1("Online SMM Service");
        $item1->setCategory2("Online SMM Service");
        $item1->setItemType(BasketItemType::VIRTUAL);
        $item1->setPrice($deposit->payable_amount);
        $items[] = $item1;

        $request->setBasketItems($items);

        $checkoutFormInitialize = CheckoutFormInitialize::create($request, $options);
        $status = $checkoutFormInitialize->getStatus();
        $paymentPageUrl = $checkoutFormInitialize->getPaymentPageUrl();

        if ($status == 'success') {
            $send['redirect'] = true;
            $send['redirect_url'] = $paymentPageUrl;
        } else {
            $send['error'] = true;
            $send['message'] = "Invalid Request";
        }
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null)
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey($gateway->parameters->api_key);
        $options->setSecretKey($gateway->parameters->secrest_key);
        $options->setBaseUrl($gateway->parameters->base_url);

        $token = $request->input('token');

        $iyzicoRequest = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId($deposit->trx_id);
        $iyzicoRequest->setToken($token);

        $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($iyzicoRequest, $options);

        $paymentStatus = strtoupper($checkoutForm->getPaymentStatus());

        if ($paymentStatus === 'SUCCESS') {
            BasicService::preparePaymentUpgradation($deposit);

            $data['status'] = 'success';
            $data['msg'] = 'Transaction was successful.';
            $data['redirect'] = route('success');
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Unsuccessful transaction.';
            $data['redirect'] = route('failed');
        }

        return $data;
    }

}
