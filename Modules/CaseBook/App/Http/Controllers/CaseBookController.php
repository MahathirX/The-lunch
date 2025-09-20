<?php

namespace Modules\CaseBook\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\CaseBook\App\Http\Requests\CaseBookRequest;
use Modules\CaseBook\App\Models\CaseBook;
use Modules\Purchase\App\Models\Purchase;
use Modules\SalesInvoice\App\Models\SalesInvoice;
use Yajra\DataTables\Facades\DataTables;
use staff;
use Carbon\Carbon;
use Modules\ExpenseList\App\Models\ExpenseList;
use Modules\SalesInvoice\App\Models\CustomerDueAmoutPaid;

class CaseBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminIndex()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Cash Books Title'));
            $data['activeAccountsMenu']        = 'active';
            $data['activeAdminCashBookMenu']   = 'active';
            $data['showAccountsMenu']          = 'show';
            $data['orginalAssets']             = Purchase::sum('admin_sub_total');
            $data['buyAssets']                 = Purchase::sum('sub_total');
            $data['sellAmount']                = SalesInvoice::whereDate('create_date', Carbon::today())->sum('paid_amount');
            $data['collectAmount']             = CustomerDueAmoutPaid::whereDate('paid_date', Carbon::today())->sum('amount');
            $data['totalExpences']             = ExpenseList::whereDate('create_date', Carbon::today())->sum('amount');
            $data['previusSellAmount']         = SalesInvoice::whereDate('create_date', '<', Carbon::today())->sum('paid_amount');
            $data['previusCollectAmount']      = CustomerDueAmoutPaid::whereDate('paid_date', '<', Carbon::today())->sum('amount');
            $data['previusTotalExpences']      = ExpenseList::whereDate('create_date', '<', Carbon::today())->sum('amount');
            $data['previusTotallocalPurchase'] = Purchase::whereDate('invoice_date', '<', Carbon::today())->sum('paid_amount');;
            $data['localPurchase']             = Purchase::whereDate('invoice_date', Carbon::today())->sum('paid_amount');
            $data['startDateAmount']           = ((int) $data['previusSellAmount'] + (int) $data['previusCollectAmount']) -  ((int) $data['previusTotalExpences'] + (int) $data['previusTotallocalPurchase']);
            $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Cash Books Title') => ''];
            return view('casebook::adminindex', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle(__f('Cash Books Title'));
            $data['activeAccountsMenu']        = 'active';
            $data['activeCashBookMenu']        = 'active';
            $data['showAccountsMenu']          = 'show';
            $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Cash Books Title') => ''];
            $data['sellAmount']                = SalesInvoice::whereDate('create_date', Carbon::today())->sum('paid_amount');
            $data['collectAmount']             = CustomerDueAmoutPaid::whereDate('paid_date', Carbon::today())->sum('amount');
            $data['totalExpences']             = ExpenseList::whereDate('create_date', Carbon::today())->sum('amount');
            $data['previusSellAmount']         = SalesInvoice::whereDate('create_date', '<', Carbon::today())->sum('paid_amount');
            $data['previusCollectAmount']      = CustomerDueAmoutPaid::whereDate('paid_date', '<', Carbon::today())->sum('amount');
            $data['previusTotalExpences']      = ExpenseList::whereDate('create_date', '<', Carbon::today())->sum('amount');
            $data['previusTotallocalPurchase'] = Purchase::whereDate('invoice_date', '<', Carbon::today())->where('user_id',Auth::id())->sum('paid_amount');;
            $data['localPurchase']             = Purchase::whereDate('invoice_date', Carbon::today())->where('user_id',Auth::id())->sum('paid_amount');
            $data['startDateAmount']           = ((int) $data['previusSellAmount'] + (int) $data['previusCollectAmount']) - ((int) $data['previusTotalExpences'] + (int) $data['previusTotallocalPurchase']);
            return view('casebook::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function cashbookReport()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Cash Book Report Title'));
            $data['activeAccountsMenu'] = 'active';
            $data['showAccountsMenu']   = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']         = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Cash Book Report Title') => ''];
                $data['activeCashBookMenu'] = 'active';
            } else {
                $data['breadcrumb']              = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Cash Book Report Title') => ''];
                $data['activeAdminCashBookMenu'] = 'active';
            }
            return view('casebook::report', $data);
        } else {
            abort(401);
        }
    }

    public function cashbookReportGetData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
                $endDate   = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

                $getData = [];

                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    $dayStart = $date->copy()->startOfDay();
                    $dayEnd   = $date->copy()->endOfDay();

                    $sellAmount = SalesInvoice::whereBetween('create_date', [$dayStart, $dayEnd])->sum('paid_amount');
                    $collectAmount = CustomerDueAmoutPaid::whereBetween('paid_date', [$dayStart, $dayEnd])->sum('amount');
                    $expenseAmount = ExpenseList::whereBetween('create_date', [$dayStart, $dayEnd])->sum('amount');
                    $localPurchaseAmount = Purchase::where('user_id',Auth::id())->whereBetween('invoice_date', [$dayStart, $dayEnd])->sum('paid_amount');

                    $getData[] = [
                        'date' => $date->toDateString(),
                        'sell_amount' => $sellAmount,
                        'collect_amount' => $collectAmount,
                        'expense_amount' => $expenseAmount,
                        'localPurchaseAmount' => $localPurchaseAmount,
                    ];
                }

                $getData = array_reverse($getData);

                return DataTables::of($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('date', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('date', function ($data) {
                        return formatDateByLocale(\Carbon\Carbon::parse($data['date'])->format('d-m-Y'));
                    })
                    ->addColumn('total_cash', function ($data) {
                        $total =  (int) $data['sell_amount'] + (int) $data['collect_amount'];
                        $currency = currency();
                        return $currency ? $total . ' ' . $currency : '-----';
                    })
                    ->addColumn('expense_amount', function ($data) {
                        $total = (int) $data['expense_amount'];
                        $currency = currency();
                        return $currency ? $total . ' ' . $currency : '-----';
                    })
                    ->addColumn('local_purchas_amount', function ($data) {
                        $total = (int) $data['localPurchaseAmount'];
                        $currency = currency();
                        return $currency ? $total . ' ' . $currency : '-----';
                    })
                    ->addColumn('balance', function ($data) {
                        $total = (int) $data['sell_amount'] + (int) $data['collect_amount'];
                        $totalexpense = (int) $data['expense_amount'];
                        $currency = currency();
                        return $currency ?  $total - $totalexpense . ' ' . $currency : '-----';
                    })
                    ->rawColumns(['payment_type', 'note', 'status', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle('Cash Books Create');
            $data['activeAccountsMenu'] = 'active';
            $data['activeCashBookMenu'] = 'active';
            $data['showAccountsMenu']   = 'show';
            $data['sellAmount']         = SalesInvoice::whereDate('create_date', Carbon::today())->sum('paid_amount');
            $data['collectAmount']      = CustomerDueAmoutPaid::whereDate('paid_date', Carbon::today())->sum('amount');
            $data['totalExpences']      = ExpenseList::whereDate('create_date', Carbon::today())->sum('amount');

            $data['previusSellAmount']    = SalesInvoice::whereDate('create_date', '<', Carbon::today())->sum('paid_amount');
            $data['previusCollectAmount'] = CustomerDueAmoutPaid::whereDate('paid_date', '<', Carbon::today())->sum('amount');
            $data['previusTotalExpences'] = ExpenseList::whereDate('create_date', '<', Carbon::today())->sum('amount');
            $data['startDateAmount']      = ((int) $data['previusSellAmount'] + (int) $data['previusCollectAmount']) - $data['previusTotalExpences'];
            $data['breadcrumb']           = ['Staff Dashboard' => route('staff.dashboard.index'), 'Cash Books Create' => ''];
            return view('casebook::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CaseBookRequest $request)
    {
        if (Gate::allows('isStaff')) {
            if ($request->ajax()) {
                CaseBook::create([
                    'staff_id'     => Auth::id(),
                    'payment_date' => $request->payment_date,
                    'amount'       => $request->amount,
                    'payment_type' => $request->payment_type,
                    'note'         => $request->note ?? null,
                    'status'       => '0',
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Cashbook create successfully !',
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = CaseBook::where('staff_id', Auth::id())->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('amount', 'like', "%{$value}%")
                                    ->orWhere('payment_type', 'like', "%{$value}%")
                                    ->orWhere('payment_date', 'like', "%{$value}%")
                                    ->orWhere('status', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('date', function ($data) {
                        return $data->payment_date ?? '-----';
                    })
                    ->addColumn('amount', function ($data) {
                        return $data->amount ? (int) $data->amount . ' ' . currency() : '-----';
                    })
                    ->addColumn('payment_type', function ($data) {
                        return   paymentType($data->payment_type);
                    })
                    ->addColumn('note', function ($data) {
                        return  $data->note ?? '-----';
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '0') {
                            return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item align-items-center" href="' . route('staff.cashbook.edit', ['cashbook' => $data->id]) . '">
                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a></li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                                        </li>
                                        <form action="' . route('staff.cashbook.delete', ['id' => $data->id]) . '"
                                            id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                            @csrf
                                            @method("DELETE")
                                        </form>
                                    </ul>
                                </div>';
                        } else {
                            return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li class="text-danger px-3"><i class="fa-solid fa-ban me-2"></i>No Action Available</li>
                                    </ul>
                                </div>';
                        }
                    })
                    ->rawColumns(['payment_type', 'note', 'status', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }
    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('casebook::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle('Cash Books Edit');
            $data['activeAccountsMenu'] = 'active';
            $data['activeCashBookMenu'] = 'active';
            $data['showAccountsMenu']   = 'show';
            $data['editData']           = CaseBook::where('id', $id)->first();
            $data['breadcrumb']         = ['Staff Dashboard' => route('staff.dashboard.index'), 'Cash Books Edit' => ''];
            return view('casebook::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Gate::allows('isStaff')) {
            if ($request->ajax()) {
                $getCasebook = CaseBook::where('id', $id)->first();
                $getCasebook->update([
                    'payment_date' => $request->payment_date,
                    'amount'       => $request->amount,
                    'payment_type' => $request->payment_type,
                    'note'         => $request->note ?? null,
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Cashbook update successfully !',
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Gate::allows('isStaff')) {
            $getCasebook = CaseBook::where('id', $id)->first();
            if ($getCasebook) {
                $getCasebook->delete();
            }
            return back()->with('status', 'Cashbook delete successfully !');
        } else {
            abort(401);
        }
    }
    public function cashBookDetails(Request $request)
    {
        if (Gate::allows('isStaff')) {
            if ($request->ajax()) {
                if ($request->date != null) {
                    $data['sellAmount']    = SalesInvoice::whereDate('create_date', $request->date)->sum('paid_amount');
                    $data['collectAmount'] = CustomerDueAmoutPaid::whereDate('paid_date', $request->date)->sum('amount');
                    $data['totalExpences'] = (int) ExpenseList::whereDate('create_date', $request->date)->sum('amount');

                    $data['previusSellAmount']         = SalesInvoice::whereDate('create_date',  '<', $request->date)->sum('paid_amount');
                    $data['previusCollectAmount']      = CustomerDueAmoutPaid::whereDate('paid_date', '<', $request->date)->sum('amount');
                    $data['previusTotalExpences']      = ExpenseList::whereDate('create_date', '<', $request->date)->sum('amount');
                    $data['previusTotalLocalPurchase'] = Purchase::where('user_id',Auth::id())->whereDate('invoice_date','<', $request->date)->sum('paid_amount');
                    $data['localPurchase']             = (int) Purchase::where('user_id',Auth::id())->whereDate('invoice_date', $request->date)->sum('paid_amount');

                    $data['startDateAmount'] = ((int) $data['previusSellAmount'] +  (int) $data['previusCollectAmount']) -  ((int) $data['previusTotalExpences'] + (int) $data['previusTotalLocalPurchase']);
                    $data['totalCase']       = (int) $data['startDateAmount'] + (int) $data['sellAmount'] +  (int) $data['collectAmount'];
                    $data['totalBalence']    = ((int) $data['totalCase'])  -  ((int) $data['totalExpences'] + $data['localPurchase']);
                    return response()->json([
                        'status'  => 'success',
                        'data'    => $data,
                    ]);
                }
            }
        } else {
            abort(401);
        }
    }

    public function aminCashBookDetails(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->date != null) {
                    $data['sellAmount']           = SalesInvoice::whereDate('create_date', $request->date)->sum('paid_amount');
                    $data['collectAmount']        = CustomerDueAmoutPaid::whereDate('paid_date', $request->date)->sum('amount');
                    $data['totalExpences']        = (int) ExpenseList::whereDate('create_date', $request->date)->sum('amount');

                    $data['previusSellAmount']    = SalesInvoice::whereDate('create_date',  '<', $request->date)->sum('paid_amount');
                    $data['previusCollectAmount'] = CustomerDueAmoutPaid::whereDate('paid_date', '<', $request->date)->sum('amount');
                    $data['previusTotalExpences'] = ExpenseList::whereDate('create_date', '<', $request->date)->sum('amount');

                    $data['startDateAmount']      = ((int) $data['previusSellAmount'] +  (int) $data['previusCollectAmount']) -  (int) $data['previusTotalExpences'];
                    $data['totalCase']            = (int) $data['startDateAmount'] + (int) $data['sellAmount'] +  (int) $data['collectAmount'];
                    $data['totalBalence']         = ((int) $data['totalCase'])  -  (int) $data['totalExpences'];
                    return response()->json([
                        'status'  => 'success',
                        'data'    => $data,
                    ]);
                }
            }
        } else {
            abort(401);
        }
    }
}
