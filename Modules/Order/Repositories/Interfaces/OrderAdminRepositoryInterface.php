<?php

namespace Modules\Order\Repositories\Interfaces;

interface OrderAdminRepositoryInterface
{

    public function index($data);

    public function canceledOrder();

    public function finishedOrder();

    public function updateOrderToAdmin($data, $id);

    public function changeStates($data, $id);

    public function show($id);


    public function destroy($id);


}






