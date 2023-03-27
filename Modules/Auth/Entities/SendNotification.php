<?php

namespace Modules\Auth\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SendNotification extends Model
{
    use HasFactory;

    protected $table = 'send_notifications';
    protected $guarded = [];

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function isRead()
    {
        return $this->is_read;
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

}
