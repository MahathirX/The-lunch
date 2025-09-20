<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orderreview;
use App\Models\ReviewDetail;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Modules\Brand\App\Models\Brand;
class ReviewController extends Controller
{



    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Review Text'));
            $data['activeProductMenu'] = 'active';
            $data['showProductMenu']   = 'show';
            $data['activeReveiwMenu']  = 'active';
            $data['breadcrumb']        = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Review Text') => ''];
            return view('backend.review.index', $data);
        } else {
            abort(401);
        }

    }

    public function getData(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                $getData = Orderreview::with('user')->latest('id');
                return DataTables::eloquent($getData)
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        if (!empty($request->search)) {
                            $query->when($request->search, function ($query, $value) {
                                $query->where('review', 'like', "%{$value}%")

                                      ->orWhere('status', 'like', "%{$value}%");
                            });
                        }
                    })
                    ->addColumn('name', function ($data) {
                        return $data->user ? $data->user->fname . ' ' . $data->user->lname : 'N/A';
                    })
                    ->addColumn('review', function ($data) {
                        return productreview($data->review);
                    })
                    ->addColumn('review_text', function ($data) {
                        return $data->review_text;
                    })
                    ->addColumn('status', function ($data) {
                        return status($data->status);
                    })
                    ->addColumn('action', function ($data) {
                        if ($data->status == '0') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.review.status', ['id' => $data->id, 'status' => '1']) . '">
                                                <i class="fa-solid fa-check me-2 text-success"></i>Publish</a>';
                        } else if ($data->status == '1') {
                            $statusAction = '<a class="dropdown-item align-items-center" href="' . route('admin.review.status', ['id' => $data->id, 'status' => '0']) . '">
                                                <i class="fa-regular fa-hourglass-half me-2 text-warning"></i>Pending</a>';
                        }

                        return '<div class="btn-group dropstart text-end">
                                    <button type="button" class="btn border-0 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li>' . $statusAction . '</li>
                                        <li>
                                            <button class="dropdown-item align-items-center" onclick="delete_data(' . $data->id . ')">
                                                <i class="fa-solid fa-trash me-2 text-danger"></i>Delete</button>
                                        </li>
                                        <form action="' . route('admin.review.delete', ['id' => $data->id]) . '"
                                              id="delete-form-' . $data->id . '" method="DELETE" class="d-none">
                                              @csrf
                                              @method("DELETE")
                                        </form>
                                    </ul>
                                </div>';
                    })
                    ->rawColumns(['status','image', 'review','action'])
                    ->make(true);
            }
        } else {
            abort(401);
        }
    }

    public function store(Request $request){

        if ($request->ajax()) {
            $request->validate([
                'review'      => 'required',
                'review_text' => 'required',
            ]);

            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                ]);
            }

            $existingReview = Orderreview::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

            if ($existingReview) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'You have already reviewed this product.'
                ]);
            }

            $review =  Orderreview::create([
                'review' => $request->review,
                'review_text' => $request->review_text,
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'status' => '0',

            ]);
            if ($request->file('image')) {
                foreach ($request->file('image') as $imageFile) {
                    $uploadedImage = $this->imageUpload($imageFile, 'images/review/', null, null);
                    ReviewDetail::create([
                        'orderreview_id' => $review->id,
                        'image' => $uploadedImage,
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Product review store successfully'
            ]);


        }
    }
    public function ReviewStatus($id, $status)
    {
        if (Gate::allows('isAdmin')) {
            $getreview = Orderreview::where('id', $id)->first();
            $getreview->update([
                'status' => $status,
            ]);
            return back()->with('success', 'Review Status Change Successfully');
        } else {
            abort(401);
        }
    }
    public function destroy($id)
    {
        if (Gate::allows('isAdmin')) {
            $getreview = Orderreview::where('id', $id)->first();
            $this->imageDelete( $getreview->image);
            $getreview->delete();
            return back()->with('success', 'Review Delete Successfully');
        } else {
            abort(401);
        }
    }
}
