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

        if (!empty($data['requirement_id'])) {
            $requirements = Requirement::whereIn('id', $data['requirement_id'])->get();

            $result = [];
            foreach ($requirements as $key => $requirement) {
                $result[] = $requirement->requirement_price * (isset($data['count'][$key]) ? $data['count'][$key] : 0);

            }

            $requirementPrices = array_sum($result);
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
