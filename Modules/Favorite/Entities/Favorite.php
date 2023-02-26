<?php

namespace Modules\Favorite\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Service\Entities\Service;

class Favorite extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function services()
    {
        return $this->hasMany(Service::class,'id','service_id');
    }

    public function users()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    protected static function newFactory()
    {
        return \Modules\Favorite\Database\factories\FavoriteFactory::new();
    }
}
