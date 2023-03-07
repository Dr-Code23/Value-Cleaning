<?php

namespace Modules\Order\Repositories\Interfaces;

interface OrderRepositoryInterface
{

    public function index();

    public function cansaledOrder();

    public function finishedOrder();

    public function orderCode($id);


    public function store($data);

    public function makePayment($data);

    public function show($id);

    public function Cansale($id);

    public function Update($data,$id);

    public function destroy($id);

    public  function downloadPdf($id);




}






