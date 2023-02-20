<?php
namespace Modules\Auth\Repositories\Repository\Interfaces;

Interface ServiceRepositoryInterface{

    public function allServices();
    public function storeService($data);
    public function findService($id);
    public function updateService($data, $id);
    public function destroyService($id);
}
