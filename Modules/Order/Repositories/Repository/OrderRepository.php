<?php

namespace Modules\Order\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Transformers\OrderResource;

class OrderRepository implements OrderRepositoryInterface
{

    private $orderModel;

    public function __construct(Order $order)
    {
        $this->orderModel = $order;
    }

    public function index()
    {
        $userId = Auth::id();
        $Order = $this->orderModel->where('user_id', $userId)->with(['users', 'services', 'workers'])->latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => OrderResource::collection($Order)
        ];

    }

    public function cansaledOrder()
    {
        $userId = Auth::id();

        $Order = $this->orderModel->where(['Status' => 'Cansaled', 'user_id' => $userId])->with(['users', 'services', 'workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'CansaledOrder' => OrderResource::collection($Order)
        ];
    }

    public function finishedOrder()
    {
        $userId = Auth::id();

        $Order = $this->orderModel->where(['Status' => 'Finished', 'user_id' => $userId])->with(['users', 'services', 'workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'FinishedOrder' => OrderResource::collection($Order)
        ];
    }

    public function store($data)
    {
        if (Auth::guard('api')->check()) {
            $userId = auth('api')->user()->getKey();
        }

        $data['user_id'] = $userId;
        $data['order_code'] = '#' . str_pad($userId + 1, 8, "0", STR_PAD_LEFT);
        $Order = $this->orderModel->create($data->all());
        $Order->sub_services()->sync($data->sub_service_id);

        $Order->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('Orders');
        });

        $Order->save();
        return ['statusCode' => 200, 'status' => true
        ];


    }

    public function orderCode($id)
    {
        $order = $this->orderModel->where('id', $id)->first('order_code');
        return ['statusCode' => 200, 'status' => true,
            'Order Code ' => $order

        ];

    }

    public function show($id)
    {
        $userId = Auth::id();

        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->with(['users', 'services', 'workers'])->first();
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($order)
        ];


    }


    public function Cansale($id)
    {
        $userId = Auth::id();

        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->first();
        $order['Status'] = 'Cansaled';
        $order->update();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function Update($data, $id)
    {
        $userId = Auth::id();
        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->with(['users', 'services', 'workers'])->first();;
        $order->update($data->all());
        $order->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('Orders');
        });
        $order->save();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order updated successfully ',
            'data' => new OrderResource($order)
        ];
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $order = $this->orderModel->where(['user_id' => $userId, 'id' => $id])->first();
        try {
            $order->delete();
            $msg = 'Deleted';
            return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 400, 'status' => false, 'message' => $msg]);

        }

    }


}
