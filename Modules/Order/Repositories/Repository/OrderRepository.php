<?php

namespace Modules\Order\Repositories\Repository;


use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Transformers\OrderResource;
use PDF;
use Stripe\StripeClient;
use Stripe;

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
        $data['order_code']='#' . str_pad($userId + 1, 8, "0", STR_PAD_LEFT);
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

        $order = $this->orderModel->where(['id'=>$id,'user_id'=>$userId])->with(['users', 'services', 'workers'])->first();
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($order)
        ];


    }


    public function Cansale($id)
    {
        $userId = Auth::id();

        $order = $this->orderModel->where(['id'=>$id,'user_id'=>$userId])->first();
        $order['Status'] = 'Cansaled';
        $order->update();
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function Update($data, $id)
    {
        $userId = Auth::id();

        $order = $this->orderModel->where(['id'=>$id,'user_id'=>$userId])->with(['users', 'services', 'workers'])->first();;
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

    public function downloadPdf($id)
    {

        $order = $this->orderModel->where(['user_id' => Auth::id(), 'id' => $id])->first();
        $data = [
            'date' => date('m/d/Y'),
            'order' => $order
        ];
        $pdf = PDF::loadView('order::index', $data);
        // download PDF file with download method
        return $pdf->download('pdf_file.pdf');
    }

    public function makePayment($data)

    {
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $token = $stripe->tokens->create([
                'card' => [
                    'number'    => $data->number,
                    'exp_month' => $data->exp_month,
                    'exp_year'  => $data->exp_year,
                    'cvc'       => $data->cvc,
                ],
            ]);

            $charge = $stripe->charges->create([
                'card'         => $token['id'],
                'currency'     => 'USD',
                'amount'       => ($data->amount * 100),
                'description'  => "New Payment Received from mobile app",
                'metadata'     => [
                    "order_id" => $data->order_id,
                ]

            ]);

            if ($charge->status == 'succeeded') {
                $data = ['transaction_id' => $charge->id];
                return ['success' => 1, 'message' => 'Transaction Success', 'data' => $data];
            } else {
                return ['success' => 0, 'message' => 'Card not charge, Please try again later', 'data' => []];
            }
        } catch (Exception $e) {
            return ['success' => 400, 'message' => "Error Processing Transaction", 'data' =>[]];
        }
    }

}
