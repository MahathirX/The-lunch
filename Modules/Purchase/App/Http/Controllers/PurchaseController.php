<?php

namespace Modules\Purchase\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PurchasInvoiceSentMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Modules\Product\App\Models\Product;
use Modules\Purchase\App\Http\Requests\PurchaseRequest;
use Modules\Purchase\App\Models\Purchase;
use Modules\Purchase\App\Models\PurchaseInvoiceDetails;
use Modules\Supplier\App\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Purchase Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseMenu']       = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Purchase Title') => ''];
            return view('purchase::index', $data);
        } else {
            abort(401);
        };
    }



    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = Purchase::with(['supplier'])->latest('id');
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
                        return '#'.$data->invoice_id ?? '----';
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
                    ->addColumn('status', function ($data) {
                        return purchasestatus($data->status);
                    })

                    ->addColumn('action', function ($data) {
                        if ($data->status == '3') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>'.__f('Purchase Status Pending Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>'. __f('Purchase Status Ordered Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>'. __f('Purchase Status Partial Title').'</a></li>';
                        } else if ($data->status == '2') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>'. __f('Purchase Status Received Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>'. __f('Purchase Status Ordered Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>'. __f('Purchase Status Partial Title').'</a></li>';
                        } else if ($data->status == '1') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>'. __f('Purchase Status Received Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>'.__f('Purchase Status Pending Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>'. __f('Purchase Status Partial Title').'</a></li>';
                        } else {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>'. __f('Purchase Status Received Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>'.__f('Purchase Status Pending Title').'</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('admin.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>'. __f('Purchase Status Ordered Title').'</a></li>';
                        }
                    //     <li>
                    //     <a class="dropdown-item align-items-center" href="' . route('admin.purchase.invoice.download', $data->id) . '">
                    //         <i class="fa-solid fa-file-pdf me-2 text-warning"></i>'. __f("Genarate Invoice Title") .'</a>
                    // </li>

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
                    ->rawColumns(['status', 'action', 'note','purchase_type'])
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
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Purchase Create Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseCreateMenu'] = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['products']                 = Product::where('status', '1')->get();
            $data['suppliers']                = Supplier::where('status', '1')->get();
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Purchase Create Title') => ''];
            return view('purchase::create', $data);
        } else {
            abort(401);
        };
    }

    //product search by name
    public function Search(Request $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            if ($request->ajax()) {
                if ($request->text !== null) {
                    $products = Product::where('name', 'LIKE', "%$request->text%")->where('status','1')->get();
                    if (count($products) > 0) {
                        $html = '<ul class="px-0">';
                        foreach ($products as $product) {
                            $html .= '<li class="suggestion-item" data-id="' . e($product->id) . '">' . e($product->name) . '</li>';
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
     * Show the form for creating a new resource.
     */
    public function searchProducts(Request $request)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            if ($request->text != null) {
                $searchText = $request->text;
                $products = Product::orWhere('name', 'LIKE', "%$searchText%")
                    ->orWhere('productcategory_id', 'LIKE', "%$searchText%")
                    ->orWhere('product_sku', 'LIKE', "%$searchText%")
                    ->orWhere('location', 'LIKE', "%$searchText%")
                    ->orWhere('featured', 'LIKE', "%$searchText%")
                    ->get();

                if (!empty($products)) {
                    $html = '<ul class="px-0">';
                    foreach ($products as $product) {
                        $html .= '<li>' . e($product->name) . '</li>';
                    }
                    $html .= '</ul>';
                    return response()->json([
                        'status' => 'success',
                        'data'   => $html,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                    ]);
                }
            }
        } else {
            abort(401);
        };
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->productname && (count($request->productname)) > 0) {
                    $admin_sub_total = 0;
                    $sub_total = 0;
                    $total_qnt = 0;

                    foreach ($request->productname as $key => $product) {
                        $admin_sub_total += $request->admin_buy_price[$key] * $request->qty[$key];
                        $sub_total += $request->buy_price[$key] * $request->qty[$key];
                        $total_qnt += $request->qty[$key];
                    }
                    if ($request->discount != null) {
                        $dueAmount = ($admin_sub_total - $request->paid_amount) - $request->discount;
                    } else {
                        $dueAmount = $admin_sub_total - $request->paid_amount;
                    }

                    $invoiceid       = rand(100000, 999999);
                    $purchase = Purchase::create([
                        'user_id'         => Auth::id(),
                        'supplier_id'     => $request->supplier_id,
                        'invoice_id'      => $invoiceid,
                        'invoice_date'    => $request->invoice_date,
                        'admin_sub_total' => $admin_sub_total,
                        'sub_total'       => $sub_total,
                        'total_qnt'       => $total_qnt,
                        'discount'        => $request->discount ?? 0,
                        'tax'             => $request->tax ?? 0,
                        'paid_amount'     => $request->paid_amount ?? 0,
                        'due_amount'      => $dueAmount,
                        'note'            => $request->note,
                        'purchase_type'   => $request->purchase_type,
                        'purchase_by'     => 'admin',
                        'status'          => $request->status,
                    ]);

                    foreach ($request->productname as $key => $product) {
                        $getDetails = PurchaseInvoiceDetails::where('product_id', $request->productids[$key])
                            ->orderBy('id', 'desc')
                            ->first();

                        PurchaseInvoiceDetails::create([
                            'purchase_id'     => $purchase->id,
                            'invoice_id'      => $invoiceid,
                            'product_id'      => $request->productids[$key],
                            'qty'             => $request->qty[$key],
                            'admin_buy_price' => $request->admin_buy_price[$key],
                            'buy_price'       => $request->buy_price[$key],
                            'admin_sub_total' => $request->admin_buy_price[$key] * $request->qty[$key],
                            'sub_total'       => $request->buy_price[$key] * $request->qty[$key],
                            'batch_no'        => $getDetails ? $getDetails->batch_no + 1 : 1,
                        ]);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => __f('Purchase Create Success Message'),
                    ]);
                }
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
            $this->setPageTitle(__f('Purchase Show Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseMenu']       = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['purchase']                 = Purchase::with(['supplier', 'purchaseinvoicedetails'])->find($id);
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Purchase Show Title') => ''];
            return view('purchase::show', $data);
        } else {
            abort(401);
        };
    }


    public function downloadPrint($id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $this->setPageTitle(__f('Print Invoice Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseMenu']       = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['purchase']                 = Purchase::with(['supplier', 'purchaseinvoicedetails'])->find($id);
            return view('purchase::printinvoice', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Purchase Edit Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseMenu']       = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['editinvoice']              = Purchase::with(['purchaseinvoicedetails'])->find($id);
            $data['products']                 = Product::where('status', '1')->get();
            $data['suppliers']                = Supplier::where('status', '1')->get();
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Purchase Edit Title') => ''];
            return view('purchase::edit', $data);
        } else {
            abort(401);
        };
    }

    /**
     * Update the specified resource in storage.
     */
    public function Update(PurchaseRequest $request, $id)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->productname && (count($request->productname)) > 0) {
                    $admin_sub_total = 0;
                    $sub_total = 0;
                    $total_qnt = 0;

                    foreach ($request->productname as $key => $product) {
                        $admin_sub_total += $request->admin_buy_price[$key] * $request->qty[$key];
                        $sub_total += $request->buy_price[$key] * $request->qty[$key];
                        $total_qnt += $request->qty[$key];
                    }

                    $grandTotal = $admin_sub_total - $request->discount ?? 0;
                    $getPurchase = Purchase::find($id);
                    if ($getPurchase->paid_amount != null) {
                        $due_amount = ($grandTotal - $request->paid_amount) - $getPurchase->paid_amount;
                        $paid_amount = $request->paid_amount + $getPurchase->paid_amount;
                    } else {
                        $due_amount = $grandTotal - $request->paid_amount;
                        $paid_amount = $request->paid_amount;
                    }
                    $getPurchase->update([
                        'supplier_id'     => $request->supplier_id,
                        'invoice_date'    => $request->invoice_date,
                        'admin_sub_total' => $admin_sub_total,
                        'sub_total'       => $sub_total,
                        'total_qnt'       => $total_qnt,
                        'discount'        => $request->discount ?? 0,
                        'tax'             => $request->tax ?? 0,
                        'paid_amount'     => $paid_amount ?? 0,
                        'due_amount'      => $due_amount,
                        'note'            => $request->note,
                        'purchase_type'   => $request->purchase_type,
                        'status'          => $request->status,
                    ]);

                    PurchaseInvoiceDetails::where('purchase_id', $getPurchase->id)->delete();
                    foreach ($request->productname as $key => $product) {
                        $getDetails = PurchaseInvoiceDetails::where('product_id', $request->productids[$key])
                            ->orderBy('id', 'desc')
                            ->first();
                        PurchaseInvoiceDetails::create([
                            'purchase_id'     => $getPurchase->id,
                            'invoice_id'      => $getPurchase->invoice_id,
                            'product_id'      => $request->productids[$key],
                            'qty'             => $request->qty[$key],
                            'admin_buy_price' => $request->admin_buy_price[$key],
                            'buy_price'       => $request->buy_price[$key],
                            'admin_sub_total' => $request->admin_buy_price[$key] * $request->qty[$key],
                            'sub_total'       => $request->buy_price[$key] * $request->qty[$key],
                            'batch_no'        => $getDetails ? $getDetails->batch_no + 1 : 1,
                        ]);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => __f('Purchase Update Success Message'),
                    ]);
                }
            }
        } else {
            abort(401);
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Gate::allows('isAdmin')) {
            $getInvoice = Purchase::find($id);
            if (!$getInvoice) {
                return back()->with('error', 'Invoice not found.');
            }
            PurchaseInvoiceDetails::where('purchase_id', $getInvoice->id)->delete();
            $getInvoice->delete();
            return back()->with('success', __f('Purchase Delete Success Message'));
        } else {
            abort(401);
        }
    }

    public function changeStatus($id, $status)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $statuschange = Purchase::where('id', $id)->first();
            $statuschange->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Purchase Status Change Message'));
        } else {
            abort(401);
        }
    }

    public function downloadMailSend($id)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $data['purchase']    = Purchase::with(['supplier', 'purchaseinvoicedetails'])->find($id);
            if($data['purchase']->supplier->email != null){
                Mail::to($data['purchase']->supplier->email)->send(new PurchasInvoiceSentMail($data));
            }
            return back()->with('success', 'Email sent successfully !');
        } else {
            abort(401);
        }
    }

    public function downloadInvoicePDF($invoiceId)
    {
        if (Gate::any(['isAdmin','isStaff'])) {
            $purchase = Purchase::with(['supplier', 'purchaseinvoicedetails'])->find($invoiceId);
            $title = 'Invoice #' . $purchase->invoice_id;
            $pdf = Pdf::loadView('purchase::downloadpdf', compact('purchase', 'title'))
                ->setPaper('a4', 'portrait');
            return $pdf->download('invoice_' . $purchase->invoice_id . '.pdf');
        } else {
            abort(401);
        }
    }

     /**
     * Display a listing of the resource.
     */
    public function staffIndex()
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle(__f('Purchase List Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseMenu']       = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['breadcrumb']               = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Purchase List Title') => ''];
            return view('purchase::staffindex', $data);
        } else {
            abort(401);
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function staffCreate()
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle(__f('Purchase Create Title'));
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseCreateMenu'] = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['products']                 = Product::where('status', '1')->get();
            $data['suppliers']                = Supplier::where('status', '1')->get();
            $data['breadcrumb']               = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Purchase Create Title') => ''];
            return view('purchase::staffcreate', $data);
        } else {
            abort(401);
        };
    }

     /**
     * Store a newly created resource in storage.
     */
    public function staffStore(PurchaseRequest $request)
    {
        if (Gate::allows('isStaff')) {
            if ($request->ajax()) {
                if ($request->productname && (count($request->productname)) > 0) {
                    $admin_sub_total = 0;
                    $sub_total = 0;
                    $total_qnt = 0;

                    foreach ($request->productname as $key => $product) {
                        $admin_sub_total += $request->admin_buy_price[$key] * $request->qty[$key];
                        $sub_total += $request->admin_buy_price[$key] * $request->qty[$key];
                        $total_qnt += $request->qty[$key];
                    }
                    if ($request->discount != null) {
                        $dueAmount = ($admin_sub_total - $request->paid_amount) - $request->discount;
                    } else {
                        $dueAmount = $admin_sub_total - $request->paid_amount;
                    }

                    $invoiceid       = rand(100000, 999999);
                    $purchase = Purchase::create([
                        'user_id'         => Auth::id(),
                        'supplier_id'     => $request->supplier_id,
                        'invoice_id'      => $invoiceid,
                        'invoice_date'    => $request->invoice_date,
                        'admin_sub_total' => $admin_sub_total,
                        'sub_total'       => $sub_total,
                        'total_qnt'       => $total_qnt,
                        'discount'        => $request->discount ?? 0,
                        'tax'             => $request->tax ?? 0,
                        'paid_amount'     => $request->paid_amount ?? 0,
                        'due_amount'      => $dueAmount,
                        'note'            => $request->note,
                        'purchase_type'   => $request->purchase_type,
                        'purchase_by'     => 'staff',
                        'status'          => $request->status,
                    ]);

                    foreach ($request->productname as $key => $product) {
                        $getDetails = PurchaseInvoiceDetails::where('product_id', $request->productids[$key])
                            ->orderBy('id', 'desc')
                            ->first();

                        PurchaseInvoiceDetails::create([
                            'purchase_id'     => $purchase->id,
                            'invoice_id'      => $invoiceid,
                            'product_id'      => $request->productids[$key],
                            'qty'             => $request->qty[$key],
                            'admin_buy_price' => $request->admin_buy_price[$key],
                            'buy_price'       => $request->admin_buy_price[$key],
                            'admin_sub_total' => $request->admin_buy_price[$key] * $request->qty[$key],
                            'sub_total'       => $request->admin_buy_price[$key] * $request->qty[$key],
                            'batch_no'        => $getDetails ? $getDetails->batch_no + 1 : 1,
                        ]);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => __f('Purchase Create Success Message'),
                    ]);
                }
            }
        } else {
            abort(401);
        };
    }

    public function staffGetData(Request $request)
    {
        if (Gate::allows('isStaff')) {
            if ($request->ajax()) {
                $getData = Purchase::with(['supplier'])->where('user_id',Auth::id())->latest('id');
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
                        return $data->invoice_date ?? '----';
                    })
                    ->addColumn('supplier_name', function ($data) {
                        return $data->supplier->name ?? '----';
                    })
                    ->addColumn('total_quantity', function ($data) {
                        return $data->total_qnt ?? '----';
                    })
                    ->addColumn('total_admin_amount', function ($data) {
                        return $data->admin_sub_total . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('total_amount', function ($data) {
                        return $data->sub_total . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('discount', function ($data) {
                        return $data->discount . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('paid_amount', function ($data) {
                        return $data->paid_amount . ' ' . config('settings.currency') ?? '----';
                    })
                    ->addColumn('due_amount', function ($data) {
                        return $data->due_amount . ' ' . config('settings.currency') ?? '----';
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
                    ->addColumn('status', function ($data) {
                        return purchasestatus($data->status);
                    })

                    ->addColumn('action', function ($data) {
                        if ($data->status == '3') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>Pending</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>Ordered</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>Partial</a></li>';
                        } else if ($data->status == '2') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>Received</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>Ordered</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>Partial</a></li>';
                        } else if ($data->status == '1') {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>Received</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>Pending</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                <i class="fa-solid fa-handshake me-2 text-warning"></i>Partial</a></li>';
                        } else {
                            $statusAction = '<li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '3']) . '">
                                <i class="fa-solid fa-check me-2 text-success"></i>Received</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '2']) . '">
                                <i class="fa-solid fa-hourglass-half text-danger me-2"></i>Pending</a></li>
                                <li><a class="dropdown-item align-items-center" href="' . route('staff.purchase.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                <i class="fa-solid fa-truck-fast text-info me-2"></i>Ordered</a></li>';
                        }

                    //     <li>
                    //     <a class="dropdown-item align-items-center" href="' . route('staff.purchase.invoice.download', $data->id) . '">
                    //         <i class="fa-solid fa-file-pdf me-2 text-warning"></i>'. __f("Genarate Invoice Title") .'</a>
                    // </li>
                        return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('staff.purchase.show', ['purchase' => $data->id]) . '">
                                                <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                        </li>

                                    <form action="' . route('staff.purchase.delete', ['id' => $data->id]) . '"
                                            id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                            @csrf
                                            @method("DELETE")
                                    </form>
                                    </ul>
                                </div>';

                            //     <li>
                            //     <a class="dropdown-item align-items-center" href="' . route('staff.purchase.edit', ['purchase' => $data->id]) . '">
                            //         <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a>
                            // </li>
                            // ' . $statusAction . '
                            // <li>
                            //     <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                            //      <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                            // </li>
                    })
                    ->rawColumns(['status', 'action', 'note','purchase_type'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function staffEdit($id)
    {
        if (Gate::allows('isStaff')) {
            $this->setPageTitle('Purchase Edit');
            $data['activeParentPurchaseMenu'] = 'active';
            $data['activePurchaseMenu']       = 'active';
            $data['showPurchaseMenu']         = 'show';
            $data['editinvoice']              = Purchase::with(['purchaseinvoicedetails'])->find($id);
            $data['products']                 = Product::where('status', '1')->get();
            $data['suppliers']                = Supplier::where('status', '1')->get();
            $data['breadcrumb']               = ['Staff Dashboard' => route('staff.dashboard.index'), 'Purchase Edit' => ''];
            return view('purchase::staffedit', $data);
        } else {
            abort(401);
        };
    }

    /**
     * Update the specified resource in storage.
     */
    public function staffUpdate(PurchaseRequest $request, $id)
    {
        if (Gate::allows('isStaff')) {
            if ($request->ajax()) {
                if ($request->productname && (count($request->productname)) > 0) {
                    $admin_sub_total = 0;
                    $sub_total = 0;
                    $total_qnt = 0;

                    foreach ($request->productname as $key => $product) {
                        $admin_sub_total += $request->admin_buy_price[$key] * $request->qty[$key];
                        $sub_total += $request->admin_buy_price[$key] * $request->qty[$key];
                        $total_qnt += $request->qty[$key];
                    }

                    $grandTotal = $admin_sub_total - $request->discount ?? 0;
                    $getPurchase = Purchase::find($id);
                    if ($getPurchase->paid_amount != null) {
                        $due_amount = ($grandTotal - $request->paid_amount) - $getPurchase->paid_amount;
                        $paid_amount = $request->paid_amount + $getPurchase->paid_amount;
                    } else {
                        $due_amount = $grandTotal - $request->paid_amount;
                        $paid_amount = $request->paid_amount;
                    }
                    $getPurchase->update([
                        'supplier_id'     => $request->supplier_id,
                        'invoice_date'    => $request->invoice_date,
                        'admin_sub_total' => $admin_sub_total,
                        'sub_total'       => $sub_total,
                        'total_qnt'       => $total_qnt,
                        'discount'        => $request->discount ?? 0,
                        'tax'             => $request->tax ?? 0,
                        'paid_amount'     => $paid_amount ?? 0,
                        'due_amount'      => $due_amount,
                        'note'            => $request->note,
                        'purchase_type'   => $request->purchase_type,
                        'status'          => $request->status,
                    ]);

                    PurchaseInvoiceDetails::where('purchase_id', $getPurchase->id)->delete();
                    foreach ($request->productname as $key => $product) {
                        $getDetails = PurchaseInvoiceDetails::where('product_id', $request->productids[$key])
                            ->orderBy('id', 'desc')
                            ->first();
                        PurchaseInvoiceDetails::create([
                            'purchase_id'     => $getPurchase->id,
                            'invoice_id'      => $getPurchase->invoice_id,
                            'product_id'      => $request->productids[$key],
                            'qty'             => $request->qty[$key],
                            'admin_buy_price' => $request->admin_buy_price[$key],
                            'buy_price'       => $request->admin_buy_price[$key],
                            'admin_sub_total' => $request->admin_buy_price[$key] * $request->qty[$key],
                            'sub_total'       => $request->admin_buy_price[$key] * $request->qty[$key],
                            'batch_no'        => $getDetails ? $getDetails->batch_no + 1 : 1,
                        ]);
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Purchase product update successfully',
                    ]);
                }
            }
        } else {
            abort(401);
        };
    }
}
