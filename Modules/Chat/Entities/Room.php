<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [];

    function users(){
        return $this->belongsToMany(User::class, 'room_users');
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\RoomFactory::new();
    }
}
