<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderreview extends Model
{
    use HasFactory;
    protected $fillable = [
        'review',
        'review_text',
        'product_id',
        'user_id',
        'status',

    ];
    public function reviewDetails()
    {
        return $this->hasMany(ReviewDetail::class, 'orderreview_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
