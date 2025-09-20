<?php

namespace Modules\Supplier\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\App\Models\PayBillCustomerSupplier;
use Modules\Supplier\Database\factories\SupplierFactory;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'group',
        'name',
        'company_name',
        'phone',
        'photo',
        'email',
        'address',
        'vat',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
        'previous_due',
    ];

    public function lastpayment(){
        return $this->belongsTo(PayBillCustomerSupplier::class,'id','user_id')->where('role','supplier')->orderBy('id','asc');
    }
}

