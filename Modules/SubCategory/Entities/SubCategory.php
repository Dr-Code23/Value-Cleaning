<?php

namespace Modules\SubCategory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Database\factories\CategoryFactory;
use Modules\Category\Entities\Category;
use Modules\Service\Entities\Service;
use Modules\SubSubCategory\Entities\SubSubCategory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SubCategory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;

    protected $table = 'subcategories';

    public $translatable =
        [
            'title',
        ];
    protected $guarded = [];

    public function subsubcategories()
    {
        return $this->belongsTo(SubSubCategory::class,'subsubcategory_id');
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function services()
    {
        return $this->hasManyThrough(Service::class, Category::class);
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
