<?php

namespace Modules\SalesInvoice\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\App\Models\Customer;
use Modules\Product\App\Models\Product;

class SalesInvoiceDetails extends Model
{
    use HasFactory;
    // use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'create_date',
        'sales_id',
        'invoice_id',
        'product_id',
        'cost',
        'qty',
        'orginal_profit',
        'profit',
        'batch_no',
    ];

    protected $table = 'sales_invoice_details';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }
}
