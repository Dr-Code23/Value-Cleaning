<?php

namespace Modules\Service\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Favorite\Entities\Favorite;
use Modules\Order\Entities\Order;
use Modules\Review\Entities\Review;
use Modules\Review\Transformers\ReviewResource;
use Modules\Service\Entities\Service;
use Modules\Worker\Transformers\WorkerResource;
use Modules\Worker\Transformers\WorkerShowResource;

class ServiceResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {


        $favorite = Favorite::where(['service_id' => $this->id, 'user_id' => Auth::id()])->first();
        if ($favorite) {
            $is_favorite = true;

        } else {
            $is_favorite = false;
        }
        $rate = Review::where('service_id', $this->id)->avg('star_rating');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            "category" => $this->category->title,
            'active' => $this->active,
            'workers' => WorkerShowResource::collection($this->workers),
            'images' => $this->getFirstMediaUrl('services'),
            'Review' => ReviewResource::collection($this->revices),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'rate' => $rate ?? 0,
            'is_favorite' => $is_favorite,
        ];
    }
}
