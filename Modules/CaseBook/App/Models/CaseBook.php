<?php

namespace Modules\CaseBook\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CaseBook\Database\factories\CaseBookFactory;

class CaseBook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'staff_id',
        'amount',
        'payment_type',
        'payment_date',
        'note',
        'status',
    ];


}
