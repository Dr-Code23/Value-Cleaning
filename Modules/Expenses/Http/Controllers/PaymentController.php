<?php

namespace Modules\Expenses\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Expenses\Http\Requests\PaymentRequest;
use Modules\Expenses\Repositories\Interfaces\PaymentInterface;

class PaymentController extends Controller
{

    use ExpenseResponseTrait;

    protected $payment;

    public function __construct(PaymentInterface $payment)
    {
        $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete');
        $this->middleware('permission:payment-create', ['only' => ['store']]);
        $this->middleware('permission:payment-edit', ['only' => ['update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
        $this->payment = $payment;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return $payments = $this->payment->getPayment();
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
        return $payments = $this->payment->storePayment($request);
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
     * @return Application|ResponseFactory|Response
     */
    public function edit(Request $request)
    {
        $payment = $this->payment->editPayment($request);
        if ($payment) {
            return $this->expenseResponse(($payment), 'payment Saved', 200);
        }
        return $this->expenseResponse(null, 'payment Not Saved', 400);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Application|ResponseFactory|Response
     */
    public function update(Request $request)
    {
        $payment = $this->payment->updatePayment($request);
        if ($payment) {

            return $this->expenseResponse(($payment), 'payment update', 200);
        }
        return $this->expenseResponse(null, 'payment Not update', 400);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Application|ResponseFactory|Response
     */
    public function destroy(Request $request)
    {
        $payment = $this->payment->destroy($request);
        if ($payment) {
            return $this->expenseResponse(($payment), 'payment deleted', 200);
        }
        return $this->expenseResponse(null, 'payment Not deleted', 400);
    }
}
