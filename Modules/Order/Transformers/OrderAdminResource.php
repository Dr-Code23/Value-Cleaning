<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserResource;
use Modules\Requirement\Transformers\RequirementResource;
use Modules\Service\Transformers\ServiceResource;
use Modules\Service\Transformers\SubServiceResource;

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
            'requirement' => RequirementResource::collection($this->requirements),
            'total_price' => $this->total_price,
            'gallery' => $this->getMedia('Orders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
