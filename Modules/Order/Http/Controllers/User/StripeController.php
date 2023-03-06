<?php

namespace Modules\Order\Http\Controllers\User;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;

class StripeController extends Controller
{
    private $OrderRepository;

    public function __construct(OrderRepositoryInterface $OrderRepository)
    {
        $this->OrderRepository = $OrderRepository;
    }
    public function makePayment(Request $request)

    {
        return $this->OrderRepository->makePayment($request);
    }


}
