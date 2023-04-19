<?php

namespace Modules\Offer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Service;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Offer extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'offer_percent',
        'service_id'
    ];

    public function Service()
    {
        return $this->belongsTo(Service::class);
    }

    protected static function newFactory()
    {
        return \Modules\Offer\Database\factories\OfferFactory::new();
    }
}
