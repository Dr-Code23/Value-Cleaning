<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['delete_by'];
    protected $dates = ['deleted_at'];

    function users(){
        return $this->belongsToMany(User::class, 'room_users');
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
