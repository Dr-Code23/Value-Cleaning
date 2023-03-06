<?php

namespace Modules\Service\Repositories;

use Modules\Offer\Entities\Offer;
use Modules\Service\Entities\Service;
use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    private $serviceModel;

    public function __construct(Service $service)
    {
        $this->serviceModel = $service;
    }

    public function allServices()
    {
        return $this->serviceModel->latest()->get();
    }

    public function storeService($data)
    {
//        if (isset($data['offer_id'])) {
//            $myoffer = Offer::when($data['offer_id'])->where("id", $data["offer_id"])->pluck("offer_price")->first();
//        $data["price"] = $data["price"] - ($myoffer['offer_price'] / 100 * $data["price"]);
//
//    }
        $sevice =$this->serviceModel->create([
            'title' => [
                'en' => $data['title_en'],
                'sv' => $data['title_sv']
            ],
            'description'=> [
                'en' => $data['description_en'],
                'sv' => $data['description_sv']
            ],
            "category_id"=> $data['category_id'],
            'price'=> $data['price'],
       ]);

        $sevice->workers()->sync($data['worker_id']);
        return $sevice;
    }

    public function addServiceWoeker($data, $id)
    {
        $sevice = $this->serviceModel->findOrFail($id);
        $sevice->workers()->sync($data->all());
    }

    public function deleteWoekerFromService($data, $id)
    {
        $sevice = $this->serviceModel->findOrFail($id);

        $sevice->workers()->detach($data->worker_id);

        return ['statusCode' => 200, 'status' => true, 'message' => 'service Deleted successfully ',];
    }

    public function findService($id)
    {
        return $this->serviceModel->where('id', $id)->with(['revices', 'workers'])->first();
    }

    public function updateService($data, $id)
    {
        $service = $this->serviceModel->where('id', $id)->first();

if(isset($data['offer_id'])){
          //sending the model data to the frontend
    $service->update([
        'title' => [
            'en' => $data['title_en'],
            'sv' => $data['title_sv']
        ],
        'description'=> [
            'en' => $data['description_en'],
            'sv' => $data['description_sv']
        ],
        "category_id"=> $data['category_id'],
        'price'=> $data['price'],
    ]);
       return $service;
    }
        $service->update();
        return $service;
    }

    public function destroyService($id)
    {
        $Service = $this->serviceModel->find($id);
       return $Service->delete();
    }
}
