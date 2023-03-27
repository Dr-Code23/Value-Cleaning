<?php

namespace Modules\Order\Repositories\Repository;


use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Order\Events\OrderCanceled;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Traits\OrderSchedule;
use Modules\Order\Traits\TotalPrice;
use Modules\Order\Transformers\OrderResource;
use Modules\Service\Entities\Service;
use Modules\Service\Transformers\ServiceResource;
use Exception;

class OrderRepository implements OrderRepositoryInterface
{

    use TotalPrice, OrderSchedule;

    /**
     * @var Order
     */
    private Order $orderModel;

    /**
     * @var Offer
     */
    private Offer $offerModel;

    /**
     * @var Service
     */
    private Service $serviceModel;

    /**
     * @param Order $order
     * @param Offer $offer
     * @param Service $service
     */
    public function __construct(Order $order, Offer $offer, Service $service)
    {
        $this->orderModel = $order;
        $this->offerModel = $offer;
        $this->serviceModel = $service;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $orders = $this->orderModel->query()->whereHas('Schedules', function ($query) {

            $query->where('user_id', Auth::id());

        })->latest()->get();

        return response()->json(['statusCode' => 200, 'status' => true,
            'data' => OrderResource::collection($orders)
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function canceledOrder(): JsonResponse
    {
        $userId = Auth::id();
        $Order = $this->orderModel->query()
            ->where(['status' => 'canceled', 'user_id' => $userId])
            ->latest()
            ->get();
        return response()->json(['statusCode' => 200, 'status' => true,
            'canceledOrder' => OrderResource::collection($Order)
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function finishedOrder(): JsonResponse
    {
        $userId = Auth::id();

        $order = $this->orderModel->query()
            ->where(['status' => 'finished', 'user_id' => $userId])
            ->latest()
            ->get();

        return response()->json(['statusCode' => 200, 'status' => true,
            'finishedOrder' => OrderResource::collection($order)
        ]);
    }

    /**
     * @param $data
     * @return JsonResponse
     */
    public function store($data): JsonResponse
    {
        $data['user_id'] = auth()->id();
        $data['total_price'] = $this->totalPrice($data);
        $data['order_code'] = '#' . str_pad($this->totalPrice($data) + 1, 8, "0", STR_PAD_LEFT);
        $data['date'] = Carbon::parse(date('Y-m-d', strtotime($data['date'])));
        $data['day'] = Carbon::parse($data['date'])->dayOfWeek;
        $order = $this->orderModel->create($data->all());

        $schedule = $this->schedule($order);

        if ($data['sub_service_id']) {
            $order->sub_services()->sync($data['sub_service_id']);
        }

        if ($data->gallery) {
            foreach ($data->gallery as $gallery) {
                $order->addMedia($gallery)->toMediaCollection('Orders');
            }
        }

        event(new OrderCreated($order));
//        broadcast(new OrderCreated($order))->toOthers();

        return response()->json([
            'statusCode' => 200,
            'status' => true,
            'data' => new OrderResource($order),
            'schedule' => $schedule
        ]);
    }

    /**
     * @param $id
     * @return array
     */
    public function orderCode($id): array
    {
        $order = $this->orderModel->query()->where('id', $id)->first('order_code');
        return ['statusCode' => 200, 'status' => true,
            'Order Code ' => $order
        ];
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id): array
    {
        $userId = Auth::id();

        $order = $this->orderModel->query()->where(['id' => $id, 'user_id' => $userId])->first();

        $offer = $this->offerModel->query()
            ->when('service_id', $order->service_id)
            ->where('service_id', $order->service_id)
            ->first('offer_percent');

        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderResource($order),
            'offer' => $offer
        ];
    }

    /**
     * @param $id
     * @return array
     */
    public function cancel($id): array
    {
        $userId = Auth::id();
        $order = $this->orderModel->query()->where(['id' => $id, 'user_id' => $userId])->first();

        $order->update(['status' => 'canceled']);

        event(new OrderCanceled($order));

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order Canceled successfully ',
        ];
    }

    /**
     * @param $data
     * @param $id
     * @return array
     */
    public function update($data, $id): array
    {
        $userId = Auth::id();

        $order = $this->orderModel->query()
            ->where(['id' => $id, 'user_id' => $userId])
            ->first();

        $data['date'] = date('Y-m-d', strtotime($data->date));
        $data['day'] = Carbon::parse($data['date'])->dayOfWeek;

        $order->update($data->all());

        $this->updateSchedule($order);

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order updated successfully ',
            'data' => new OrderResource($order)
        ];
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $userId = Auth::id();

        $order = $this->orderModel->query()->where(['user_id' => $userId, 'id' => $id])->first();

        try {
            $order->delete();

            $message = 'Deleted';

            return response()->json(['statusCode' => 200, 'status' => true, 'message' => $message]);

        } catch (Exception) {
            $message = 'Deleted';
            return response()->json(['statusCode' => 400, 'status' => false, 'message' => $message]);
        }
    }

    /**
     * @param $id
     * @return Response
     */
    public function downloadPdf($id): Response
    {
        $order = $this->orderModel->query()->where(['user_id' => Auth::id(), 'id' => $id])->first();

        $service = $this->serviceModel->query()->where('id', $order->service_id)->first();

        $offer = $this->offerModel->query()->where('service_id', $order->service_id)->first();

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
