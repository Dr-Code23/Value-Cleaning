<?php

namespace Modules\Order\Repositories\Interfaces;

interface PaymentRepositoryInterface
{

    public function makePayment($data);

    public function allPayment();

    public function checkoutPayment($data);

    public function deletePayment($data);
}






