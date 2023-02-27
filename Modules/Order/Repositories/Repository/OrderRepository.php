<?php

namespace Modules\Order\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Transformers\OrderAdminResource;
use Modules\Order\Transformers\OrderResource;

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

    public function cansaledOrder()
    {
        $UserID = Auth::id();

        $Order = Order::where(['Status' => 'Cansaled', 'user_id' => $UserID])->with(['users', 'services', 'workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'CansaledOrder' => OrderResource::collection($Order)
        ];
    }

    public function finishedOrder()
    {
        $UserID = Auth::id();

        $Order = Order::where(['Status' => 'Finished', 'user_id' => $UserID])->with(['users', 'services', 'workers'])->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'FinishedOrder' => OrderResource::collection($Order)
        ];
    }

    public function store($data)
    {
        if (Auth::guard('api')->check()) {
            $userID = auth('api')->user()->getKey();
        }

        $data['user_id'] = $userID;
        $data['order_code']='#' . str_pad($userID + 1, 8, "0", STR_PAD_LEFT);
        $Order = Order::create($data->all());
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
        $Order = Order::where('id', $id)->first('order_code');
        return ['statusCode' => 200, 'status' => true,
            'Order Code ' => $Order

        ];

    }


    public function show($id)
    {
        $userId = Auth::id();

        $Order = Order::where(['id'=>$id,'user_id'=>$userId])->with(['users', 'services', 'workers'])->first();
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($Order)
        ];


    }


    public function Cansale($id)
    {
        $userId = Auth::id();

        $Order = Order::where(['id'=>$id,'user_id'=>$userId])->first();
        $Order['Status'] = 'Cansaled';
        $Order->update();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function Update($data, $id)
    {
        $userId = Auth::id();

        $Order = Order::where(['id'=>$id,'user_id'=>$userId])->with(['users', 'services', 'workers'])->first();;
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

    public function destroy($id)
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
