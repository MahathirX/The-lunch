<?php

namespace Modules\SalesInvoice\App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Modules\SalesInvoice\App\Models\CustomerDueAmoutPaid;
use Modules\SalesInvoice\App\Models\SalesInvoice;
use Modules\SalesInvoice\App\Models\SalesInvoiceDetails;
use Yajra\DataTables\Facades\DataTables;

class SalesProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($phone)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $data['customer'] = Customer::where('phone', $phone)->first();
            if (!$data['customer']) {
                return;
            }
            $data['activeParentCustomersMenu']  = 'active';
            $data['activeInvoiceCustomersMenu'] = 'active';
            $data['showCustomersMenu']          = 'show';
            $this->setPageTitle($data['customer']->customer_name);
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']         = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Sales Invoice Title") => route('staff.salesinvoice.index'), __f('Customer Profile Title') => ''];
            }else{
                $data['breadcrumb']         = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Sales Invoice Title") => route('admin.salesinvoice.index'), __f('Customer Profile Title') => ''];
            }
            $data['totalsellamount'] = SalesInvoice::where('customer_phone', $phone)->sum('total_amount');
            $data['dueamount']       = SalesInvoice::where('customer_phone', $phone)->sum('due_amount');
            $data['totalamount']     = (int) $data['customer']->previous_due ?? 0;
            $data['paidamount']      = SalesInvoice::where('customer_phone', $phone)->sum('paid_amount');
            $data['discount']        = SalesInvoice::where('customer_phone', $phone)->sum('discount');
            $data['totalinvoice']    = SalesInvoice::where('customer_phone', $phone)->distinct('create_date')->count('create_date');
            $data['paiddates']       = CustomerDueAmoutPaid::where('user_id', $data['customer']->id)->distinct('paid_date')->pluck('paid_date');
            $data['customerid']      = $data['customer']->id;
            $data['customerphone']   = $data['customer']->phone;
            return view('salesinvoice::profile', $data);
        } else {
            abort(401);
        };
    }



    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                if($request->date != null){
                    $getData = CustomerDueAmoutPaid::with(['customer','receivedby'])->where('user_id',$request->customerid)->whereDate('paid_date',$request->date)->latest('id');
                }else{
                    $getData = CustomerDueAmoutPaid::with(['customer','receivedby'])->where('user_id',$request->customerid)->latest('id');
                }

                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->date)) {
                            $query->when($request->date, function ($query, $value) {
                                $query->where('paid_date', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('paid_date', function ($data) {
                        return  formatDateByLocale(\Carbon\Carbon::parse($data->paid_date)->format('d-m-Y'))?? ''  ?? '-----';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->customer->customer_name ?? '-----';
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->customer->phone ?? '-----';
                    })
                    ->addColumn('previous_due', function ($data) {
                        return convertToLocaleNumber($data->paid_after_due).' '.currency();
                    })
                    ->addColumn('paid_amount', function ($data){
                        return convertToLocaleNumber((int) $data->amount).' '.currency();
                    })
                    ->addColumn('present_amount', function ($data) {
                        return convertToLocaleNumber((int) $data->paid_after_due - (int) $data->amount ).' '.currency();
                    })
                    ->addColumn('received_by', function ($data) {
                        return $data->receivedby->fname .' '. $data->receivedby->lname;
                    })
                    ->addColumn('pdf', function ($data) {
                        // if($data->file != null){
                        //     return '<a title="'.__f('Download PDF Title').'" class="text-white bg-success py-1 px-2 rounded-1" target="_blank" href="'.asset($data->file).'" download><i class="fa-solid fa-download"></i></a>';
                        // }else{
                        //     return '<a title="'.__f('Generate PDF Title').'" class="text-white bg-primary py-1 px-2 rounded-1" href="'.route('admin.regenerate.paid.PDF',['id' =>$data->id]).'"><i class="fa-solid fa-recycle"></i></a>';
                        // }
                    })
                    ->rawColumns(['pdf'])
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
        return view('salesinvoice::create');
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
        return view('salesinvoice::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Sales Invoice Edit');
            $data['activeProductMenu']  = 'active';
            $data['activeCategoryMenu'] = 'active';
            $data['showProductMenu']    = 'show';
            $data['breadcrumb']         = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), 'Sales Invoice' => route('admin.salesinvoice.index'), 'Invoice Edit' => ''];
            $data['salesinvoice']       = SalesInvoice::where('id', $id)->first();
            $data['customer']           = Customer::where('phone', $data['salesinvoice']['customer_phone'])->first();
            $data['invoiceDetails']     = SalesInvoiceDetails::where('invoice_id', $data['salesinvoice']['invoice_id'])->get();
            return view('salesinvoice::singleInvoiceedit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }
}
