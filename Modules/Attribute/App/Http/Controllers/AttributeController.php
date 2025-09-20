<?php

namespace Modules\Attribute\App\Http\Controllers;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Modules\Attribute\App\Models\Attribute;
use Modules\Attribute\App\Models\Attributeoption;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Attributes');
            $data['activeProductMenu']   = 'active';
            $data['activeAttributeMenu'] = 'active';
            $data['showProductMenu']     = 'show';
            $data['breadcrumb']          = ['Admin Dashboard' => route('admin.dashboard.index'), 'Attributes' => ''];
            return view('attribute::index', $data);
        } else {
            abort(401);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = Attribute::with('options')->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request->search}%")
                                      ->orWhere('status', 'like', "%{$request->search}%")
                                      ->orWhereHas('options', function ($q) use ($request) {
                                          $q->where('attribute_option', 'like', "%{$request->search}%");
                                      });
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->name;
                    })
                    ->addColumn('slug', function ($data) {
                        return $data->slug;
                    })
                    ->addColumn('attribute_option', function ($data) {
                        return $data->options->pluck('attribute_option')->implode(', ');
                    })


                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.atribute.status', ['id' => $data->id, 'status' => '1']) . '"><i class="fa-solid fa-check me-2 text-success"></i>Publish</a>';
                        } else {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.atribute.status', ['id' => $data->id, 'status' => '0']) . '"><i class="fa-regular fa-hourglass-half me-2 text-warning"></i>Pending</a>';
                        }

                        return ' <div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('admin.attribute.edit', ['attribute' => $data->id]) . '">
                                                <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a>
                                        </li>
                                        <li>' . $statusAction . '</li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                                        </li>
                                        <form action="' . route('admin.atribute.delete', ['id' => $data->id]) . '"
                                              id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                              @csrf
                                              @method("DELETE")
                                        </form>
                                    </ul>
                                </div>';
                    })
                    ->rawColumns(['running_image', 'threed_image', 'status', 'action'])
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
            $this->setPageTitle('Attribute Create');
            $data['activeProductMenu']   = 'active';
            $data['activeAttributeMenu'] = 'active';
            $data['showProductMenu']     = 'show';
            $data['breadcrumb']          = ['Admin Dashboard' => route('admin.dashboard.index'), 'Attributes Create' => ''];
            return view('attribute::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            $request->validate([
                'name'               => 'required',
                'attribute_option'   => 'required|array|min:1',
                'attribute_option.*' => 'required|string',
                'status'             => 'required',
            ]);

            $attribute = Attribute::create([
                'name'    => $request->name,
                'user_id' => Auth::id(),
                'slug'    => Str::slug($request->name),
                'status'  => $request->status,
            ]);

            $options = explode(',', $request->attribute_option[0]);
            foreach ($options as $option) {
                Attributeoption::create([
                    'attribute_id'     => $attribute->id,
                    'attribute_option' => trim($option),
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Attribute created successfully!'
            ], 200);
        } else {
            abort(401);
        }
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('attribute::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Attribute Create');
            $data['activeProductMenu']   = 'active';
            $data['activeAttributeMenu'] = 'active';
            $data['showProductMenu']     = 'show';
            $data['breadcrumb']          = ['Admin Dashboard' => route('admin.dashboard.index'), 'Attribute Edit' => ''];
            $data['attribute']           = Attribute::with('options')->findOrFail($id);
            return view('attribute::edit', $data);
        } else {
            abort(401);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('isAdmin')) {
            $request->validate([
                'name'               => 'required',
                'attribute_option'   => 'required|array|min:1',
                'attribute_option.*' => 'required|string',
                'status'             => 'required',
            ]);

            $attribute = Attribute::findOrFail($id);
            $attribute->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);

            Attributeoption::where('attribute_id', $id)->delete();
            $options = explode(',', $request->attribute_option[0]);
            foreach ($options as $option) {
                Attributeoption::create([
                    'attribute_id'     => $id,
                    'attribute_option' => trim($option),
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Attribute updated successfully!',
            ], 200);
        } else {
            abort(401);
        }
    }

    public function delete($id)
    {
        if (Gate::allows('isAdmin')) {
            $attribute = Attribute::findOrFail($id);
            $attribute->options()->delete();
            $attribute->delete();
            return back()->with('success', 'Attribute Delete Successfully');
        } else {
            abort(401);
        }
    }

    public function atributeStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $getProject = Attribute::where('id', $id)->first();
            $getProject->update([
                'status' => $status,
            ]);
            return back()->with('success', 'attribute Status Change Successfully');
        } else {
            abort(401);
        }
    }
}
