<?php

namespace Modules\Review\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'user' => $this->users,
            'worker_id' => $this->worker_id,
        ];    }
}
