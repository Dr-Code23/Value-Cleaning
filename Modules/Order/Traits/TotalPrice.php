<?php

namespace Modules\Order\Traits;

use Modules\Offer\Entities\Offer;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;

trait TotalPrice
{

    public function totalPrice($data)
    {
        $subservicesPrices=0;
      if($data['sub_service_id']) {
          $subservicesPrices = SubService::whereIn('id', $data['sub_service_id'])->sum('price');
      }

      $servicesPrice=Service::where('id',$data['service_id'])->first('price');
      $offer=Offer::where('service_id',$data['service_id'])->first('offer_percent');
      if($offer && $subservicesPrices){
          $servicePrice=$servicesPrice-($servicesPrice*$offer/100);
          $totalPrice=$servicePrice+$subservicesPrices;
      return $totalPrice;
      }elseif($subservicesPrices){

          return $totalPrice =($subservicesPrices+$servicesPrice->price);
      }
      else{
           return $totalPrice = $servicesPrice->price;
      }

    }


}
