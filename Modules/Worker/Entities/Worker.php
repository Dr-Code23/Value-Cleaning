<?php

namespace Modules\Worker\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Worker extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;

    protected $fillable = [
        'name',
'email',
'address',
'phone',
'NIN'
    ];

    protected static function newFactory()
    {
        return \Modules\Worker\Database\factories\WorkerFactory::new();
    }
}
