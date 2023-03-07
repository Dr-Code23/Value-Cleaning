<?php

namespace Modules\Order\Repositories\Repository;


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

    public function changeStutes($data, $id)
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

    public function cansaledOrder()
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

}
