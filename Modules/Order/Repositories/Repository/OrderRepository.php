<?php

namespace Modules\Order\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Transformers\OrderAdminResource;
use Modules\Order\Transformers\OrderResource;
use Modules\Service\Entities\Service;

class OrderRepository implements OrderRepositoryInterface
{


    public function index()
    {
        $UserID = Auth::id();
        $Order = Order::where('user_id', $UserID)->with(['users', 'services', 'workers'])->latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => OrderResource::collection($Order)
        ];

    }

    public function CansaledOrder()
    {
        $UserID = Auth::id();

        $Order = Order::where('Status', 'Cansaled')->where('user_id', $UserID)->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'CansaledOrder' => OrderResource::collection($Order)
        ];
    }

    public function FinishedOrder()
    {
        $UserID = Auth::id();

        $Order = Order::where('Status', 'Finished')->where('user_id', $UserID)->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'FinishedOrder' => OrderResource::collection($Order)
        ];
    }

    public function create($data)
    {
        if (Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }

        $data['user_id'] = $userID;
        $Order = Order::create($data->all());
        $Order['order_code'] = '#' . str_pad($Order->id + 1, 8, "0", STR_PAD_LEFT);

        $Order->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('Orders');
        });

        $Order->save();

        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($Order)
        ];


    }


    public function OrderCode($id)
    {
        $Order = Order::where('id', $id)->first('order_code');
        return ['statusCode' => 200, 'status' => true,
            'Order Code ' => $Order

        ];

    }


    public function show($id)
    {
        $UserID = Auth::id();

        $Order = Order::find($id);
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($Order)
        ];


    }


    public function Cansale($id)
    {
        $Order = Order::find($id);
        $Order['Status'] = 'Cansaled';
        $Order->update();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function Update($data, $id)
    {

        $UserID = Auth::id();

        $Order = Order::find($id);
        $Order->update($data->all());
        $Order->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('Orders');
        });
        $Order->save();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order updated successfully ',
            'data' => new OrderResource($Order)
        ];
    }

    public function UpdateOeserToAdmin($data, $id)
    {
       $Order = Order::find($id);
        $Order->workers()->sync($data->all());
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order updated successfully ',
            'data' => new OrderAdminResource($Order)
        ];


    }


    public function delete($id)
    {
        $UserID = Auth::id();


        $Order = Order::where(['user_id' => $UserID, 'id' => $id])->first();
        try {

            $Order->delete();
            $msg = 'Deleted';
            return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['statusCode' => 400, 'status' => false, 'message' => $msg]);

        }

    }


}
