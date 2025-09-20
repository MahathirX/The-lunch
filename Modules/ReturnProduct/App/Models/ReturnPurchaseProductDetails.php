<?php

namespace Modules\ReturnProduct\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\App\Models\Product;
use Modules\ReturnProduct\Database\factories\ReturnPurchaseProductDetailsFactory;

class ReturnPurchaseProductDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'return_purchase_id',
        'supplier_id',
        'product_id',
        'invoice_id',
        'create_date',
        'cost',
        'qty',
        'batch_no',
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
