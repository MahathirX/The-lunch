<?php

namespace Modules\Customer\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayBillCustomerSupplier extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'role',
        'user_id',
        'total_amount',
        'pay_amount',
        'due',
        'pay_date',
        'image',
        'details',
    ];
}
