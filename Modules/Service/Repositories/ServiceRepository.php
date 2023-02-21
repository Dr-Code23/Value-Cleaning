<?php

namespace Modules\Service\Repositories;

use Modules\Offer\Entities\Offer;
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

        if (isset($data['offer_id']) ){
            $myoffer = Offer::where("id",$data["offer_id"])->select("offer_price")->first();
            $data["price"] =  $data["price"] - ($myoffer['offer_price']/100 * $data["price"]);
        }

//        $requestData["sale_price"] =  $requestData["regular_price"] - ($myoffer['offer_price']/100 * $requestData["regular_price"]);
//    }else{
//$requestData["sale_price"] = $requestData["regular_price"];
//}
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
        $Service->offer_id = $data['offer_id'];

        $Service->save();
       return $Service;
    }

    public function destroyService($id)
    {
        $Service = Service::find($id);
       return $Service->delete();
    }
}
