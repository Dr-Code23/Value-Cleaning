<?php

namespace Modules\Order\Repositories\Interfaces;

interface OrderRepositoryInterface
{

    public function index();
    public function CansaledOrder();
    public function FinishedOrder();

    public function OrderCode($id);


    public function create($data);


    public function show($id);
    public function Cansale($id);

    public function Update($data,$id);

    public function delete($id);


}






