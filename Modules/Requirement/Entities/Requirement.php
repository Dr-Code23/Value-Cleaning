<?php

namespace Modules\Requirement\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Spatie\Translatable\HasTranslations;


class Requirement extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title',
    ];
    protected $guarded = [];

    public function Service()
    {
        return $this->belongsTo(Service::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
