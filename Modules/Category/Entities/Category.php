<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Database\factories\CategoryFactory;
use Modules\Service\Entities\Service;
use Modules\SubCategory\Entities\SubCategory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasTranslations;


    public $translatable =
        [
            'title',
        ];

    protected $guarded = [];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
