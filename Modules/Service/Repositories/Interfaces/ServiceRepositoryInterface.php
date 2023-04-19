<?php

namespace Modules\Service\Repositories\Interfaces;

interface ServiceRepositoryInterface
{

    public function allServices($data);

    public function storeService($data);

    public function addServiceWorker($data, $id);

    public function WorkerFromService($id);

    public function findService($id);

    public function updateService($data, $id);

    public function destroyService($id);
}
