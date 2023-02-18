<?php

namespace Modules\Service\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            "category_id"=> $this->category_id,
            'images'  => $this->getFirstMediaUrl('services'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
