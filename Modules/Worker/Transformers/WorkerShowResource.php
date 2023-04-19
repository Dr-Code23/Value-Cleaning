<?php

namespace Modules\Worker\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Review\Transformers\ReviewWorkerResource;
use Modules\Worker\Entities\Worker;

class WorkerShowResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'review' => ReviewWorkerResource::collection($this->revices),
            'photo' => $this->getFirstMediaUrl('workers'),
        ];
    }
}
