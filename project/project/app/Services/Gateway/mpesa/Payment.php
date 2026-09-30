<?php

namespace App\Services\Gateway\mpesa;

use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Payment
{

    public static function prepareData($deposit, $gateway)
    {
        $send['amount'] = round($deposit->payable_amount);
        $send['view'] = 'user.payment.mpesa';

        return json_encode($send);
    }
}
