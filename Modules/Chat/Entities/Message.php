<?php
namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'sender_id','room_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function room()
    {
        return $this->hasMany(Room::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\MessageFactory::new();


    }
}
