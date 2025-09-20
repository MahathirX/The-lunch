<?php

namespace Modules\Product\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Database\factories\ProductItemFactory;

class ProductItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['product_id','item_name','qty','price'];

     public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory()
    {
        //return ProductItemFactory::new();
    }
}
