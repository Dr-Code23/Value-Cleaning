<?php

namespace Modules\Order\Repositories\Repository;


use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Transformers\OrderResource;

class OrderRepository implements OrderRepositoryInterface
{


    public function index(){
        $UserID=Auth::id();
        $Order=  Order::latest()->get()->where('user_id',$UserID);

        return ['statusCode' => 200, 'status' => true,
            'data' =>  OrderResource::collection($Order)
        ];

    }
    public function CansaledOrder()
    {
        $UserID=Auth::id();

        $Order=  Order::latest()->Where('Status','Cansaled')->Where('user_id',$UserID)->get();
        return ['statusCode' => 200, 'status' => true,
            'CansaledOrder' =>  OrderResource::collection($Order)
        ];
    }
    public function FinishedOrder()
    {
        $UserID=Auth::id();

        $Order=  Order::latest()->Where('Status','Finished' )->get()->Where('user_id',$UserID);
        return ['statusCode' => 200, 'status' => true,
            'FinishedOrder' =>  OrderResource::collection($Order)
        ];
    }
    public function create($data)
    {




        $Order = Order::create($data->all());
        $Order['order_code']= '#'.str_pad($Order->id + 1, 8, "0", STR_PAD_LEFT);

        $Order->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('Orders');  });

        $Order->save();

        return ['statusCode' => 200,'status' => true ,
            'data' => new OrderResource($Order)
        ];


    }


    public function OrderCode($id)
    {
        $Order =  Order::select('order_code')->where('id', $id)->first();


        return ['statusCode' => 200,'status' => true ,
        'Order Code ' => $Order

    ];

    }


    public function show($id)
    {
        $UserID=Auth::id();

        $Order = Order::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new OrderResource($Order)
        ];


    }


    public function Cansale($id)
    {
        $Order = Order::find($id);
        $Order['Status']='Cansaled';
        $Order->update();
        return ['statusCode' => 200,'status' => true ,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function Update($data ,$id)
    {



        $UserID=Auth::id();

        $Order = Order::find($id);
        $Order->update($data->all());


        $Order->addMultipleMediaFromRequest(['gallery'])->each(function ($fileAdder) {
            $fileAdder->toMediaCollection('Orders');  });

        $Order->save();

        return ['statusCode' => 200,'status' => true ,
            'message' => 'Order updated successfully ',
            'data' => new OrderResource($Order)
        ];
    }


    public function delete($id){
        $UserID=Auth::id();

        $Order = Order::find($id)->Where('user_id',$UserID);


    $Order->delete();

        $msg='Deleted';
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);    }



}
