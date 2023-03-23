<?php

namespace Modules\Order\Repositories\Repository;


use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderAdminRepositoryInterface;
use Modules\Order\Transformers\OrderAdminIndexResource;
use Modules\Order\Transformers\OrderAdminResource;

class OrderAdminRepository implements OrderAdminRepositoryInterface
{
    /**
     * @var Order
     */
    private $orderModel;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->orderModel = $order;
    }

    /**
     * @return array
     */
    public function sendNewOrderNotification()
    {
        $notifications = auth()->user()->Notifications()->get("data");
        return ['statusCode' => 200, 'status' => true,
            'data' => $notifications
        ];
    }

    /**
     * @param $data
     * @param $id
     * @return array
     */
    public function changeStates($data, $id)
    {

        $order = $this->orderModel->find($id);
        $order->update([
            'status' => $data['Status'],

        ]);
        return ['statusCode' => 200, 'status' => true,
            'Order_Status' => $order->status
        ];

    }

    /**
     * @return array
     */
    public function index(): array
    {
        $order = $this->orderModel->latest()->get();

        return ['statusCode' => 200, 'status' => true,
            'data' => OrderAdminIndexResource::collection($order)
        ];

    }

    /**
     * @return array
     */
    public function canceledOrder(): array
    {

        $order = $this->orderModel->query()->where('status', 'canceled')->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'canceledOrder' => OrderAdminIndexResource::collection($order)
        ];
    }

    /**
     * @return array
     */
    public function finishedOrder(): array
    {

        $order = $this->orderModel->query()->where('Status', 'finished')->latest()->get();
        return ['statusCode' => 200, 'status' => true,
            'finishedOrder' => OrderAdminIndexResource::collection($order)
        ];
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {

        $order = $this->orderModel->find($id);
        return ['statusCode' => 200, 'status' => true,
            'data' => new OrderAdminResource($order)
        ];

    }

    /**
     * @param $data
     * @param $id
     * @return array
     */
    public function updateOrderToAdmin($data, $id): array
    {

        $order = $this->orderModel->query()->find($id);

        $order->workers()->sync($data->all());

        return ['statusCode' => 200, 'status' => true,
            'message' => 'Order updated successfully ',
            'data' => new OrderAdminResource($order)
        ];

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {

        $order = $this->orderModel->find($id);
        $order->delete();
        $msg = 'Deleted';
        return response()->json(['statusCode' => 200, 'status' => true, 'message' => $msg]);
    }

    /**
     * @return JsonResponse
     */
    public function home()
    {

        $toDay = Order::query()->whereDate('created_at', Carbon::today())->count();
        $finished = Order::query()->where('status', 'finished')->count();
        $cancel = Order::query()->where('status', 'canceled')->count();
        $process = Order::query()->where('status', 'processing')->count();
        return response()->json(['toDay' => $toDay, 'finished' => $finished, 'cancel' => $cancel, 'process' => $process]);
    }

    /**
     * @param $id
     * @return array
     */
    public function serviceCount($id)
    {
        $Receipt = $this->orderModel->query()->where(['id' => $id, 'payment_status' => 'Receipt'])->count();
        $Credit = $this->orderModel->query()->where(['id' => $id, 'payment_status' => 'Credit'])->count();
        return ['statusCode' => 200, 'status' => true,
            'Credit' => $Credit,
            'Receipt' => $Receipt
        ];
    }
}
