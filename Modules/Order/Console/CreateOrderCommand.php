<?php

namespace Modules\Order\Console;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Schedule;
use Modules\Order\Notifications\TaskReminderNotification;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:create';


    protected $description = 'Create orders that weekly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $schedules = Schedule::where([
            'repeat' => 'weekly',
            'status' => 'Processing'
        ])->get();

        foreach ($schedules as $schedule) {

            $dayOfWeek = Carbon::parse($schedule->date)->dayOfWeek;

            $newschedule = Schedule::create([
                'order_id' => $schedule->order_id,
                'date' => $schedule->date,
                'time' => $schedule->time,
                'day' => $dayOfWeek,
                'repeat' => $schedule->repeat,
                'user_id' => $schedule->user_id,
            ]);

            Notification::send(Auth::user(), new TaskReminderNotification($newschedule));

            return true;
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
