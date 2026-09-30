<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{


    protected $commands = [];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('update-provider:status')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('update-refill:status')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('draft-mass-order:delete')->daily();
        $schedule->command('app:auto-currency-update')->daily();
        $schedule->command('app:check-expired-domain')->daily();
        $schedule->command('app:gateway-currency-update')->daily();
        $schedule->command('app:service-price-update')->hourly();
        $schedule->command('model:prune')->dailyAt('03:30');
        $schedule->command('app:check-provider-balance')->hourly()->withoutOverlapping();

        // Heartbeat: the admin dashboard warns when this stops updating (cron not set up / stopped).
        $schedule->call(fn() => \Illuminate\Support\Facades\Cache::forever('zk_cron_last_run', now()->timestamp))->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
