<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\SubService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'worke_aera'=> $this->worke_aera,
            'date' => $this->date,
            'time' => $this->time,
            'address' => $this->address,
            'repeat' => $this->repeat,
            'status' => $this->status,
            'payment_status'=>$this->payment_status,
            'service' => $this->services,
            'workers' => $this->workers,
            'total_price' => $this->total_price,
            'order_code' => $this->order_code,
            'subService'=>$this->sub_services,
            'gallery1'  => $this->getFirstMediaUrl('Orders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
//



        ];
    }
}
