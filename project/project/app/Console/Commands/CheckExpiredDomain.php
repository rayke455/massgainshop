<?php

namespace App\Console\Commands;

use Modules\ChildPanel\App\Models\ChildPanel;
use App\Models\Transaction;
use App\Traits\Notify;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckExpiredDomain extends Command
{
    use Notify;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-expired-domain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $childPanelPrice = basicControl()->child_panel_price;
        $childPanels = ChildPanel::where('status', 1)->expired()->get();

        foreach ($childPanels as $panel) {
            try {
                if ($panel->auto_renew == 0) {
                    $panel->status = 3;
                } elseif ($panel->auto_renew == 1) {

                    if (optional($panel->user)->balance > $childPanelPrice) {
                        $panel->user->balance = floatval(optional($panel->user)->balance) - floatval($childPanelPrice);
                        $panel->user->save();

                        $panel->validity_end = now()->addMonth();
                        $panel->status = 1;

                        Transaction::create([
                            'transactional_id' => $panel->id,
                            'transactional_type' => ChildPanel::class,
                            'user_id' => optional($panel->user)->id,
                            'trx_type' => '+',
                            'amount' => $panel->price,
                            'remarks' => 'Renewed child panel',
                            'charge' => 0
                        ]);
                        $this->sendNotification($panel, 'AUTOMATICALLY_RENEW_CHILD_PANEL');
                    }else{
                        $panel->status = 3;
                        $this->sendNotification($panel, 'FAIL_AUTOMATICALLY_RENEW_CHILD_PANEL');
                    }
                }
                $panel->save();
            } catch (\Exception $exception) {
                continue;
            }
        }
    }

    public function sendNotification($panel, $templateKey): void
    {
        try {
            $msg = [
                'username' => $panel->username,
                'domain' => $panel->domain,
            ];

            $action = [
                "link" => route('user.child.panel.index'),
                "icon" => "fa-light fa-bullhorn"
            ];

            $this->userPushNotification($panel->user, $templateKey, $msg, $action);
            $this->userFirebasePushNotification($panel->user, $templateKey, $msg, route('user.child.panel.index'));
            $this->sendMailSms($panel->user, $templateKey, [
                'domain' => $panel->domain,
                'username' => $panel->username,
            ]);
        } catch (\Exception $exception) {

        }
    }
}
