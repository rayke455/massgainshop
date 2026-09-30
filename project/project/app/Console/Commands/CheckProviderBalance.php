<?php

namespace App\Console\Commands;

use App\Models\ApiProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Refreshes every active provider's balance and warns the admin when it drops below the provider's
 * "low balance alert" amount — before customer orders start failing. Emails at most every 12 hours.
 */
class CheckProviderBalance extends Command
{
    protected $signature = 'app:check-provider-balance';

    protected $description = 'Refresh provider balances and alert the admin when one is running low';

    public function handle(): int
    {
        $low = [];
        foreach (ApiProvider::where('status', 1)->get() as $provider) {
            try {
                $res = app(\App\Services\ProviderApi::class)->balance($provider);
            } catch (\Throwable $e) {
                $this->warn("{$provider->api_name}: could not reach the provider ({$e->getMessage()})");
                continue;
            }
            if (!isset($res['balance'])) {
                $this->warn("{$provider->api_name}: " . ($res['error'] ?? 'no balance in response'));
                continue;
            }

            $provider->balance = (float) $res['balance'];
            $provider->currency = $res['currency'] ?? $provider->currency;
            $provider->balance_checked_at = now();

            $isLow = $provider->low_balance_alert > 0 && $provider->balance < (float) $provider->low_balance_alert;
            if (!$isLow) {
                $provider->balance_alerted_at = null; // topped up: alert again next time it drops
            } elseif (!$provider->balance_alerted_at || $provider->balance_alerted_at->lt(now()->subHours(12))) {
                $low[] = $provider;
                $provider->balance_alerted_at = now();
            }
            $provider->save();

            $this->line(sprintf('%s: %s %s%s', $provider->api_name, $provider->balance, $provider->currency, $isLow ? '  <- LOW' : ''));
        }

        if ($low) {
            $this->sendEmail($low);
        }
        return self::SUCCESS;
    }

    private function sendEmail(array $providers): void
    {
        $to = basicControl()->alert_email ?? null;
        if (!$to) {
            return; // the red warning on the admin dashboard still shows
        }

        $site = basicControl()->site_title ?: config('app.name');
        $lines = array_map(fn($p) => "- {$p->api_name}: {$p->balance} {$p->currency} left (alert below {$p->low_balance_alert})", $providers);
        $body = "Your provider balance is running low on {$site}.\n\n" . implode("\n", $lines)
            . "\n\nTop up soon, otherwise new customer orders will fail.\n\n" . route('admin.api-provider.index');

        try {
            Mail::raw($body, fn($m) => $m->to($to)->subject("⚠ Low provider balance on {$site}"));
        } catch (\Throwable $e) {
            $this->warn('Alert email could not be sent: ' . $e->getMessage());
            report($e);
        }
    }
}
