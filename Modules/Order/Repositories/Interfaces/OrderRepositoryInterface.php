<?php

namespace Modules\Order\Repositories\Interfaces;

interface OrderRepositoryInterface
{

    public function index();

    public function canceledOrder();

    public function finishedOrder();

    public function orderCode($id);


    public function store($data);


    public function show($id);

    public function cancele($id);

    public function update($data, $id);

    public function destroy($id);

    public function downloadPdf($id);

}






