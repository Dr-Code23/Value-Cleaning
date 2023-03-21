<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Order\Console\CreateOrderCommand;


class Kernel extends ConsoleKernel
{
    protected $commands = [
        CreateOrderCommand::class
    ];


    protected function schedule(Schedule $schedule)
    {

        $schedule->command(CreateOrderCommand::class)->weeklyOn(3, '1:00 PM');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
