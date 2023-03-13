<?php

namespace Modules\Order\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Http\Requests\UpdateRequest;
use Modules\Order\Repositories\Interfaces\OrderAdminRepositoryInterface;

class OrderAdminController extends Controller
{
    private $OrderAdminRepository;

    public function __construct(OrderAdminRepositoryInterface $OrderAdminRepository)
    {
        $this->OrderAdminRepository = $OrderAdminRepository;
    }

    public function index()
    {
        return $this->OrderAdminRepository->index();
    }

    public function canceledOrder()
    {

    return $this->OrderAdminRepository->CanceledOrder();

    }
    public function finishedOrder()

    {

    return $this->OrderAdminRepository->finishedOrder();

    }

    public function show($id)
    {
        return $this->OrderAdminRepository->show($id);
    }

    public function updateOrderToAdmin( Request $request,$id)
    {
        return $this->OrderAdminRepository->updateOrderToAdmin($request, $id);
    }
    public function changeStates(Request $request, $id)
    {
        return $this->OrderAdminRepository->changeStates($request->all(), $id);
    }

    public function destroy($id)
    {
        return $this->OrderAdminRepository->destroy($id);

    }

    public function sendNewOrderNotification()
    {
        return $this->OrderAdminRepository->sendNewOrderNotification();
    }

}
