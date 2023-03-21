<?php
namespace Modules\Service\Repositories\Interfaces;

Interface ServiceRepositoryInterface{

    public function allServices($data);
    public function storeService($data);

    public function addServiceWorker($data, $id);

    public function deleteWorkerFromService($data, $id);

    public function findService($id);
    public function updateService($data, $id);
    public function destroyService($id);
}
