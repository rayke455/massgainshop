<?php

namespace App\Services;


use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Notify;
use Illuminate\Support\Facades\DB;

class BasicService
{
    use Notify;

    public function setEnv($value)
    {
        $envPath = base_path('.env');
        $env = file($envPath);
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            $env[$env_key] = array_key_exists($entry[0], $value) ? $entry[0] . "=" . $value[$entry[0]] . "\n" : $env_value;
        }
        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);
    }

    public function

    preparePaymentUpgradation($deposit)
    {
        try {
            if ($deposit->status == 0 || $deposit->status == 2) {
                $deposit->status = 1;
                $deposit->save();

                if ($deposit->user) {
                    $user = $deposit->user;
                    $user->balance += $deposit->payable_amount_in_base_currency;
                    $user->save();

                    $transaction = new Transaction();
                    $transaction->user_id = $deposit->user_id;
                    $transaction->child_panel_id = $deposit->child_panel_id ?? null;
                    $transaction->amount = $deposit->payable_amount_in_base_currency;
                    $transaction->charge = getAmount($deposit->base_currency_charge);
                    $transaction->balance = getAmount($user->balance);
                    $transaction->trx_type = '+';
                    $transaction->trx_id = $deposit->trx_id;
                    $transaction->remarks = 'Deposit Via ' . optional($deposit->gateway)->name;
                    $deposit->transactional()->save($transaction);
                    $deposit->save();

                    if (basicControl()->deposit_commission == 1) {
                        $this->setBonus($user, $deposit->amount, $type = 'deposit');
                    }
                }

                if ($deposit->child_panel_id) {
                    $this->sendNotificationChildPanel($deposit);
                } else {
                    $this->sendNotification($deposit);
                }

                return true;
            }
        } catch (\Exception $e) {
        }
    }


    public function sendNotification($deposit)
    {
        $params = [
            'amount' => currencyPosition($deposit->amount),
            'transaction' => $deposit->trx_id,
        ];

        $action = [
            "link" => route('user.fund.index'),
            "icon" => "fa-light fa-bullhorn"
        ];

        $this->sendMailSms($deposit->user, 'ADD_FUND_USER_USER', $params);
        $this->userPushNotification($deposit->user, 'ADD_FUND_USER_USER', $params, $action);
        $this->userFirebasePushNotification($deposit->user, 'ADD_FUND_USER_USER', $params, route('user.fund.index'));

        $params = [
            'username' => optional($deposit->user)->username,
            'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
            'transaction' => $deposit->trx_id,
        ];
        $actionAdmin = [
            "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
            "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image),
            "link" => route('user.fund.index'),
            "icon" => "fa-light fa-bullhorn"
        ];

        $this->adminMail('ADD_FUND_USER_ADMIN', $params, $action);
        $this->adminPushNotification('ADD_FUND_USER_ADMIN', $params, $actionAdmin);
        $this->adminFirebasePushNotification('ADD_FUND_USER_ADMIN', $params, route('user.transaction'));
    }

    public function sendNotificationChildPanel($deposit)
    {
        $params = [
            'amount' => currencyPosition($deposit->amount),
            'transaction' => $deposit->trx_id,
        ];

        $action = [
            "link" => route('child.panel.user.fund.index'),
            "icon" => "fa-light fa-bullhorn"
        ];

        $this->sendMailSms($deposit->user, 'ADD_FUND_USER_USER', $params, null, null, true);
        $this->userPushNotification($deposit->user, 'ADD_FUND_USER_USER', $params, $action, true);
        $this->userFirebasePushNotification($deposit->user, 'ADD_FUND_USER_USER', $params, route('child.panel.user.fund.index'), true);

        $params = [
            'username' => optional($deposit->user)->username,
            'amount' => currencyPosition($deposit->amount),
            'transaction' => $deposit->trx_id,
        ];
        $actionAdmin = [
            "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
            "link" => route('child.panel.admin.payment.log'),
            "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image)
        ];

        $this->childPanelAdminMail('ADD_FUND_USER_CHILD_PANEL_ADMIN', $params);
        $this->childPanelAdminPushNotification('ADD_FUND_USER_CHILD_PANEL_ADMIN', $params, $actionAdmin);
        $this->childPanelAdminFirebaseNotification('ADD_FUND_USER_CHILD_PANEL_ADMIN', $params, route('child.panel.admin.payment.log'));
    }

    public function setBonus($user, $amount, $commissionType = '')
    {
        $userId = $user->id;
        $i = 1;
        $level = Referral::where('commission_type', $commissionType)->count();

        DB::beginTransaction();
        while ($userId != "" || $userId != "0" || $i < $level) {

            $userRef = User::with('referral')->find($userId);
            $refer = $userRef->referral;
            if (!$refer) {
                break;
            }

            $commission = Referral::where('commission_type', $commissionType)->where('level', $i)->first();
            if (!$commission) {
                break;
            }

            $newCommission = ($amount * $commission->percent) / 100;
            $new_bal = getAmount($refer->balance + $newCommission);
            $refer->balance = $new_bal;
            $refer->save();

            $remarks = 'level ' . $i . ' Referral bonus From ' . $user->username;

            $transaction = Transaction::create([
                'transactional_id' => $commission->id,
                'transactional_type' => Referral::class,
                'user_id' => $refer->id,
                'trx_type' => '+',
                'amount' => $newCommission,
                'remarks' => $remarks,
                'charge' => 0,
            ]);

            ReferralBonus::create([
                'from_user_id' => $refer->id,
                'to_user_id' => $user->id,
                'level' => $i,
                'amount' => $newCommission,
                'main_balance' => $new_bal,
                'transaction' => $transaction->trx_id,
                'type' => $commissionType,
                'remarks' => $remarks,
            ]);

            $this->sendMailSms($refer, 'REFERRAL_BONUS', [
                'amount' => currencyPosition($newCommission),
                'currency' => basicControl()->currency_symbol,
                'bonus_from' => $user->username,
                'final_balance' => $refer->balance,
                'level' => $i
            ]);

            $msg = [
                'bonus_from' => $user->username,
                'amount' => currencyPosition($newCommission),
                'currency' => basicControl()->currency_symbol,
                'level' => $i
            ];
            $action = [
                "link" => route('user.referral.bonus'),
                "icon" => "fa fa-money-bill-alt"
            ];
            $this->userPushNotification($refer, 'REFERRAL_BONUS', $msg, $action);

            $userId = $refer->id;
            $i++;
        }
        DB::commit();
        return 0;
    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {
        $cryptoQr = $wallet . "?amount=" . $amount;
        return "https://quickchart.io/chart?cht=qr&chl=$cryptoQr";
    }
}
