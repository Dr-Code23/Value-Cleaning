<?php

namespace Modules\Auth\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\factories\FooterFactory;

class Footer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return FooterFactory::new();
    }
}
