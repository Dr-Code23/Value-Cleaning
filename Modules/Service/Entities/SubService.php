<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Entities\Order;
use Spatie\Translatable\HasTranslations;

class SubService extends Model
{
    use HasFactory,HasTranslations;
    protected $table = 'sub-services';

    public $translatable = [ 'title',
    ];
    protected $fillable = [
        'title',
        'price',
        'service_id'
    ];

    public function services()
    {
        return $this->hasMany(Service::class,'service_id','id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    protected static function newFactory()
    {
        return \Modules\Service\Database\factories\SubServiceFactory::new();
    }
}
