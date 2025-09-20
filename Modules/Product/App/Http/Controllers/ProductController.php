<?php

namespace Modules\Product\App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Brand\App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Product\App\Models\Product;
use Yajra\DataTables\Facades\DataTables;
use Modules\Category\App\Models\Category;
use Modules\Attribute\App\Models\Attribute;
use Modules\Product\App\Models\ProductItem;
use Modules\ProductTag\App\Models\ProductTag;
use Modules\Product\App\Models\ProductAttribute;
use Modules\Attribute\App\Models\Attributeoption;
use Modules\ImageGallery\App\Models\ImageGallery;
use Modules\Product\App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Products Title'));
            $data['activeProductMenu'] = 'active';
            $data['activeProductListMenu'] = 'active';
            $data['showProductMenu'] = 'show';
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb'] = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Products Title') => ''];
            } else {
                $data['breadcrumb'] = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Products Title') => ''];
            }
            return view('product::index', $data);
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
            $this->setPageTitle(__f('Product Create Title'));
            $data['activeProductMenu'] = 'active';
            $data['showProductMenu'] = 'show';
            $data['tags'] = ProductTag::where('status', '1')->get();
            $data['brands'] = Brand::where('status', '1')->get();
            $data['categories'] = Category::with('subcategories')->where('status', '1')->where('parent_id', '0')->where('submenu_id', '0')->get();
            $data['colorattributes'] = Attribute::with(['options'])->where('slug', 'color')->where('status', '1')->first();
            $data['sizeattributes'] = Attribute::with(['options'])->where('slug', 'size')->where('status', '1')->first();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb'] = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Products Title') => route('staff.product.index'), __f('Product Create Title') => ''];
                $data['activeProductCreateMenu'] = 'active';
            } else {
                $data['breadcrumb'] = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Products Title') => route('admin.product.index'), __f('Product Create Title') => ''];
                $data['showProductCreateMenu'] = 'active';
            }
            return view('product::create', $data);
        } else {
            abort(401);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            if ($request->ajax()) {
                $getData = Product::with(['productattibute', 'brand'])->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->where(function ($query) use ($request) {
                                $query->where('name', 'like', "%{$request->search}%")
                                    ->orWhere('status', 'like', "%{$request->search}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->name;
                    })
                    ->addColumn('brand', function ($data) {
                        if ($data && $data->brand && $data->brand->name != null) {
                            return $data->brand->name;
                        } else {
                            return '-----';
                        }
                    })
                    ->addColumn('category', function ($data) {

                        if ($data->categories !== null) {
                            $parentCategories = [];
                            foreach ($data->categories as $cat) {
                                if ($cat->parent_id == 0 && $cat->submenu_id == 0) {

                                    $parentCategories[] = '<span class="badge badge-sm bg-success">' . ($cat->name) . '</span>';
                                }
                            }

                            if (!empty($parentCategories)) {
                                return implode(' ', $parentCategories);
                            } else {
                                return '<span class="badge badge-sm bg-success">' . __f('No Parent Categories Title') . '</span>';
                            }
                        }
                        return '<span class="badge badge-sm bg-success">' . __f('No Categories Title') . '</span>';
                    })


                    ->addColumn('producttype', function ($data) {
                        return productType($data->producttype);
                    })
                    ->addColumn('discount_price', function ($data) {
                        return convertToLocaleNumber($data->discount_price);
                    })
                    ->addColumn('regular_price', function ($data) {
                        return convertToLocaleNumber($data->price);
                    })
                    ->addColumn('product_location', function ($data) {
                        return '<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productlocation' . $data->id . '">
                       <i class="fa-solid fa-eye"></i>
                        </button>
                        <div class="modal fade" id="productlocation' . $data->id . '" tabindex="-1" aria-labelledby="productlocationLabel' . $data->id . '" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="productlocationLabel' . $data->id . '">Product Location Modal</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ' . $data->product_location . '
                            </div>
                            </div>
                        </div>
                        </div>';
                    })
                    ->addColumn('product_image', function ($data) {
                        if ($data->product_image != null) {
                            return '<a target="_blank" href="' . asset($data->product_image) . '"><img id="getDataImage" src="' . asset($data->product_image) . '" alt="image"></a>';
                        } else {
                            return '<img id="getDataImage" src="' . asset('backend/assets/img/avatars/blank image.jpg') . '" alt="image">';
                        }
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    // ->addColumn('productcolor', function ($data) {
                    //     if ($data->producttype == '1') {
                    //         $getColorid = $data->productattibute->pluck('color_id');
                    //         $getcolors = Attributeoption::whereIn('id', $getColorid)->pluck('attribute_option');
                    //         if ($getcolors) {
                    //             return $getcolors->implode(', ');
                    //         } else {
                    //             return '-----------';
                    //         }
                    //     } else {
                    //         return '-----------';
                    //     }
                    // })
                    // ->addColumn('productsize', function ($data) {
                    //     if ($data->producttype == '1') {
                    //         $getSizeid = $data->productattibute->pluck('size_id');
                    //         $getsizes = Attributeoption::whereIn('id', $getSizeid)->pluck('attribute_option');
                    //         if ($getsizes) {
                    //             return $getsizes->implode(', ');
                    //         } else {
                    //             return '-----------';
                    //         }
                    //     } else {
                    //         return '-----------';
                    //     }
                    // })
                    ->addColumn('action', function ($data) {
                        if (Auth::check() && Auth::user()->role_id == 3) {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.product.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>' . __f("Status Publish Title") . '</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('staff.product.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>' . __f("Status Pending Title") . '</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('staff.product.edit', ['product' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>' . __f("Edit Title") . '</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>' . __f("Delete Title") . '</button>
                                            </li>
                                        <form action="' . route('staff.product.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                        </form>
                                        </ul>
                                    </div>';
                        } else {
                            if ($data->status == '0') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.product.status', ['id' => $data->id, 'status' => '1']) . '">
                                                    <i class="fa-solid fa-check me-2 text-success"></i>' . __f("Status Publish Title") . '</a>';
                            } else if ($data->status == '1') {
                                $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.product.status', ['id' => $data->id, 'status' => '0']) . '">
                                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>' . __f("Status Pending Title") . '</a>';
                            }

                            return '<div class="btn-group dropstart text-end">
                                        <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item align-items-center" href="' . route('admin.product.edit', ['product' => $data->id]) . '">
                                                    <i class="fa-solid fa-pen-to-square me-2 text-primary"></i>' . __f("Edit Title") . '</a>
                                            </li>
                                            <li>' . $statusAction . '</li>
                                            <li>
                                                <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>' . __f("Delete Title") . '</button>
                                            </li>
                                        <form action="' . route('admin.product.delete', ['id' => $data->id]) . '"
                                                id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                                @csrf
                                                @method("DELETE")
                                        </form>
                                        </ul>
                                    </div>';
                        }
                    })
                    ->rawColumns(['product_location', 'producttype', 'category', 'status', 'product_image', 'action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(ProductRequest $request)
{
    if (!Gate::any(['isAdmin', 'isStaff'])) {
        abort(401);
    }

    if ($request->ajax()) {

        // Single product image upload
        $image = $request->file('product_image')
            ? $this->imageUpload($request->file('product_image'), 'images/product/', null, null)
            : null;

        // Create product
        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'discount_price' => $request->discount_price,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
            'product_image' => $image,
            'product_stock_qty' => $request->product_stock_qty,
            'product_sales_qty' => $request->product_sales_qty ?? 0,
            'product_sku' => $request->product_sku,
            'product_location' => $request->product_location,
            'short_description' => $request->short_description,
            'special_feature' => $request->special_feature,
            'tag' => $request->tag ?? null,
            'producttype' => $request->producttype,
            'brand_id' => $request->brand_id ?? null,
            'available_date' => $request->available_date,
            'package_type' => $request->package_type,
        ]);

        // Save Items
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                ProductItem::create([
                    'product_id' => $product->id,
                    'item_name' => $item['item_name'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }
        }

        // Sync categories
        if ($request->has('productcategory_id')) {
            $product->categories()->sync($request->productcategory_id);
        }

        // Multiple gallery images
        if ($request->file('product_gallery')) {
            foreach ($request->file('product_gallery') as $imageFile) {
                $uploadedImage = $this->imageUpload($imageFile, 'images/gallery/', null, null);
                ImageGallery::create([
                    'product_id' => $product->id,
                    'image_path' => $uploadedImage,
                ]);
            }
        }

        // Color & Size attributes
        if ($request->producttype == '1') {
            foreach ($request->colorattributes as $key => $color) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'color_id' => $color,
                    'size_id' => $request->sizeattributes[$key],
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => __f('Product Create Success Message')
        ]);
    }
}




    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $this->setPageTitle(__f('Products Edit Title'));
            $data['activeProductMenu'] = 'active';
            $data['activeProductListMenu'] = 'active';
            $data['showProductMenu'] = 'show';
            $data['tags'] = ProductTag::where('status', '1')->get();
            $data['brands'] = Brand::where('status', '1')->get();
            $data['categories'] = Category::with('subcategories')->where('status', '1')->where('parent_id', '0')->where('submenu_id', '0')->get();
            $data['colorattributes'] = Attribute::with(['options'])->where('slug', 'color')->where('status', '1')->first();
            $data['sizeattributes'] = Attribute::with(['options'])->where('slug', 'size')->where('status', '1')->first();
            if (Auth::check() && Auth::user()->role_id == 3) {
                $data['breadcrumb'] = [__f('Staff Dashboard Title') => route('staff.dashboard.index'), __f('Products Title') => route('staff.product.index'), __f('Products Edit Title') => ''];
            } else {
                $data['breadcrumb'] = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Products Title') => route('admin.product.index'), __f('Products Edit Title') => ''];
            }
            $data['product'] = Product::with(['categories', 'productattibute'])->where('id', $id)->first();
            $data['imageGallery'] = ImageGallery::where('product_id', $id)->get();
            return view('product::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function Update(ProductRequest $request)
    {
        // dd($request->all());
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getproduct = Product::where('id', $request->id)->first();
            if ($request->file('product_image')) {
                $this->imageDelete($getproduct->product_image);
                $image = $this->imageUpload($request->file('product_image'), 'images/product/', null, null);
            } else {
                $image = $getproduct->product_image;
            }
            $getproduct->update([
                'name' => $request->name,
                'description' => $request->description,
                'product_image' => $image,
                'price' => $request->price,
                'discount_price' => $request->discount_price,
                'status' => $request->status,
                'product_stock_qty' => $request->product_stock_qty,
                'product_sales_qty' => $request->product_sales_qty,
                'product_sku' => $request->product_sku,
                'short_description' => $request->short_description,
                'product_location' => $request->product_location,
                'special_feature' => $request->special_feature,
                'tag' => $request->tag ?? null,
                'brand_id' => $request->brand_id ?? null,
            ]);

            // Sync the selected categories
            if ($request->has('productcategory_id')) {
                $getproduct->categories()->sync($request->productcategory_id); // Sync categories
            }


            if ($request->hasFile('product_gallery')) {
                foreach ($request->file('product_gallery') as $gallery) {
                    $image = $this->imageUpload($gallery, 'images/gallery/', null, null);
                    ImageGallery::create([
                        'product_id' => $getproduct->id,
                        'image_path' => $image,

                    ]);
                }
            }

            if ($request->producttype == '1') {
                $getattribute = ProductAttribute::where('product_id', $getproduct->id)->get();
                if ($getattribute) {
                    foreach ($getattribute as $key => $attribute) {
                        $attribute->delete();
                    }
                }
                foreach ($request->colorattributes as $key => $color) {
                    ProductAttribute::create([
                        'product_id' => $getproduct->id,
                        'color_id' => $color,
                        'size_id' => $request->sizeattributes[$key],
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => __f('Product Update Success Message')
            ]);
        } else {
            abort(401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getProduct = Product::where('id', $id)->first();
            $this->imageDelete($getProduct->product_image);
            $imageGallery = ImageGallery::where('product_id', $id)->get();
            foreach ($imageGallery as $gallery) {
                $this->imageDelete($gallery->image_path);
            }
            $getProduct->delete();
            return back()->with('success', __f('Product Delete Success Message'));
        } else {
            abort(401);
        }
    }


    public function productStatus($id, $status)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $getProduct = Product::where('id', $id)->first();
            $getProduct->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Product Status Change Message'));
        } else {
            abort(401);
        }
    }

    public function galleryImageDelete($id)
    {
        if (Gate::any(['isAdmin', 'isStaff'])) {
            $imageGallery = ImageGallery::where('id', $id)->first();
            if ($imageGallery) {
                $this->imageDelete($imageGallery->image_path);
                $imageGallery->delete();
            }
            return response()->json([
                'status' => 'success',
                'message' => __f('Product Gallery image delete Message')
            ]);
        } else {
            abort(401);
        }
    }
}
