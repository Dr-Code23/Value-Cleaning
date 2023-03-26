<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\SubService;
use Modules\Service\Transformers\ServiceResource;
use Modules\Service\Transformers\SubServiceResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'work_area' => $this->work_area,
            'date' => $this->date,
            'time' => $this->time,
            'day' => $this->day,
            'address' => $this->address,
            'repeat' => $this->repeat,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'service' => ServiceResource::collection($this->services),
            'workers' => $this->workers,
            'total_price' => $this->total_price,
            'order_code' => $this->order_code,
            'subService' => SubServiceResource::collection($this->sub_services),
            'gallery1' => $this->getMedia('Orders'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
