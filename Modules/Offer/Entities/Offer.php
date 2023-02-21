<?php

namespace Modules\Offer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Service\Entities\Service;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = ["offer_price"];

    public function Services(){
        return $this->hasMany(Service::class,'offer_id');
    }
    protected static function newFactory()
    {
        return \Modules\Offer\Database\factories\OfferFactory::new();
    }
}
