<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Service extends Model implements HasMedia
{
    use HasFactory;    use InteractsWithMedia;


    protected $fillable = [
    'title',
    'description',
    "category_id",
//    "offer_id",
    'price'];



    protected static function newFactory()
    {
        return \Modules\Service\Database\factories\ServiceFactory::new();
    }
}
