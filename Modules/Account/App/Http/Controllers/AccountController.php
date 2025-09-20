<?php

namespace Modules\Account\App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Purchase\App\Models\Purchase;
use Modules\ExpenseList\App\Models\ExpenseList;
use Modules\SalesInvoice\App\Models\SalesInvoice;
use Modules\SalesInvoice\App\Models\SalesInvoiceDetails;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Account Title'));
            $data['activeAccountsMenu']    = 'active';
            $data['showAccountsMenu']      = 'show';
            $data['activeAccounting']      = 'active';
            $data['admin_sub_total']       = Purchase::sum('admin_sub_total');
            $data['sub_total']             = Purchase::sum('sub_total');
            $data['total_sales_amount']    = SalesInvoice::sum('total_amount');
            $data['total_discount_amount'] = SalesInvoice::sum('discount');
            $data['original_sales_price']  = SalesInvoiceDetails::sum('orginal_profit');
            $data['sales_price']           = SalesInvoiceDetails::sum('profit');
            $data['expense']               = (int) ExpenseList::sum('amount');
            return view('account::index', $data);
        } else {
            abort(401);
        }
    }

    public function filterByDate(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $start_date                  = Carbon::parse($request->start_date)->startOfDay();
                $end_date                    = Carbon::parse($request->end_date)->endOfDay();
                $expenseDates = ExpenseList::where('status', '1')
                    ->whereBetween('create_date', [$start_date, $end_date])
                    ->pluck('create_date')
                    ->unique();

                $salesDates = SalesInvoiceDetails::whereBetween('create_date', [$start_date, $end_date])
                    ->pluck('create_date')
                    ->unique();

                $allDates = $expenseDates->merge($salesDates)->unique()->sortDesc()->values();

                $data['expenses'] = $allDates->map(function ($date) {
                    $expenses = ExpenseList::where('status', '1')->where('create_date', $date)->get();
                    $amount = $expenses->sum('amount');

                    $orginalProfit = SalesInvoiceDetails::where('create_date', $date)->sum('orginal_profit');
                    $profit = SalesInvoiceDetails::where('create_date', $date)->sum('profit');
                    $discount = SalesInvoice::where('create_date',$date)->sum('discount');
                    return [
                        'create_date'   => $date,
                        'amount'        => $amount ?? 0,
                        'orginalprofit' => $orginalProfit ?? 0,
                        'profit'        => $profit ?? 0,
                        'discount'      => $discount ?? 0,
                    ];
                });
                $data['startDate']           = $start_date;
                $data['endDate']             = $end_date;
                $renderData                  = view('account::table', $data)->render();
                return response()->json([
                    'status'  => 'success',
                    'data' => $renderData
                ]);
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
        return view('account::create');
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
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('account::edit');
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
