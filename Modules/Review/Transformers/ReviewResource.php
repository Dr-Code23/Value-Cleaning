<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserReviewResource;
use Modules\Review\Entities\Review;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $user=Review::find($this->id)->users;
        $users=UserReviewResource::collection($user);

       return [
           'id'=> $this->id,
           'comments' => $this->comments,
           'rating' => $this->star_rating,
           'user' => $users,
           'service_id' => $this->service_id,




       ];
    }
}
