<?php

namespace Modules\ReturnProduct\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\App\Models\PurchaseInvoiceDetails;
use Modules\ReturnProduct\App\Http\Requests\ReturnProductRequest;
use Modules\ReturnProduct\App\Models\ReturnProduct;
use Modules\ReturnProduct\App\Models\ReturnProductDetail;
use Modules\ReturnProduct\App\Models\ReturnPurchaseProduct;
use Modules\ReturnProduct\App\Models\ReturnPurchaseProductDetails;
use Modules\SalesInvoice\App\Models\SalesInvoice;
use Modules\SalesInvoice\App\Models\SalesInvoiceDetails;
use Yajra\DataTables\Facades\DataTables;

class ReturnProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $this->setPageTitle(__f('Return Product List Title'));
            $data['activeParentReturnMenu'] = 'active';
            $data['activeReturnMenu']       = 'active';
            $data['showReturnMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Return Product List Title') => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Return Product List Title') => ''];
            }
            return view('returnproduct::index', $data);
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
            $this->setPageTitle(__f('Return Product Create Title'));
            $data['activeParentReturnMenu'] = 'active';
            $data['activeReturnCreateMenu'] = 'active';
            $data['showReturnMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Return Product Create Title') => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Return Product Create Title') => ''];
            }
            return view('returnproduct::create', $data);
        } else {
            abort(401);
        }
    }

    public function seacrhInvoice(Request $request){
        if (Gate::any(['isAdmin','isStaff'])) {
            if($request->ajax()){
                if ($request->ajax() &&  $request->invoiceno != null && $request->invoiceno != '') {
                    $salesInvoices = SalesInvoice::with(['customer'])->select('customer_phone','customer_name', 'invoice_id', 'customer_address','total_amount','due_amount')->where('invoice_id', 'LIKE', '%' . $request->invoiceno . '%')->get();
                    if ($salesInvoices->isEmpty()) {
                        return response()->json([
                            'status' => 'error',
                            'invoices' => '<div class="text-danger">'.__f("No invoice Found Title").'</div>'
                        ]);
                    }
                    $html = '<div class="showcustomerdata"><ul class="px-0">';
                    foreach ($salesInvoices as $invoices) {
                        $html .= '<li onclick="fillupcustomerdata(\'' . e($invoices->invoice_id) . '\', \'' . e($invoices->customer_name) . '\', \'' . e($invoices->customer_address) . '\',\'' . e($invoices->total_amount) . '\',\'' . e($invoices->due_amount) . '\',\'' . e($invoices->customer->id) . '\')">' . e($invoices->invoice_id) . '</li>';
                    }
                    $html .= '</ul></div>';

                    if ($salesInvoices) {
                        return response()->json([
                            'status' => 'success',
                            'invoices' => $html,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => __f("No invoice Found Title"),
                        ]);
                    }
                }
            }
        } else {
            abort(401);
        }
    }

    public function seacrhReturnProduct(Request $request){
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                if ($request->text !== null && $request->invoiceid !== null) {
                    $salesProduct = SalesInvoiceDetails::with(['product' => function($query) use ($request) {
                    }])
                    ->where('invoice_id', $request->invoiceid)
                    ->whereHas('product', function($query) use ($request) {
                        $query->where('name', 'LIKE', "%{$request->text}%");
                    })
                    ->get();
                    if (count($salesProduct) > 0) {
                        $html = '<ul class="px-0">';
                        foreach ($salesProduct as $product) {
                            $html .= '<li class="suggestion-item" data-product_qty="' . e($product->qty) . '" data-product_name="' . e($product->product->name) . '" data-batch_no="' . $product->batch_no . '" data-price="' . e($product->cost) . '" data-id="' . e($product->product->id) . '">' . e($product->product->name) . ' (' . $product->qty . ')' . '<span class="ms-2 badge bg-success">Batch ' . $product->batch_no . '</span>' . '</li>';
                        }
                        $html .= '</ul>';
                        return response()->json([
                            'status' => 'success',
                            'data'   => $html,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => __f("No product Found Title"),
                        ]);
                    }
                }
            }
        } else {
            abort(401);
        };
    }

    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = ReturnProduct::with(['customer'])->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $value = $request->search;
                                $query->where('invoice_id', 'like', "%{$value}%")
                                    ->orWhereHas('customer', function ($q) use ($value) {
                                        $q->where('customer_name', 'like', "%{$value}%")
                                          ->orWhere('phone', 'like', "%{$value}%");
                                    });
                            });
                        }
                    })
                    ->addColumn('invoice_no', function ($data) {
                        return '#'.$data->invoice_id ?? '-----';
                    })
                    ->addColumn('created_date', function ($data) {
                        return formatDateByLocale(\Carbon\Carbon::parse($data->create_date)->format('d-m-Y')) ?? '-----';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->customer->customer_name ?? '-----';
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->customer->phone ?? '-----';
                    })
                    ->addColumn('refunded_qty', function ($data) {
                        $refundedqty = ReturnProductDetail::where('invoice_id',$data->invoice_id)->sum('qty');
                        return convertToLocaleNumber($refundedqty) ??  '-----';
                    })
                    ->addColumn('refunded_amount', function ($data) {
                        return ($data->total_amount ? convertToLocaleNumber($data->total_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('paid_amount', function ($data) {
                        return ($data->paid_amount ? convertToLocaleNumber($data->paid_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('due_amount', function ($data) {
                        return ($data->due_amount ? convertToLocaleNumber($data->due_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('discount', function ($data) {
                        return ($data->discount ? convertToLocaleNumber($data->discount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('grand_refunded_amount', function ($data) {
                        $grandrefunedamount = $data->total_amount - $data->due_amount;
                        if($grandrefunedamount > 0){
                            return ($grandrefunedamount ? convertToLocaleNumber($grandrefunedamount) . ' ' . currency() : '-----');
                        }else{
                            return '-----';
                        }
                    })
                    ->addColumn('grand_pay_amount', function ($data) {
                        $grandpayamount = $data->total_amount - $data->due_amount;
                        if($grandpayamount < 0){
                            return ($grandpayamount ? convertToLocaleNumber($grandpayamount) . ' ' . currency() : '-----');
                        }else{
                            return '-----';
                        }
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
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if (Auth::check() && Auth::user()->role_id == 3) {
                    //         $statusAction = '';
                    //         if ($data->status == '0') {
                    //             $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.sale.status.change', ['id' => $data->id, 'status' => 1]) . '">
                    //                                 <i class="fa-solid fa-check me-2 text-success"></i>Complete</a>';
                    //         }


                    //         if ($data->status == '0') {
                    //             $edit = '<li><a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.edit', ['salesinvoice' => $data->id]) . '">
                    //                     <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a></li>';
                    //         } else {
                    //             $edit = '';
                    //         }

                    //         return '<div class="btn-group dropstart text-end">
                    //                     <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    //                         <i class="fa-solid fa-ellipsis-vertical"></i>
                    //                     </button>
                    //                     <ul class="dropdown-menu">
                    //                         <li>
                    //                             <a class="dropdown-item align-items-center"
                    //                             href="' . route('staff.invoice.download', $data->id) . '">
                    //                                 <i class="fa-solid fa-file-pdf me-2 text-info"></i>Genarate Invoice
                    //                             </a>
                    //                         </li>
                    //                         <li>
                    //                             <a class="dropdown-item align-items-center"
                    //                             href="' . route('staff.profile.index', ['phone' => $data->customer_phone]) . '">
                    //                                 <i class="fa-solid fa-user text-primary me-2"></i>Profile
                    //                             </a>
                    //                         </li>
                    //                         <li>
                    //                             <a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.show', ['salesinvoice' => $data->id]) . '">
                    //                                 <i class="fa-solid fa-eye me-2 text-info"></i>View</a>
                    //                         </li>
                    //                         ' . $edit . '
                    //                         <li>' . $statusAction . '</li>
                    //                         <li>
                    //                             <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                    //                                 <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                    //                         </li>
                    //                         <form action="' . route('staff.sale.delete', ['id' => $data->id]) . '"
                    //                             id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                    //                             @csrf
                    //                             @method("DELETE")
                    //                         </form>
                    //                     </ul>
                    //                 </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.returnproduct.status.change', ['id' => $data->id, 'status' => 1]) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Complete Status Title').'</a>';
                            }else if($data->status == '1'){
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.returnproduct.status.change', ['id' => $data->id, 'status' => 0]) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            // <li>
                            //                     <a class="dropdown-item align-items-center"
                            //                     href="' . route('admin.returnproduct.invoice.download', ['id' => $data->id]) . '">
                            //                         <i class="fa-solid fa-file-pdf me-2 text-info"></i>'. __f("Genarate Invoice Title") .'
                            //                     </a>
                            //                 </li>
                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.returnproduct.show', ['returnproduct' => $data->id]) . '">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('admin.returnproduct.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        }
                    })
                    ->rawColumns(['status', 'action', 'note','phone', 'name'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReturnProductRequest $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if($request->ajax()){
                $existingInvoice = ReturnProduct::where([
                    ['customer_id', '=', $request->customer_id],
                    ['create_date', '=', Carbon::today()->toDateString()]
                ])->first();

                if ($existingInvoice) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __f('Invoice already exists for this customer today. Please edit it Message')
                    ], 400);
                }

                $sub_total = 0;
                if ($request->productname != null && count($request->productname) > 0) {
                    foreach ($request->productname as $key => $product) {
                        $sub_total +=  $request->qty[$key] * $request->cost[$key];
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' =>  __f('Select At List One Product And Create Invoice Message'),
                    ], 400);
                }

                $salesDetails = SalesInvoice::where('invoice_id', $request->search_invoices_number)->first();
                $returnProduct = ReturnProduct::create([
                    'invoice_id'   => $salesDetails->invoice_id,
                    'customer_id'  => $request->customer_id,
                    'create_date'  => $request->create_date ?? Carbon::today()->toDateString(),
                    'total_amount' => $sub_total,
                    'due_amount'   => $salesDetails->due_amount,
                    'note'         => $request->note,
                    'paid_amount'  => $salesDetails->paid_amount,
                    'discount'     => $salesDetails->discount,
                    'status'       => '1',
                ]);


                if (count($request->productname) > 0) {
                    foreach ($request->productname as $key => $product) {
                        $sub_total = $request->qty[$key] * $request->cost[$key];
                        $getSalesDetails = SalesInvoiceDetails::where('product_id', $request->product_id[$key])->where('batch_no', $request->batch_no[$key])->first();
                        $purchasDetails = PurchaseInvoiceDetails::where('product_id',$request->product_id[$key])->where('batch_no', $request->batch_no[$key])->first();

                        if ($getSalesDetails) {
                            if ($request->product_id[$key] != null) {
                                $salesDetails->update([
                                    'total_amount' => $salesDetails->total_amount - $sub_total,
                                    'sub_total'    => $salesDetails->sub_total - $sub_total,
                                    'due_amount'   => $salesDetails->due_amount - $sub_total,
                                ]);
                                ReturnProductDetail::create([
                                    'create_date'       => $request->create_date ?? Carbon::today()->toDateString(),
                                    'invoice_id'        => $salesDetails->invoice_id,
                                    'product_id'        => $request->product_id[$key],
                                    'return_product_id' => $returnProduct->id,
                                    'customer_id'       => $request->customer_id,
                                    'cost'              => $request->cost[$key],
                                    'qty'               => $request->qty[$key],
                                    'batch_no'          => $request->batch_no[$key],
                                ]);
                                $adminPrice = $purchasDetails->admin_buy_price *  (int) $request->qty[$key];
                                $buyPrice = $purchasDetails->buy_price *  (int) $request->qty[$key];
                                $getadminprofilt = $sub_total - $adminPrice;
                                $getprofilt = $sub_total - $buyPrice;
                                $getSalesDetails->update([
                                    'qty'            => $getSalesDetails->qty - (int) $request->qty[$key],
                                    'orginal_profit' => $getSalesDetails->orginal_profit - $getadminprofilt,
                                    'profit'         => $getSalesDetails->profit - $getprofilt
                                ]);
                                $purchasDetails->update([
                                    'sales_qty'            => $purchasDetails->sales_qty - (int) $request->qty[$key],
                                ]);
                            }
                        }
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => __f("Return Create Success Message"),
                    'return_product_id'    => $returnProduct->id
                ]);
            }
        } else {
            abort(401);
        };
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $this->setPageTitle(__f("Return Product View Title"));
            $data['activeParentReturnMenu'] = 'active';
            $data['activeReturnMenu']       = 'active';
            $data['showReturnMenu']         = 'show';
            $data['returnProduct']          = ReturnProduct::with(['returnproductdetails','customer','salesinvoice'])->where('id',$id)->first();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Return Product View Title") => ''];
            } else {
                $data['breadcrumb']                = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Return Product View Title") => ''];
            }
            return view('returnproduct::show', $data);
        } else {
            abort(401);
        }
    }

    public function downloadPrint($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f("Return Product Invoice Print Title"));
            $data['activeParentSalesMenu']  = 'active';
            $data['activeSalesMenu']        = 'active';
            $data['showSalesMenu']          = 'show';
            $data['returnProduct']          = ReturnProduct::with(['returnproductdetails','customer','salesinvoice'])->where('id',$id)->first();
            return view('returnproduct::printinvoice', $data);
        } else {
            abort(403);
        }
    }



     // pdf download
     public function downloadInvoicePDF($id)
     {
         if (Gate::any(['isAdmin', 'isStaff'])) {
             $returnProduct  = ReturnProduct::with(['returnproductdetails','customer','salesinvoice'])->where('id',$id)->first();
             $title = 'Invoice #' . $returnProduct->invoice_id;
             $pdf = Pdf::loadView('returnproduct::downloadpdf', compact('returnProduct', 'title'))
                 ->setPaper('a4', 'portrait');
             return $pdf->download('invoice_' . $returnProduct->invoice_id . '.pdf');
         } else {
             abort(401);
         }
     }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('returnproduct::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getInvoice = ReturnProduct::find($id);
            if ($getInvoice) {
                $getallinvoices = ReturnProductDetail::where('invoice_id', $getInvoice->invoice_id)->get();
                if(count($getallinvoices) > 0){
                    foreach($getallinvoices as $invoice){
                        $salesDetails = SalesInvoice::where('invoice_id',$invoice->invoice_id)->first();
                        $getsales     = SalesInvoiceDetails::where('product_id',$invoice->product_id)->where('batch_no',$invoice->batch_no)->first();
                        $getpurchase  = PurchaseInvoiceDetails::where('product_id',$invoice->product_id)->where('batch_no',$invoice->batch_no)->first();
                        $sub_total       = $invoice->qty * $invoice->cost;
                        $adminPrice      = $getpurchase->admin_buy_price *  (int) $invoice->qty;
                        $buyPrice        = $getpurchase->buy_price *  (int) $invoice->qty;
                        $getadminprofilt = $sub_total - $adminPrice;
                        $getprofilt      = $sub_total - $buyPrice;
                        $salesDetails->update([
                            'total_amount' => $salesDetails->total_amount + $sub_total,
                            'sub_total'    => $salesDetails->sub_total + $sub_total,
                            'due_amount'   => $salesDetails->due_amount + $sub_total,
                        ]);

                        $salessuccess = $getsales->update([
                            'qty'            => $getsales->qty + $invoice->qty,
                            'orginal_profit' => $getsales->orginal_profit + $getadminprofilt,
                            'profit'         => $getsales->profit + $getprofilt
                        ]);

                        $success = $getpurchase->update([
                            'sales_qty' => $getpurchase->sales_qty + $invoice->qty,
                        ]);
                        if($salessuccess && $success){
                            $invoice->delete();
                        }
                    }
                    $getInvoice->delete();
                }
                return back()->with('success', __f("Return Delete Success Message"));
            } else {
                return back()->with('error', __f("Return Invoice not found Message"));
            }
        } else {
            abort(401);
        }
    }

    public function changeStatus($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $statuschange = ReturnProduct::where('id', $id)->first();
            $statuschange->update([
                'status' => $status,
            ]);
            return back()->with('success', __f("Return Status Change Message"));
        } else {
            abort(401);
        }
    }



    public function purchasReturncreate()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Return Purchase Product Create Title'));
            $data['activeParentReturnMenu']         = 'active';
            $data['activeReturnPurchaseCreateMenu'] = 'active';
            $data['showReturnMenu']                 = 'show';
            $data['breadcrumb']                     = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Return Purchase Product Create Title') => ''];
            return view('returnproduct::purchasecreate', $data);
        } else {
            abort(401);
        }
    }

    public function seacrhPurchaseInvoice(Request $request){
        if (Gate::allows('isAdmin')) {
            if($request->ajax()){
                if ($request->ajax() &&  $request->invoiceno != null && $request->invoiceno != '') {
                    $purchaseInvoices = Purchase::with(['supplier'])->select('invoice_id','admin_sub_total','due_amount','supplier_id')->where('invoice_id', 'LIKE', '%' . $request->invoiceno . '%')->get();
                    if ($purchaseInvoices->isEmpty()) {
                        return response()->json([
                            'status' => 'error',
                            'invoices' => '<div class="text-danger">'.__f("No invoice Found Title").'</div>'
                        ]);
                    }
                    $html = '<div class="showcustomerdata"><ul class="px-0">';
                    foreach ($purchaseInvoices as $invoices) {
                        $html .= '<li onclick="fillupcustomerdata(\'' . e($invoices->invoice_id) . '\', \'' . e($invoices->supplier->name) . '\', \'' . e($invoices->supplier->phone) . '\',\'' . e($invoices->admin_sub_total) . '\',\'' . e($invoices->due_amount) . '\',\'' . e($invoices->supplier->id) . '\')">' . e($invoices->invoice_id) . '</li>';
                    }
                    $html .= '</ul></div>';

                    if ($purchaseInvoices) {
                        return response()->json([
                            'status' => 'success',
                            'invoices' => $html,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => __f("No invoice Found Title"),
                        ]);
                    }
                }
            }
        } else {
            abort(401);
        }
    }

    public function seacrhReturnPurchase(Request $request){
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->text !== null && $request->invoiceid !== null) {
                    $salesProduct = PurchaseInvoiceDetails::with(['product' => function($query) use ($request) {
                    }])
                    ->where('invoice_id', $request->invoiceid)
                    ->whereHas('product', function($query) use ($request) {
                        $query->where('name', 'LIKE', "%{$request->text}%");
                    })
                    ->get();
                    if (count($salesProduct) > 0) {
                        $html = '<ul class="px-0">';
                        foreach ($salesProduct as $product) {
                            $avaibleQty = (int) $product->qty - (int) $product->sales_qty;
                            $html .= '<li class="suggestion-item" data-product_qty="' . e($avaibleQty) . '" data-product_name="' . e($product->product->name) . '" data-batch_no="' . $product->batch_no . '" data-price="' . e($product->admin_buy_price) . '" data-id="' . e($product->product->id) . '">' . e($product->product->name) . ' (' . $avaibleQty . ')' . '<span class="ms-2 badge bg-success">Batch ' . $product->batch_no . '</span>' . '</li>';
                        }
                        $html .= '</ul>';
                        return response()->json([
                            'status' => 'success',
                            'data'   => $html,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => __f("No product Found Title"),
                        ]);
                    }
                }
            }
        } else {
            abort(401);
        };
    }


    /**
     * Store a newly created resource in storage.
     */
    public function purchaseStore(ReturnProductRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if($request->ajax()){
                $existingInvoice = ReturnPurchaseProduct::where([
                    ['supplier_id', '=', $request->customer_id],
                    ['create_date', '=', Carbon::today()->toDateString()]
                ])->first();

                if ($existingInvoice) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __f('Invoice already exists for this customer today. Please edit it Message')
                    ], 400);
                }

                $sub_total = 0;
                if ($request->productname != null && count($request->productname) > 0) {
                    foreach ($request->productname as $key => $product) {
                        $sub_total +=  $request->qty[$key] * $request->cost[$key];
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' =>  __f('Select At List One Product And Create Invoice Message'),
                    ], 400);
                }

                $getPurchaseDetails = Purchase::where('invoice_id', $request->search_invoices_number)->first();
                $returnpurchaseProduct = ReturnPurchaseProduct::create([
                    'invoice_id'   => $getPurchaseDetails->invoice_id,
                    'supplier_id'  => $request->customer_id,
                    'create_date'  => $request->create_date ?? Carbon::today()->toDateString(),
                    'total_amount' => $sub_total,
                    'due_amount'   => $getPurchaseDetails->due_amount,
                    'note'         => $request->note,
                    'paid_amount'  => $getPurchaseDetails->paid_amount,
                    'discount'     => $getPurchaseDetails->discount,
                    'status'       => '1',
                ]);


                if (count($request->productname) > 0) {
                    foreach ($request->productname as $key => $product) {
                        $sub_total = $request->qty[$key] * $request->cost[$key];
                        $purchasDetails = PurchaseInvoiceDetails::where('product_id',$request->product_id[$key])->where('batch_no', $request->batch_no[$key])->first();

                        if ($purchasDetails) {
                            if ($request->product_id[$key] != null) {
                                ReturnPurchaseProductDetails::create([
                                    'create_date'        => $request->create_date ?? Carbon::today()->toDateString(),
                                    'invoice_id'         => $getPurchaseDetails->invoice_id,
                                    'product_id'         => $request->product_id[$key],
                                    'return_purchase_id' => $returnpurchaseProduct->id,
                                    'supplier_id'        => $request->customer_id,
                                    'cost'               => $request->cost[$key],
                                    'qty'                => $request->qty[$key],
                                    'batch_no'           => $request->batch_no[$key],
                                ]);


                                $adminPrice = $purchasDetails->admin_buy_price *  (int) $request->qty[$key];
                                $buyPrice = $purchasDetails->buy_price *  (int) $request->qty[$key];

                                $getPurchaseDetails->update([
                                    'admin_sub_total' => $getPurchaseDetails->admin_sub_total - $adminPrice,
                                    'sub_total'       => $getPurchaseDetails->sub_total - $buyPrice,
                                    'total_qnt'       => $getPurchaseDetails->total_qnt - (int) $request->qty[$key],
                                    'due_amount'      => $getPurchaseDetails->due_amount - $adminPrice,
                                ]);

                                $purchasDetails->update([
                                    'qty'             => $purchasDetails->qty - (int) $request->qty[$key],
                                    'admin_sub_total' => $purchasDetails->admin_sub_total - $adminPrice,
                                    'sub_total'       => $purchasDetails->sub_total - $buyPrice,
                                ]);
                            }
                        }
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => __f("Return Purchase Create Success Message"),
                    'return_purchase_id'    => $returnpurchaseProduct->id
                ]);
            }
        } else {
            abort(401);
        };
    }

    /**
     * Show the specified resource.
     */
    public function purchaseShow($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f("Return Purchase Product View Title"));
            $data['activeParentReturnMenu']   = 'active';
            $data['activePurchaseReturnMenu'] = 'active';
            $data['showReturnMenu']           = 'show';
            $data['returnPurchaseProduct']    = ReturnPurchaseProduct::with(['returnpurchasedetails','supplier','purchasinvoice'])->where('id',$id)->first();
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Return Purchase Product View Title") => ''];
            return view('returnproduct::purchaseshow', $data);
        } else {
            abort(401);
        }
    }

    public function returnPurchaseDownloadPrint($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f("Return Purchase Product Invoice Print Title"));
            $data['activeParentReturnMenu']   = 'active';
            $data['activePurchaseReturnMenu'] = 'active';
            $data['showReturnMenu']           = 'show';
            $data['returnPurchaseProduct']    = ReturnPurchaseProduct::with(['returnpurchasedetails','supplier','purchasinvoice'])->where('id',$id)->first();
            return view('returnproduct::purchaseprintinvoice', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function purchasReturnIndex()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Return Purchase Product List Title'));
            $data['activeParentReturnMenu']   = 'active';
            $data['activePurchaseReturnMenu'] = 'active';
            $data['showReturnMenu']           = 'show';
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Return Purchase Product List Title') => ''];
            return view('returnproduct::purchasindex', $data);
        } else {
            abort(401);
        }
    }

    public function PurchaseGetData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = ReturnPurchaseProduct::with(['supplier'])->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $value = $request->search;
                                $query->where('invoice_id', 'like', "%{$value}%")
                                    ->orWhereHas('supplier', function ($q) use ($value) {
                                        $q->where('name', 'like', "%{$value}%")
                                          ->orWhere('phone', 'like', "%{$value}%");
                                    });
                            });
                        }
                    })
                    ->addColumn('invoice_no', function ($data) {
                        return '#'.$data->invoice_id ?? '-----';
                    })
                    ->addColumn('created_date', function ($data) {
                        return formatDateByLocale(\Carbon\Carbon::parse($data->create_date)->format('d-m-Y')) ?? '-----';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->supplier->name ?? '-----';
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->supplier->phone ?? '-----';
                    })
                    ->addColumn('refunded_qty', function ($data) {
                        $refundedqty = ReturnPurchaseProductDetails::where('invoice_id',$data->invoice_id)->sum('qty');
                        return convertToLocaleNumber($refundedqty) ??  '-----';
                    })
                    ->addColumn('refunded_amount', function ($data) {
                        return ($data->total_amount ? convertToLocaleNumber($data->total_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('paid_amount', function ($data) {
                        return ($data->paid_amount ? convertToLocaleNumber($data->paid_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('due_amount', function ($data) {
                        return ($data->due_amount ? convertToLocaleNumber($data->due_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('discount', function ($data) {
                        return ($data->discount ? convertToLocaleNumber($data->discount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('grand_refunded_amount', function ($data) {
                        $grandrefunedamount = $data->total_amount - $data->due_amount;
                        if($grandrefunedamount > 0){
                            return ($grandrefunedamount ? convertToLocaleNumber($grandrefunedamount) . ' ' . currency() : '-----');
                        }else{
                            return '-----';
                        }
                    })
                    ->addColumn('grand_pay_amount', function ($data) {
                        $grandpayamount = $data->due_amount - $data->total_amount;
                        if($grandpayamount > 0){
                            return ($grandpayamount ? convertToLocaleNumber($grandpayamount) . ' ' . currency() : '-----');
                        }else{
                            return '-----';
                        }
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
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if (Auth::check() && Auth::user()->role_id == 3) {
                    //         $statusAction = '';
                    //         if ($data->status == '0') {
                    //             $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.sale.status.change', ['id' => $data->id, 'status' => 1]) . '">
                    //                                 <i class="fa-solid fa-check me-2 text-success"></i>Complete</a>';
                    //         }


                    //         if ($data->status == '0') {
                    //             $edit = '<li><a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.edit', ['salesinvoice' => $data->id]) . '">
                    //                     <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a></li>';
                    //         } else {
                    //             $edit = '';
                    //         }

                    //         return '<div class="btn-group dropstart text-end">
                    //                     <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    //                         <i class="fa-solid fa-ellipsis-vertical"></i>
                    //                     </button>
                    //                     <ul class="dropdown-menu">
                    //                         <li>
                    //                             <a class="dropdown-item align-items-center"
                    //                             href="' . route('staff.invoice.download', $data->id) . '">
                    //                                 <i class="fa-solid fa-file-pdf me-2 text-info"></i>Genarate Invoice
                    //                             </a>
                    //                         </li>
                    //                         <li>
                    //                             <a class="dropdown-item align-items-center"
                    //                             href="' . route('staff.profile.index', ['phone' => $data->customer_phone]) . '">
                    //                                 <i class="fa-solid fa-user text-primary me-2"></i>Profile
                    //                             </a>
                    //                         </li>
                    //                         <li>
                    //                             <a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.show', ['salesinvoice' => $data->id]) . '">
                    //                                 <i class="fa-solid fa-eye me-2 text-info"></i>View</a>
                    //                         </li>
                    //                         ' . $edit . '
                    //                         <li>' . $statusAction . '</li>
                    //                         <li>
                    //                             <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                    //                                 <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                    //                         </li>
                    //                         <form action="' . route('staff.sale.delete', ['id' => $data->id]) . '"
                    //                             id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                    //                             @csrf
                    //                             @method("DELETE")
                    //                         </form>
                    //                     </ul>
                    //                 </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.returnpurchase.status.change', ['id' => $data->id, 'status' => 1]) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Status Publish Title').'</a>';
                            }else if($data->status == '1'){
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.returnpurchase.status.change', ['id' => $data->id, 'status' => 0]) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            // <li>
                            //                     <a class="dropdown-item align-items-center"
                            //                     href="' . route('admin.returnpurchase.invoice.download', ['id' => $data->id]) . '">
                            //                         <i class="fa-solid fa-file-pdf me-2 text-info"></i>'. __f("Genarate Invoice Title") .'
                            //                     </a>
                            //                 </li>
                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.returnpurchase.show', ['id' => $data->id]) . '">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('admin.returnpurchase.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        }
                    })
                    ->rawColumns(['status', 'action', 'note','phone', 'name'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }


    
    public function returnpurchaseDelete($id)
    {
        if (Gate::allows('isAdmin')) {
            $getInvoice = ReturnPurchaseProduct::find($id);
            if ($getInvoice) {
                $getallinvoices = ReturnPurchaseProductDetails::where('invoice_id', $getInvoice->invoice_id)->get();
                if(count($getallinvoices) > 0){
                    foreach($getallinvoices as $invoice){
                        $purchaseDetails = Purchase::where('invoice_id',$invoice->invoice_id)->first();
                        $getpurchase     = PurchaseInvoiceDetails::where('product_id',$invoice->product_id)->where('batch_no',$invoice->batch_no)->first();
                        
                        $sub_total       = $invoice->qty * $invoice->cost;
                        $adminPrice      = $getpurchase->admin_buy_price *  (int) $invoice->qty;
                        $buyPrice        = $getpurchase->buy_price *  (int) $invoice->qty;

                        $purchasesuccess = $purchaseDetails->update([
                            'admin_sub_total' => $purchaseDetails->admin_sub_total + $adminPrice,
                            'sub_total'       => $purchaseDetails->sub_total + $buyPrice,
                            'total_qnt'       => $purchaseDetails->total_qnt + (int) $invoice->qty,
                            'due_amount'      => $purchaseDetails->due_amount + $adminPrice,
                        ]);

                        $getpurchasesuccess = $getpurchase->update([
                            'qty'             => $getpurchase->qty + (int) $invoice->qty,
                            'admin_sub_total' => $getpurchase->admin_sub_total + $adminPrice,
                            'sub_total'       => $getpurchase->sub_total + $buyPrice,
                        ]);
      
                        if($purchasesuccess && $getpurchasesuccess){
                            $invoice->delete();
                        }
                    }
                    $getInvoice->delete();
                }
                return back()->with('success', __f("Return Purchase Delete Success Message"));
            } else {
                return back()->with('error', __f("Return Invoice not found Message"));
            }
        } else {
            abort(401);
        }
    }

    public function returnPurchaseChangeStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $statuschange = ReturnPurchaseProduct::where('id', $id)->first();
            $statuschange->update([
                'status' => $status,
            ]);
            return back()->with('success', __f("Return Purchase Status Change Message"));
        } else {
            abort(401);
        }
    }

     // pdf download
     public function returnPurchasedownloadInvoicePDF($id)
     {
         if (Gate::any(['isAdmin', 'isStaff'])) {
             $returnPurchaseProduct  = ReturnPurchaseProduct::with(['returnpurchasedetails','supplier','purchasinvoice'])->where('id',$id)->first();;
             $title = 'Invoice #' . $returnPurchaseProduct->invoice_id;
             $pdf = Pdf::loadView('returnproduct::purchasedownloadpdf', compact('returnPurchaseProduct', 'title'))
                 ->setPaper('a4', 'portrait');
             return $pdf->download('invoice_' . $returnPurchaseProduct->invoice_id . '.pdf');
         } else {
             abort(401);
         }
     }
}
