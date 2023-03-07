<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,HasTranslations;


    public $translatable =
        [
        'title',
        ];

    protected static function newFactory()
    {
        return \Modules\Category\Database\factories\CategoryFactory::new();
    }
}
