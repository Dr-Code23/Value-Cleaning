<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Expenses\Http\Requests\PaymentRequest;
use Modules\Expenses\Repositories\Interfaces\PaymentInterface;

class PaymentController extends Controller
{

    use ExpenseResponseTrait;

    protected $payment;

    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $payment = $this->payment->getPayment();
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('expenses::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        return $payment = $this->payment->storePayment($request);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('expenses::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        $payment = $this->payment->editPayment($request);
        if ($payment) {
            return $this->expenseResponse(($payment), 'payment Saved', 201);
        }
        return $this->expenseResponse(null, 'payment Not Saved', 400);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $payment = $this->payment->updatePayment($request);
        if ($payment) {
            return $this->expenseResponse(($payment), 'payment update', 201);
        }
        return $this->expenseResponse(null, 'payment Not update', 400);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $payment = $this->payment->destroy($request);
        if ($payment) {
            return $this->expenseResponse(($payment), 'payment deleted', 201);
        }
        return $this->expenseResponse(null, 'payment Not deleted', 400);
    }
}
