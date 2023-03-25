<?php

namespace Modules\Order\Console;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $tomorrow = Carbon::now()->addDay()->dayOfWeek;

        $orders = $this->orderModel->query()
            ->whereNot([
                'repeat' => 'once',
                'status' => 'canceled'
            ])
            ->where('date', '=', Carbon::now()->addDay())
            ->where('day', $tomorrow)
            ->get();

        if ($orders) {
            foreach ($orders as $order) {

                $dayOfWeek = Carbon::parse($order->date)->dayOfWeek;

                try {
                    DB::beginTransaction();

                    Schedule::create([
                        'order_id' => $order->id,
                        'date' => Carbon::now(),
                        'time' => $order->time,
                        'day' => $dayOfWeek,
                        'repeat' => $order->repeat,
                        'user_id' => $order->user_id,
                    ]);

                    // update => date at order
                    $order = $this->orderModel
                        ->query()
                        ->where('id', $order->id)->first();


                    $order->update(['date' => Carbon::now()]);

                    DB::commit();

                } catch (Exception) {
                    DB::rollBack();
                }

                $user = User::where('id', $order->user_id)->first();

                $user->notify(new TaskReminderNotification($order));
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
