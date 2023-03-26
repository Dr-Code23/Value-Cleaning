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

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->OrderAdminRepository->index();
    }

    public function canceledOrder()
    {

        return $this->OrderAdminRepository->canceledOrder();

    }

    public function finishedOrder()

    {

        return $this->OrderAdminRepository->finishedOrder();

    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->OrderAdminRepository->show($id);
    }

    public function updateOrderToAdmin(Request $request, $id)
    {
        return $this->OrderAdminRepository->updateOrderToAdmin($request, $id);

    }

    public function changeStates(Request $request, $id)
    {
        return $this->OrderAdminRepository->changeStates($request->all(), $id);


    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return $this->OrderAdminRepository->destroy($id);

    }

    public function sendNewOrderNotification()
    {
        return $this->OrderAdminRepository->sendNewOrderNotification();
    }

    public function home()
    {
        return $this->OrderAdminRepository->home();
    }

    public function serviceCount($id)
    {
        return $this->OrderAdminRepository->serviceCount($id);
    }
}
