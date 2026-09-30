<?php

namespace App\Services\Gateway\cinetpay;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;
use CinetPay\CinetPay;
use Exception;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        return json_encode([
            'view' => 'user.payment.cinetpay',
        ]);
    }

    public static function ipn($request, $gateway, $deposit = null)
    {
        if (empty($deposit) || empty($deposit->trx_id)) {
            return [
                'status' => 'error',
                'msg' => 'Invalid transaction reference.',
                'redirect' => route('failed'),
            ];
        }

        try {
            $apiKey = $gateway->parameters->apiKey;
            $siteId = $gateway->parameters->site_id;

            $cp = new CinetPay($siteId, $apiKey);
            $cp->setTransId($deposit->trx_id)->getPayStatus();


            if (floatval($cp->_cpm_amount) != floatval($deposit->payable_amount)) {
                return [
                    'status' => 'error',
                    'msg' => 'Payment amount mismatch.',
                    'redirect' => route('failed'),
                ];
            }

            if ($cp->isValidPayment() && $cp->_cpm_result == '00') {
                BasicService::preparePaymentUpgradation($deposit);

                return [
                    'status' => 'success',
                    'msg' => 'Transaction was successful.',
                    'redirect' => route('success'),
                ];
            }

            return [
                'status' => 'error',
                'msg' => 'Payment validation failed. Please contact support.',
                'redirect' => route('failed'),
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'msg' => 'Server communication error: ' . $e->getMessage(),
                'redirect' => route('failed'),
            ];
        }
    }
}
