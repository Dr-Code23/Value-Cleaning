<?php
namespace Modules\Expenses\Repositories\Interfaces;


interface PaymentInterface
{

    // edit Payment
    public function editPayment($request);

    // store Payment
    public function storePayment($request);

    //Delete Payment
    public function destroy($request);

    // update Payment
    public function updatePayment($request);

    // get Payment
    public function getPayment();

}
