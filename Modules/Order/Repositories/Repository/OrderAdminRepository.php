<?php

namespace Modules\Order\Repositories\Repository;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Category\Entities\Category;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\Interfaces\OrderAdminRepositoryInterface;
use Modules\Order\Transformers\OrderAdminIndexResource;
use Modules\Order\Transformers\OrderAdminResource;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;
use Modules\Worker\Entities\Worker;
use Spatie\Permission\Models\Role;

class OrderAdminRepository implements OrderAdminRepositoryInterface
{
    /**
     * @var Order
     */
    private $orderModel;
    private Service $serviceModel;
    private Category $categoryMpdel;
    private SubService $subServiceModel;
    private User $userModel;
    private Worker $workerModel;
    private Role $roleModel;

    /**
     * @param Order $order
     */
    public function __construct(Order $order, Service $service, SubService $subService, Category $category, Worker $worker, User $user, Role $role)
    {
        $this->orderModel = $order;
        $this->serviceModel = $service;
        $this->categoryMpdel = $category;
        $this->subServiceModel = $subService;
        $this->userModel = $user;
        $this->workerModel = $worker;
        $this->roleModel = $role;
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

        $order->workers()->sync($data->worker_id);

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
        $service = $this->serviceModel->query()->count();

        $category = $this->categoryMpdel->query()->count();

        $subService = $this->subServiceModel->query()->count();

        $user = $this->userModel->query()->where('type', 'user')->count();
        $company = $this->userModel->query()->where('type', 'company')->count();
        $employee = $this->userModel
            ->query()
            ->where('type', 'admin')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();

        $worker = $this->workerModel->query()->count();

        $role = $this->roleModel->query()->count();


        $toDay = $this->orderModel->query()->whereDate('created_at', Carbon::today())->count();
        $toMonth = $this->orderModel->query()
            ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
            ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
            ->count();
        $toYear = $this->orderModel->query()->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])->count();
        $total_price_atYear = $this->orderModel->
        query()
            ->where('status', 'finished')
            ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->sum('total_price');
        $total_price = $this->orderModel->query()->where('status', 'finished')
            ->whereDate('created_at', '>=', Carbon::now()->startOfMonth())
            ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
            ->sum('total_price');
        $all_total_price = $this->orderModel->query()->where('status', 'finished')->sum('total_price');
        $finished = $this->orderModel->query()->where('status', 'finished')->count();

        $cancel = $this->orderModel->query()->where('status', 'canceled')->count();
        $process = $this->orderModel->query()->where('status', 'processing')->count();

        return response()->json([
            'statusCode' => 200,
            'status' => true,
            'all_total_price' => $all_total_price,
            'total_price_atYear' => $total_price_atYear,
            'totale_price_atMonth' => $total_price,
            'toDay' => $toDay,
            'tomonth' => $toMonth,
            'toyear' => $toYear,
            'finished' => $finished,
            'cancel' => $cancel,
            'processing' => $process,
            'service' => $service,
            'category' => $category,
            'subService' => $subService,
            'user' => $user,
            'company' => $company,
            'employee' => $employee,
            'worker' => $worker,
            'role' => $role,

        ]);
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
