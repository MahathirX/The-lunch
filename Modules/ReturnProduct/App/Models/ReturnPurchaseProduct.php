<?php

namespace Modules\ReturnProduct\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Purchase\App\Models\Purchase;
use Modules\ReturnProduct\Database\factories\ReturnPurchaseProductFactory;
use Modules\Supplier\App\Models\Supplier;

class ReturnPurchaseProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'invoice_id',
        'supplier_id',
        'create_date',
        'total_amount',
        'due_amount',
        'note',
        'paid_amount',
        'discount',
        'status',
    ];


    public function returnpurchasedetails(){
        return $this->hasMany(ReturnPurchaseProductDetails::class, 'return_purchase_id','id')->with('product');
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id','id');
    }

    public function purchasinvoice(){
        return $this->belongsTo(Purchase::class, 'invoice_id','invoice_id');
    }
    
}
