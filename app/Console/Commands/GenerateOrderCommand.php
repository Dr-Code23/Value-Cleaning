<?php
//
//namespace App\Console\Commands;
//
//use Carbon\Carbon;
//use Illuminate\Console\Command;
//use Modules\Order\Entities\Order;
//
//class GenerateOrderCommand extends Command
//{
//    /**
//     * The name and signature of the console command.
//     *
//     * @var string
//     */
//    protected $signature = 'orders:create';
//
//
//    protected $description = 'Create orders that are due';
//    /**
//     * Execute the console command.
//     *
//     * @return int
//     */
//
//        public function handle()
//    {
//        $orders = Order::where('repeat','weekly')->get();
//        foreach ($orders as $order) {
//            // Code to create the order goes here
//            $orderDate = Carbon::parse($order->date);
//            $orderTime = Carbon::parse($order->time);
//            $code='#' . str_pad( $order->id+ 1, 8, "0", STR_PAD_LEFT);
//
//            $order= Order::create([
//                'worke_aera'=>$order->worke_aera,
//                'date'=>$orderDate ,
//                'time'=>$orderTime,
//                'address'=>$order->address,
//                'repeat'=>$order->repeat,
//                'status'=>$order->status,
//                'payment_status'=>$order->payment_status,
//                'user_id'=>$order->user_id,
//                'service_id'=>$order->service_id,
//                'total_price'=>$order->total_price,
//                'delivery_price'=>$order->delivery_price,
//                'order_code'=>$code,
//            ]);
//
//        }
//    }
//}
