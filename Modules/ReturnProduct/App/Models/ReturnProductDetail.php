<?php

namespace Modules\ReturnProduct\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\App\Models\Customer;
use Modules\Product\App\Models\Product;
use Modules\ReturnProduct\Database\factories\ReturnProductDetailFactory;

class ReturnProductDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'return_product_id',
        'invoice_id',
        'create_date',
        'customer_id',
        'product_id',
        'cost',
        'qty',
        'batch_no',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
