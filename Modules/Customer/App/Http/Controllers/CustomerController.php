<?php

namespace Modules\Customer\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\App\Http\Requests\CustomerRequest;
use Yajra\DataTables\Facades\DataTables;
use Modules\Customer\App\Models\Customer;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Customer List Title'));
            $data['activeParentCustomersMenu']  = 'active';
            $data['activeInvoiceCustomersMenu'] = 'active';
            $data['showCustomersMenu']          = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                 = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Customer List Title') => ''];
            } else {
                $data['breadcrumb']                 = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Customer List Title') => ''];
            }
            return view('customer::index', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Customer Create Title'));
            $data['activeParentCustomersMenu']       = 'active';
            $data['activeInvoiceCustomerCreateMenu'] = 'active';
            $data['showCustomersMenu']               = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                  = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Customer List Title') => route('staff.customer.index'), __f('Customer Create Title') => ''];
            } else {
                $data['breadcrumb']                  = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Customer List Title') => route('admin.customer.index'), __f('Customer Create Title') => ''];
            }
            return view('customer::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                if ($request->file('customer_photo')) {
                    $image = $this->imageUpload($request->file('customer_photo'), 'images/customer/', null, null);
                } else {
                    $image = null;
                }
                $getcustomer = Customer::where('phone', $request->phone)->first();
                if (!$getcustomer) {
                    Customer::create([
                        'customer_name' => $request->name,
                        'phone'         => $request->phone,
                        'address'       => $request->address,
                        'previous_due'  => $request->previous_due ?? 0,
                        'status'        => $request->status,
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
            }
        } else {
            abort(401);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = Customer::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('customer_name', 'like', "%{$request->search}%")
                                    ->orWhere('phone', 'like', "%{$request->search}%")
                                    ->orWhere('address', 'like', "%{$request->search}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->customer_name;
                    })
                    ->addColumn('phone', function ($data) {
                        return $data->phone;
                    })
                    ->addColumn('previous_due', function ($data) {
                        return convertToLocaleNumber($data->previous_due ?? 0);
                    })
                    ->addColumn('address', function ($data) {
                        return $data->address ?? 'No address';
                    })

                    ->addColumn('photo', function ($data) {
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
                        if (Auth::check() && Auth::user()->role_id == 3) {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.customer.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.customer.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="'.route('staff.profile.index',['phone' => $data->phone]).'">
                                                    <i class="fa-solid fa-user text-info me-2"></i>Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('staff.customer.edit', ['customer' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                 <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                        <form action="' . route('staff.customer.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                        </form>
                                        </ul>
                                    </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.customer.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.customer.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center"
                                                href="'.route('admin.profile.index',['phone' => $data->phone]).'">
                                                    <i class="fa-solid fa-user text-info me-2"></i>'. __f("Profile Title") .'
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.customer.edit', ['customer' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                 <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                        <form action="' . route('admin.customer.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                        </form>
                                        </ul>
                                    </div>';
                        }
                    })
                    ->rawColumns(['status', 'photo', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Customer Edit Title'));
            $data['activeParentCustomersMenu']  = 'active';
            $data['activeInvoiceCustomersMenu'] = 'active';
            $data['showCustomersMenu']          = 'show';
            $data['customer']                   = Customer::where('id', $id)->first();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']                 = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Customer List Title') => route('staff.customer.index'), __f('Customer Edit Title') => ''];
            } else {
                $data['breadcrumb']                 = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Customer List Title') => route('admin.customer.index'), __f('Customer Edit Title') => ''];
            }
            return view('customer::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, $id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getcustomer = Customer::where('id', $id)->first();
            if ($request->file('customer_photo')) {
                $this->imageDelete($getcustomer->photo);
                $image = $this->imageUpload($request->file('customer_photo'), 'images/customer/', null, null);
            } else {
                $image = $getcustomer->photo;
            }
            $getcustomer->update([
                'customer_name' => $request->name,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'previous_due'  => $request->previous_due ?? 0,
                'status'        => $request->status,
                'photo'         => $image,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Customer Update Success Message')
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
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getCustomer = Customer::where('id', $id)->first();
            $this->imageDelete($getCustomer->photo);
            $getCustomer->delete();
            return back()->with('success', __f('Customer Delete Success Message'));
        } else {
            abort(401);
        }
    }


    public function changeStatus($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getCustomer = Customer::where('id', $id)->first();
            $getCustomer->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Customer Status Change Message'));
        } else {
            abort(401);
        }
    }
}
