<?php

namespace Modules\Order\Console;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Schedule;
use Modules\Order\Notifications\TaskReminderNotification;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateOrderMonthlyCommand extends Command
{
    protected $signature = 'orders:createMonthly';


    protected $description = 'Create orders that monthly';

    /**
     * @var Order
     */
    protected Order $orderModel;

    /**
     * @var User
     */
    protected User $userModel;

    /**
     * @var Schedule
     */
    protected Schedule $scheduleModel;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Order $order, User $user, Schedule $schedule)
    {
        $this->orderModel = $order;
        $this->userModel = $user;
        $this->scheduleModel = $schedule;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $tomorrow = Carbon::now()->addMonthNoOverflow()->day;

        $data = $this->orderModel->query()
            ->whereNot([
                'repeat' => 'once',
                'status' => 'canceled'
            ])
            ->whereNot(['scheduled_at' => Carbon::now()])
            ->where(['day' => $tomorrow, 'repeat' => 'monthly'])
            ->take(200)
            ->get()->toArray();
        if ($data) {
            if (count($data) > 0) {
                foreach (array_chunk($data, 50) as $orders) {
                    foreach ($orders as $order) {
                        $dayOfWeek = Carbon::parse($order['date'])->dayOfWeek;
                        try {
                            DB::beginTransaction();

                            $this->scheduleModel->create([
                                'order_id' => $order['id'],
                                'date' => Carbon::now()->addDay(),
                                'time' => $order['time'],
                                'day' => $dayOfWeek,
                                'user_id' => $order['user_id'],
                            ]);

                            $orderModel = $this->orderModel->find($order['id']);
                            $orderModel->scheduled_at = Carbon::now();
                            $orderModel->save();

                            DB::commit();

                        } catch (Exception) {
                            DB::rollBack();
                        }

                        $user = $this->userModel->where('id', $order['user_id'])->first();

                        $user->notify(new TaskReminderNotification($order));
                    }
                }
            }
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
