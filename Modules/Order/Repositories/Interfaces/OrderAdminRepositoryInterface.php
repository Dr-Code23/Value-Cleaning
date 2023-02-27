<?php

namespace Modules\Order\Repositories\Interfaces;

interface OrderAdminRepositoryInterface
{

    public function index();
    public function CansaledOrder();

    public function finishedOrder();


    public function updateOrderToAdmin($data, $id);


    public function changeStutes($data, $id);


    public function show($id);


    public function destroy($id);


}






