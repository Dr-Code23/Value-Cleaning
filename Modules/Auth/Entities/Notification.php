<?php

namespace Modules\Auth\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function users()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    protected static function newFactory()
    {
        return \Modules\Auth\Database\factories\NotificationFactory::new();
    }
}
