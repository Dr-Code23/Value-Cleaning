<?php

namespace Modules\Expenses\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeExpense extends Model
{
    use HasFactory;

    protected $fillable = ['name','notes'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

}
