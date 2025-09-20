<?php

namespace Modules\Purchase\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Supplier\App\Models\Supplier;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'supplier_id',
        'invoice_id',
        'invoice_date',
        'admin_sub_total',
        'sub_total',
        'total_qnt',
        'discount',
        'tax',
        'paid_amount',
        'due_amount',
        'note',
        'purchase_type',
        'purchase_by',
        'status',

    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }
    public function purchaseinvoicedetails(){
        return $this->hasMany(PurchaseInvoiceDetails::class,'purchase_id','id')->with(['product']);
    }
}
