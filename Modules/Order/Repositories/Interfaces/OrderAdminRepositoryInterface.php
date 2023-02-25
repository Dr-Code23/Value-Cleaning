<?php

namespace Modules\Order\Repositories\Interfaces;

use Illuminate\Http\Request;

interface OrderAdminRepositoryInterface
{

    public function index();
    public function CansaledOrder();
    public function FinishedOrder();


    public function UpdateOeserToAdmin($data ,$id);


    public function ChangeStutes($data, $id);


    public function show($id);


    public function delete($id);


}






