<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Service\Entities\Service;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    protected $fillable = [

'worke_aera',
'date',
'time',
'address',
'repeat',
'status',
'user_id',
'service_id',
'total_price',
'order_code',];

    public function services()
    {
        return $this->belongsTo(Service::class,'id');
    }

    public function users()
    {
        return $this->hasMany(User::class,'user_id','id');
    }

    public function worker()
    {
        return $this->belongsToMany(Worker::class, 'order-worker', 'order_id', 'worker_id');
    }
    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderFactory::new();
    }
}
