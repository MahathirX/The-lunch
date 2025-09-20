<?php

namespace Modules\Purchase\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Purchase\App\Models\PurchaseInvoiceDetails;
use Yajra\DataTables\Facades\DataTables;

class StockProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function StockProductList()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Stock Product List Title'));
            $data['activeParentPurchaseMenu']       = 'active';
            $data['activeStockPurchaseProductMenu'] = 'active';
            $data['showPurchaseMenu']               = 'show';
            return view('purchase::stock_product_list', $data);
        } else {
            abort(401);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $purchase = PurchaseInvoiceDetails::with(['product'])->latest('id');
                return DataTables::eloquent($purchase)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('invoice_id', 'like', "%{$request->search}%")
                                        ->orWhere('batch_no', 'like', "%{$request->search}%")
                                    ->orWhereHas('product', function ($q) use ($request) {
                                        $q->where('name', 'like', "%{$request->search}%");
                                    });
                            });
                        }
                    })
                    ->addColumn('admins_get_total', function ($data) {
                        return ($data->admin_buy_price ? convertToLocaleNumber($data->admin_buy_price) . ' ' . currency() : '-----');
                    })
                    ->addColumn('buys_price', function ($data) {
                        return convertToLocaleNumber($data->buy_price) . ' ' . currency()  ?? '----';
                    })
                    ->addColumn('product_name', function ($data) {
                        return $data->product->name ?? '----';
                    })
                    ->addColumn('product_location', function ($data) {
                        return $data->product->product_location ? $data->product->product_location : '-----------';
                    })
                    ->addColumn('invoice_id', function ($data) {
                        return '#'.($data->invoice_id) ?? '----';
                    })
                    ->addColumn('qty', function ($data) {
                        return '<span class="badge bg-success px-3 py-2">'.convertToLocaleNumber($data->qty) ?? '----'.'</span>';
                    })
                    ->addColumn('product_sales_qty', function ($data) {
                        return '<span class="badge bg-info px-3 py-2">'.convertToLocaleNumber($data->sales_qty) ?? '----'.'</span>';
                    })
                    ->addColumn('stock_qty', function ($data) {
                        return '<span class="badge bg-warning px-3 py-2">'.convertToLocaleNumber($data->qty - $data->sales_qty).'</span>';
                    })
                    ->addColumn('batch_no', function ($data) {
                        return '<span class="badge bg-success p-1">Batch No : ' . (convertToLocaleNumber($data->batch_no) ?? '----') . '</span>';
                    })
                    ->rawColumns(['product_location','qty','product_sales_qty','stock_qty','status', 'batch_no'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('purchase::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('purchase::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('purchase::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
