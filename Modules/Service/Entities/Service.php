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
    'gallery',
    'description',
    "category_id",
    "offer_id",
    'price'];

    public function getGalleryAttribute()
   {
    $mediaItems = $this->getMedia('service_images');
    $gallery = [];
    if(!empty($mediaItems)){
       foreach($mediaItems as $image){
        array_push($gallery, $image->getUrl());
       }
    }
    return  $gallery;
   }
    
    protected static function newFactory()
    {
        return \Modules\Service\Database\factories\ServiceFactory::new();
    }
}
