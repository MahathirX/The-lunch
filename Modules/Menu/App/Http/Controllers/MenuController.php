<?php

namespace Modules\Menu\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Menu\App\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;
use Modules\Menu\App\Http\Requests\MenuRequest;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Menu List Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeMenuMenu']         = 'active';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Menu List Title') => '',];
            return view('menu::index', $data);
        } else {
            abort(401);
        }
    }

    /**
     * All data display
     * Search functionality
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = Menu::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('name', 'like', "%{$value}%")
                                    ->orWhere('url', 'like', "%{$value}%")
                                    ->orWhere('status', 'like', "%{$value}%")
                                    ->orWhere('order_by', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->name;
                    })
                    ->addColumn('url', function ($data) {
                        return '<span title="'.$data->url.'">'.$data->url.'</span>';
                    })
                    ->addColumn('icon', function ($data) {
                        if ($data->icon != null) {
                            return '<a target="_blank" href="' . asset($data->icon) . '"><img id="getDataImage" src="' . asset($data->icon) . '" alt="image"></a>';
                        } else {
                            return '<img id="getDataImage" src="' . asset('backend/assets/img/avatars/blank image.jpg') . '" alt="image">';
                        }
                    })
                    ->addColumn('parent_menu', function ($data) {
                        $menu_id = Menu::pluck('id')->toArray();
                        $parentMenuName = Menu::where('id', $data->parent_id)->first();
                        $parentSubMenuName = Menu::where('id', $data->child_id)->first();
                        if (in_array($data->parent_id, $menu_id) && in_array($data->child_id, $menu_id)) {
                            return $parentSubMenuName->name . '<span class="badge bg-info text-white">'.__f('Megamenu Title').'</span>';
                        } elseif (in_array($data->parent_id, $menu_id)) {
                            return $parentMenuName->name . '<span class="badge bg-warning text-white">'.__f('Submenu Title').'</span>';
                        } else {
                            return '<span class="badge bg-success text-white">'.__f('Parent Menu Title').'</span>';
                        }
                    })
                    ->addColumn('target', function ($data) {
                        return target($data->target);
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.menu.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                                <i class="fa-solid fa-check me-2 text-success"></i>'. __f("Status Publish Title") .'</a>';
                        } else if ($data->status == '1') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.menu.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                                <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>'. __f("Status Pending Title") .'</a>';
                        }

                        return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('admin.menu.edit', ['menu' => $data->id]) . '">
                                                <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>'. __f("Edit Title") .'</a>
                                        </li>
                                        <li>' . $statusAction . '</li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>'. __f("Delete Title") .'</button>
                                        </li>
                                        <form action="' . route('admin.menu.delete', ['id' => $data->id]) . '"
                                              id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                              @csrf
                                              @method("DELETE")
                                        </form>
                                    </ul>
                                </div>';
                    })
                    ->rawColumns(['icon','url','parent_menu', 'target', 'status', 'action'])
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
            $this->setPageTitle(__f('Menu Create Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeMenuCreateMenu']   = 'active';
            $data['parentMenu']             = Menu::where('parent_id', 0)->where('status', '1')->orderBy('id', 'desc')->get();
            $data['totalMenus']             = Menu::get();
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Menu List Title') => route('admin.menu.index'), __f('Menu Create Title') => '',];
            return view('menu::create', $data);
        } else {
            abort(401);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->file('icon')) {
                    $icon = $this->imageUpload($request->file('icon'), 'menu/icons/', null, null);
                } else {
                    $icon = null;
                }
                Menu::create([
                    'name'      => $request->name,
                    'title'     => $request->title,
                    'slug'      => Str::slug($request->slug),
                    'url'       => $request->url,
                    'target'    => $request->target,
                    'order_by'  => $request->order_by,
                    'parent_id' => $request->parent_id ?? 0,
                    'child_id'  => $request->child_id ?? 0,
                    'icon'      => $icon ?? null,
                    'status'    => $request->status,
                    'position'  => $request->position,
                ]);
                Cache::forget('navbar_menus');
                Cache::forget('top_menus');
                Cache::forget('footers_menus');
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Menu Create Success Message'),
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
        return view('menu::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Menu Edit Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeMenuMenu']         = 'active';
            $data['parentMenu']             = Menu::where('parent_id', 0)->where('status', '1')->orderBy('id', 'desc')->get();
            $data['editMenu']               = Menu::where('id', $id)->first();
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Menu List Title') => route('admin.menu.index'), __f('Menu Edit') => '',];
            return view('menu::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function Update(MenuRequest $request, $id)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                 $menu = Menu::where('id', $id)->first();
               if ($request->file('icon')) {
                    $this->imageDelete($menu->icon);
                    $icon = $this->imageUpload($request->file('icon'), 'menu/icons/', null, null);
                } else {
                    $icon = $menu->icon;
                }
                $menu->update([
                    'name'      => $request->name,
                    'title'     => $request->title,
                    'slug'      => Str::slug($request->slug),
                    'url'       => $request->url,
                    'target'    => $request->target,
                    'order_by'  => $request->order_by,
                    'parent_id' => $request->parent_id ?? 0,
                    'child_id'  => $request->child_id ?? 0,
                    'icon'      => $icon ?? null,
                    'status'    => $request->status,
                    'position'  => $request->position,
                ]);
                Cache::forget('navbar_menus');
                Cache::forget('top_menus');
                Cache::forget('footers_menus');
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Menu Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }


    // Category status update
    public function changeStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $getCategory = Menu::where('id', $id)->first();
            $getCategory->update([
                'status' => $status,
            ]);
            Cache::forget('navbar_menus');
            Cache::forget('top_menus');
            Cache::forget('footers_menus');
            return back()->with('success', __f('Menu Status Change Message'));
        } else {
            abort(401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Gate::allows('isAdmin')) {
            $menu = Menu::where('id', $id)->first();
            $menu->delete();
            Cache::forget('navbar_menus');
            Cache::forget('top_menus');
            Cache::forget('footers_menus');
            return back()->with('success',  __f('Menu Delete Success Message'));
        } else {
            abort(401);
        }
    }
}
