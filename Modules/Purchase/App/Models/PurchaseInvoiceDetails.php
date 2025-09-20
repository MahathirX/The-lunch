<?php

namespace Modules\Purchase\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\App\Models\Product;

class PurchaseInvoiceDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'purchase_invoice_details'; 
    protected $fillable = [
        'purchase_id',
        'invoice_id',
        'product_id',
        'qty',
        'sales_qty',
        'admin_buy_price',
        'buy_price',
        'admin_sub_total',
        'sub_total',
        'batch_no'
    ];

   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }
}
