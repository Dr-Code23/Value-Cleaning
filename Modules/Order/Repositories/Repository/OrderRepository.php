<?php

namespace Modules\Order\Repositories\Repository;


use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Order\Notifications\CancelOrderNotification;
use Modules\Order\Notifications\NewOrderNotification;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Traits\totalPrice;
use Modules\Order\Transformers\OrderResource;
use Modules\Service\Entities\Service;
use Modules\Service\Transformers\ServiceResource;
use PDF;

class OrderRepository implements OrderRepositoryInterface
{

use totalPrice;
    private $orderModel;

    public function __construct(Order $order)
    {
        $this->orderModel = $order;
    }

    public function index()
    {
        $userId = Auth::id();
        $order = $this->orderModel->where('user_id', $userId)->latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => OrderResource::collection($order)
        ];

    }

    public function canceledOrder()
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
        $data['total_price'] = $this->totalPrice($data);
        $data['order_code']='#' . str_pad($this->totalPrice($data) + 1, 8, "0", STR_PAD_LEFT);
        $data['date']=date('Y-m-d H:i:s', strtotime($data['date']));
        $order = $this->orderModel->create($data->all());
        $order->sub_services()->sync($data->sub_service_id);
        if ($data->hasFile('gallery')) {
            $order->addMediaFromRequest('gallery')->toMediaCollection('Orders');

            $order->save();
        }
        $admin = User::whereHas('roles', function ($query) {

            $query->where('name', '=', 'admin');

        })->get();
        Notification::send($admin, new NewOrderNotification($order));

        return [
            'statusCode' => 200,
            'status' => true,
            'data'=> new OrderResource($order),
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
        $order = $this->orderModel->where(['id'=>$id,'user_id'=>$userId])->first();
        $offer =Offer::when('service_id',$order->service_id)->where('service_id',$order->service_id)->first('offer_percent');
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($order),
            'offer'=>$offer
        ];


    }


    public function cancele($id)
    {
        $userId = Auth::id();
        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->first();
        $order->update(['Status'=>'Cansaled']);
        $admin = User::whereHas('roles', function ($query) {

            $query->where('name', '=', 'admin');

        })->get();
        Notification::send($admin, new CancelOrderNotification($order));

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function update($data, $id)
    {
        $userId = Auth::id();
        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->with(['users', 'services', 'workers'])->first();
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
        } catch (Exception $e) {
            return response()->json(['statusCode' => 400, 'status' => false, 'message' => $msg]);

        }

    }

    public function downloadPdf($id)
    {
        $order = $this->orderModel->where(['user_id' => Auth::id(), 'id' => $id])->first();
        $service=Service::where('id',$order->service_id)->first();
        $offer=Offer::where('service_id',$order->service_id)->first();
        $data = [
            'date' => date('m/d/Y'),
            'order' =>  new OrderResource($order),
            'user'=>Auth::user(),
            'service'=> new ServiceResource($service),
            'offer' => $offer,
        ];
        $pdf = PDF::loadView('order::index', $data);
        // download PDF file with download method
        return $pdf->download('pdf_file.pdf');
    }

}
