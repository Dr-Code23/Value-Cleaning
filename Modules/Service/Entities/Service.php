<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Entities\Category;
use Modules\Offer\Entities\Offer;
use Modules\Review\Entities\Review;
use Modules\Worker\Entities\Worker;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Service extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia,HasTranslations;


    public $translatable = [ 'title',
        'description',
        ];
    protected $guarded = [];


    protected $casts = [
        'options' => 'array',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }



    public function revices(){

        return $this->hasMany(Review::class);
    }

    public function offer(){
        return $this->belongsTo(Offer::class,'offer_id');
    }
    public function workers()
    {
        return $this->belongsToMany(Worker::class);
    }




    protected static function newFactory()
    {
        return \Modules\Service\Database\factories\ServiceFactory::new();
    }
}
