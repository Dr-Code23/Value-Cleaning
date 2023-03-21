<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Entities\Order;

class OrderAdminIndexResource extends JsonResource
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
            'status' => $this->status,
            'payment_status'=>$this->payment_status,
            'total_price' => $this->total_price,
            'gallery'  => $this->getMedia('Orders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,


        ];
    }
}
