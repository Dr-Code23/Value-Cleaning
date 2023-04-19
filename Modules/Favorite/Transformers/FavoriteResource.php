<?php

namespace Modules\Favorite\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Transformers\ServiceResource;


class FavoriteResource extends JsonResource
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
            'service' => ServiceResource::collection($this->services),
        ];
    }
}
