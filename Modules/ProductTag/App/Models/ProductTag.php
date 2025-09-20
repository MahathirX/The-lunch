<?php

namespace Modules\ProductTag\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\ProductTag\Database\factories\ProductTagFactory;

class ProductTag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'tag_name',
        'slug',
        'status'
    ];
}
