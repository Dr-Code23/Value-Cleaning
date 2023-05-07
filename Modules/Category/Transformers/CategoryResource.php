<?php

namespace Modules\Category\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\SubCategory\Transformers\SubCategoryResource;

class CategoryResource extends JsonResource
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
            'subCategory' => new SubCategoryResource($this->subcategory),
            'images'  => $this->getFirstMediaUrl('categories'),
        ];    }
}
