<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
class Service extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;




    protected $guarded =['id'];


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
}
