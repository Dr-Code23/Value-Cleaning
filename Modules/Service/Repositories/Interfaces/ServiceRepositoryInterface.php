<?php
namespace Modules\Service\Repositories\Interfaces;

Interface ServiceRepositoryInterface{

    public function allServices();
    public function storeService($data);
    public function AddServiceWoeker($data, $id);

    public function updateServiceWoeker($data, $id);

    public function DeleteWoekerFromService($data,$id);

    public function findService($id);
    public function updateService($data, $id);
    public function destroyService($id);
}
