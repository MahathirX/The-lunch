<?php

namespace Modules\SalesInvoice\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Customer\App\Models\Customer;
use Modules\Product\App\Models\Product;
use Modules\Purchase\App\Models\PurchaseInvoiceDetails;
use Modules\SalesInvoice\App\Http\Requests\SalesInvoiceRequest;
use Modules\SalesInvoice\App\Models\CustomerDueAmoutPaid;
use Modules\SalesInvoice\App\Models\SalesInvoice;
use Modules\SalesInvoice\App\Models\SalesInvoiceDetails;
use Yajra\DataTables\Facades\DataTables;

class SalesInvoiceController extends Controller
{
    // search customer by phone
    function customerSearch(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $customers = Customer::select('customer_name', 'phone', 'address')->where('phone', 'LIKE', '%' . $request->phone . '%')->get();
                if ($customers->isEmpty()) {
                    return response()->json(['html' => '<div>'.__f("No customers found Title").'</div>']);
                }

                $html = '<div class="showcustomerdata"><ul class="px-0">';
                foreach ($customers as $customer) {
                    $html .= '<li onclick="fillupcustomerdata(\'' . e($customer->phone) . '\', \'' . e($customer->customer_name) . '\', \'' . e($customer->address) . '\')">' . e($customer->phone) . '</li>';
                }
                $html .= '</ul></div>';
                if ($customer) {
                    return response()->json([
                        'status' => 'success',
                        'customars' => $html,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => __f("No customers found Title"),
                    ]);
                }
            }
        } else {
            abort(401);
        };
    }

    //product name search
    public function Search(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                if ($request->text !== null) {
                    $products = Product::with(['purchaseproduct'])->has('purchaseproduct')->where('name', 'LIKE', "%{$request->text}%")->get();
                    // dd($products);
                    if (count($products) > 0) {
                        $html = '<ul class="px-0">';
                        foreach ($products as $product) {
                            if ($product->discount_price != null) {
                                $productPrice =  $product->discount_price;
                            } else {
                                $productPrice =  $product->price;
                            }
                            $getPurchasProducts = PurchaseInvoiceDetails::whereIn('product_id', [$product->id])->get();
                            if (count($getPurchasProducts) > 0) {
                                foreach ($getPurchasProducts as $purchasProduct) {
                                    $purchasAvaiableQty = (int) $purchasProduct->qty - (int) $purchasProduct->sales_qty;
                                    if ($purchasAvaiableQty !== 0) {
                                        $html .= '<li class="suggestion-item" data-product_qty="' . e($purchasAvaiableQty) . '" data-product_name="' . e($product->name) . '" data-batch_no="' . $purchasProduct->batch_no . '" data-price="' . e($productPrice) . '" data-id="' . e($product->id) . '">' . e($product->name) . ' (' . $purchasAvaiableQty . ')' . '<span class="ms-2 badge bg-success">'.__f("Batch No Title").' ' . $purchasProduct->batch_no . '</span>' . '</li>';
                                    }
                                }
                            }
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
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f("Sales Invoice Title"));
            $data['activeParentSalesMenu']  = 'active';
            $data['activeSalesMenu']        = 'active';
            $data['showSalesMenu']          = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']             = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Sales Invoice Title") => ''];
            } else {
                $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Sales Invoice Title") => ''];
            }
            return view('salesinvoice::index', $data);
        } else {
            abort(401);
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f("Invoice Create Title"));
            $data['activeParentSalesMenu'] = 'active';
            $data['showSalesMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']         = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Sales Invoice Title") => route('staff.salesinvoice.index'), __f("Invoice Create Title") => ''];
                $data['activeSalessCreateMenu'] = 'active';
            } else {
                $data['breadcrumb']         = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Sales Invoice Title") => route('admin.salesinvoice.index'), __f("Invoice Create Title") => ''];
                $data['activeSalesCreateMenu'] = 'active';
            }
            return view('salesinvoice::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // SalesInvoiceRequest
    public function store(SalesInvoiceRequest $request)
    {
        if ($request->ajax()) {
            if ($request->file('attachment')) {
                $uploadAttachment = $this->imageUpload($request->file('attachment'), 'invoice/attachments/', null, null);
            } else {
                $uploadAttachment = null;
            }

            $existingInvoice = SalesInvoice::where([
                ['customer_name', '=', $request->customer_phone],
                ['create_date', '=', Carbon::today()->toDateString()]
            ])->first();

            if ($existingInvoice) {
                return response()->json([
                    'status' => 'error',
                    'message' =>  __f('Invoice already exists for this customer today. Please edit it Message')
                ], 400);
            }

            $customer = Customer::where([
                'customer_name' => $request->customer_name,
                'phone' => $request->customer_phone
            ])->first();

            if (!$customer) {
                $customer = Customer::create([
                    'customer_name' => $request->customer_name,
                    'phone'         => $request->customer_phone,
                    'address'       => $request->customer_address,
                    'photo'         => null,
                ]);
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
            $grand_total = $sub_total -  $request->discount;
            $due_amount =  $grand_total -  $request->paid_amount;

            $invoice_id = rand(10000, 99999999);
            $sales = SalesInvoice::create([
                'invoice_id'       => $invoice_id,
                'customer_name'    => $request->customer_name,
                'terms'            => $request->terms ?? 0,
                'total_amount'     => $grand_total,
                'sub_total'        => $sub_total,
                'note'             => $request->note,
                'attachment'       => $uploadAttachment,
                'customer_phone'   => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'create_date'      => $request->create_date ?? Carbon::today()->toDateString(),
                'due_date'         => $request->due_date,
                'tax'              => 0,
                'paid_amount'      => $request->paid_amount,
                'discount'         => $request->discount,
                'due_amount'       => $due_amount,
                'status'           => $request->status,
            ]);


            if (count($request->productname) > 0) {
                foreach ($request->productname as $key => $product) {
                    $sub_total = $request->qty[$key] * $request->cost[$key];
                    $getPurchasDetails = PurchaseInvoiceDetails::where('product_id', $request->product_id[$key])->where('batch_no', $request->batch_no[$key])->first();
                    if ($getPurchasDetails) {
                        $adminBuyingPrince = $getPurchasDetails->admin_buy_price * (int) $request->qty[$key];
                        $buyingPrince = $getPurchasDetails->buy_price * (int) $request->qty[$key];
                        $selesPrice = (int) $request->cost[$key] * (int) $request->qty[$key];
                        if ($request->product_id[$key] != null) {
                            SalesInvoiceDetails::create([
                                'create_date'    => $request->create_date ?? Carbon::today()->toDateString(),
                                'invoice_id'     => $invoice_id,
                                'product_id'     => $request->product_id[$key],
                                'sales_id'       => $sales->id,
                                'cost'           => $request->cost[$key],
                                'qty'            => $request->qty[$key],
                                'orginal_profit' => $selesPrice -  $adminBuyingPrince,
                                'profit'         => $selesPrice -  $buyingPrince,
                                'batch_no'       => $request->batch_no[$key],
                            ]);
                            $getPurchasDetails->update([
                                'sales_qty' => $getPurchasDetails->sales_qty + (int) $request->qty[$key],
                            ]);
                        }
                    }
                }
            }
            return response()->json([
                'status' => "success",
                'message' =>  __f('Sales Create Success Message'),
                'sales_id'    => $sales->id
            ]);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = SalesInvoice::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('customer_name', 'like', "%{$value}%")
                                    ->orWhere('customer_phone', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('invoice_no', function ($data) {
                        return '#'.$data->invoice_id ?? '-----';
                    })
                    ->addColumn('phone', function ($data) {
                        return '<a href="'.route('staff.profile.index', ['phone' => $data->customer_phone]).'">'.$data->customer_phone ?? '-----'.'</a>';
                    })
                    ->addColumn('name', function ($data) {
                        return '<a href="'.route('staff.profile.index', ['phone' => $data->customer_phone]).'">'.$data->customer_name ?? '-----'.'</a>';
                    })
                    ->addColumn('sub_total', function ($data) {
                        return ($data->sub_total ? convertToLocaleNumber($data->sub_total) . ' ' . currency() : '-----');
                    })
                    ->addColumn('discount', function ($data) {
                        return ($data->discount ? convertToLocaleNumber($data->discount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('total_amount', function ($data) {
                        return ($data->total_amount ? convertToLocaleNumber($data->total_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('paid_amount', function ($data) {
                        return ($data->paid_amount ? convertToLocaleNumber($data->paid_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('due_amount', function ($data) {
                        return ($data->due_amount ? convertToLocaleNumber($data->due_amount) . ' ' . currency() : '-----');
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
                        return invoiceStatus($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if (Auth::check() && Auth::user()->role_id == 3) {
                            $statusAction = '';
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.sale.status.change', ['id' => $data->id, 'status' => 1]) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Complete Status Title').'</a>';
                            }


                            if ($data->status == '0') {
                                $edit = '<li><a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.edit', ['salesinvoice' => $data->id]) . '">
                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a></li>';
                            } else {
                                $edit = '';
                            }

                            // <li>
                            //                     <a class="dropdown-item align-items-center"
                            //                     href="' . route('staff.salesinvoice.invoice.download', $data->id) . '">
                            //                         <i class="fa-solid fa-file-pdf me-2 text-info"></i>'. __f("Genarate Invoice Title") .'
                            //                     </a>
                            //                 </li>

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="' . route('staff.profile.index', ['phone' => $data->customer_phone]) . '">
                                                    <i class="fa-solid fa-user text-primary me-2"></i>'. __f("Profile Title") .'
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.show', ['salesinvoice' => $data->id]) . '">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                            </li>
                                            ' . $edit . '
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('staff.sale.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.sale.status.change', ['id' => $data->id, 'status' => 1]) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Complete Status Title').'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.sale.status.change', ['id' => $data->id, 'status' => 0]) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }


                            if ($data->status == '0') {
                                $edit = '<li><a class="dropdown-item align-items-center" href="' . route('admin.salesinvoice.edit', ['salesinvoice' => $data->id]) . '">
                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a></li>';
                            } else {
                                $edit = '';
                            }

                            // <li>
                            //                     <a class="dropdown-item align-items-center"
                            //                     href="' . route('admin.salesinvoice.invoice.download', $data->id) . '">
                            //                         <i class="fa-solid fa-file-pdf me-2 text-info"></i>'. __f("Genarate Invoice Title") .'
                            //                     </a>
                            //                 </li>

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="' . route('admin.profile.index', ['phone' => $data->customer_phone]) . '">
                                                    <i class="fa-solid fa-user text-primary me-2"></i>'. __f("Profile Title") .'
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.salesinvoice.show', ['salesinvoice' => $data->id]) . '">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                            </li>
                                            ' . $edit . '
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('admin.sale.delete', ['id' => $data->id]) . '"
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Sales Invoice Edit Title'));
            $data['activeParentSalesMenu'] = 'active';
            $data['activeSalesMenu']       = 'active';
            $data['showSalesMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']            = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Sales Invoice Title") => route('staff.salesinvoice.index'), __f('Sales Invoice Edit Title') => ''];
            } else {
                $data['breadcrumb']            = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Sales Invoice Title") => route('admin.salesinvoice.index'), __f('Sales Invoice Edit Title') => ''];
            }
            $data['salesinvoice']          = SalesInvoice::where('id', $id)->first();
            $data['customer']              = Customer::where('phone', $data['salesinvoice']['customer_phone'])->first();
            $data['invoiceDetails']        = SalesInvoiceDetails::with(['product'])->where('invoice_id', $data['salesinvoice']['invoice_id'])->get();
            return view('salesinvoice::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Sales Invoice View Title'));
            $data['activeParentSalesMenu']  = 'active';
            $data['activeSalesMenu']        = 'active';
            $data['showSalesMenu']          = 'show';
            $data['invoice']               = SalesInvoice::findOrFail($id);
            $data['invoiceDetails']        = SalesInvoiceDetails::with(['product'])->where('invoice_id', $data['invoice']['invoice_id'])->get();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']            = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Sales Invoice Title") => route('staff.salesinvoice.index'), __f('Sales Invoice View Title') => ''];
            } else {
                $data['breadcrumb']            = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Sales Invoice Title") => route('admin.salesinvoice.index'), __f('Sales Invoice View Title') => ''];
            }
            return view('salesinvoice::show', $data);
        } else {
            abort(403);
        }
    }

    /**
     * Show the specified resource.
     */
    public function downloadPrint($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Sale Invoice Print Title'));
            $data['activeParentSalesMenu']  = 'active';
            $data['activeSalesMenu']        = 'active';
            $data['showSalesMenu']          = 'show';
            $data['invoice']               = SalesInvoice::findOrFail($id);
            $data['invoiceDetails']        = SalesInvoiceDetails::with(['product'])->where('invoice_id', $data['invoice']['invoice_id'])->get();
            return view('salesinvoice::printinvoice', $data);
        } else {
            abort(403);
        }
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getcustomer = Customer::where('phone', $request->customer_phone)->first();
                if ($getcustomer) {
                    $getcustomer->update([
                        'customer_name' => $request->customer_name,
                        'phone'         => $request->customer_phone,
                        'address'       => $request->customer_address,
                    ]);
                }

                //attachment update area
                $invoice = SalesInvoice::where('id', $id)->first();
                if ($request->file('attachment')) {
                    $this->imageDelete($invoice->attachment);
                    $uploadAttachment = $this->imageUpload($request->file('attachment'), 'invoice/attachments/', null, null);
                } else {
                    $uploadAttachment = $invoice->attachment ?? null;
                }


                $sub_total = 0;
                if ($request->productname != null && count($request->productname) > 0) {
                    foreach ($request->productname as $key => $product) {
                        $sub_total +=  $request->qty[$key] * $request->cost[$key];
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => __f('Select At List One Product And Create Invoice Message'),
                    ], 400);
                }
                $grand_total = $sub_total - $request->discount;

                if ($invoice->paid_amount != null) {
                    $due_amount = ($grand_total - $request->paid_amount) - $invoice->paid_amount;
                    $paid_amount = $request->paid_amount + $invoice->paid_amount;
                } else {
                    $due_amount = $grand_total - $request->paid_amount;
                    $paid_amount = $request->paid_amount;
                }

                DB::transaction(function () use ($request, $grand_total, $sub_total, $uploadAttachment, $paid_amount, $due_amount) {
                    // Remove old sales details first
                    $getDetails = SalesInvoiceDetails::where('invoice_id', $request->invoice_id)->get();
                    if ($getDetails->isNotEmpty()) {
                        foreach ($getDetails as $details) {
                            $getPurchasDetails = PurchaseInvoiceDetails::where('product_id', $details->product_id)
                                ->where('batch_no', $details->batch_no)
                                ->first();
                            if ($getPurchasDetails) {
                                $getPurchasDetails->update([
                                    'sales_qty' => $getPurchasDetails->sales_qty - $details->qty,
                                ]);
                            }
                        }
                    }
                    $details = SalesInvoiceDetails::where('invoice_id', $request->invoice_id)->delete();
                    // Update or create the sales invoice
                    $sales = SalesInvoice::updateOrCreate(
                        ['invoice_id' => $request->invoice_id],
                        [
                            'invoice_id'       => $request->invoice_id,
                            'customer_name'    => $request->customer_name,
                            'terms'            => $request->terms ?? 0,
                            'total_amount'     => $grand_total,
                            'sub_total'        => $sub_total,
                            'note'             => $request->note,
                            'attachment'       => $uploadAttachment,
                            'customer_phone'   => $request->customer_phone,
                            'customer_address' => $request->customer_address,
                            'create_date'      => $request->create_date,
                            'due_date'         => $request->due_date,
                            'tax'              => $request->tax,
                            'paid_amount'      => $paid_amount,
                            'discount'         => $request->discount,
                            'due_amount'       => $due_amount,
                            'status'           => $request->status,
                        ]
                    );

                    // Insert new sales invoice details
                    if ($details) {
                        if ($request->has('productname') && count($request->productname) > 0) {
                            foreach ($request->productname as $key => $product) {
                                $sub_total = $request->qty[$key] * $request->cost[$key];
                                $getPurchasDetails = PurchaseInvoiceDetails::where('product_id', $request->product_id[$key])
                                    ->where('batch_no', $request->batch_no[$key])
                                    ->first();
                                if ($getPurchasDetails) {
                                    $adminBuyingPrince = $getPurchasDetails->admin_buy_price * (int) $request->qty[$key];
                                    $buyingPrince = $getPurchasDetails->buy_price * (int) $request->qty[$key];
                                    $selesPrice = (int) $request->cost[$key] * (int) $request->qty[$key];
                                    if ($request->product_id[$key] != null) {
                                        SalesInvoiceDetails::create([
                                            'create_date'    => $request->create_date ?? Carbon::today()->toDateString(),
                                            'invoice_id'     => $request->invoice_id,
                                            'product_id'     => $request->product_id[$key],
                                            'sales_id'       => $sales->id,
                                            'cost'           => $request->cost[$key],
                                            'qty'            => $request->qty[$key],
                                            'orginal_profit' => $selesPrice -  $adminBuyingPrince,
                                            'profit'         => $selesPrice -  $buyingPrince,
                                            'batch_no'       => $request->batch_no[$key],
                                        ]);

                                        $getPurchasDetails->update([
                                            'sales_qty' => $getPurchasDetails->sales_qty + (int) $request->qty[$key],
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                });

                return response()->json([
                    'message' =>  __f('Sales Update Success Message'),
                    'status' => "success"
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
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getInvoice = SalesInvoice::find($id);
            if ($getInvoice) {
                $getallinvoices = SalesInvoiceDetails::where('invoice_id', $getInvoice->invoice_id)->get();
                if(count($getallinvoices) > 0){
                    foreach($getallinvoices as $invoice){
                        $getpurchase = PurchaseInvoiceDetails::where('product_id',$invoice->product_id)->first();
                        $success = $getpurchase->update([
                            'sales_qty' => $getpurchase->sales_qty - $invoice->qty,
                        ]);
                        if($success){
                            $invoice->delete();
                        }
                    }
                    $getInvoice->delete();
                }
                return back()->with('success', __f('Sales Delete Success Message'));
            } else {
                return back()->with('error', __f('Invoice not found Title'));
            }
        } else {
            abort(401);
        }
    }

    public function changeStatus($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $statuschange = SalesInvoice::where('id', $id)->first();
            $statuschange->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Sales Status Change Message'));
        } else {
            abort(401);
        }
    }

    public function dueAmoutPayment(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $previuscustomer = Customer::where('id', $request->user_id)->first();
            $customer        = Customer::where('id', $request->user_id)->first();
            $invoicedue      = (int) SalesInvoice::where('customer_phone', $previuscustomer->phone)->sum('due_amount');
            if ($customer) {
                if ((int) $request->paidamount <= ($customer->previous_due + $invoicedue) ) {
                    if($customer->previous_due >= (int) $request->paidamount){
                        $customer->update([
                            'previous_due'   => $customer->previous_due -  (int) $request->paidamount,
                        ]);
                    }else{
                        $totalextraamount =  (int) $request->paidamount - (int) $customer->previous_due;
                        $customer->update([
                            'previous_due'   => 0,
                        ]);
                        $getInvoices = SalesInvoice::where('customer_phone', $previuscustomer->phone)->get();
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
                    $invoicenowdue          = (int) SalesInvoice::where('customer_phone', $previuscustomer->phone)->sum('due_amount');
                    $returnpaidamount       = SalesInvoice::where('customer_phone', $previuscustomer->phone)->sum('paid_amount');
                    $returndueamount        = SalesInvoice::where('customer_phone', $previuscustomer->phone)->sum('due_amount');
                    $returnprevisudueamount = $customer->previous_due;
                    if ($request->genarateinvoice == 1) {
                        $data['customer']        = $customer;
                        $data['previuscustomer'] = $previuscustomer;
                        $data['paidamount']      = $request->paidamount;
                        $data['paiddate']        = $request->paiddate;
                        $data['invoicedue']      = $invoicedue ;
                        $data['invoicenowdue']   = $invoicenowdue;

                        $pdf = app('dompdf.wrapper');
                        $pdf->setPaper('A4');
                        $pdf->loadView('salesinvoice::paidamountprint', $data);

                        $directory = public_path('uploads/due/pdf');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0777, true);
                        }

                        $filename = 'previous_due_' . time() . '.pdf';
                        $pdfPath = "$directory/$filename";
                        $pdf->save($pdfPath);
                        CustomerDueAmoutPaid::create([
                            'amount_get_user_id' => Auth::id(),
                            'user_id'            => $customer->id,
                            'paid_after_due'     => (int) $previuscustomer->previous_due + (int) $invoicedue,
                            'amount'             => $request->paidamount,
                            'paid_date'          => $request->paiddate,
                            'file'               => "uploads/due/pdf/$filename",
                        ]);

                        return response()->json([
                            'previous_due'     => (int) $customer->previous_due + (int) $invoicenowdue,
                            'status'           => 'success',
                            'message'          => __f("Previous Due Paid Message"),
                            'paidamount'       => $returnpaidamount,
                            'dueamount'        => $returndueamount,
                            'previsudueamount' => $returnprevisudueamount,
                            'reciviableamount' => (int) $returnprevisudueamount + (int) $returndueamount,
                            'pdf_url'          => asset("uploads/due/pdf/$filename"),
                        ]);

                    } else {
                        CustomerDueAmoutPaid::create([
                            'amount_get_user_id' => Auth::id(),
                            'user_id'            => $customer->id,
                            'paid_after_due'     => (int) $previuscustomer->previous_due + (int) $invoicedue,
                            'amount'             => $request->paidamount,
                            'paid_date'          => $request->paiddate,
                            'file'               => null,
                        ]);
                        return response()->json([
                            'previous_due'     => (int) $customer->previous_due + (int) $invoicenowdue,
                            'status'           => 'success',
                            'message'          => __f("Previous Due Paid Message"),
                            'paidamount'       => $returnpaidamount,
                            'dueamount'        => $returndueamount,
                            'previsudueamount' => $returnprevisudueamount,
                            'reciviableamount' => (int) $returnprevisudueamount + (int) $returndueamount,
                        ]);
                    }
                }
                return response()->json([
                    'status' => 'error',
                    'message' => __f("Invaid Amount Message"),
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => __f("Customer not found Message"),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function regeneratePaidPDF($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getdueinfo = CustomerDueAmoutPaid::with(['customer'])->where('id',$id)->first();
            $data['customer'] = (object)[
                'previous_due'  => (int) $getdueinfo->paid_after_due - (int) $getdueinfo->amount,
                'customer_name' => $getdueinfo->customer->customer_name,
                'phone'         => $getdueinfo->customer->phone,
                'address'       => $getdueinfo->customer->address,
            ];
            $data['previuscustomer'] = (object)[
                'previous_due' => (int) $getdueinfo->paid_after_due,
            ];
            $data['paidamount'] = $getdueinfo->amount;
            $data['paiddate']   = $getdueinfo->paid_date;

            $pdf = app('dompdf.wrapper');
            $pdf->setPaper('A4');
            $pdf->loadView('salesinvoice::paidamountprint', $data);

            $directory = public_path('uploads/due/pdf');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $filename = 'previous_due_' . time() . '.pdf';
            $pdfPath = "$directory/$filename";
            $pdf->save($pdfPath);
            $getdueinfo->update([
                'file'     => "uploads/due/pdf/$filename",
            ]);
            return $pdf->download($filename);
        } else {
            abort(401);
        }
    }

    public function userProfileGetData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = SalesInvoice::where('customer_phone',$request->customer_phone)->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('customer_name', 'like', "%{$value}%")
                                    ->orWhere('customer_phone', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->customer_phone ?? '-----';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->customer_name ?? '-----';
                    })
                    ->addColumn('sub_total', function ($data) {
                        return ($data->sub_total ? convertToLocaleNumber($data->sub_total) . ' ' . currency() : '-----');
                    })
                    ->addColumn('discount', function ($data) {
                        return ($data->discount ? convertToLocaleNumber($data->discount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('total_amount', function ($data) {
                        return ($data->total_amount ? convertToLocaleNumber($data->total_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('paid_amount', function ($data) {
                        return ($data->paid_amount ? convertToLocaleNumber($data->paid_amount ). ' ' . currency() : '-----');
                    })
                    ->addColumn('due_amount', function ($data) {
                        return ($data->due_amount ? convertToLocaleNumber($data->due_amount) . ' ' . currency() : '-----');
                    })
                    ->addColumn('status', function ($data) {
                        return invoiceStatus($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if (Auth::check() && Auth::user()->role_id == 3) {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.sale.status.change', ['id' => $data->id, 'status' => 1]) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Complete Status Title').'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.sale.status.change', ['id' => $data->id, 'status' => 0]) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }


                            if ($data->status == '0') {
                                $edit = '<li><a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.edit', ['salesinvoice' => $data->id]) . '">
                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a></li>';
                            } else {
                                $edit = '';
                            }

                            // <li>
                            //                     <a class="dropdown-item align-items-center"
                            //                     href="' . route('staff.salesinvoice.invoice.download', $data->id) . '">
                            //                         <i class="fa-solid fa-file-pdf me-2 text-info"></i>'. __f("Genarate Invoice Title") .'
                            //                     </a>
                            //                 </li>
                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="' . route('staff.profile.index', ['phone' => $data->customer_phone]) . '">
                                                    <i class="fa-solid fa-user text-primary me-2"></i>'. __f("Profile Title") .'
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('staff.salesinvoice.show', ['salesinvoice' => $data->id]) . '">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                            </li>
                                            ' . $edit . '
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('staff.sale.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.sale.status.change', ['id' => $data->id, 'status' => 1]) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'.__f('Complete Status Title').'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.sale.status.change', ['id' => $data->id, 'status' => 0]) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }


                            if ($data->status == '0') {
                                $edit = '<li><a class="dropdown-item align-items-center" href="' . route('admin.salesinvoice.edit', ['salesinvoice' => $data->id]) . '">
                                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a></li>';
                            } else {
                                $edit = '';
                            }

                            // <li>
                            //                     <a class="dropdown-item align-items-center"
                            //                     href="' . route('admin.salesinvoice.invoice.download', $data->id) . '">
                            //                         <i class="fa-solid fa-file-pdf me-2 text-info"></i>'. __f("Genarate Invoice Title") .'
                            //                     </a>
                            //                 </li>

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="' . route('admin.profile.index', ['phone' => $data->customer_phone]) . '">
                                                    <i class="fa-solid fa-user text-primary me-2"></i>'. __f("Profile Title") .'
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.salesinvoice.show', ['salesinvoice' => $data->id]) . '">
                                                    <i class="fa-solid fa-eye me-2 text-info"></i>'. __f("View Title") .'</a>
                                            </li>
                                            ' . $edit . '
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('admin.sale.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        }
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }


    // pdf download
    public function downloadInvoicePDF($invoiceId)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $invoice = SalesInvoice::findOrFail($invoiceId);
            $invoiceDetails = SalesInvoiceDetails::with(['product'])->where('invoice_id', $invoice->invoice_id)->get();
            $title = 'Invoice #' . $invoice->invoice_id;
            $pdf = Pdf::loadView('salesinvoice::downloadpdf', compact('invoice', 'invoiceDetails', 'title'))
                ->setPaper('a4', 'portrait');
            return $pdf->download('invoice_' . $invoice->invoice_id . '.pdf');
        } else {
            abort(401);
        }
    }

    // send email

    public function sendEmail(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $request->validate([
                'email'      => 'required|email',
                'invoice_id' => 'required',
            ]);
            $data['invoice'] = SalesInvoice::findOrFail($request->invoice_id);
            $data['invoiceDetails'] = SalesInvoiceDetails::with(['product'])->where('invoice_id', $data['invoice']->invoice_id)->get();
            Mail::to($request->email)->send(new SendEmail($data));
            return response()->json([
                'status' => 'success',
                'message' => "Email sent successfully !"
            ]);
        } else {
            abort(401);
        }
    }
}
