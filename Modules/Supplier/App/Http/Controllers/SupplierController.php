<?php

namespace Modules\Supplier\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\Customer\App\Models\Customer;
use Modules\Purchase\App\Models\Purchase;
use Modules\Supplier\App\Http\Requests\SupplierRequest;
use Modules\Supplier\App\Models\Supplier;
use Modules\Supplier\App\Models\SupplierDueAmount;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $this->setPageTitle( __f('Supplier List Title'));
            $data['activeParentCustomersMenu'] = 'active';
            $data['activeSupplierMenu']        = 'active';
            $data['showCustomersMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                 = [__f('Staff Dashboard Title') => route('staff.dashboard.index'),  __f('Supplier List Title') => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'),  __f('Supplier List Title') => ''];
            }
            return view('supplier::index', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $this->setPageTitle(__f('Supplier Create Title'));
            $data['activeParentCustomersMenu'] = 'active';
            $data['activeSupplierCreateMenu']  = 'active';
            $data['showCustomersMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Supplier List Title') => route('staff.supplier.index'), __f('Supplier Create Title') => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Supplier List Title') => route('admin.supplier.index'), __f('Supplier Create Title') => ''];
            }
            return view('supplier::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Fetch Data from Supplier.
     */
    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            if ($request->ajax()) {
                $getData = Supplier::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request->search}%")
                                    ->orWhere('phone', 'like', "%{$request->search}%")
                                    ->orWhere('address', 'like', "%{$request->search}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return '<a href="'. route('admin.supplier.profile',  $data->id).'">'.$data->name ?? '---------'.'</a>';
                    })
                    ->addColumn('phone', function ($data) {
                        if($data->phone != null){
                            return '<a href="'. route('admin.supplier.profile',  $data->id).'">'.Str::limit($data->phone,11,'...').'</a>';
                        }else{
                            return '---------';
                        }
                    })
                    ->addColumn('email', function ($data) {
                        if($data->email != null){
                            return '<a href="'. route('admin.supplier.profile',  $data->id).'">'.$data->email ?? '---------'.'</a>';
                        }else{
                            return '---------';
                        }
                    })
                    ->addColumn('compay_name', function ($data) {
                        if($data->company_name != null){
                            return '<a href="'. route('admin.supplier.profile',  $data->id).'">'.Str::limit($data->company_name,10,'...').'</a>';
                        }else{
                            return '---------';
                        }
                    })
                    ->addColumn('previous_due', function ($data) {
                        return convertToLocaleNumber($data->previous_due).' '.currency() ?? '----';
                    })
                    ->addColumn('total_qty', function ($data) {
                        $qty = Purchase::where('supplier_id' , $data->id)->sum('total_qnt');
                        return convertToLocaleNumber($qty) ?? '----';
                    })
                    ->addColumn('paid_amount', function ($data) {
                        $paid_amount = Purchase::where('supplier_id' , $data->id)->sum('paid_amount');
                        return convertToLocaleNumber($paid_amount).' '.currency() ?? 0 .' '.currency() ;
                    })
                    ->addColumn('due_amount', function ($data) {
                        $due_amount = Purchase::where('supplier_id' , $data->id)->sum('due_amount');
                        return convertToLocaleNumber($due_amount).' '.currency() ?? 0 .' '.currency();
                    })
                    ->addColumn('image', function ($data) {
                        if ($data->photo != null) {
                            return '<a target="_blank" href="' . asset($data->photo) . '"><img id="getDataImage" src="' . asset($data->photo) . '" alt="image"></a>';
                        } else {
                            return '<img id="getDataImage" src="' . asset('backend/assets/img/avatars/blank image.jpg') . '" alt="image">';
                        }
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {

                        if(Auth::check() && Auth::user()->role_id == 3){
                            $statusAction = '';
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.supplier.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.supplier.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item align-items-center"
                                            href="' . route('staff.supplier.profile',  $data->id). '">
                                                <i class="fa-solid fa-user text-info me-2"></i>'. __f("Profile Title") .'
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('staff.supplier.edit',  $data->id) . '">
                                                <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                        </li>
                                        <li>' . $statusAction . '</li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                            <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                        </li>
                                    <form action="' . route('staff.supplier.destroy', $data->id) . '"
                                            id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                            @csrf
                                            @method("DELETE")
                                    </form>
                                    </ul>
                                </div>';
                        }else{
                            $statusAction = '';
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.supplier.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.supplier.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="' . route('admin.supplier.profile',  $data->id). '">
                                                    <i class="fa-solid fa-user text-info me-2"></i>'. __f("Profile Title") .'
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.supplier.edit',  $data->id) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                        <form action="' . route('admin.supplier.destroy', $data->id) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                        </form>
                                        </ul>
                                    </div>';
                                }
                    })
                    ->rawColumns(['action', 'image', 'status', 'name', 'phone', 'email', 'compay_name'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            if ($request->file('photo')) {
                $image = $this->imageUpload($request->file('photo'), 'supplier/', null, null);
            } else {
                $image = null;
            }

            $supplier =  Supplier::create([
                'group'           => $request->group,
                'name'            => $request->name,
                'company_name'    => $request->company_name,
                'phone'           => $request->phone,
                'photo'           => $image,
                'email'           => $request->email,
                'address'         => $request->address,
                'vat'             => $request->vat,
                'city'            => $request->city,
                'state'           => $request->state,
                'postal_code'     => $request->postal_code,
                'country'         => $request->country,
                'status'          => $request->status,
                'previous_due'    => $request->previous_due ?? 0,
            ]);

            if ($request->check_supplier == 1) {
                Customer::create([
                    'customer_name' => $request->name,
                    'supplier_id'   => $supplier->id,
                    'phone'         => $request->phone,
                    'address'       => $request->address,
                    'status'        => $request->status,
                    'photo'         => $image,
                ]);
            }
            return response()->json([
                'status'  => 'success',
                'message' => __f('Supplier Create Success Message'),
            ]);
        } else {
            abort(401);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('supplier::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $this->setPageTitle(__f('Supplier Edit Title'));
            $data['activeParentCustomersMenu'] = 'active';
            $data['activeSupplierMenu']        = 'active';
            $data['showCustomersMenu']         = 'show';
            $data['supplier']                  = Supplier::where('id', $id)->first();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'),  __f('Supplier List Title') => route('staff.supplier.index'), __f('Supplier Edit Title') => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'),  __f('Supplier List Title') => route('admin.supplier.index'), __f('Supplier Edit Title') => ''];
            }
            return view('supplier::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, $id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $supplier = Supplier::findOrFail($id);
            if ($request->file('photo')) {
                if($supplier->photo != null && $supplier->photo != ''){
                    $this->imageDelete($supplier->photo);
                }
                $image = $this->imageUpload($request->file('photo'), 'supplier/', null, null);
            } else {
                $image = $supplier->photo;
            }

            $supplier->update([
                'name'            => $request->name,
                'company_name'    => $request->company_name,
                'phone'           => $request->phone,
                'photo'           => $image,
                'email'           => $request->email,
                'address'         => $request->address,
                'vat'             => $request->vat,
                'city'            => $request->city,
                'state'           => $request->state,
                'postal_code'     => $request->postal_code,
                'country'         => $request->country,
                'status'          => $request->status,
                'previous_due'    => $request->previous_due ?? 0
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => __f('Supplier Update Success Message'),
            ]);
        } else {
            abort(401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $getSupplier = Supplier::where('id', $id)->first();
            if($getSupplier->photo != null && $getSupplier->photo != ''){
                $this->imageDelete($getSupplier->photo);
            }
            $getSupplier->delete();
            return back()->with('success', __f('Supplier Delete Success Message'));
        } else {
            abort(401);
        }
    }


    public function changeStatus($id, $status)
    {

        if (Gate::any(['isAdmin','isStaff'])) {
            $getSupplier = Supplier::where('id', $id)->first();
            $getSupplier->update([
                'status' => $status
            ]);
            return back()->with('success', __f('Supplier Status Change Message'));
        } else {
            abort(401);
        }
    }

    public function profile($id){
        if (Gate::any(['isAdmin','isStaff'])) {
            $data['activeParentCustomersMenu'] = 'active';
            $data['activeSupplierMenu']        = 'active';
            $data['showCustomersMenu']         = 'show';
            $data['supplier']                  = Supplier::where('id', $id)->first();
            $this->setPageTitle($data['supplier']->name ?? __f('Supplier Profile Title'));
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'),  __f('Supplier List Title') => route('staff.supplier.index'), __f('Supplier Profile Title') => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'),  __f('Supplier List Title') => route('admin.supplier.index'), __f('Supplier Profile Title') => ''];
            }
            $data['orginal_sub_total']         = Purchase::where('supplier_id', $id)->sum('admin_sub_total');
            $data['sub_total']                 = Purchase::where('supplier_id', $id)->sum('sub_total');
            $data['discount']                  = Purchase::where('supplier_id', $id)->sum('discount');
            $data['paid_amount']               = Purchase::where('supplier_id', $id)->sum('paid_amount');
            $data['total_qty']                 = Purchase::where('supplier_id', $id)->sum('total_qnt');
            $data['orginal_due_amount']        = (int) $data['orginal_sub_total'] - (int) ($data['paid_amount'] + (int) $data['discount']);
            $data['due_amount']                = (int) $data['sub_total'] - ((int) $data['paid_amount'] + (int) $data['discount']);
            $data['invoices']                  = Purchase::where('supplier_id', $id)->count();
            $data['supplierid']                = $data['supplier']->id;
            $data['paiddates']                 = SupplierDueAmount::where('supplier_id', $data['supplier']->id)->distinct('paid_date')->pluck('paid_date');
            return view('supplier::supplier_profile',$data);
        }

    }

    public function dueAmoutPayment(Request $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $previussupplier = Supplier::where('id', $request->user_id)->first();
            $supplier        = Supplier::where('id', $request->user_id)->first();
            $invoicedue      = (int) Purchase::where('supplier_id', $request->user_id)->sum('due_amount');
            if ($supplier) {
                if ((int) $request->paidamount <= (int) $supplier->previous_due + (int) $invoicedue ) {
                    if($supplier->previous_due >= (int) $request->paidamount){
                        $supplier->update([
                            'previous_due'   => $supplier->previous_due -  (int) $request->paidamount,
                        ]);
                    }else{
                        $totalextraamount =  (int) $request->paidamount - (int) $supplier->previous_due;
                        $supplier->update([
                            'previous_due'   => 0,
                        ]);
                        $getInvoices = Purchase::where('supplier_id', $request->user_id)->get();
                        if (count($getInvoices) > 0 && $totalextraamount > 0) {
                            foreach ($getInvoices as $invoice) {
                                if ($totalextraamount <= 0) {
                                    break;
                                }
                                $due = (int) $invoice->due_amount;
                                if ($due > 0) {
                                    if ($totalextraamount >= $due) {
                                        $totalextraamount -= $due;
                                        $invoice->due_amount = 0;
                                        $invoice->status = '1';
                                        $invoice->paid_amount = (int) $invoice->paid_amount + $due;
                                    } else {
                                        $invoice->due_amount = $due - $totalextraamount;
                                        $invoice->paid_amount = (int) $invoice->paid_amount + $totalextraamount;
                                        $totalextraamount = 0;
                                    }
                                    $invoice->save();
                                }
                            }
                        }
                    }
                    $invoicenowdue          = (int) Purchase::where('supplier_id', $request->user_id)->sum('due_amount');
                    $returnpaidamount       = Purchase::where('supplier_id', $request->user_id)->sum('paid_amount');
                    $returndueamount        = Purchase::where('supplier_id', $request->user_id)->sum('due_amount');
                    $returnprevisudueamount = $supplier->previous_due;
                    if ($request->genarateinvoice == 1) {
                        $data['customer'] = (object)[
                            'previous_due'  => (int) $supplier->previous_due ,
                            'customer_name' => $supplier->name,
                            'phone'         => $supplier->phone,
                            'address'       => $supplier->address,
                        ];
                        $data['previuscustomer'] = $previussupplier;
                        $data['paidamount']      = $request->paidamount;
                        $data['paiddate']        = $request->paiddate;
                        $data['invoicedue']      = $invoicedue ;
                        $data['invoicenowdue']   = $invoicenowdue;

                        $pdf = app('dompdf.wrapper');
                        $pdf->setPaper('A4');
                        $pdf->loadView('salesinvoice::paidamountprint', $data);

                        $directory = public_path('uploads/due/supplier/pdf');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }

                        $filename = 'previous_due_' . time() . '.pdf';
                        $pdfPath = "$directory/$filename";
                        $pdf->save($pdfPath);

                        SupplierDueAmount::create([
                            'supplier_id'        => $supplier->id,
                            'payment_by'         => Auth::id(),
                            'paid_after_due'     => (int) $previussupplier->previous_due + (int) $invoicedue,
                            'amount'             => $request->paidamount,
                            'paid_date'          => $request->paiddate,
                            'file'               => "uploads/due/supplier/pdf/$filename",
                        ]);

                        return response()->json([
                            'previous_due'     => (int) $supplier->previous_due + (int) $invoicenowdue,
                            'status'           => 'success',
                            'message'          => 'Previous due amount paid successfully',
                            'paidamount'       => $returnpaidamount,
                            'dueamount'        => $returndueamount,
                            'previsudueamount' => $returnprevisudueamount,
                            'pdf_url'      => asset("uploads/due/supplier/pdf/$filename"),
                        ]);

                    } else {
                        SupplierDueAmount::create([
                            'supplier_id'    => $supplier->id,
                            'payment_by'     => Auth::id(),
                            'paid_after_due' => (int) $previussupplier->previous_due + (int) $invoicedue,
                            'amount'         => $request->paidamount,
                            'paid_date'      => $request->paiddate,
                            'file'           => null,
                        ]);
                        return response()->json([
                            'previous_due'     => (int) $supplier->previous_due + (int) $invoicenowdue,
                            'status'           => 'success',
                            'message'          => 'Previous due amount paid successfully',
                            'paidamount'       => $returnpaidamount,
                            'dueamount'        => $returndueamount,
                            'previsudueamount' => $returnprevisudueamount,
                        ]);
                    }
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invaid Amount',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supplier not found',
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function userDueGetData(Request $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            if ($request->ajax()) {
                if($request->date != null){
                    $getData = SupplierDueAmount::with(['supplier','paymentby'])->where('supplier_id',$request->supplierid)->whereDate('paid_date',$request->date)->latest('id');
                }else{
                    $getData = SupplierDueAmount::with(['supplier','paymentby'])->where('supplier_id',$request->supplierid)->latest('id');
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
                        return formatDateByLocale(\Carbon\Carbon::parse($data->paid_date)->format('d-m-Y')) ;
                    })
                    ->addColumn('name', function ($data) {
                        return $data->supplier->name ?? '-----';
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->supplier->phone ?? '-----';
                    })
                    ->addColumn('previous_due', function ($data) {
                        return convertToLocaleNumber($data->paid_after_due).' '.currency();
                    })
                    ->addColumn('paid_amount', function ($data){
                        return convertToLocaleNumber((int) $data->amount).' '.currency();
                    })
                    ->addColumn('payment_by', function ($data) {
                        return  $data->paymentby->fname .' '.$data->paymentby->lname;
                    })
                    ->addColumn('present_amount', function ($data) {
                        return convertToLocaleNumber((int) $data->paid_after_due - (int) $data->amount) .' '.currency();
                    })
                    ->addColumn('pdf', function ($data) {
                        if($data->file != null){
                            // return '<a title="'.__f('Download PDF Title').'" class="text-white bg-success py-1 px-2 rounded-1" target="_blank" href="'.asset($data->file).'" download><i class="fa-solid fa-download"></i></a>';
                        }else{
                            // return '<a title="'.__f('Generate PDF Title').'" class="text-white bg-primary py-1 px-2 rounded-1" href="'.route('admin.supplier.regenerate.paid.PDF',['id' =>$data->id]).'"><i class="fa-solid fa-recycle"></i></a>';
                        }
                    })
                    ->rawColumns(['pdf'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    public function regeneratePaidPDF($id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $getdueinfo = SupplierDueAmount::with(['supplier'])->where('id',$id)->first();
            $data['customer'] = (object)[
                'previous_due'  => (int) $getdueinfo->paid_after_due - (int) $getdueinfo->amount,
                'customer_name' => $getdueinfo->supplier->name,
                'phone'         => $getdueinfo->supplier->phone,
                'address'       => $getdueinfo->supplier->address,
            ];
            $data['previuscustomer'] = (object)[
                'previous_due' => (int) $getdueinfo->paid_after_due,
            ];
            $data['paidamount'] = $getdueinfo->amount;
            $data['paiddate']   = $getdueinfo->paid_date;

            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('A4');
            $pdf->loadView('salesinvoice::paidamountprint', $data);

            $directory = public_path('uploads/due/supplier/pdf');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $filename = 'previous_due_' . time() . '.pdf';
            $pdfPath = "$directory/$filename";
            $pdf->save($pdfPath);
            $getdueinfo->update([
                'file'     => "uploads/due/supplier/pdf/$filename",
            ]);
            return $pdf->download($filename);
        } else {
            abort(401);
        }
    }

    public function purchaseGetData(Request $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            if ($request->ajax()) {
                $getData = Purchase::with(['supplier'])->where('supplier_id',$request->supplierid)->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('invoice_id', 'like', "%{$request->search}%")
                                    ->orWhere('status', 'like', "%{$request->search}%")
                                    ->orWhere('invoice_date', 'like', "%{$request->search}%")
                                    ->orWhereHas('supplier', function ($q) use ($request) {
                                        $q->where('name', 'like', "%{$request->search}%")
                                            ->orWhere('phone', 'like', "%{$request->search}%");
                                    });
                            });
                        }
                    })
                    ->addColumn('invoice_id', function ($data) {
                        return $data->invoice_id ?? '----';
                    })
                    ->addColumn('invoice_date', function ($data) {
                        return formatDateByLocale(\Carbon\Carbon::parse($data->invoice_date)->format('d-m-Y')) ?? '----';
                    })
                    ->addColumn('supplier_name', function ($data) {
                        return $data->supplier->name ?? '----';
                    })
                    ->addColumn('total_quantity', function ($data) {
                        return convertToLocaleNumber($data->total_qnt) ?? '----';
                    })
                    ->addColumn('total_admin_amount', function ($data) {
                        return convertToLocaleNumber($data->admin_sub_total) . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('total_amount', function ($data) {
                        return convertToLocaleNumber($data->sub_total) . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('discount', function ($data) {
                        return convertToLocaleNumber($data->discount) . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('paid_amount', function ($data) {
                        return convertToLocaleNumber($data->paid_amount) . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('due_amount', function ($data) {
                        return convertToLocaleNumber($data->due_amount) . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('status', function ($data) {
                        return purchasestatus($data->status);
                    })
                    ->addColumn('purchase_by', function ($data) {
                        return $data->user->fname .' ' . $data->user->lname;
                    })
                    ->addColumn('purchase_type', function ($data) {
                        return purchase_type($data->purchase_type);
                    })
                    ->addColumn('note', function ($data) {
                        return '<button type="button" title="'.__f('Note Title').'" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#expenseListNoteView' . $data->id . '">
                                <i class="fa-solid fa-eye"></i>
                                </button>
                                <div class="modal fade" id="expenseListNoteView' . $data->id . '" tabindex="-1" aria-labelledby="expenseListNoteView' . $data->id . 'Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="expenseListNoteView' . $data->id . 'Label">'.__f('Note Modal Title').'</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    '.$data->note.'
                                </div>
                                </div>
                            </div>
                            </div>';
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '3') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>'. __f("Purchase Status Pending Title") .'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>'.__f('Purchase Status Ordered Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>'.__f('Purchase Status Partial Title').'</a></li>';
                        } else if ($data->status == '2') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Purchase Status Received Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>'.__f('Purchase Status Ordered Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>'.__f('Purchase Status Partial Title').'</a></li>';
                        } else if ($data->status == '1') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Purchase Status Received Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>'. __f("Purchase Status Pending Title") .'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>'.__f('Purchase Status Partial Title').'</a></li>';
                        } else {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Purchase Status Received Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>'. __f("Purchase Status Pending Title") .'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>'.__f('Purchase Status Ordered Title').'</a></li>';
                        }

                        // <li>
                        //                     <a class="dropdown-item align-items-center" href="' . route('admin.purchase.invoice.download', $data->id) . '">
                        //                         <i class="fa-solid fa-file-pdf me-2 text-warning"></i>'.__f('Genarate Invoice Title').'</a>
                        //                 </li>

                        return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('admin.purchase.show', ['purchase' => $data->id]) . '">
                                                <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('admin.purchase.edit', ['purchase' => $data->id]) . '">
                                                <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                        </li>
                                        ' . $statusAction . '
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                             <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                        </li>
                                    <form action="' . route('admin.purchase.delete', ['id' => $data->id]) . '"
                                            id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                            @csrf
                                            @method("DELETE")
                                    </form>
                                    </ul>
                                </div>';
                    })
                    ->rawColumns(['status', 'action','purchase_type','note'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }
}
