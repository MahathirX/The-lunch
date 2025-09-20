<?php

namespace Modules\Product\App\Models;

use App\Models\OrderDetail;
use App\Models\Orderreview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Brand\App\Models\Brand;
use Modules\Category\App\Models\Category;
use Modules\ImageGallery\App\Models\ImageGallery;
use Modules\Purchase\App\Models\PurchaseInvoiceDetails;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_id',
        'name',
        'discount_price',
        'product_stock_qty',
        'product_sku'  ,
        'short_description',
        'special_feature',
        'price',
        'description',
        'product_location',
        'product_image',
        'product_gallery',
        'status',
        'tag',
        'producttype',
    ];

    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'productcategory_id','id');
    }

    // public function activecategory(){
    //     return $this->belongsTo(Category::class,'productcategory_id','id')->where('status','1');
    // }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Orderreview::class, 'product_id', 'id')->where('status', '1')->with('user');
    }

    public function images()
    {
        return $this->hasMany(ImageGallery::class);
    }

    public function bestselling()
    {
        return $this->hasMany(OrderDetail::class,'product_id','id');
    }

    public function productattibute(){
        return $this->hasMany(ProductAttribute::class);
    }
    public function purchaseproduct(){
        return $this->belongsTo(PurchaseInvoiceDetails::class,'id','product_id')->where('qty','!=','0');
    }
//Product item relation
    public function items()
{
    return $this->hasMany(ProductItem::class);
}

}
