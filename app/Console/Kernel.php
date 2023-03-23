<?php

namespace App\Console;

use App\Jobs\TranferBuildDataCalendar;
use App\Models\Calendar;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        'App\Console\Commands\TranferBuildDataCalendar'
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('TranferBuildDataCalendar:test')->dailyAt('09:10')->timezone('Asia/Ho_Chi_Minh');
        $schedule->command('command:UpdateDateCreateRequest')->monthlyOn(5, '09:30');
        $schedule->command('command:UpdateLeaveQuotas')->monthlyOn(5, '09:30');
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
