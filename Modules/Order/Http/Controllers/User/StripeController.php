<?php

namespace Modules\Order\Http\Controllers\User;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Repositories\Interfaces\OrderRepositoryInterface;
use Modules\Order\Repositories\Interfaces\PaymentRepositoryInterface;

class StripeController extends Controller
{
    private $PaymentRepository;

    public function __construct(PaymentRepositoryInterface $PaymentRepository)
    {
        $this->PaymentRepository = $PaymentRepository;
    }
    public function makePayment(Request $request)

    {
        return $this->PaymentRepository->makePayment($request);
    }

    public function allPayment()

    {
        return $this->PaymentRepository->allPayment();
    }

    public function checkoutPayment(Request $request)
    {
    return $this->PaymentRepository->checkoutPayment($request);

    }

    public function deletePaymament(Request $request)

    {
        return $this->PaymentRepository->deletePayment($request);
    }
}
