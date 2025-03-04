<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\UpdateDatBanStatus::class, // Đảm bảo có dòng này
    ];


    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('datban:update-status')->everyFiveMinutes();
        $schedule->command('datban:update-status')->everyMinute(); // Cập nhật trạng thái bàn
        $schedule->command('datban:send-reminder')->everyMinute(); // Gửi email nhắc nhở
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
