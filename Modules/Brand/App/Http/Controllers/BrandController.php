<?php

namespace Modules\Brand\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Str;
use Modules\Brand\App\Http\Requests\BrandRequest;
use Modules\Brand\App\Models\Brand;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Brand Title'));
            $data['activeProductMenu'] = 'active';
            $data['showProductMenu']   = 'show';
            $data['activeBrandMenu']   = 'active';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']        = [__f('Staff Dashboard Title')  => route('staff.dashboard.index'), __f('Brand Title') => ''];
            } else {
                $data['breadcrumb']        = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Brand Title') => ''];
            }
            return view('brand::index', $data);
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
            $this->setPageTitle(__f('Brand Create Title'));
            $data['activeProductMenu']     = 'active';
            $data['showProductMenu']       = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']            = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Brand Create Title') => ''];
                $data['activeBrandCreateMenu'] = 'active';
            } else {
                $data['breadcrumb']        = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Brand Create Title') => ''];
                $data['activeCreateBrandMenu'] = 'active';
            }
            return view('brand::create', $data);
        } else {
            abort(401);
        }
    }


    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = Brand::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('name', 'like', "%{$value}%")
                                    ->orWhere('status', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->name;
                    })
                    ->addColumn('image', function ($data) {
                        if ($data->image != null) {
                            return '<a target="_blank" href="' . asset($data->image) . '"><img id="getDataImage" src="' . asset($data->image) . '" alt="image"></a>';
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
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.brand.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.brand.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('staff.brand.edit', ['brand' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('staff.brand.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.brand.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.brand.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.brand.edit', ['brand' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('admin.brand.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        }
                    })
                    ->rawColumns(['status', 'image', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        if ($request->ajax()) {
            if ($request->file('image')) {
                $image = $this->imageUpload($request->file('image'), 'images/brand/', null, null);
            } else {
                $image = null;
            }
            Brand::create([
                'name'           => $request->name,
                'slug'           => Str::slug($request->name),
                'image'          => $image ?? '',
                'status'         => $request->status,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Brand Create Success Message')
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
        return view('brand::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Brand Edit Title'));
            $data['activeProductMenu'] = 'active';
            $data['showProductMenu']   = 'show';
            $data['activeBrandMenu']   = 'active';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb']        = [__f('Staff Dashboard Title')  => route('staff.dashboard.index'), __f('Brand Edit Title') => ''];
            } else {
                $data['breadcrumb']        = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Brand Edit Title') => ''];
            }
            $data['brand']             = Brand::where('id', $id)->first();
            return view('brand::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, $id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getbrand = Brand::where('id', $id)->first();
            if ($request->file('image')) {
                $this->imageDelete($getbrand->image);
                $image = $this->imageUpload($request->file('image'), 'images/brand/', null, null);
            } else {
                $image = $getbrand->image;
            }
            $getbrand->update([
                'name'           => $request->name,
                'slug'           => Str::slug($request->name),
                'image'          => $image ?? '',
                'status'         => $request->status,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Brand Update Success Message')
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
            $getBrand = Brand::where('id', $id)->first();
            if($getBrand->image != null){
                $this->imageDelete($getBrand->image);
            }
            $getBrand->delete();
            return back()->with('success',  __f('Brand Delete Success Message'));
        } else {
            abort(401);
        }
    }
    public function brandStatus($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getBrand = Brand::where('id', $id)->first();
            $getBrand->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Brand Status Change Message'));
        } else {
            abort(401);
        }
    }
}
