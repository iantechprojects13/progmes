<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\LockComplianceTools::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('update:lock-compliance-tools')
                ->dailyAt('14:08')
                ->timezone('Asia/Manila')
                ->when(function() {
                    $now = Carbon::now('Asia/Manila');
                    return $now->year == 2025 && $now->month == 5 && $now->day == 16;
                });
    }



    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
