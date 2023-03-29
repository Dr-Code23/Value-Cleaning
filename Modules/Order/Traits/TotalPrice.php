<?php

namespace Modules\Order\Traits;

use Illuminate\Support\Facades\DB;
use Modules\Offer\Entities\Offer;
use Modules\Requirement\Entities\Requirement;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;

trait TotalPrice
{
    /**
     * @param $data
     * @return float|int
     */
    public function totalPrice($data)
    {

        $subservicesPrices = 0;
        if ($data['sub_service_id']) {
            $subservicesPrices = SubService::whereIn('id', $data['sub_service_id'])->sum('price');
        }
        $requirementPrices = 0;
        if ($data['requirement_id']) {
            $requirements = Requirement::whereIn('id', $data['requirement_id'])->sum('requirement_price');
            $count = array_sum($data['count']);

            $requirementPrices = $requirements * $count;

        }

        $service = Service::where('id', $data['service_id'])->first();
        $servicePrice = $service->price;

        $offerPercent = 0;
        $offer = Offer::where('service_id', $service->id)->first();
        if ($offer) {
            $offerPercent = $offer->offer_percent;
        }

        $discountAmount = $servicePrice * $offerPercent / 100;
        $totalPrice = $servicePrice + $subservicesPrices + $requirementPrices - $discountAmount;

        return $totalPrice;
    }


}
