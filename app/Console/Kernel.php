<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Order\Console\CreateOrderCommand;
use Modules\Order\Console\CreateOrderMonthlyCommand;

class Kernel extends ConsoleKernel
{
    /**
     * @var string[]
     */
    protected $commands = [
        CreateOrderCommand::class,
        CreateOrderMonthlyCommand::class

    ];

    /**
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(CreateOrderCommand::class)->weeklyOn(3, '1:00 PM');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
