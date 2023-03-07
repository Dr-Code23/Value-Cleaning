<?php

namespace Modules\Worker\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Entities\Order;
use Modules\Review\Entities\WorkerReview;
use Modules\Service\Entities\Service;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Worker extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    protected $fillable = [
        'name',
'email',
'address',
'phone',
'NIN'
    ];



    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function revices(){

        return $this->hasMany(WorkerReview::class);
    }
    protected static function newFactory()
    {
        return \Modules\Worker\Database\factories\WorkerFactory::new();
    }
}
