<?php

namespace Modules\SalesInvoice\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\App\Models\Customer;


class SalesInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'sub_total',
        'total_amount',
        'attachment',
        'note',
        'invoice_id',
        'customer_phone',
        'customer_name',
        'customer_address',
        'create_date',
        'due_date',
        'tax',
        'paid_amount',
        'discount',
        'due_amount',
        'status'
    ];


    protected $table = 'sales_invoices';

    public function invoiceDetails()
    {
        return $this->hasMany(SalesInvoiceDetails::class, 'sales_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_phone','phone');
    }
}
