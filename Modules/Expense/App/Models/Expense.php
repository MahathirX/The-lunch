<?php

namespace Modules\Expense\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'expenses';
    protected $fillable = [
        'name',
        'code',
        'status',
        'expense_note'
    ];
}
