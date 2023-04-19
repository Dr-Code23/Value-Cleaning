<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Transformers\UserResource;
use Modules\Review\Entities\WorkerReview;

class ReviewWorkerResource extends JsonResource
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
            'id'=> $this->id,
            'comments' => $this->comments,
            'rating' => $this->star_rating,
            'user' => UserResource::collection($this->users) ,
            'worker_id' => $this->worker_id,
            'rate' => $rate= WorkerReview::where('worker_id',$this->id)->avg('star_rating'),
        ];    }
}
