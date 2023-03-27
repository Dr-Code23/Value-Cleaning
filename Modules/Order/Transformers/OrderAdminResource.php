<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Modules\Service\Transformers\ServiceResource;
use Modules\Service\Transformers\SubServiceResource;
use Modules\Auth\Transformers\UserResource;

class OrderAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'work_area' => $this->work_area,
            'date' => $this->date,
            'time' => $this->time,
            'address' => $this->address,
            'repeat' => $this->repeat,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'user' => UserResource::collection($this->users),
            'service' => ServiceResource::collection($this->services),
            'workers' => $this->workers,
            'extraServices' => SubServiceResource::collection($this->sub_services),
            'total_price' => $this->total_price,
            'gallery' => $this->getMedia('Orders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
