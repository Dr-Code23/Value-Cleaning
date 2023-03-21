<?php

namespace Modules\Order\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
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

    /**
     * @var string
     */
    protected $description = 'Create orders that weekly';

    /**
     * @var Order
     */
    protected Order $orderModel;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->orderModel = $order;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $tomorrow =  Carbon::now()->addDay()->dayOfWeek;

        $schedules = $this->orderModel->query()
            ->where([
                'repeat' => 'weekly',
                'status' => 'processing'
            ])
            ->whereNot('date','<>', Carbon::now())
            ->where('day', $tomorrow)
            ->take(20)
            ->get();

        if($schedules){
            foreach ($schedules as $schedule) {

              $dayOfWeek = Carbon::parse($schedule->date)->dayOfWeek;

                try {
                    DB::beginTransaction();

                    Schedule::create([
                        'order_id' => $schedule->order_id,
                        'date' => Carbon::now(),
                        'time' => $schedule->time,
                        'day' => $dayOfWeek,
                        'repeat' => $schedule->repeat,
                        'user_id' => $schedule->user_id,
                    ]);

                     // update => updated_at at order

                    DB::commit();

                }catch (\Exception){
                    DB::rollBack();
                }

               //

                  // review with eng.mohamed
           //     Notification::send(Auth::user(), new TaskReminderNotification($newSchedule));
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
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
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
