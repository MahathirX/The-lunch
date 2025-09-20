<?php

namespace Modules\Category\App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\Category\App\Http\Requests\CategoryRequest;
use Modules\Category\App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Category Title'));
            $data['showProductMenu']    = 'show';
            $data['activeProductMenu']  = 'active';
            $data['activeCategoryMenu'] = 'active';
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']           = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Category Title') => ''];
            }else{
                $data['breadcrumb']           = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Category Title') => ''];
            }
            return view('category::index', $data);
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
            $this->setPageTitle(__f('Category Create Title'));
            $data['showProductMenu']   = 'show';
            $data['activeProductMenu'] = 'active';
            $data['categories']        = Category::where('status','1')->where('parent_id','0')->where('submenu_id','0')->get();
            $data['totalcategory']     = Category::count();
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']               = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Category Title') => route('staff.category.index'), __f('Category Create Title') => ''];
                $data['activeCategoryCreateMenu'] = 'active';
            }else{
                $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Category Title') => route('admin.category.index'), __f('Category Create Title') => ''];
                $data['activeCategoryCreateMenu'] = 'active';
            }
            return view('category::create', $data);
        } else {
            abort(401);
        }
    }
    /**
     * All data display
     * Search functionality
     */
    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = Category::orderBy('order_by','asc');
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
                    ->addColumn('parent_category', function ($data) {
                        $category_id = Category::pluck('id')->toArray();
                        $parentCategoryName = Category::where('id', $data->parent_id)->first();
                        $parentSubCategoryName = Category::where('id', $data->submenu_id)->first();

                        if (in_array($data->parent_id, $category_id) && in_array($data->submenu_id, $category_id)) {
                            return $parentSubCategoryName->name . '<span class="badge bg-info text-white">'.__f('Mega Category Title').'</span>';
                        } elseif (in_array($data->parent_id, $category_id)) {
                            return $parentCategoryName->name . '<span class="badge bg-warning text-white">'.__f('Sub Category Title').'</span>';
                        } else {
                            return '<span class="badge bg-success text-white">'.__f('Parent Category Title').'</span>';
                        }
                    })
                    ->addColumn('image', function ($data) {
                        if ($data->image != null) {
                            return '<a target="_blank" href="' . asset($data->image) . '"><img id="getDataImage" src="' . asset($data->image) . '" alt="image"></a>';
                        } else {
                            return '<img id="getDataImage" src="' . asset('backend/assets/img/avatars/blank image.jpg') . '" alt="image">';
                        }
                    })
                    ->addColumn('tag', function ($data) {
                        if($data->tag != null){
                            return '<span class="badge bg-danger">'.$data->tag.'</span>';
                        }else{
                            return '--------';
                        }

                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('home_page_show', function ($data) {
                        return homepageshowstatus($data->home_page_show);
                    })
                    ->addColumn('order_by', function ($data) {
                        return convertToLocaleNumber($data->order_by);
                    })
                    ->addColumn('action', function ($data) {
                        if (Auth::check() && Auth::user()->role_id == 3) {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.category.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.category.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('staff.category.edit', ['category' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('staff.category.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                        }else{
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.category.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.category.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.category.edit', ['category' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                            </li>
                                            <form action="' . route('admin.category.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                            </form>
                                        </ul>
                                    </div>';
                            }
                    })
                    ->rawColumns(['parent_category','name','status','image', 'tag', 'home_page_show', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if ($request->ajax()) {
            if ($request->file('image')) {
                $image = $this->imageUpload($request->file('image'), 'images/category/', null, null);
            }else{
                $image = null;
            }
            Category::create([
                'name'           => $request->name,
                'slug'           => Str::slug($request->name),
                'image'          => $image,
                'parent_id'      => $request->parent_id ?? 0,
                'submenu_id'     => $request->submenu_id ?? 0,
                'tag'            => $request->tag,
                'status'         => $request->status,
                'home_page_show' => $request->home_page_show ?? 0,
                'order_by'       => $request->order_by,
            ]);
            return response()->json([
                'status' => 'success',
                'message' =>  __f('Category Create Success Message')
            ]);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('category::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle( __f('Category Edit Title'));
            $data['activeProductMenu']  = 'active';
            $data['activeCategoryMenu'] = 'active';
            $data['showProductMenu']    = 'show';
            $data['category']           = Category::where('id', $id)->first();
            $data['categories']         = Category::where('status','1')->where('parent_id','0')->where('submenu_id','0')->get();
            $data['subcategories']      = Category::where('status','1')->where('parent_id',$data['category']->parent_id ?? '')->where('submenu_id','0')->get();
            if(Auth::check() && Auth::user()->role_id == 3){
                $data['breadcrumb']         = [__f('Staff Dashboard Title') => route('staff.dashboard.index'),  __f('Category Title') => route('staff.category.index'), __f('Category Edit Title') => ''];
            }else{
                $data['breadcrumb']         = [__f('Admin Dashboard Title') => route('admin.dashboard.index'),  __f('Category Title') => route('admin.category.index'), __f('Category Edit Title') => ''];
            }
            return view('category::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {

        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getcategory = Category::where('id', $id)->first();
            if ($request->file('image')) {
                $image = $this->imageUpload($request->file('image'), 'images/category/', null, null);
            }else{
                $image = $getcategory->image;
            }
            $getcategory->update([
                'name'       => $request->name,
                'slug'       => Str::slug($request->name),
                'image'      => $image,
                'tag'        => $request->tag,
                'parent_id'  => $request->parent_id ?? 0,
                'submenu_id' => $request->submenu_id ?? 0,
                'status'     => $request->status,
                'home_page_show' => $request->home_page_show ?? 0,
                'order_by'       => $request->order_by,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Category Update Success Message')
            ]);
        } else {
            abort(401);
        }
    }


    public function categorySubMenu(Request $request){
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if($request->ajax()){
                if($request->categoryId != null){
                    $getCategorys = Category::where('parent_id',$request->categoryId)->where('submenu_id',0)->get();
                    $output = '<option value="">'. __f("Sub Category Label Title") .'</option>';
                    if (!empty($getCategorys) && count($getCategorys) > 0) {
                        foreach ($getCategorys as $getCategory) {
                            $id = isset($getCategory->id) ? htmlspecialchars($getCategory->id) : '';
                            $name = isset($getCategory->name) ? htmlspecialchars($getCategory->name) : '';
                            $output .= '<option value="' . $id . '">' . $name . '</option>';
                        }
                    } else {
                        $output .= '<option value="" disabled class="text-danger">'. __f("No Sub Category Found Text") .'</option>';
                    }
                    return response()->json([
                        'status'  => 'success',
                        'data' => $output,
                    ]);
                }else{
                    $output = '<option value="">'. __f("Sub Category Label Title") .'</option>';
                    return response()->json([
                        'status'  => 'success',
                        'data' => $output,
                    ]);
                }
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
            $getCategory = Category::where('id', $id)->first();
            $this->imageDelete( $getCategory->image);
            $getCategory->delete();
            return back()->with('success', __f('Category Delete Success Message'));
        } else {
            abort(401);
        }
    }


    // Category status update
    public function changeStatus($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getCategory = Category::where('id', $id)->first();
            $getCategory->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Category Status Change Message'));
        } else {
            abort(401);
        }
    }
}
