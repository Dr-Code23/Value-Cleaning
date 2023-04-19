<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
}
