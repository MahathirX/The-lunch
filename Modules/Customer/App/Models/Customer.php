<?php

namespace Modules\Customer\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\App\Models\PayBillCustomerSupplier;

class Customer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "customer_name",
        "phone",
        "address",
        "previous_due",
        "status",
        "photo"
    ];

    protected $table = 'customers';

    public function lastpayment(){
        return $this->belongsTo(PayBillCustomerSupplier::class,'id','user_id')->where('role','customer')->orderBy('id','asc');
    }

}
