<?php

namespace Modules\Page\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Page\Database\factories\PageFactory;
use Modules\PageMultiImage\App\Models\PageMultiImage;
use Modules\Product\App\Models\Product;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'product_id',
        'page_name',
        'slug',
        'page_heading',
        'page_link',
        'product_overview',
        'slider_title',
        'features',
        'old_price',
        'new_price',
        'phone',
        'extra_content',
        'status',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function image(){
        return $this->hasMany(PageMultiImage::class);
    }

}
