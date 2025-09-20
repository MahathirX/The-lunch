<?php

namespace App\Models;

use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'user_id',
        'charge',
        'amount',
        'quantity',
        'discount',
        'payment_status',
        'adress',
        'phone',
        'customer',
        'shippingtype',
        'couriertype',
        'couriertrakingid',
        'pickdate',
        'cityid',
        'zoneid',
        'weight',
        'note',
        'status',
    ];

    public function totalearnings(){
      return $this->hasMany(OrderDetail::class,'order_id','id');
    }
    public function orderdetails(){
      return $this->hasMany(OrderDetail::class,'order_id','id')->with('product');
    }

    public function user (){
        return $this->belongsTo(User::class);
    }

}
