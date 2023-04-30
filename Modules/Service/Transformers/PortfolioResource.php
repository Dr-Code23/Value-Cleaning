<?php

namespace Modules\Service\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
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
//            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'gallery' => $this->getMedia('portfolio'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
