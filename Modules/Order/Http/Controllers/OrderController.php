<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\CreateRequest;
use Modules\Order\Http\Requests\UpdateRequest;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;

class OrderController extends Controller
{
    private $OrderRepository;

    public function __construct(OrderRepositoryInterface $OrderRepository)
    {
        $this->OrderRepository = $OrderRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $this->OrderRepository->index();
    }

    public function CansaledOrder()
    {

    return $this->OrderRepository->CansaledOrder();

    }
    public function FinishedOrder()

    {

    return $this->OrderRepository->FinishedOrder();

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateRequest $request)
    {
      return $this->OrderRepository->create($request);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return $this->OrderRepository->show($id);
    }

    public function OrderCode($id)
    {
        return $this->OrderRepository->OrderCode($id);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function Cansale($id)
    {
        return $this->OrderRepository->Cansale($id);


    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateRequest $request, $id)
    {
      return $this->OrderRepository->Update($request, $id)   ;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return $this->OrderRepository->delete($id)   ;

    }
}
