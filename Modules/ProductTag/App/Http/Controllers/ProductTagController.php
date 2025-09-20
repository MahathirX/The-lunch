<?php

namespace Modules\ProductTag\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Modules\ProductTag\App\Http\Requests\ProductTagRequest;
use Yajra\DataTables\Facades\DataTables;
use Modules\ProductTag\App\Models\ProductTag;

class ProductTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Tags');
            $data['showProductMenu']   = 'show';
            $data['activeProductMenu'] = 'active';
            $data['activeTagMenu']     = 'active';
            $data['breadcrumb']        = ['Admin Dashboard' => route('admin.dashboard.index'), 'Tags' => '',];
            return view('producttag::index', $data);
        } else {
            abort(401);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = ProductTag::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('tag_name', 'like', "%{$request->search}%")
                                    ->orWhere('status', 'like', "%{$request->search}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->tag_name;
                    })
                    ->addColumn('slug', function ($data) {
                        return $data->slug;
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.producttag.status', ['id' => $data->id, 'status' => '1']) . '">
                                                <i class="fa-solid fa-check me-2 text-success"></i>Publish</a>';
                        } else if ($data->status == '1') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.producttag.status', ['id' => $data->id, 'status' => '0']) . '">
                                                <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>Pending</a>';
                        }

                        return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('admin.producttag.edit', ['producttag' => $data->id]) . '">
                                                <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a>
                                        </li>
                                        <li>' . $statusAction . '</li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                             <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                                        </li>
                                    <form action="' . route('admin.producttag.delete', ['id' => $data->id]) . '"
                                            id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                            @csrf
                                            @method("DELETE")
                                    </form>
                                    </ul>
                                </div>';
                    })
                    ->rawColumns(['producttype', 'status', 'product_image', 'action'])
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
            $this->setPageTitle('Create Tag');
            $data['showProductMenu']   = 'show';
            $data['activeProductMenu'] = 'active';
            $data['activeTagMenu']     = 'active';
            $data['breadcrumb']        = ['Admin Dashboard' => route('admin.dashboard.index'), 'Create Tag' => '',];
            return view('producttag::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductTagRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                ProductTag::create([
                    'user_id'  => Auth::id(),
                    'tag_name' => $request->name,
                    'slug'     => Str::slug($request->name),
                    'status'   => $request->status,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Tag create successfully'
                ]);
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
        return view('producttag::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Edit Tag');
            $data['showProductMenu']   = 'show';
            $data['activeProductMenu'] = 'active';
            $data['activeTagMenu']     = 'active';
            $data['editTag']           = ProductTag::where('id',$id)->first();
            $data['breadcrumb']        = ['Admin Dashboard' => route('admin.dashboard.index'), 'Edit Tag' => '',];
            return view('producttag::edit', $data);
        } else {
            abort(401);
        }
    }


    public function Update(ProductTagRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            $gettag = ProductTag::where('id', $request->id)->first();
            $gettag->update([
                'tag_name' => $request->name,
                'slug'     => Str::slug($request->name),
                'status'   => $request->status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Tag update successfully'
            ]);
        } else {
            abort(401);
        }
    }

    public function delete($id)
    {

        if (Gate::allows('isAdmin')) {
            $gettag = ProductTag::where('id', $id)->first();
            $gettag->delete();
            return back()->with('success', 'Tag Delete Successfully');
        } else {
            abort(401);
        }
    }


    public function productTagStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $getProduct = ProductTag::where('id', $id)->first();
            $getProduct->update([
                'status' => $status,
            ]);
            return back()->with('success', 'Tag Status Change Successfully');
        } else {
            abort(401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
