<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;

class OrderAdminResource extends JsonResource
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
            'user' => $this->users,
            'service' => $this->services,
            'workers' => $this->workers,
            'extraServices'=>$this->sub_services,
            'total_price' => $this->total_price,
            'gallery'  => $this->gerMediaUrl('Orders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
