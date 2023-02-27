<?php

namespace Modules\Service\Repositories;

use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Modules\Service\Repositories\Interfaces\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{

    public function allServices()
    {
        return Service::latest()->get();
    }

    public function storeService($data)
    {
      if(isset($data['offer_id']))
      {
      $myoffer = Offer::when($data['offer_id'])->where("id", $data["offer_id"])->pluck("offer_price")->first();
        $data["price"] = $data["price"] - ($myoffer['offer_price'] / 100 * $data["price"]);

    }
        $sevice = Service::create($data);

        $sevice->workers()->sync($data['worker_id']);
        return $sevice;


    }

    public function addServiceWoeker($data, $id)
    {
        $sevice = Service::findOrFail($id);


        $sevice->workers()->sync($data->all());

    }

    public function deleteWoekerFromService($data, $id)
    {
        $sevice = Service::findOrFail($id);

        $sevice->workers()->detach($data->worker_id);

        return ['statusCode' => 200, 'status' => true, 'message' => 'service Deleted successfully ',];

    }

    public function findService($id)
    {
        return Service::where('id',$id)->with(['revices','workers'])->first();
    }

    public function updateService($data, $id)
    {
         $Service = Service::where('id', $id)->first();

if(isset($data['offer_id'])){
          //sending the model data to the frontend
        $Service->title = $data['title'];
        $Service->description = $data['description'];
        $Service->price = $data['price'];
        $Service->category_id = $data['category_id'];
        $Service->offer_id = $data['offer_id'];

        $Service->save();
       return $Service;
    }
        $Service->update();
        return $Service;

    }

    public function destroyService($id)
    {
        $Service = Service::find($id);
       return $Service->delete();
    }
}
