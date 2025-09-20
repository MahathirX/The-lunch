<?php

namespace Modules\Expense\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\App\Models\Expense;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Modules\Expense\App\Http\Requests\ExpenseRequest;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle( __f('Expenses Title'));
            $data['activeParentExpensesMenu'] = 'active';
            $data['activeExpensesMenu']       = 'active';
            $data['showExpensesMenu']         = 'show';
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']           = [__f('Staff Dashboard Title') => route('staff.dashboard.index'),  __f('Expenses Title') => ''];
            }else{
                $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'),  __f('Expenses Title') => ''];
            }
            return view('expense::index', $data);
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
            $this->setPageTitle(__f("Expense Create Title"));
            $data['activeParentExpensesMenu'] = 'active';
            $data['activeExpensesCreateMenu'] = 'active';
            $data['showExpensesMenu']         = 'show';
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']           = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Expenses Title') => route('staff.expense.index'), __f("Expense Create Title") => ''];
            }else{
                $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Expenses Title') => route('admin.expense.index'), __f("Expense Create Title") => ''];
            }
            return view('expense::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            Expense::create([
                'name' => $request->name,
                'code' => $request->code,
                'status' => $request->status,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f("Expense Category Create Success Message")
            ]);
        } else {
            abort(401);
        }
    }

    /**
     * Fetch data from resource.
     */
    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $expense = Expense::latest('id');
                return DataTables::eloquent($expense)
                ->addIndexColumn()
                ->filter(function ($query) use ($request) {
                    if (!empty($request->search)) {
                        $query->where(function ($query) use ($request) {
                            $query->where('name', 'like', "%{$request->search}%")
                                ->orWhere('code', 'like', "%{$request->search}%");

                        });
                    }
                })
                ->addColumn('name', function($data) {
                    return $data->name;
                })
                ->addColumn('code',function($data){
                    return $data->code ?? '-----';
                })
                ->addColumn('status',function($data){
                    return status($data->status);
                })
                ->addColumn('action', function ($data) {
                    if(Auth::check() && Auth::user()->role_id == 3){
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.expense.status', ['id' => $data->id, 'status' => '1']) . '">
                                            <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title").'</a>';
                        } else if ($data->status == '1') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.expense.status', ['id' => $data->id, 'status' => '0']) . '">
                                            <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title").'</a>';
                        }

                        return '<div class="btn-group dropstart text-end">
                                <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item align-items-center" href="' . route('staff.expense.edit',  $data->id) . '">
                                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                    </li>
                                    <li>' . $statusAction . '</li>
                                    <li>
                                        <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                         <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                    </li>
                                <form action="' . route('staff.expense.destroy', $data->id) . '"
                                        id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                        @csrf
                                        @method("DELETE")
                                </form>
                                </ul>
                            </div>';
                    }else{
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.expense.status', ['id' => $data->id, 'status' => '1']) . '">
                                            <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title").'</a>';
                        } else if ($data->status == '1') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.expense.status', ['id' => $data->id, 'status' => '0']) . '">
                                            <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title").'</a>';
                        }

                        return '<div class="btn-group dropstart text-end">
                                <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item align-items-center" href="' . route('admin.expense.edit',  $data->id) . '">
                                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                    </li>
                                    <li>' . $statusAction . '</li>
                                    <li>
                                        <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                         <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                    </li>
                                <form action="' . route('admin.expense.destroy', $data->id) . '"
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f("Expense Edit Title"));
            $data['activeParentExpensesMenu'] = 'active';
            $data['activeExpensesMenu']       = 'active';
            $data['showExpensesMenu']         = 'show';
            $data['expense']                  = Expense::where('id', $id)->first();
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']           = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Expenses Title') => route('staff.expense.index'), __f("Expense Edit Title") => ''];
            }else{
                $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Expenses Title') => route('admin.expense.index'), __f("Expense Edit Title") => ''];
            }
            return view('expense::edit', $data);
        } else {
            abort(401);
        }
    }
    public function show($id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, $id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $expense = Expense::findOrFail($id);
            $expense->update([
                'name'   => $request->name,
                'code'   => $request->code,
                'status' => $request->status,
            ]);
            return response()->json([
                'status'  => 'success',
                'message' => __f("Expense Update Create Success Message"),
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
            $getexpense = Expense::where('id', $id)->first();
            if ($getexpense) {
                $getexpense->delete();
                return back()->with('success', __f("Expense Delete Create Success Message"));
            }
        } else {
            abort(401);
        }
    }

    public function statusChange($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getexpense = Expense::where('id', $id)->first();
            $getexpense->update([
                'status' => $status
            ]);
            return back()->with('success', __f("Expense Category Status Change Message"));
        } else {
            abort(401);
        }
    }
}
