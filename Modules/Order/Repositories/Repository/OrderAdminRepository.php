<?php

namespace Modules\Order\Repositories\Repository;


use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderAdminRepositoryInterface;
use Modules\Order\Transformers\OrderAdminIndexResource;
use Modules\Order\Transformers\OrderAdminResource;

class OrderAdminRepository implements OrderAdminRepositoryInterface
{
    public function ChangeStutes($data, $id)
    {

        $Order = Order::find($id);
        $Order->update([
            'status' => $data['Status'],


        ]);
        return ['statusCode' => 200, 'status' => true,
            'Order_Status' =>  $Order->status
        ];

    }


    public function index(){
        $Order=  Order::latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' =>  OrderAdminIndexResource::collection($Order)
        ];

    }
    public function CansaledOrder()
    {

        $Order=  Order::latest()->Where('Status','Cansaled')->get();
        return ['statusCode' => 200, 'status' => true,
            'CansaledOrder' =>  OrderAdminIndexResource::collection($Order)
        ];
    }
    public function FinishedOrder()
    {

        $Order=  Order::latest()->Where('Status','Finished' )->get();
        return ['statusCode' => 200, 'status' => true,
            'FinishedOrder' =>  OrderAdminIndexResource::collection($Order)
        ];
    }








    public function show($id)
    {

        $Order = Order::find($id);


        return ['statusCode' => 200,'status' => true ,
            'data' => new OrderAdminResource($Order)
        ];


    }





    public function UpdateOeserToAdmin($data ,$id)
    {




        $Order = Order::find($id);



        $Order->workers()->sync($data->all());


       return ['statusCode' => 200,'status' => true ,
        'message' => 'Order updated successfully ',
        'data' => new OrderAdminResource($Order)
    ] ;


    }


    public function delete($id){

        $Order = Order::find($id);


    $Order->delete();

        $msg='Deleted';
        return response()->json(['statusCode' => 200,'status' => true , 'message' =>  $msg ]);    }



}
