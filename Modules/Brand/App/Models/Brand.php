<?php

namespace Modules\Brand\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Brand\Database\factories\BrandFactory;
use Modules\Product\App\Models\Product;

class Brand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'name',
        'slug',
        'status',
        'image',
    ];

    public function products (){
        return $this->hasMany(Product::class, 'brand_id', 'id')->where('status','1');
    }

}
