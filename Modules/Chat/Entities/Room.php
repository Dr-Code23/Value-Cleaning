<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [ 'user_1', 'user_2'];

    function user(){
        return $this->belongsTo(User::class);
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
    public function images()
    {
        return $this->morphMany('Modules\Chat\Entities\Image', 'imageable');
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\RoomFactory::new();
    }
}
