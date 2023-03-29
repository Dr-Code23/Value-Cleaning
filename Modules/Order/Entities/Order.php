<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\Requirement\Entities\Requirement;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\SubService;
use Modules\Worker\Entities\Worker;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @method create($all)
 */
class Order extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Notifiable;

    protected $fillable = [

        'work_area',
        'date',
        'time',
        'day',
        'address',
        'latitude',
        'longitude',
        'repeat',
        'status',
        'payment_status',
        'user_id',
        'service_id',
        'total_price',
        'order_code',
        'scheduled_at',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'id', 'service_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function workers()
    {
        return $this->belongsToMany(Worker::class);
    }

    public function sub_services()
    {
        return $this->belongsToMany(SubService::class);
    }

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

}
