<?php

namespace Modules\Review\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Worker\Entities\Worker;

class WorkerReview extends Model
{
    use HasFactory;

    protected $table = 'worker-reviews';

    protected $guarded = [];

    public function services()
    {
        return $this->hasMany(Worker::class);
    }

    public function users()
    {
        return $this->hasMany(User::class,'id','user_id');
    }
    protected static function newFactory()
    {
        return \Modules\Review\Database\factories\WorkerReviewFactory::new();
    }
}
