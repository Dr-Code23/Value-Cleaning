<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserReviewResource;
use Modules\Review\Entities\Review;

class ReviewResource extends JsonResource
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
            'comments' => $this->comments,
            'rating' => $this->star_rating,
            'user' => UserReviewResource::collection($this->users),
            'service_id' => $this->service_id,
        ];
    }
}
