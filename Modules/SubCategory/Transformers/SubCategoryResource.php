<?php

namespace Modules\SubCategory\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\SubSubCategory\Transformers\SubSubCategoryResource;

class SubCategoryResource extends JsonResource
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
            'subsubcategory' =>new SubSubCategoryResource($this->subsubcategories),
            'images'  => $this->getFirstMediaUrl('categories'),
        ];    }
}
