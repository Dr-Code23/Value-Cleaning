<?php

namespace Modules\Order\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Order\Entities\Order;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateOrderMonthlyCommand extends Command
{
    protected $signature = 'orders:createMonthly';


    protected $description = 'Create orders that monthly';

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
        $orders = Order::where(function ($query) {
            return $query->where('status', 'Finished')
                ->orWhere('status', 'Processing');
        })->where('repeat', 'monthly')->get();
        foreach ($orders as $order) {
            // Create the new order
            $newOrder = Order::create([
                'worke_aera' => $order->worke_aera,
                'date' => Carbon::parse($order->date),
                'time' => Carbon::parse($order->time),
                'address' => $order->address,
                'repeat' => $order->repeat,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'user_id' => $order->user_id,
                'service_id' => $order->service_id,
                'total_price' => $order->total_price,
                'delivery_price' => $order->delivery_price,
                'order_code' => '#' . str_pad($order->id + 1, 8, '0', STR_PAD_LEFT),
            ]);
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
