<?php

namespace Modules\Attribute\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Attribute\Database\factories\AttributeFactory;

class Attribute extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'status',
        'user_id',
        'slug',
    ];
    public function options()
    {
        return $this->hasMany(Attributeoption::class, 'attribute_id');
    }

}
