<?php

namespace Modules\SubSubCategory\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SubSubCategoryResource extends JsonResource
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
            'title'   => $this->title,
            'images'  => $this->getFirstMediaUrl('categories'),
        ];    }
}
