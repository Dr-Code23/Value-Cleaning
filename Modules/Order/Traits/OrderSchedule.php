<?php

namespace Modules\Order\Traits;

use Carbon\Carbon;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Schedule;
trait OrderSchedule
{


    public function schedule($data)
    {
        $order=Order::where('id',$data->id)->first();
        $dayOfWeek = Carbon::parse($data->date)->dayOfWeek;
        return $schedule =Schedule::create([
            'order_id'       => $data->id,
            'date'           => $data->date,
            'day'            => $dayOfWeek,
            'time'           => $data->time,
            'repeat'         => $data->repeat,
            'status'         => $order->status,
            'payment_status' => $order->payment_status,
            'user_id'        => $data->user_id,
        ]);


    }
    public function updateSchedule($data)
    {
        $schedule = Schedule::where(['order_id' => $data->id, 'user_id' => $data->user_id])->first();
        $dayOfWeek= Carbon::parse($data->date)->dayOfWeek;
        return $schedule->update([
            'order_id'       => $data->id,
            'date'           => $data->date,
            'day'            => $dayOfWeek,
            'time'           => $data->time,
            'repeat'         => $data->repeat,
            'status'         => $data->status,
            'payment_status' => $data->payment_status,
            'user_id'        => $data->user_id,
        ]);


    }

}
