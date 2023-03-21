<?php

namespace Modules\Service\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Entities\Order;
use Modules\Review\Entities\Review;
use Modules\Review\Transformers\ReviewResource;
use Modules\Service\Entities\Service;

class ServiceResource extends JsonResource
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
            'id'      =>  $this->id,
            'title'   => $this->title,
            'description' =>$this->description,
            'price' => $this->price,
            "category"=> $this->category->title,
            "offer_id"=> $this->offer_id,
            'active' =>$this->active,
            'workers' => $this->workers,
            'images'  => $this->getFirstMediaUrl('services'),
            'Review' => ReviewResource::collection($this->revices),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
