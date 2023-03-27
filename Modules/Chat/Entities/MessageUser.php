<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['delete_by','message_id'];
    protected $dates = ['deleted_at'];
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

}
