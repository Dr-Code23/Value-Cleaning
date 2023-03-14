<?php

namespace Modules\Order\Repositories\Repository;


use Carbon\Carbon;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderAdminRepositoryInterface;
use Modules\Order\Transformers\OrderAdminIndexResource;
use Modules\Order\Transformers\OrderAdminResource;

class OrderAdminRepository implements OrderAdminRepositoryInterface
{

    private $orderModel;

    public function __construct(Order $order)
    {
        $this->orderModel = $order;
    }


    public function sendNewOrderNotification()
    {
       return auth()->user()->Notifications()->get("data");
        return ['statusCode' => 200, 'status' => true,
            'data' => $notifications
        ];
    }

    public function changeStates($data, $id)
    {

        $order = $this->orderModel->find($id);
        $order->update([
            'status' => $data['Status'],
        ]);
        return ['statusCode' => 200, 'status' => true,
            'Order_Status' => $order->status
        ];

    }

    public function index()
    {
        $order = $this->orderModel->latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => OrderAdminIndexResource::collection($order)
        ];

    }

    public function canceledOrder()
    {

        $order = $this->orderModel->where('status', 'Cansaled')->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'CansaledOrder' => OrderAdminIndexResource::collection($order)
        ];
    }

    public function finishedOrder()
    {

        $order = $this->orderModel->where('Status', 'Finished')->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'FinishedOrder' => OrderAdminIndexResource::collection($order)
        ];
    }

    public function show($id)
    {

        $order = $this->orderModel->find($id);
        return ['statusCode' => 200,'status' => true ,
            'data' => new OrderAdminResource($order)
        ];

    }

    public function updateOrderToAdmin($data, $id)
    {

        $order = $this->orderModel->find($id);

        $order->workers()->sync($data->all());

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order updated successfully ',
            'data' => new OrderAdminResource($order)
        ];

    }

    public function destroy($id)
    {

        $order = $this->orderModel->find($id);
        $order->delete();
        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
    }
        public function home()
    {

        $toDay=Order::whereDate('created_at', Carbon::today())->count();
        $finished=Order::where('status','Finished')->count();
        $cancel=Order::where('status','Cansaled')->count();
        $process=Order::where('status','In Process')->count();
        return response()->json(['toDay'=> $toDay ,'finished'=>$finished,'cancel'=>$cancel,'process'=>$process]);
    }

    public function serviceCount($id)
    {
        $Receipt = $this->orderModel->where(['id'=>$id,'payment_status'=>'Receipt'])->count();
        $Credit = $this->orderModel->where(['id'=>$id,'payment_status'=>'Credit'])->count();
        return ['statusCode' => 200,'status' => true ,
            'Credit' => $Credit,
            'Receipt'=>$Receipt
        ];
    }

}
