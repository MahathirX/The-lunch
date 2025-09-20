<?php

namespace Modules\ExpenseList\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Expense\App\Http\Requests\ExpenseRequest;
use Modules\Expense\App\Models\Expense;
use Yajra\DataTables\Facades\DataTables;
use Modules\ExpenseList\App\Models\ExpenseList;
use Illuminate\Support\Facades\Auth;
use Modules\ExpenseList\App\Http\Requests\ExpenseListRequest;

class ExpenseListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle( __f("Expense List Title"));
            $data['activeParentExpensesMenu'] = 'active';
            $data['activeExpensesListMenu']   = 'active';
            $data['showExpensesMenu']         = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']           = [__f('Staff Dashboard Title') => route('staff.dashboard.index'),  __f("Expense List Title") => ''];
            } else {
                $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'),  __f("Expense List Title") => ''];
            }
            return view('expenselist::index', $data);
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
            $this->setPageTitle(__f("Expense List Create Title"));
            $data['activeParentExpensesMenu']     = 'active';
            $data['activeExpensesListCreateMenu'] = 'active';
            $data['showExpensesMenu']             = 'show';
            $data['expense_category']             = Expense::where('status', '1')->get();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']               = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Expense List Title") => route('staff.expenselist.index'), __f("Expense List Create Title") => ''];
            } else {
                $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Expense List Title") => route('admin.expenselist.index'), __f("Expense List Create Title") => ''];
            }
            return view('expenselist::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseListRequest $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            ExpenseList::create([
                'create_date'         => $request->create_date,
                'expense_category_id' => $request->expense_category_id,
                'expense_note'        => $request->expense_note,
                'amount'              => $request->amount,
                'status'              => $request->status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => __f('Expense Create Success Message')
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
                $expense = ExpenseList::query()->with(['expensecategories'])->latest('id');
                return DataTables::eloquent($expense)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('create_date', 'like', "%{$request->search}%")
                                    ->orWhere('amount', 'like', "%{$request->search}%")
                                    ->orWhere('status', 'like', "%{$request->search}%")
                                    ->orWhereHas('expensecategories', function ($q) use ($request) {
                                        $q->where('name', 'like', "%{$request->search}%")
                                            ->orWhere('code', 'like', "%{$request->search}%")
                                            ->orWhere('status', 'like', "%{$request->search}%");
                                    });
                            });
                        }
                    })
                    ->addColumn('create_date', function ($data) {
                        return formatDateByLocale(\Carbon\Carbon::parse($data->create_date)->format('d-m-Y')) ?? '----';
                    })
                    ->addColumn('expense_category_id', function ($data) {
                        return $data->expensecategories->name ?? '----';
                    })
                    ->addColumn('amount', function ($data) {
                        return ($data->amount ?  convertToLocaleNumber((int)($data->amount)) . ' ' . currency() : '-----');
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
                                    '.$data->expense_note.'
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
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.expense.list.status', ['id' => $data->id, 'status' => '1']) . '">
                                            <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title").'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.expense.list.status', ['id' => $data->id, 'status' => '0']) . '">
                                            <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title").'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item align-items-center" href="' . route('staff.expenselist.edit',  $data->id) . '">
                                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                    </li>
                                    <li>' . $statusAction . '</li>
                                    <li>
                                        <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                         <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                    </li>
                                <form action="' . route('staff.expense.list.destroy', $data->id) . '"
                                        id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                        @csrf
                                        @method("DELETE")
                                </form>
                                </ul>
                            </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.expense.list.status', ['id' => $data->id, 'status' => '1']) . '">
                                            <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title").'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.expense.list.status', ['id' => $data->id, 'status' => '0']) . '">
                                            <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title").'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item align-items-center" href="' . route('admin.expenselist.edit',  $data->id) . '">
                                            <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                    </li>
                                    <li>' . $statusAction . '</li>
                                    <li>
                                        <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                         <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                    </li>
                                <form action="' . route('admin.expense.list.destroy', $data->id) . '"
                                        id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                        @csrf
                                        @method("DELETE")
                                </form>
                                </ul>
                            </div>';
                        }
                    })
                    ->rawColumns(['status', 'action', 'note'])
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
            $this->setPageTitle(__f('Expense List Edit Title'));
            $data['activeParentExpensesMenu'] = 'active';
            $data['activeExpensesListMenu']   = 'active';
            $data['showExpensesMenu']         = 'show';
            $data['expense_category']         = Expense::where('status', '1')->get();
            $data['editexpenses']             = ExpenseList::where('id', $id)->first();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']           = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f("Expense List Title") => route('staff.expense.index'), __f('Expense List Edit Title') => ''];
            } else {
                $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f("Expense List Title") => route('admin.expense.index'), __f('Expense List Edit Title') => ''];
            }
            return view('expenselist::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function Update(ExpenseListRequest $request, $id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getExpenseList = ExpenseList::find($id);
            $getExpenseList->update([
                'create_date'         => $request->create_date,
                'expense_category_id' => $request->expense_category_id,
                'amount'              => $request->amount,
                'expense_note'         => $request->expense_note,
                'status'              => $request->status,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Expense Update Success Message')
            ]);
        } else {
            abort(401);
        }
    }

    public function statusChange($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getexpense = ExpenseList::where('id', $id)->first();
            $getexpense->update([
                'status' => $status
            ]);
            return back()->with('success', __f('Expense Status Change Message'));
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
            $getExpenseList = ExpenseList::find($id);
            if ($getExpenseList) {
                $getExpenseList->delete();
            }
            return back()->with('success', __f('Expense Delete Success Message'));
        } else {
            abort(401);
        }
    }
}
