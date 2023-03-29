<?php

namespace Modules\Expenses\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['money','name','date','notes','type_id'];

    public function type()
    {
        return $this->belongsTo(TypeExpense::class);
    }
}
