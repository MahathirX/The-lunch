<?php

namespace Modules\PageMultiImage\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PageMultiImage\Database\factories\PageMultiImageFactory;

class PageMultiImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'page_id',
        'image',
    ];

}
