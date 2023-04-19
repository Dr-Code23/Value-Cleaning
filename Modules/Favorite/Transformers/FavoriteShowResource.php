<?php

namespace Modules\Favorite\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Favorite\Entities\Favorite;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Modules\Service\Transformers\ServiceResource;

class FavoriteShowResource extends JsonResource
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
