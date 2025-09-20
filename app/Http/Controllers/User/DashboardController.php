<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserProfileUpdateRequest;
use App\Models\Order;
use App\Models\Orderreview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Coupon\App\Models\CouponUses;

class DashboardController extends Controller
{
    public function index(){
        $this->setPageTitle(__f('Dashboard Title'));
        $data['orders']         = Order::with('orderdetails')->where('user_id',Auth::id())->get();
        $data['coupons']        = CouponUses::with('coupon')->where('user_id',Auth::id())->get();
        $data['productreviews'] = Orderreview::with(['product','reviewDetails'])->where('user_id',Auth::id())->get();
        return view('frontend.userdashboard.index',$data);
    }

    public function dashboardUpdate(UserProfileUpdateRequest $request){
        $user = User::findOrFail(Auth::id());
        if ($request->file('avater')) {
            $avater = $this->imageUpload($request->file('avater'), 'images/user/', null, null);
        } else {
            $avater = Auth::user()->avater;
        }
        $user->update([
            'fname'        => $request->fname,
            'lname'        => $request->lname,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'house_number' => $request->house_number,
            'city'         => $request->city,
            'state'        => $request->state,
            'zip'          => $request->zip,
            'avater'       => $avater,
        ]);
        return response()->json([
            'status'  => 'success',
            'message' => 'Profile Updated Successfully',
        ]);
    }

    public function dashboardPasswordUpdate(UpdatePasswordRequest $request)
    {
        $user = User::findOrFail(Auth::id());
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Current password does not match.',
                ]);
            }
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password Updated Successfully',
        ]);
    }
}
