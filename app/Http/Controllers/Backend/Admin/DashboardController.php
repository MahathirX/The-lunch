<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSupplierRequest;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Setting\App\Models\Setting;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\App\Models\Customer;
use Modules\Customer\App\Models\PayBillCustomerSupplier;
use Modules\Product\App\Models\Product;
use Modules\Supplier\App\Models\Supplier;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Dashboard Title'));
            $data['activeDashboard']      = 'active';
            $data['role']                 = Role::where('slug', 'client_portal')->first();
            $data['totalUsers']           = User::count();
            $data['totalStaffUsers']      = User::where('role_id', 3)->count();
            $data['clientTotalUsers']     = User::where('role_id', $data['role']->id)->count();
            $data['totalProducts']        = Product::count();
            $data['totalOrders']          = Order::count();
            $data['pendingOrders']        = Order::where('status', 0)->count();
            $data['processingOrders']     = Order::where('status', 1)->count();
            $data['sellOrders']           = Order::where('status', 3)->count();
            $data['totalEarnings']        = Order::with(['totalearnings'])->has('totalearnings')->where('status', 3)->get();

            // For Home Tab
            $data['todaytotalsell']       = PayBillCustomerSupplier::where('role', 'customer')->whereDate('pay_date', now())->sum('total_amount');
            $data['todaycollectamount']   = PayBillCustomerSupplier::where('role', 'customer')->whereDate('pay_date', now())->sum('pay_amount');
            $data['collectamountamount']  = PayBillCustomerSupplier::where('role', 'customer')->sum('pay_amount');
            $data['todaypaymentsupplier'] = PayBillCustomerSupplier::where('role', 'supplier')->whereDate('pay_date', now())->sum('pay_amount');
            $data['allpaymentsupplier']   = PayBillCustomerSupplier::where('role', 'supplier')->sum('pay_amount');

            $customers = DB::table('customers')
                ->leftJoin(DB::raw("(
                    SELECT t1.user_id, t1.due, t1.created_at
                    FROM pay_bill_customer_suppliers t1
                    INNER JOIN (
                        SELECT user_id, MAX(id) as max_id
                        FROM pay_bill_customer_suppliers
                        WHERE role = 'customer'
                        GROUP BY user_id
                    ) t2 ON t1.user_id = t2.user_id AND t1.id = t2.max_id
                    WHERE t1.role = 'customer'
                ) as pbcs"), function ($join) {
                    $join->on('customers.id', '=', 'pbcs.user_id');
                })
                ->select(
                    'customers.id',
                    'customers.customer_name as name',
                    'customers.phone',
                    DB::raw("IFNULL(pbcs.due, 0) + customers.previous_due as total_due"),
                    'customers.photo',
                    DB::raw("COALESCE(pbcs.created_at, customers.created_at) as created_at"),
                    DB::raw("'customer' as role")
                );
            $suppliers = DB::table('suppliers')
                ->leftJoin(DB::raw("(
                    SELECT t1.user_id, t1.due, t1.created_at
                    FROM pay_bill_customer_suppliers t1
                    INNER JOIN (
                        SELECT user_id, MAX(id) as max_id
                        FROM pay_bill_customer_suppliers
                        WHERE role = 'supplier'
                        GROUP BY user_id
                    ) t2 ON t1.user_id = t2.user_id AND t1.id = t2.max_id
                    WHERE t1.role = 'supplier'
                ) as pbcs"), function ($join) {
                    $join->on('suppliers.id', '=', 'pbcs.user_id');
                })
                ->select(
                    'suppliers.id',
                    'suppliers.name',
                    'suppliers.phone',
                    DB::raw("IFNULL(pbcs.due, 0) + suppliers.previous_due as total_due"),
                    'suppliers.photo',
                    DB::raw("COALESCE(pbcs.created_at, suppliers.created_at) as created_at"),
                    DB::raw("'supplier' as role")
                );

            $data['combinedusers'] = $customers
                ->unionAll($suppliers)
                ->get();
            $data['totalcustomer'] = $data['combinedusers']->where('role', 'customer')->count();
            $data['totalsupplier'] = $data['combinedusers']->where('role', 'supplier')->count();
            $totalpayableone = $data['combinedusers']->where('role', '=', 'customer')->where('total_due', '>', 0)->sum('total_due');
            $totalpayabletwo = $data['combinedusers']->where('role', 'supplier')->where('total_due', '<', 0)->sum('total_due');
            $data['totalpayable'] = $totalpayableone + (str_replace('-', '', $totalpayabletwo));
            $totalpaymentone = $data['combinedusers']->where('role', '=', 'customer')->where('total_due', '<', 0)->sum('total_due');
            $totalpaymenttwo = $data['combinedusers']->where('role', 'supplier')->where('total_due', '>', 0)->sum('total_due');
            $data['totalpayment'] = $totalpaymenttwo + (str_replace('-', '', $totalpaymentone));
            $data['combinedusers'] = collect($data['combinedusers'])->map(function ($item) {
                $item->created_at = \Carbon\Carbon::parse($item->created_at);
                return $item;
            })->sortByDesc('created_at')->values();

            // For  CashBox Tab
            $data['boxCashSell'] = PayBillCustomerSupplier::where('role', 'cash_sell')->whereDate('pay_date', now())->sum('total_amount');
            $data['boxCashBuy']  = PayBillCustomerSupplier::where('role', 'cash_buy')->whereDate('pay_date', now())->sum('total_amount');
            $data['boxCashExpence']  = PayBillCustomerSupplier::where('role', 'expence')->whereDate('pay_date', now())->sum('total_amount');
            $data['boxCashOwneGave']  = PayBillCustomerSupplier::where('role', 'owner_gave')->whereDate('pay_date', now())->sum('total_amount');
            $data['boxCashOwnerNeil']  = PayBillCustomerSupplier::where('role', 'owner_neil')->whereDate('pay_date', now())->sum('total_amount');

            return view('backend.admin.dashboard.index', $data);
        } else {
            abort(401);
        }
    }
    public function dashboardOrderChartCount(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $month = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
                $pendingOrder    = [];
                $processingOrder = [];
                $sellOrder       = [];
                foreach ($month as $value) {
                    $pendingOrder[]    = Order::where('status', 0)->whereMonth('updated_at', '=', $value)->count();
                    $processingOrder[] = Order::where('status', 1)->whereMonth('updated_at', '=', $value)->count();
                    $sellOrder[]       = Order::where('status', 3)->whereMonth('updated_at', '=', $value)->count();
                }
                return response()->json([
                    'pendingOrder'    => $pendingOrder,
                    'processingOrder' => $processingOrder,
                    'sellOrder'       => $sellOrder,
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function dashboardProductsChartCount(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $month = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
                $pendingproducts = [];
                $activeproducts  = [];
                foreach ($month as $value) {
                    $pendingproducts[]    = Product::where('status', '0')->whereMonth('updated_at', '=', $value)->count();
                    $activeproducts[] = Product::where('status', '1')->whereMonth('updated_at', '=', $value)->count();
                }
                return response()->json([
                    'pendingproducts' => $pendingproducts,
                    'activeproducts'  => $activeproducts,
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function orderReview()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Order Reviews');
            $data['activeParentOrderMenu']   = 'active';
            $data['showOrderMenu']           = 'show';
            $data['activeOrderReview']       = 'active';
            return view('backend.admin.reviews.index', $data);
        } else {
            abort(401);
        }
    }

    public function create()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Customer Supplier Create Title'));
            return view('backend.admin.dashboard.create');
        } else {
            abort(401);
        }
    }

    public function customerSupplier(CustomerSupplierRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->usertype == 'customer') {
                    $getcustomer = Customer::where('phone', $request->phone_number)->first();
                    if (!$getcustomer) {
                        if ($request->file('userimage')) {
                            $image = $this->imageUpload($request->file('userimage'), 'images/customer/', null, null);
                        } else {
                            $image = null;
                        }
                        Customer::create([
                            'customer_name' => $request->name,
                            'phone'         => $request->phone_number,
                            'address'       => null,
                            'previous_due'  => $request->previous_due ?? 0,
                            'status'        => '1',
                            'photo'         => $image,
                        ]);
                    } else {
                        return response()->json([
                            'status'  => 'error',
                            'message' => __f('Customer already exists Message'),
                        ]);
                    }
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Customer Create Success Message'),
                    ]);
                } else {
                    $getsupplier = Supplier::where('phone', $request->phone_number)->first();
                    if (!$getsupplier) {
                        if ($request->file('userimage')) {
                            $image = $this->imageUpload($request->file('userimage'), 'images/supplier/', null, null);
                        } else {
                            $image = null;
                        }

                        Supplier::create([
                            'group'           => null,
                            'name'            => $request->name,
                            'company_name'    => null,
                            'phone'           => $request->phone_number,
                            'photo'           => $image,
                            'email'           => null,
                            'address'         => null,
                            'vat'             => null,
                            'city'            => null,
                            'state'           => null,
                            'postal_code'     => null,
                            'country'         => null,
                            'status'          => null,
                            'previous_due'    => $request->previous_due ?? 0,
                        ]);
                    } else {
                        return response()->json([
                            'status'  => 'error',
                            'message' => __f('Supplier already exists Message'),
                        ]);
                    }
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Supplier Create Success Message'),
                    ]);
                }
            }
        } else {
            abort(401);
        }
    }

    public function payBillCustomerSupplier($id, $role)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Customer Supplier Payment Title'));
            $data['role']       = $role;

            $todaycollectamount = PayBillCustomerSupplier::where('role', 'customer')->whereDate('pay_date', now())->sum('pay_amount');
            $boxCashSell        = PayBillCustomerSupplier::where('role', 'cash_sell')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashOwneGave    = PayBillCustomerSupplier::where('role', 'owner_gave')->whereDate('pay_date', now())->sum('total_amount');

            $todaypaymentsupplier = PayBillCustomerSupplier::where('role', 'supplier')->whereDate('pay_date', now())->sum('pay_amount');
            $boxCashBuy           = PayBillCustomerSupplier::where('role', 'cash_buy')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashExpence       = PayBillCustomerSupplier::where('role', 'expence')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashOwnerNeil     = PayBillCustomerSupplier::where('role', 'owner_neil')->whereDate('pay_date', now())->sum('total_amount');
            $data['cashboxAmount']= (($todaycollectamount ?? 0) + ($boxCashSell ?? 0) + ($boxCashOwneGave ?? 0)) - (($todaypaymentsupplier ?? 0) + ($boxCashBuy ?? 0) + ($boxCashExpence ?? 0) + ($boxCashOwnerNeil ?? 0));

            if ($role == 'customer') {
                $data['user'] = Customer::with('lastpayment')->findOrFail($id);
            } else {
                $data['user'] = Supplier::with('lastpayment')->findOrFail($id);
            }
            return view('backend.admin.dashboard.paybill', $data);
        } else {
            abort(401);
        }
    }

    public function payBillCustomerSupplierStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                // if ($request->message == 1) {
                //     dd($request->all());
                // }


                $payDate = Carbon::parse($request->pay_date)->startOfDay();
                $today = now()->startOfDay();
                $currentTime = now()->format('H:i:s');
                $finalDateTime = Carbon::parse($request->pay_date . ' ' . $currentTime);

                if ($payDate->lt($today)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Your Can't Add In Previous Date",
                    ]);
                } else {
                    if ($request->file('image')) {
                        $image = $this->imageUpload($request->file('image'), 'images/pay-bill/', null, null);
                    } else {
                        $image = null;
                    }

                    if ($request->role == 'customer') {
                        $paymentAmount = $request->pay_amount;
                        $customer = Customer::with('lastpayment')->findOrFail($request->user_id);
                        $payForDue = min($customer->previous_due, $paymentAmount);
                        $customer->update([
                            'previous_due' => $customer->previous_due - $payForDue,
                        ]);
                        $paymentAmount -= $payForDue;
                        $lastDue = $customer->lastpayment->due ?? 0;
                        $paymentAmount -= $lastDue;
                        $currentDue = $request->total_amount - $paymentAmount;
                        PayBillCustomerSupplier::create([
                            'role'         => $request->role,
                            'user_id'      => $request->user_id,
                            'total_amount' => $request->total_amount ?? 0,
                            'pay_amount'   => $request->pay_amount ?? 0,
                            'due'          => $currentDue,
                            'pay_date'     => $finalDateTime,
                            'image'        => $image,
                            'details'      => $request->details,
                        ]);
                    } else {
                        $paymentAmount = $request->total_amount;
                        $supplier = Supplier::with('lastpayment')->findOrFail($request->user_id);
                        $payForDue = min($supplier->previous_due, $paymentAmount);
                        $supplier->update([
                            'previous_due' => $supplier->previous_due - $payForDue,
                        ]);
                        $paymentAmount -= $payForDue;
                        $lastDue = $supplier->lastpayment->due ?? 0;
                        $paymentAmount -= $lastDue;
                        $currentDue = $request->pay_amount - $paymentAmount;

                        PayBillCustomerSupplier::create([
                            'role'         => $request->role,
                            'user_id'      => $request->user_id,
                            'total_amount' => $request->pay_amount ?? 0,
                            'pay_amount'   => $request->total_amount ?? 0,
                            'due'          => $currentDue,
                            'pay_date'     => $finalDateTime,
                            'image'        => $image,
                            'details'      => $request->details,
                        ]);

                        if($request->uppercash_sell && $request->uppercash_sell != null){
                            PayBillCustomerSupplier::create([
                                'role'         => 'cash_sell',
                                'user_id'      => Auth::id(),
                                'total_amount' => $request->uppercash_sell ?? 0,
                                'pay_amount'   => $request->pay_amount ?? 0,
                                'due'          => 0,
                                'pay_date'     => $finalDateTime,
                                'image'        => null,
                                'details'      => null,
                            ]);
                        };

                        if($request->uppercash_owner_give && $request->uppercash_owner_give != null){
                            PayBillCustomerSupplier::create([
                                'role'         => 'owner_gave',
                                'user_id'      => Auth::id(),
                                'total_amount' => $request->uppercash_owner_give ?? 0,
                                'pay_amount'   => $request->pay_amount ?? 0,
                                'due'          => 0,
                                'pay_date'     => $finalDateTime,
                                'image'        => null,
                                'details'      => null,
                            ]);
                        };
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment Successfully',
                    ]);
                }
            }
        } else {
            abort(401);
        }
    }

    public function payBill($role)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Customer Supplier Payment Title'));
            $data['role'] = $role;
            $todaycollectamount = PayBillCustomerSupplier::where('role', 'customer')->whereDate('pay_date', now())->sum('pay_amount');
            $boxCashSell        = PayBillCustomerSupplier::where('role', 'cash_sell')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashOwneGave    = PayBillCustomerSupplier::where('role', 'owner_gave')->whereDate('pay_date', now())->sum('total_amount');

            $todaypaymentsupplier = PayBillCustomerSupplier::where('role', 'supplier')->whereDate('pay_date', now())->sum('pay_amount');
            $boxCashBuy           = PayBillCustomerSupplier::where('role', 'cash_buy')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashExpence       = PayBillCustomerSupplier::where('role', 'expence')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashOwnerNeil     = PayBillCustomerSupplier::where('role', 'owner_neil')->whereDate('pay_date', now())->sum('total_amount');
            $data['cashboxAmount']= (($todaycollectamount ?? 0) + ($boxCashSell ?? 0) + ($boxCashOwneGave ?? 0)) - (($todaypaymentsupplier ?? 0) + ($boxCashBuy ?? 0) + ($boxCashExpence ?? 0) + ($boxCashOwnerNeil ?? 0));
            return view('backend.admin.dashboard.cashbook', $data);
        } else {
            abort(401);
        }
    }
    public function payBillStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->file('image')) {
                $image = $this->imageUpload($request->file('image'), 'images/pay-bill/', null, null);
            } else {
                $image = null;
            }

            $currentTime = now()->format('H:i:s');
            $finalDateTime = Carbon::parse($request->pay_date . ' ' . $currentTime);

            PayBillCustomerSupplier::create([
                'role'         => $request->role,
                'user_id'      => Auth::id(),
                'total_amount' => $request->total_amount ?? 0,
                'pay_amount'   => $request->pay_amount ?? 0,
                'due'          => 0,
                'pay_date'     => $finalDateTime,
                'image'        => $image,
                'details'      => $request->details,
            ]);

            if($request->uppercash_sell && $request->uppercash_sell != null){
                PayBillCustomerSupplier::create([
                    'role'         => 'cash_sell',
                    'user_id'      => Auth::id(),
                    'total_amount' => $request->uppercash_sell ?? 0,
                    'pay_amount'   => $request->pay_amount ?? 0,
                    'due'          => 0,
                    'pay_date'     => $finalDateTime,
                    'image'        => null,
                    'details'      => null,
                ]);
            };

            if($request->uppercash_owner_give && $request->uppercash_owner_give != null){
                PayBillCustomerSupplier::create([
                    'role'         => 'owner_gave',
                    'user_id'      => Auth::id(),
                    'total_amount' => $request->uppercash_owner_give ?? 0,
                    'pay_amount'   => $request->pay_amount ?? 0,
                    'due'          => 0,
                    'pay_date'     => $finalDateTime,
                    'image'        => null,
                    'details'      => null,
                ]);
            };
            return response()->json([
                'status' => 'success',
                'message' => 'Payment Successfully',
            ]);
        } else {
            abort(401);
        }
    }
    public function cashboxAmountCheck()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Customer Supplier Payment Title'));
            $todaycollectamount = PayBillCustomerSupplier::where('role', 'customer')->whereDate('pay_date', now())->sum('pay_amount');
            $boxCashSell        = PayBillCustomerSupplier::where('role', 'cash_sell')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashOwneGave    = PayBillCustomerSupplier::where('role', 'owner_gave')->whereDate('pay_date', now())->sum('total_amount');

            $todaypaymentsupplier = PayBillCustomerSupplier::where('role', 'supplier')->whereDate('pay_date', now())->sum('pay_amount');
            $boxCashBuy           = PayBillCustomerSupplier::where('role', 'cash_buy')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashExpence       = PayBillCustomerSupplier::where('role', 'expence')->whereDate('pay_date', now())->sum('total_amount');
            $boxCashOwnerNeil     = PayBillCustomerSupplier::where('role', 'owner_neil')->whereDate('pay_date', now())->sum('total_amount');
            $data['cashboxAmount']= (($todaycollectamount ?? 0) + ($boxCashSell ?? 0) + ($boxCashOwneGave ?? 0)) - (($todaypaymentsupplier ?? 0) + ($boxCashBuy ?? 0) + ($boxCashExpence ?? 0) + ($boxCashOwnerNeil ?? 0));
            return view('backend.admin.dashboard.cashbookcheckaount', $data);
        } else {
            abort(401);
        }
    }

     public function dashboardNotificationsCount(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $notificationCount = formatNumber(Auth::user()->unreadNotifications->count());
                $notifications = Auth::user()->unreadNotifications;
                $getNotification = '';
                if (!empty($notifications)) {
                    foreach ($notifications as $notification) {
                        $icon = $notification->data['type'] == 'new_order' ? '<i class="fa-solid fa-bag-shopping text-success"></i>' : '<i class="fa-solid fa-user text-primary"></i>';

                        $getNotification .= '<a title="' . __f('Read Notification Titile') . '" href="' . route('admin.dashboard.notification.read', ['id' => $notification->id]) . '" class="list-group-item">';
                        $getNotification .= '<div class="row g-0 align-items-center">';
                        $getNotification .= '<div class="col-2">';
                        $getNotification .=  $icon;
                        $getNotification .= '</div>';
                        $getNotification .= '<div class="col-10">';
                        $getNotification .= '<div class="text-dark">' . htmlspecialchars($notification->data['title'] ?? 'New Notification') . '</div>';
                        $getNotification .= '<div class="text-muted small mt-1">' . htmlspecialchars($notification->data['message'] ?? 'New Notification') . '</div>';
                        $getNotification .= '<div class="text-muted small mt-1">' . $notification->created_at->diffForHumans() . '</div>';
                        $getNotification .= '</div>';
                        $getNotification .= '</div>';
                        $getNotification .= '</a>';
                    }
                } else {
                    $getNotification .= '<p class="text-danger text-center">No Notification</p>';
                }

                return response()->json([
                    'status'            => 'success',
                    'notificationCount' => $notificationCount,
                    'getNotification'   => $getNotification,
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function notificationsView()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Notifications View Title'));
            $data['activeDashboard']      = 'active';
            $data['breadcrumb']          = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Notifications View Title') => ''];
            return view('backend.admin.dashboard.notification-view', $data);
        } else {
            abort(401);
        }
    }

    public function notificationsRead($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Read Notification Titile'));
            $data['notification'] = DatabaseNotification::find($id);
            if ($data['notification'] && is_null($data['notification']->read_at)) {
                $data['notification']->markAsRead();
            }
            $data['breadcrumb']          = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Read Notification Titile') => ''];
            return view('backend.admin.dashboard.notification-single', $data)->with('success', __f('Notification Read Success Message'));
        } else {
            abort(401);
        }
    }


    public function markAsRead()
    {
        if (Gate::allows('isAdmin')) {
            foreach (Auth::user()->unreadNotifications as $notification) {
                $notification->markAsRead();
            }
            return back()->with('success', __f('Mark As Read Notification Success Message'));
        } else {
            abort(401);
        }
    }

    public function notificationsDelete($id)
    {
        if (Gate::allows('isAdmin')) {
            $data['notification'] = DatabaseNotification::find($id);
            if ($data['notification']) {
                $data['notification']->delete();
            }
            return back()->with('success', __f('Notification Delete Success Message'));
        } else {
            abort(401);
        }
    }

    public function profile()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Profile Title'));
            $data['activeDashboard']      = 'active';
            $data['breadcrumb']          = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Profile Title') => ''];
            return view('backend.admin.dashboard.profile', $data);
        } else {
            abort(401);
        }
    }

    public function passwordChange()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Admin Password Change Title'));
            $data['activeDashboard']      = 'active';
            $data['breadcrumb']          = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Admin Password Change Title') => ''];
            return view('backend.admin.dashboard.passwordchange', $data);
        } else {
            abort(401);
        }
    }

    public function mailUpdate()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Mail Settings Title'));
            $data['activeMailMenu']       = 'active';
            $data['activeCreateMailMenu'] = 'active';
            $data['showMailMenu']         = 'show';
            $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Mail Settings Title') => ''];
            return view('backend.admin.dashboard.mailsettings', $data);
        } else {
            abort(401);
        }
    }

    public function mailStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            $request->validate([
                'email'    => ['required','email'],
                'password' => ['required'],
            ]);
            Setting::updateOrCreate(['option_key' => 'app_mail_email'], ['option_value' => $request->email ?? null]);
            Setting::updateOrCreate(['option_key' => 'app_mail_password'], ['option_value' => $request->password ?? null]);
            $this->changeEnvData([
                'MAIL_USERNAME'     => $request->email,
                'MAIL_PASSWORD'     => str_replace(' ', '', $request->password),
                'MAIL_FROM_ADDRESS' => $request->email,
            ]);
            Cache::forget('app_settings');
            return response()->json([
                'status'  => 'success',
                'message' => __f('Mail Update Success Message')
            ]);
        } else {
            abort(401);
        }
    }
}
