<?php

namespace Modules\Supplier\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Supplier\Database\factories\SupplierDueAmountFactory;

class SupplierDueAmount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'supplier_id',
        'payment_by',
        'amount',
        'paid_after_due',
        'paid_date',
        'file',
    ];

    public function paymentby(){
        return $this->belongsTo(User::class,'payment_by','id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
}
