<?php

namespace Modules\SalesInvoice\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\App\Models\Customer;
use Modules\SalesInvoice\Database\factories\CustomerDueAmoutPaidFactory;

class CustomerDueAmoutPaid extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'amount_get_user_id',
        'user_id',
        'amount',
        'paid_after_due',
        'paid_date',
        'file',
    ];

    public function receivedby(){
        return $this->belongsTo(User::class,'amount_get_user_id','id');
    }
    public function customer(){
        return $this->belongsTo(Customer::class,'user_id','id');
    }
}
