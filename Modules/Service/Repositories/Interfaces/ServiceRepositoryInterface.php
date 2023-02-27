<?php
namespace Modules\Service\Repositories\Interfaces;

Interface ServiceRepositoryInterface{

    public function allServices();
    public function storeService($data);

    public function addServiceWoeker($data, $id);

    public function deleteWoekerFromService($data, $id);

    public function findService($id);
    public function updateService($data, $id);
    public function destroyService($id);
}
