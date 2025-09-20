<?php

namespace Modules\Slider\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Modules\Slider\App\Models\Slider;
use Yajra\DataTables\Facades\DataTables;
use Modules\Slider\App\Http\Requests\SliderRequest;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexSlider()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Slider List Title'));
            $data['activeSliderMenu']       = 'active';
            $data['activeParentSliderMenu'] = 'active';
            $data['showSliderMenu']         = 'show';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Slider List Title') => ''];
            return view('slider::index', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createSlider()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Slider Create Title'));
            $data['activeSliderCreateMenu'] = 'active';
            $data['activeParentSliderMenu'] = 'active';
            $data['showSliderMenu']         = 'show';
            $data['countSlider']            = Slider::get();
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Slider List Title') => route('admin.home.slider'), __f('Slider Create Title') => ''];
            return view('slider::create', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSlider(SliderRequest $request)
    {
        if ($request->ajax()) {
            if ($request->file('slider_image')) {
                $slider_image = $this->imageUpload($request->file('slider_image'), 'images/slider/', null, null);
            } else {
                $slider_image = null;
            }

            if ($request->file('slider_m_image')) {
                $slider_m_image = $this->imageUpload($request->file('slider_m_image'), 'images/slider/', null, null);
            } else {
                $slider_m_image = null;
            }

            Slider::create([
                'status'         => $request->status,
                'slider_image'   => $slider_image,
                'slider_m_image' => $slider_m_image,
                'order_by'       => $request->order_by,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Slider Create Success Message')
            ]);
        }
    }

    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = Slider::latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                // $query->where('title', 'like', "%{$value}%")
                                //     ->orWhere('sub_title', 'like', "%{$value}%");
                            });
                        }
                    })
                    // ->addColumn('title', function ($data) {
                    //     return $data->title;
                    // })
                    // ->addColumn('sub_title', function ($data) {
                    //     return $data->sub_title;
                    // })
                    // ->addColumn('regular_price', function ($data) {
                    //     return convertToLocaleNumber($data->regular_price);
                    // })
                    // ->addColumn('discount_price', function ($data) {
                    //     return convertToLocaleNumber($data->discount_price);
                    // })
                    // ->addColumn('discount_price_sub', function ($data) {
                    //     return convertToLocaleNumber($data->discount_price_sub);
                    // })
                    // ->addColumn('btn_text', function ($data) {
                    //     return $data->btn_text;
                    // })
                    // ->addColumn('btn_url', function ($data) {
                    //     return $data->btn_url;
                    // })
                    // ->addColumn('btn_target', function ($data) {
                    //     return btntaget($data->btn_target);
                    // })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    // ->addColumn('order_by', function ($data) {
                    //     return $data->order_by;
                    // })
                    ->addColumn('slider_image', function ($data) {
                        if ($data->slider_image != null) {
                            return '<a target="_blank" href="' . asset($data->slider_image) . '"><img id="getDataImage" src="' . asset($data->slider_image) . '" alt="image"></a>';
                        } else {
                            return '<img id="getDataImage" src="' . asset('backend/assets/images/blankflats.jpg') . '" alt="image">';
                        }
                    })
                    ->addColumn('slider_m_image', function ($data) {
                        if ($data->slider_m_image != null) {
                            return '<a target="_blank" href="' . asset($data->slider_m_image) . '"><img id="getDataImage" src="' . asset($data->slider_m_image) . '" alt="image"></a>';
                        } else {
                            return '<img id="getDataImage" src="' . asset('backend/assets/images/blankflats.jpg') . '" alt="image">';
                        }
                    })
                    ->addColumn('action', function ($data) {
                        $statusAction = '';
                        if ($data->status == '0') {
                            $statusAction = '
                                <a class="dropdown-item align-items-center" href="' . route('admin.slider.status', ['id' => $data->id, 'status' => '1']) . '">
                                    <i class="fa-solid fa-check me-2 text-success"></i>' . __f("Status Publish Title") . '
                                </a>
                                ';
                        } else if ($data->status == '1') {
                            $statusAction = '
                                <a class="dropdown-item align-items-center" href="' . route('admin.slider.status', ['id' => $data->id, 'status' => '0']) . '">
                                    <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>' . __f("Status Pending Title") . '
                                </a>';
                        }

                        return ' <div class="btn-group dropleft">
                            <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                            <a class="dropdown-item align-items-center" href="' . route('admin.slider.edit', ['id' => $data->id]) . '"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i>' . __f("Edit Title") . '</a>
                            ' . $statusAction . '
                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                            <i class="fa-solid fa-trash me-2 text-danger"></i>' . __f("Delete Title") . '
                            </button><form action="' . route('admin.slider.delete', ['id' => $data->id]) . '"
                            id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                            @csrf
                            @method("DELETE") </form>
                            </div>
                        </div>';
                    })
                    ->rawColumns(['slider_m_image', 'status', 'slider_image', 'action'])
                    ->make(true);
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
        return view('slider::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Slider Edit Title'));
            $data['activeSliderMenu']       = 'active';
            $data['activeParentSliderMenu'] = 'active';
            $data['showSliderMenu']         = 'show';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Slider List Title') => route('admin.home.slider'), __f('Slider Edit Title') => ''];
            $data['slider']                 = Slider::where('id', $id)->first();
            return view('slider::edit', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function sliderUpdate(SliderRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            $getslider = Slider::where('id', $request->id)->first();

            if ($request->file('slider_image')) {
                $this->imageDelete($getslider->slider_image);
                $slider_image = $this->imageUpload($request->file('slider_image'), 'images/slider/', null, null);
            } else {
                $slider_image = $getslider->slider_image;
            }

            if ($request->file('slider_m_image')) {
                 $this->imageDelete($getslider->slider_m_image);
                $slider_m_image = $this->imageUpload($request->file('slider_m_image'), 'images/slider/', null, null);
            } else {
                $slider_m_image = $getslider->slider_m_image;
            }

            $getslider->update([
                'status'         => $request->status,
                'slider_image'   => $slider_image,
                'slider_m_image' => $slider_m_image,
                'order_by'       => $request->order_by,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => __f('Slider Update Success Message'),
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
        if (Gate::allows('isAdmin')) {
            $getSlider = Slider::where('id', $id)->first();
            $this->imageDelete($getSlider->slider_image);
            $getSlider->delete();
            return back()->with('success', __f('Slider Delete Success Message'));
        } else {
            abort(401);
        }
    }


    public function sliderStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $getSlider = Slider::where('id', $id)->first();
            $getSlider->update([
                'status' => $status,
            ]);
            return back()->with('success', __f('Slider Status Change Message'));
        } else {
            abort(401);
        }
    }
}
