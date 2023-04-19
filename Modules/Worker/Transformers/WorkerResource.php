<?php

namespace Modules\Worker\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Review\Transformers\ReviewWorkerResource;
use Modules\Worker\Entities\Worker;

class WorkerResource extends JsonResource
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
            'name'   => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'NIN' => $this->NIN,
            'active'=>$this->active,
            'review'=> ReviewWorkerResource::collection($this->revices) ,
            'count'  => Worker::count() ?? 0,
            'photo'  => $this->getFirstMediaUrl('workers'),
        ];    }
}
