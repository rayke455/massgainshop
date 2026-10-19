<?php

namespace App\Services\Gateway\snippe;

class Payment
{
    /** Snippe uses its own page (phone number → USSD push → status checks), handled by SnippeController. */
    public static function prepareData($deposit, $gateway)
    {
        $send['amount'] = (int) ceil($deposit->payable_amount);
        $send['view'] = 'user.payment.snippe';

        return json_encode($send);
    }
}
