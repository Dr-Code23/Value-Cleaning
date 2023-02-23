<?php

namespace Modules\Worker\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'photo'  => $this->getFirstMediaUrl('workers'),
        ];    }
}
