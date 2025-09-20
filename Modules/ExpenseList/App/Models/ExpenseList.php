<?php

namespace Modules\ExpenseList\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Expense\App\Models\Expense;

class ExpenseList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'expense_lists';
    protected $fillable = [
        'create_date',
        'expense_category_id',
        'amount',
        'status',
        'expense_note'
    ];

    function expensecategories (){
        return $this->belongsTo(Expense::class,'expense_category_id','id');
    }
}
