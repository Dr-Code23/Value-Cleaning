<?php

namespace Modules\Expenses\Repositories\Repository;

use Modules\Expenses\Entities\Payment;
use Modules\Expenses\Repositories\Interfaces\PaymentInterface;

class PaymentRepository implements PaymentInterface
{

    public function editPayment($request)
    {
        $payments = Payment::where('id', $request->id)->first();
        return $payments;
    }


    public function storePayment($request)
    {
        $payments = new Payment();
        $payments->name = $request->name;
        $payments->money = $request->money;
        $payments->date = $request->date;
        $payments->notes = $request->notes;
        $payments->save();
        return $payments;
    }

    public function updatePayment($request)
    {
        $payments = Payment::where('id', $request->id)->first();
        $payments->name = $request->name;
        $payments->money = $request->money;
        $payments->date = $request->date;
        $payments->notes = $request->notes;
        $payments->save();
        return $payments;

    }

    public function getPayment()
    {
        return $expenses = Payment::all();
    }

    public function destroy($request)
    {
        $payments = Payment::where('id', $request->id)->first();
        $payments->delete();
        return $payments;

    }

}
