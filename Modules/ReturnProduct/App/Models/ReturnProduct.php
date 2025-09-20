<?php

namespace Modules\ReturnProduct\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\App\Models\Customer;
use Modules\SalesInvoice\App\Models\SalesInvoice;

class ReturnProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'invoice_id',
        'customer_id',
        'create_date',
        'total_amount',
        'due_amount',
        'note',
        'paid_amount',
        'discount',
        'status',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function salesinvoice(){
        return $this->belongsTo(SalesInvoice::class,'invoice_id','invoice_id');
    }

    public function returnproductdetails(){
        return $this->hasMany(ReturnProductDetail::class,'invoice_id','invoice_id')->with(['customer','product']);
    }
}
