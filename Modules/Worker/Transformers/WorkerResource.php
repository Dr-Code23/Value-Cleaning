<?php

namespace Modules\Worker\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Modules\Order\Entities\Order;
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
            'review'=>$this->revices,
            'photo'  => $this->getFirstMediaUrl('workers'),
            'count'  => Worker::count(),

            ];
    }
}
