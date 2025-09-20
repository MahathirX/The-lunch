<?php

namespace Modules\Page\App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Page\App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Modules\Product\App\Models\Product;
use Yajra\DataTables\Facades\DataTables;
use Modules\Page\App\Http\Requests\PageRequest;
use Modules\PageMultiImage\App\Models\PageMultiImage;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Pages');
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeLandingPageMenu']  = 'active';
            $data['breadcrumb']             = ['Admin Dashboard' => route('admin.dashboard.index'), 'Pages' => ''];
            return view('page::index', $data);
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
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = Page::with(['product'])->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('page_name', 'like', "%{$value}%")
                                    ->orWhere('slug', 'like', "%{$value}%")
                                    ->orWhere('status', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('product_name', function ($data) {
                        return $data->product->name ?? '----';
                    })
                    ->addColumn('name', function ($data) {
                        return $data->page_name;
                    })
                    ->addColumn('slug', function ($data) {
                        return $data->slug;
                    })
                    ->addColumn('url', function ($data) {
                        return '<a  href="' . asset($data->slug) . '" target="_blank">' . asset($data->slug) . '</a>';
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.page.status.change', ['id' => $data->id, 'status' => '1']) . '">
                                                <i class="fa-solid fa-check me-2 text-success"></i>Publish</a>';
                        } else if ($data->status == '1') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.page.status.change', ['id' => $data->id, 'status' => '0']) . '">
                                                <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>Pending</a>';
                        }

                        return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item align-items-center" href="' . route('admin.page.edit', ['page' => $data->id]) . '">
                                                <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>Edit</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item align-items-center" target="_blank" href="' . asset($data->slug) . '">
                                                <i class="fa-regular fa-eye me-2 text-info"></i>View Page</a>
                                        </li>
                                        <li>' . $statusAction . '</li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                                        </li>
                                        <form action="' . route('admin.page.delete', ['id' => $data->id]) . '"
                                              id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                              @csrf
                                              @method("DELETE")
                                        </form>
                                    </ul>
                                </div>';
                    })
                    ->rawColumns(['status', 'url', 'action'])
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
            $this->setPageTitle('Page Create');
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeLandingPageMenu']  = 'active';
            $data['breadcrumb']             = ['Admin Dashboard' => route('admin.dashboard.index'), 'Page Create' => ''];
            $data['products']               = Product::where('status', '1')->get();
            return view('page::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PageRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $page = Page::create([
                    'product_id'       => $request->product_id,
                    'page_name'        => $request->page_name,
                    'slug'             => Str::slug($request->page_name),
                    'page_heading'     => $request->page_heading,
                    'page_link'        => $request->page_link,
                    'product_overview' => $request->product_overview,
                    'slider_title'     => $request->slider_title,
                    'features'         => $request->features,
                    'old_price'        => $request->old_price ?? null,
                    'new_price'        => $request->new_price,
                    'phone'            => $request->phone,
                    'extra_content'    => $request->extra_content,
                    'status'           => $request->status,
                ]);

                if ($request->file('sliderimage')) {
                    foreach ($request->file('sliderimage') as $imageFile) {
                        $image = $this->imageUpload($imageFile, 'images/page/image/', null, null);
                        PageMultiImage::create([
                            'page_id' => $page->id,
                            'image'   => $image,
                        ]);
                    }
                }
                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Page create successfully',
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function changeStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $page = Page::where('id', $id)->first();
            $page->update([
                'status' => $status,
            ]);
            return back()->with('success', 'Page Status Change Successfully');
        } else {
            abort(401);
        }
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('page::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Page Edit');
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeLandingPageMenu']  = 'active';
            $data['breadcrumb']             = ['Admin Dashboard' => route('admin.dashboard.index'), 'Page Edit' => ''];
            $data['products']               = Product::where('status', '1')->get();
            $data['editPage']               = Page::where('id', $id)->first();
            return view('page::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function Update(Request $request, $id)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $page = Page::where('id', $id)->first();
                $page->update([
                    'product_id'       => $request->product_id,
                    'page_name'        => $request->page_name,
                    'slug'             => Str::slug($request->page_name),
                    'page_heading'     => $request->page_heading,
                    'page_link'        => $request->page_link,
                    'product_overview' => $request->product_overview,
                    'slider_title'     => $request->slider_title,
                    'features'         => $request->features,
                    'old_price'        => $request->old_price ?? null,
                    'new_price'        => $request->new_price,
                    'phone'            => $request->phone,
                    'extra_content'    => $request->extra_content,
                    'status'           => $request->status,
                ]);

                if ($request->file('sliderimage')) {
                    foreach ($request->file('sliderimage') as $imageFile) {
                        $image = $this->imageUpload($imageFile, 'images/page/image/', null, null);
                        PageMultiImage::create([
                            'page_id' => $page->id,
                            'image'   => $image,
                        ]);
                    }
                }
                return response()->json([
                    'status'   => 'success',
                    'message'  => 'Page update successfully',
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
        if (Gate::allows('isAdmin')) {
            $getpage = Page::with(['image'])->where('id', $id)->first();
            if ($getpage->image != null) {
                foreach ($getpage->image as $img) {
                    $this->imageDelete($img->image);
                }
            }
            $getpage->delete();
            return back()->with('success', 'Page Delete Successfully');
        } else {
            abort(401);
        }
    }

    public function galleryImageDelete($id)
    {
        if (Gate::allows('isAdmin')) {
            $imageGallery = PageMultiImage::where('id', $id)->first();
            if ($imageGallery) {
                $this->imageDelete($imageGallery->image);
                $imageGallery->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Page image delete successfully'
            ]);
        } else {
            abort(401);
        }
    }
}
