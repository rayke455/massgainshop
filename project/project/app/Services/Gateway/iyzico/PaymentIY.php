<?php

namespace App\Services\Gateway\iyzico;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentIY
{
    public static function prepareData($deposit, $gateway)
    {
        $baseUrl = 'https://sandbox-api.iyzipay.com/payment/iyzipos/checkoutform/initialize/auth/ecom';

        $apiKey    = $gateway->parameters->api_key;
        $secretKey = $gateway->parameters->secret_key;
        $randomString = (string) Str::uuid();

        // 1️⃣ Build Payload
        $payload = [
            "locale" => "tr",
            "conversationId" => uniqid('conv_'),
            "price" => 6.0,
            "paidPrice" => 6.0,
            "currency" => "TRY",
            "basketId" => "B-1",
            "paymentGroup" => "PRODUCT",
            "callbackUrl" => "https://callback.com/test",
            "enabledInstallments" => [2, 3],
            "buyer" => [
                "id" => "B-BY789",
                "name" => "John",
                "surname" => "Doe",
                "identityNumber" => "11111111111",
                "email" => "sandboxtest@email.com",
                "gsmNumber" => "+905350000000",
                "registrationDate" => "2013-04-21 15:12:09",
                "lastLoginDate" => "2015-10-05 12:43:35",
                "registrationAddress" => "Altunizade Mah. İnci Çıkmazı Sokak No: 3 İç Kapı No: 10 Üsküdar İstanbul",
                "city" => "Istanbul",
                "country" => "Turkey",
                "ip" => "85.34.78.112",
            ],
            "shippingAddress" => [
                "address" => "Altunizade Mah. İnci Çıkmazı Sokak No: 3 İç Kapı No: 10 Üsküdar İstanbul",
                "zipCode" => "34742",
                "contactName" => "Jane Doe",
                "city" => "Istanbul",
                "country" => "Turkey",
            ],
            "billingAddress" => [
                "address" => "Altunizade Mah. İnci Çıkmazı Sokak No: 3 İç Kapı No: 10 Üsküdar İstanbul",
                "zipCode" => "34742",
                "contactName" => "Jane Doe",
                "city" => "Istanbul",
                "country" => "Turkey",
            ],
            "basketItems" => [
                [
                    "id" => "BI101",
                    "price" => "1.0",
                    "name" => "Binocular",
                    "category1" => "General",
                    "itemType" => "PHYSICAL",
                ],
            ],
        ];

        $jsonBody = json_encode($payload, JSON_UNESCAPED_SLASHES);

        // 2️⃣ Correct Signature Order
        $signatureRaw = $randomString . $apiKey . $secretKey . $jsonBody;
        $signature = base64_encode(sha1($signatureRaw, true));

        // 3️⃣ Authorization Header
        $authorization = 'IYZWSv2 ' . base64_encode($apiKey . ':' . $signature);

        // 4️⃣ Send Request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $authorization,
            'x-iyzi-rnd' => $randomString,
        ])->post($baseUrl, $payload);

        $resp = $response->json();

        $send = [];

        if ($response->successful() && isset($resp['status']) && $resp['status'] === 'success') {
            if (!empty($resp['paymentPageUrl'])) {
                $send['redirect'] = true;
                $send['redirect_url'] = $resp['paymentPageUrl'];
            } else {
                $send['error'] = true;
                $send['message'] = 'Payment initialization failed: Missing paymentPageUrl.';
            }
        } else {
            $send['error'] = true;
            $send['message'] = $resp['errorMessage'] ?? 'Payment initialization failed.';
            $send['errorCode'] = $resp['errorCode'] ?? null;
        }

        return $send;
    }


    public static function prepareData2($deposit, $gateway)
    {

        $options = new Options();
        $options->setApiKey('sandbox-PZSlZgUVyF1ql7boLsFiTHIFqSnOLJFx');
        $options->setSecretKey('sandbox-fKJlOjUdR3Hwa6NgDMgZ3ytaLp658DCh');
        $options->setBaseUrl('https://sandbox-api.iyzipay.com');


        $request = new CreatePayWithIyzicoInitializeRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId(uniqid());
        $request->setPrice("1");
        $request->setPaidPrice("1.2");
        $request->setCurrency(Currency::TL);
        $request->setBasketId("B67832");
        $request->setPaymentGroup(PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('success')); // Laravel route
        $request->setEnabledInstallments([2,3,6,9]);

        // Buyer
        $buyer = new Buyer();
        $buyer->setId("BY789");
        $buyer->setName("John");
        $buyer->setSurname("Doe");
        $buyer->setGsmNumber("+905350000000");
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setLastLoginDate("2015-10-05 12:43:35");
        $buyer->setRegistrationDate("2013-04-21 15:12:09");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setIp(request()->ip());
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        // Shipping & Billing Address
        $address = new Address();
        $address->setContactName("Jane Doe");
        $address->setCity("Istanbul");
        $address->setCountry("Turkey");
        $address->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $address->setZipCode("34742");

        $request->setShippingAddress($address);
        $request->setBillingAddress($address);

        // Basket Items
        $basketItems = [];

        $basketItem1 = new BasketItem();
        $basketItem1->setId("BI101");
        $basketItem1->setName("Binocular");
        $basketItem1->setCategory1("Collectibles");
        $basketItem1->setCategory2("Accessories");
        $basketItem1->setItemType(BasketItemType::PHYSICAL);
        $basketItem1->setPrice("0.3");
        $basketItems[] = $basketItem1;

        $basketItem2 = new BasketItem();
        $basketItem2->setId("BI102");
        $basketItem2->setName("Game code");
        $basketItem2->setCategory1("Game");
        $basketItem2->setCategory2("Online Game Items");
        $basketItem2->setItemType(BasketItemType::VIRTUAL);
        $basketItem2->setPrice("0.5");
        $basketItems[] = $basketItem2;

        $basketItem3 = new BasketItem();
        $basketItem3->setId("BI103");
        $basketItem3->setName("Usb");
        $basketItem3->setCategory1("Electronics");
        $basketItem3->setCategory2("Usb / Cable");
        $basketItem3->setItemType(BasketItemType::PHYSICAL);
        $basketItem3->setPrice("0.2");
        $basketItems[] = $basketItem3;

        $request->setBasketItems($basketItems);

        // Create Payment
        $payWithIyzicoInitialize = PayWithIyzicoInitialize::create($request, $options);

        return $payWithIyzicoInitialize;
    }
}
