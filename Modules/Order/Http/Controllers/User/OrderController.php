<?php

namespace Modules\Order\Http\Controllers\User;

use PDF;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

    public function canceledOrder()
    {

    return $this->OrderRepository->canceledOrder();

    }
    public function finishedOrder()

    {

        return $this->OrderRepository->finishedOrder();

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateRequest $request)
    {
        return $this->OrderRepository->store($request);
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

    public function orderCode($id)
    {
        return $this->OrderRepository->orderCode($id);
    }

    public function cansale($id)
    {
        return $this->OrderRepository->cancel($id);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateRequest $request, $id)
    {
      return $this->OrderRepository->update($request, $id)   ;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        return $this->OrderRepository->destroy($id);

    }

    public function createPdf($id)
    {
       return $this->OrderRepository->downloadPdf($id);
    }

}
