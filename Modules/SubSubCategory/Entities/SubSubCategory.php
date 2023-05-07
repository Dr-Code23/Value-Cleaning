<?php

namespace Modules\SubSubCategory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Database\factories\CategoryFactory;
use Modules\SubCategory\Entities\SubCategory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SubSubCategory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'subsubcategories';
    public $translatable =
        [
            'title',
        ];
    protected $guarded = [];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
