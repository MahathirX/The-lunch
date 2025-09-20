<?php

namespace Modules\StaffDashboad\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Modules\ExpenseList\App\Models\ExpenseList;
use Modules\Product\App\Models\Product;
use Modules\SalesInvoice\App\Models\SalesInvoice;
use Modules\SalesInvoice\App\Models\SalesInvoiceDetails;

class StaffDashboadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle(__f('Dashboard Title'));
            $data['activeStaffDashboard']  = 'active';
            $data['profit']                = SalesInvoiceDetails::sum('profit');
            $data['expense']               = ExpenseList::sum('amount');
            $data['total_sales_amount']    = SalesInvoice::sum('total_amount');
            $data['total_discount_amount'] = SalesInvoice::sum('discount');
            $data['total_products']        = Product::count();
            return view('staffdashboad::index', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staffdashboad::create');
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
        return view('staffdashboad::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('staffdashboad::edit');
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
