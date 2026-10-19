<?php

namespace App\Console\Commands;

use App\Models\ApiProvider;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\User;
use App\Services\Growth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

/** Morning summary of yesterday for the owner (sent to the alert email from the admin dashboard). */
class DailyReport extends Command
{
    protected $signature = 'app:daily-report {--print : Show the report instead of emailing it}';

    protected $description = 'Email yesterday\'s sales, profit, new customers and problems to the owner';

    public function handle(): int
    {
        $from = now()->subDay()->startOfDay();
        $to = now()->subDay()->endOfDay();
        $site = basicControl()->site_title ?: config('app.name');
        $cur = basicControl()->base_currency;
        $money = fn($v) => number_format((float) $v, 2) . ' ' . $cur;

        $orders = Order::whereBetween('created_at', [$from, $to])->whereNotIn('status', ['canceled', 'refunded', 'fail']);
        $sales = (float) (clone $orders)->sum('price');
        $cost = (float) (clone $orders)->sum('provider_cost');
        $count = (clone $orders)->count();
        $deposits = (float) Deposit::where('status', 1)->whereBetween('updated_at', [$from, $to])->sum('payable_amount_in_base_currency');
        $bonuses = (float) Deposit::where('status', 1)->whereBetween('updated_at', [$from, $to])->sum('bonus_amount');
        $newUsers = User::whereBetween('created_at', [$from, $to])->count();
        $failed = Order::whereBetween('created_at', [$from, $to])->whereNull('api_order_id')->whereNotNull('status_description')
            ->where('status_description', 'like', 'error%')->count();
        $loss = collect(Cache::get('zk_loss_events', []))->filter(fn($e) => ($e['at'] ?? '') >= $from->toDateTimeString());
        $lowProviders = ApiProvider::where('status', 1)->where('low_balance_alert', '>', 0)->get()
            ->filter(fn($p) => (float) $p->balance < (float) $p->low_balance_alert);

        $lines = [
            "{$site}: report for " . $from->format('l j M Y'),
            '',
            "Orders: {$count}",
            'Sales: ' . $money($sales),
            'Provider cost: ' . $money($cost),
            'Profit: ' . $money($sales - $cost),
            'Deposits received: ' . $money($deposits) . ($bonuses > 0 ? ' (bonuses given: ' . $money($bonuses) . ')' : ''),
            "New customers: {$newUsers}",
            '',
            $failed ? "⚠ {$failed} order(s) were refused by the provider: check Orders." : '✓ No refused orders.',
            $lowProviders->isNotEmpty() ? '⚠ Low provider balance: ' . $lowProviders->map(fn($p) => "{$p->api_name} ({$p->balance} {$p->currency})")->implode(', ') : '✓ Provider balances OK.',
            $loss->isNotEmpty() ? '⚠ Switched off by loss protection: ' . $loss->map(fn($e) => "#{$e['id']} {$e['title']} ({$e['reason']})")->implode('; ') : '✓ No services switched off.',
            '',
            route('admin.dashboard'),
        ];
        $body = implode("\n", $lines);

        if ($this->option('print')) {
            $this->line($body);
            return self::SUCCESS;
        }
        $email = basicControl()->alert_email ?? null;
        if (!Growth::get('report.daily', true) || !$email) {
            $this->info('Daily report is off or no alert email is set.');
            return self::SUCCESS;
        }
        try {
            Mail::raw($body, fn($m) => $m->to($email)->subject("{$site}: daily report " . $from->format('j M')));
            $this->info("Report sent to {$email}.");
        } catch (\Throwable $e) {
            $this->warn('Could not send the report: ' . $e->getMessage());
        }
        return self::SUCCESS;
    }
}
