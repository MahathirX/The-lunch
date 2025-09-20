<?php

namespace Modules\Slider\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "status",
        "slider_image",
        "slider_m_image",
        "order_by",
    ];

}
