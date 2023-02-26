<?php

namespace Modules\Service\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Entities\Order;
use Modules\Review\Entities\Review;
use Modules\Review\Transformers\ReviewResource;
use Modules\Service\Entities\Service;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
       $Review= Review::where('service_id',$this->id)->get();
        $Reviews=  ReviewResource::collection($Review);
        return [
            'id'      =>  $this->id,
            'title'   => $this->title,
            'description' =>$this->description,
            'price' => $this->price,
            "category_id"=> $this->category_id,
            "offer_id"=> $this->offer_id,
            'active' =>$this->active,
            'workers' => Service::find($this->id)->workers,
            'images'  => $this->getFirstMediaUrl('services'),
            'Review' => $Reviews,
            'job_done' =>Order::where(["service_id"=>$this->id,'status'=>'Finished'])->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
