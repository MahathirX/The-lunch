<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\App\Models\Product;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'order_id',
        'product_id',
        'product_name',
        'product_image',
        'quantity',
        'amount',
        'grandtotal',
        'status',
        'varient',
    ];
    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
