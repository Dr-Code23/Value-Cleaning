<?php

namespace Modules\Order\Traits;

use Modules\Offer\Entities\Offer;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;

trait totalPrice
{

    public function totalPrice($data)
    {

      $subservicesPrices=SubService::whereIn('id',$data['sub_service_id'])->sum('price');
      $servicesPrice=Service::where('id',$data['service_id'])->pluck('price')->first();
      $offer=Offer::where('service_id',$data['service_id'])->pluck('offer_percent')->first();
      If($offer){
       $servicePrice=$servicesPrice-($servicesPrice*$offer/100);
          $totalPrice=$servicePrice+$subservicesPrices;
      return $totalPrice;
      }else{
          return $totalPrice=($subservicesPrices+$servicesPrice);

      }
    }


}
