<?php

namespace Modules\Service\Repositories;

use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;
use Modules\Service\Entities\Service;

class ServiceRepository implements ServiceRepositoryInterface
{

    public function allServices()
    {
        return Service::latest()->get();
    }

    public function storeService($data)
    {



          return Service::create($data);

    }

    public function findService($id)
    {
        return Service::find($id);
    }

    public function updateService($data, $id)
    {
         $Service = Service::where('id', $id)->first();


          //sending the model data to the frontend
        $Service->title = $data['title'];
        $Service->description = $data['description'];
        $Service->price = $data['price'];
        $Service->category_id = $data['category_id'];
        $Service->save();
       return $Service;
    }

    public function destroyService($id)
    {
        $Service = Service::find($id);
        $Service->delete();
    }
}
