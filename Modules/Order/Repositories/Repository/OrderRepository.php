<?php

namespace Modules\Order\Repositories\Repository;


use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Order\Events\OrderCanceled;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Traits\orderSchedule;
use Modules\Order\Traits\totalPrice;
use Modules\Order\Transformers\OrderResource;
use Modules\Service\Entities\Service;
use Modules\Service\Transformers\ServiceResource;
use PDF;


class OrderRepository implements OrderRepositoryInterface
{

    use totalPrice, orderSchedule;

    private $orderModel;

    public function __construct(Order $order)
    {
        $this->orderModel = $order;
    }

    public function index()
    {

        $orders = $this->orderModel->whereHas('Schedules', function ($query) {

            $query->where('user_id', Auth::id());

        })->latest()->get();
        return response()->json - (['statusCode' => 200, 'status' => true,
                'data' => OrderResource::collection($orders)
            ]);

    }

    public function canceledOrder()
    {
        $userId = Auth::id();

        $Order = $this->orderModel->where(['Status' => 'Cansaled', 'user_id' => $userId])->latest()->get();
        return response()->json(['statusCode' => 200, 'status' => true,
            'CansaledOrder' => OrderResource::collection($Order)
        ]);
    }

    public function finishedOrder()
    {
        $userId = Auth::id();

        $Order = $this->orderModel->where(['Status' => 'Finished', 'user_id' => $userId])->latest()->get();
        return response()->json(['statusCode' => 200, 'status' => true,
            'FinishedOrder' => OrderResource::collection($Order)
        ]);
    }

    public function store($data)
    {
        $data['user_id'] = auth()->id();
        $data['total_price'] = $this->totalPrice($data);
        $data['order_code'] = '#' . str_pad($this->totalPrice($data) + 1, 8, "0", STR_PAD_LEFT);
        $data['date'] = date('Y-m-d', strtotime($data['date']));

        $order = $this->orderModel->create($data->all());
        $schedule = $this->schedule($order);

        if ($data['sub_service_id']) {
            $order->sub_services()->sync($data['sub_service_id']);
        }

        if ($data->hasFile('gallery')) {
            foreach ($data->gallery as $gallery) {
                $order->addMedia($gallery)->toMediaCollection('Orders');
            }
        }
        $admin = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'admin');
        })->get();

        event(new OrderCreated($order));
//        broadcast(new OrderCreated($order))->toOthers();

        return response()->json([
            'statusCode' => 200,
            'status' => true,
            'data' => new OrderResource($order),
            'schedule' => $schedule
        ], 200);

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
        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->first();
        $offer = Offer::when('service_id', $order->service_id)->where('service_id', $order->service_id)->first('offer_percent');
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($order),
            'offer' => $offer
        ];


    }


    public function cancele($id)
    {
        $userId = Auth::id();
        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->first();
        $order->update(['Status' => 'Cansaled']);
        $admin = User::whereHas('roles', function ($query) {

            $query->where('name', '=', 'admin');

        })->get();
        event(new OrderCanceled($id));
        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Cansaled successfully ',


        ];
    }

    public function update($data, $id)
    {
        $userId = Auth::id();
        $order = $this->orderModel->where(['id' => $id, 'user_id' => $userId])->first();
        $data['date'] = date('Y-m-d', strtotime($data->date));
        $order->update($data->all());
        $this->updateSchedule($order);
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
        $service = Service::where('id', $order->service_id)->first();
        $offer = Offer::where('service_id', $order->service_id)->first();
        $data = [
            'date' => date('m/d/Y'),
            'order' => new OrderResource($order),
            'user' => Auth::user(),
            'service' => new ServiceResource($service),
            'offer' => $offer,
        ];
        $pdf = PDF::loadView('order::index', $data);
        // download PDF file with download method
        return $pdf->download('pdf_file.pdf');
    }

}
