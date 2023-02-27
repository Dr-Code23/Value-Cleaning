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

    public function CansaledOrder()
    {

    return $this->OrderAdminRepository->CansaledOrder();

    }
    public function FinishedOrder()

    {

    return $this->OrderAdminRepository->FinishedOrder();

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

    public function OrderCode($id)
    {
        return $this->OrderAdminRepository->orderCode($id);
    }
    public function UpdateOeserToAdmin( Request $request,$id)
    {
        return $this->OrderAdminRepository->updateOeserToAdmin($request, $id);

    }
    public function ChangeStutes(Request $request, $id)
    {
        return $this->OrderAdminRepository->changeStutes($request->all(), $id);


    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateRequest $request, $id)
    {
      return $this->OrderAdminRepository->Update($request, $id)   ;
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
}
