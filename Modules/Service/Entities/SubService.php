<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubService extends Model
{
    use HasFactory;
    protected $table = 'sub-services';
    protected $fillable = [
        'title',
        'price',
        'service_id'
    ];
    public function services()
    {
        return $this->hasMany(Service::class,'service_id','id');
    }
    protected static function newFactory()
    {
        return \Modules\Service\Database\factories\SubServiceFactory::new();
    }
}
