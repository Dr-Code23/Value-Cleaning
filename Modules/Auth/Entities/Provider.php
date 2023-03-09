<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;


    protected $fillable = ['provider','provider_id','user_id','avatar'];
    protected $hidden = ['created_at','updated_at'];
}
