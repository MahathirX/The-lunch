<?php

namespace Modules\Setting\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Product\App\Models\Product;
use Modules\Setting\App\Models\Setting;
use Modules\Category\App\Models\Category;
use Modules\Setting\App\Http\Requests\CategoryProductRequest;
use Modules\Setting\App\Http\Requests\TopSellingProductRequest;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Settings Title'));
            $data['showThemeMenu']          = 'show';
            $data['activeThemeMenu']        = 'active';
            $data['activeThemeSettingMenu'] = 'active';
            $data['categories']             = Category::where('status', '1')->where('parent_id', '0')->where('submenu_id', '0')->get();
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Settings Title') => ''];
            $data['products']               = Product::where('status', '1')->get();
            return view('setting::index', $data);
        } else {
            abort(401);
        }
    }

    public function headerSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Header Style Setting Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeHeaderStyleMenu']  = 'active';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Header Style Setting Title') => ''];
            return view('setting::headersectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function headerSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'headerchosevalue'], ['option_value' => $request->headerchosevalue ?? 4]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Header Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }


    public function sliderSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Slider Style Setting Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeSliderStyleMenu']  = 'active';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Slider Style Setting Title') => ''];
            return view('setting::slidersectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sliderSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'slider_show_value_in_home'], ['option_value' => $request->slider_show_value_in_home ?? 4]);
                if ($request->sliderchosevalue == 1) {
                    Setting::updateOrCreate(['option_key' => 'sliderchosevalue'], ['option_value' => $request->sliderchosevalue ?? 1]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Slider Style Update Success Message'),
                    ]);
                } else if ($request->sliderchosevalue == 2) {
                    $rules = [];

                    if ($request->hasFile('slider_style_two_right_first_image')) {
                        // , 'dimensions:width=310,height=240'
                        $rules['slider_style_two_right_first_image'] = ['image'];
                    }

                    if ($request->hasFile('slider_style_two_right_second_image')) {
                        // , 'dimensions:width=310,height=240'
                        $rules['slider_style_two_right_second_image'] = ['image'];
                    }
                    $request->validate($rules);

                    if ($request->file('slider_style_two_right_first_image')) {
                        $slider_style_two_right_first_image = $this->imageUpload($request->file('slider_style_two_right_first_image'), 'images/slider/', null, null);
                    } else {
                        $slider_style_two_right_first_image = config('settings.slider_style_two_right_first_image');
                    }
                    if ($request->file('slider_style_two_right_second_image')) {
                        $slider_style_two_right_second_image = $this->imageUpload($request->file('slider_style_two_right_second_image'), 'images/slider/', null, null);
                    } else {
                        $slider_style_two_right_second_image = config('settings.slider_style_two_right_second_image');
                    }
                    Setting::updateOrCreate(['option_key' => 'sliderchosevalue'], ['option_value' => $request->sliderchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'slider_style_two_right_first_image'], ['option_value' => $slider_style_two_right_first_image ?? null]);
                    Setting::updateOrCreate(['option_key' => 'slider_style_two_right_second_image'], ['option_value' => $slider_style_two_right_second_image ?? null]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Slider Style Update Success Message'),
                    ]);
                } else if ($request->sliderchosevalue == 3) {
                    $rules = [];

                    if ($request->hasFile('slider_style_three_right_image')) {
                        // , 'dimensions:width=310,height=400'
                        $rules['slider_style_three_right_image'] = ['image'];
                    }

                    $request->validate($rules);

                    if ($request->file('slider_style_three_right_image')) {
                        $slider_style_three_right_image = $this->imageUpload($request->file('slider_style_three_right_image'), 'images/slider/', null, null);
                    } else {
                        $slider_style_three_right_image = config('settings.slider_style_three_right_image');
                    }

                    Setting::updateOrCreate(['option_key' => 'sliderchosevalue'], ['option_value' => $request->sliderchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'slider_style_three_right_image'], ['option_value' => $slider_style_three_right_image ?? null]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Slider Style Update Success Message'),
                    ]);
                } else if ($request->sliderchosevalue == 4) {
                    $rules = [];

                    if ($request->hasFile('slider_style_four_right_first_image')) {
                        // , 'dimensions:width=310,height=240'
                        $rules['slider_style_four_right_first_image'] = ['image'];
                    }

                    if ($request->hasFile('slider_style_four_right_second_image')) {
                        // , 'dimensions:width=310,height=240'
                        $rules['slider_style_four_right_second_image'] = ['image'];
                    }
                    $request->validate($rules);

                    if ($request->file('slider_style_four_right_first_image')) {
                        $slider_style_four_right_first_image = $this->imageUpload($request->file('slider_style_four_right_first_image'), 'images/slider/', null, null);
                    } else {
                        $slider_style_four_right_first_image = config('settings.slider_style_four_right_first_image');
                    }

                    if ($request->file('slider_style_four_right_second_image')) {
                        $slider_style_four_right_second_image = $this->imageUpload($request->file('slider_style_four_right_second_image'), 'images/slider/', null, null);
                    } else {
                        $slider_style_four_right_second_image = config('settings.slider_style_four_right_second_image');
                    }
                    Setting::updateOrCreate(['option_key' => 'sliderchosevalue'], ['option_value' => $request->sliderchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'slider_style_four_right_first_image'], ['option_value' => $slider_style_four_right_first_image ?? null]);
                    Setting::updateOrCreate(['option_key' => 'slider_style_four_right_second_image'], ['option_value' => $slider_style_four_right_second_image ?? null]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Slider Style Update Success Message'),
                    ]);
                } else {
                    Setting::updateOrCreate(['option_key' => 'sliderchosevalue'], ['option_value' => $request->sliderchosevalue ?? 1]);
                    return response()->json([
                        'status'  => 'success',
                        'message' => __f('Slider Style Update Success Message'),
                    ]);
                }
            }
        } else {
            abort(401);
        }
    }

    public function categorySectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Category Style Setting Title'));
            $data['showSliderMenu']          = 'show';
            $data['activeParentSliderMenu']  = 'active';
            $data['activeCategoryStyleMenu'] = 'active';
            $data['breadcrumb']              = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Category Style Setting Title') => ''];
            return view('setting::categorysectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function categorySectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'categorychosevalue'], ['option_value' => $request->categorychosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Category Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function productCardSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Product Card Style Setting Title'));
            $data['showSliderMenu']             = 'show';
            $data['activeParentSliderMenu']     = 'active';
            $data['activeProductCardStyleMenu'] = 'active';
            $data['breadcrumb']                 = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Product Card Style Setting Title') => ''];
            return view('setting::productcardsectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function productCardSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'number_of_card_show_in_home'], ['option_value' => $request->number_of_card_show_in_home ?? 4]);
                Setting::updateOrCreate(['option_key' => 'productnavbarpositionchosevalue'], ['option_value' => $request->productnavbarpositionchosevalue ?? 2]);
                Setting::updateOrCreate(['option_key' => 'productnavbarshowchosevalue'], ['option_value' => $request->productnavbarshowchosevalue ?? 2]);
                Setting::updateOrCreate(['option_key' => 'productdottedshowchosevalue'], ['option_value' => $request->productdottedshowchosevalue ?? 2]);
                Setting::updateOrCreate(['option_key' => 'productcardchosevalue'], ['option_value' => $request->productcardchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Product Card Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function brandectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Brand Style Setting Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeBrandStyleMenu']   = 'active';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Brand Style Setting Title') => ''];
            return view('setting::brandsectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function brandSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'number_of_card_show_in_home_brand'], ['option_value' => $request->number_of_card_show_in_home_brand ?? 6]);
                Setting::updateOrCreate(['option_key' => 'bradnavbarpositionchosevalue'], ['option_value' => $request->bradnavbarpositionchosevalue ?? 2]);
                Setting::updateOrCreate(['option_key' => 'brandnavbarshowchosevalue'], ['option_value' => $request->brandnavbarshowchosevalue ?? 2]);
                Setting::updateOrCreate(['option_key' => 'branddottedshowchosevalue'], ['option_value' => $request->branddottedshowchosevalue ?? 2]);
                Setting::updateOrCreate(['option_key' => 'brandchosevalue'], ['option_value' => $request->brandchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Brand Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function subcribeSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Subcribe Style Setting Title'));
            $data['showSliderMenu']          = 'show';
            $data['activeParentSliderMenu']  = 'active';
            $data['activeSubcribeStyleMenu'] = 'active';
            $data['breadcrumb']              = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Subcribe Style Setting Title') => ''];
            return view('setting::subcribesectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function subcribeSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'subcribechosevalue'], ['option_value' => $request->subcribechosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Subcribe Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function footerSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Footer Style Setting Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeFooterStyleMenu']  = 'active';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Footer Style Setting Title') => ''];
            return view('setting::footersectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function footerSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'footerchosevalue'], ['option_value' => $request->footerchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Footer Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function breadcrumbSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Breadcrumb Style Setting Title'));
            $data['showSliderMenu']         = 'show';
            $data['activeParentSliderMenu'] = 'active';
            $data['activeBreadcrumbStyleMenu']  = 'active';
            $data['breadcrumb']             = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Breadcrumb Style Setting Title') => ''];
            return view('setting::breadcrumbsectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function breadcrumbSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'breadcrumb_container_size'], ['option_value' => $request->breadcrumb_container_size ?? 1]);
                Setting::updateOrCreate(['option_key' => 'breadcrumchosevalue'], ['option_value' => $request->breadcrumchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Breadcrumb Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function productDetailsSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Product Details Page Style Setting Title'));
            $data['showSliderMenu']                    = 'show';
            $data['activeParentSliderMenu']            = 'active';
            $data['activeProductDetailsPageStyleMenu'] = 'active';
            $data['breadcrumb']                        = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Product Details Page Style Setting Title') => ''];
            return view('setting::productdestailspagesectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function productDetailsSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'productdetailspagechosevalue'], ['option_value' => $request->productdetailspagechosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'productpreviousnextshowchosevalue'], ['option_value' => $request->productpreviousnextshowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'productdetailscategoryshowchosevalue'], ['option_value' => $request->productdetailscategoryshowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'productdetailsbrandshowchosevalue'], ['option_value' => $request->productdetailsbrandshowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'productdetailsmpdalimageshowchosevalue'], ['option_value' => $request->productdetailsmpdalimageshowchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Product Details Page Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function categoryDetailsSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Category Details Page Style Setting Title'));
            $data['showSliderMenu']                    = 'show';
            $data['activeParentSliderMenu']            = 'active';
            $data['activeCategoryDetailsPageStyleMenu'] = 'active';
            $data['breadcrumb']                        = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Category Details Page Style Setting Title') => ''];
            return view('setting::categorydestailspagesectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function categoryDetailsSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'categorydetailsfilltershowchosevalue'], ['option_value' => $request->categorydetailsfilltershowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'categorydetailsbrandfilltershowchosevalue'], ['option_value' => $request->categorydetailsbrandfilltershowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'categorydetailspricefilltershowchosevalue'], ['option_value' => $request->categorydetailspricefilltershowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'categorydetailssortbyfilltershowchosevalue'], ['option_value' => $request->categorydetailssortbyfilltershowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'categorydetailscardstyleshowchosevalue'], ['option_value' => $request->categorydetailscardstyleshowchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'categorydetailspagestylechosevalue'], ['option_value' => $request->categorydetailspagestylechosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'categorydetailsnavbarposition'], ['option_value' => $request->categorydetailsnavbarposition ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Category Details Page Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function loginSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Login And Register Page Style Setting Title'));
            $data['showSliderMenu']           = 'show';
            $data['activeParentSliderMenu']   = 'active';
            $data['activeLoginPageStyleMenu'] = 'active';
            $data['breadcrumb']               = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Login And Register Page Style Setting Title') => ''];
            return view('setting::loginsectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function loginSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->loginpagestyleshowchosevalue == 2) {
                    $rules = [];

                    if ($request->hasFile('login_two_bg_image')) {
                        $rules['login_two_bg_image'] = ['image'];
                    }

                    if ($request->file('login_two_bg_image')) {
                        $login_two_bg_image = $this->imageUpload($request->file('login_two_bg_image'), 'images/login/', null, null);
                    } else {
                        $login_two_bg_image = config('settings.login_two_bg_image');
                    }
                    Setting::updateOrCreate(['option_key' => 'loginheadershowchosevalue'], ['option_value' => $request->loginheadershowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginbreadcrumbshowchosevalue'], ['option_value' => $request->loginbreadcrumbshowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginfootershowchosevalue'], ['option_value' => $request->loginfootershowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginpagestyleshowchosevalue'], ['option_value' => $request->loginpagestyleshowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'login_two_bg_image'], ['option_value' => $login_two_bg_image ?? null]);
                } else if ($request->loginpagestyleshowchosevalue == 3) {
                    $rules = [];

                    if ($request->hasFile('login_page_three_bg_image')) {
                        $rules['login_page_three_bg_image'] = ['image'];
                    }
                    if ($request->hasFile('login_page_three_right_image')) {
                        $rules['login_page_three_right_image'] = ['image'];
                    }

                    if ($request->file('login_page_three_bg_image')) {
                        $login_page_three_bg_image = $this->imageUpload($request->file('login_page_three_bg_image'), 'images/login/', null, null);
                    } else {
                        $login_page_three_bg_image = config('settings.login_page_three_bg_image');
                    }

                    if ($request->file('login_page_three_right_image')) {
                        $login_page_three_right_image = $this->imageUpload($request->file('login_page_three_right_image'), 'images/login/', null, null);
                    } else {
                        $login_page_three_right_image = config('settings.login_page_three_right_image');
                    }
                    Setting::updateOrCreate(['option_key' => 'loginheadershowchosevalue'], ['option_value' => $request->loginheadershowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginbreadcrumbshowchosevalue'], ['option_value' => $request->loginbreadcrumbshowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginfootershowchosevalue'], ['option_value' => $request->loginfootershowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginpagestyleshowchosevalue'], ['option_value' => $request->loginpagestyleshowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'login_page_three_bg_image'], ['option_value' => $login_page_three_bg_image ?? null]);
                    Setting::updateOrCreate(['option_key' => 'login_page_three_right_image'], ['option_value' => $login_page_three_right_image ?? null]);
                } else {
                    Setting::updateOrCreate(['option_key' => 'loginheadershowchosevalue'], ['option_value' => $request->loginheadershowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginbreadcrumbshowchosevalue'], ['option_value' => $request->loginbreadcrumbshowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginfootershowchosevalue'], ['option_value' => $request->loginfootershowchosevalue ?? 1]);
                    Setting::updateOrCreate(['option_key' => 'loginpagestyleshowchosevalue'], ['option_value' => $request->loginpagestyleshowchosevalue ?? 1]);
                }
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Login And Register Page Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function checkOutSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Check Out Page Style Setting Title'));
            $data['showSliderMenu']              = 'show';
            $data['activeParentSliderMenu']      = 'active';
            $data['activeCheckOutPageStyleMenu'] = 'active';
            $data['breadcrumb']                  = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Check Out Page Style Setting Title') => ''];
            return view('setting::checkoutsectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function checkOutSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'checkoutpagefrompositionchosevalue'], ['option_value' => $request->checkoutpagefrompositionchosevalue ?? 1]);
                Setting::updateOrCreate(['option_key' => 'checkoutpageshowchosevalue'], ['option_value' => $request->checkoutpageshowchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Check Out Page Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function viewCartSectionStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('View Cart Page Style Setting Title'));
            $data['showSliderMenu']              = 'show';
            $data['activeParentSliderMenu']      = 'active';
            $data['activeViewCartPageStyleMenu'] = 'active';
            $data['breadcrumb']                  = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('View Cart Page Style Setting Title') => ''];
            return view('setting::viewcartsectionstyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function viewCartSectionStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'viewcartpageshowchosevalue'], ['option_value' => $request->viewcartpageshowchosevalue ?? 1]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('View Cart Page Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function sectionShowHideStyle()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Section Show Hide Setting Title'));
            $data['showSliderMenu']                 = 'show';
            $data['activeParentSliderMenu']         = 'active';
            $data['activeSectionShowHideStyleMenu'] = 'active';
            $data['breadcrumb']                     = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Section Show Hide Setting Title') => ''];
            return view('setting::sectionshowhidestyle', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sectionShowHideStyleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                // Setting::updateOrCreate(['option_key' => 'hedershowchosevalue'], ['option_value' => $request->hedershowchosevalue ?? config('settings.hedershowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'navbarshowchosevalue'], ['option_value' => $request->navbarshowchosevalue ?? config('settings.navbarshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'herosectionshowchosevalue'], ['option_value' => $request->herosectionshowchosevalue ?? config('settings.herosectionshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'categoriessectionshowchosevalue'], ['option_value' => $request->categoriessectionshowchosevalue ?? config('settings.categoriessectionshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'newproductsectionshowchosevalue'], ['option_value' => $request->newproductsectionshowchosevalue ?? config('settings.newproductsectionshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'dynamicproductsectionshowchosevalue'], ['option_value' => $request->dynamicproductsectionshowchosevalue ?? config('settings.dynamicproductsectionshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'brandsectionshowchosevalue'], ['option_value' => $request->brandsectionshowchosevalue ?? config('settings.brandsectionshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'subscribesectionshowchosevalue'], ['option_value' => $request->subscribesectionshowchosevalue ?? config('settings.subscribesectionshowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'footershowchosevalue'], ['option_value' => $request->footershowchosevalue ?? config('settings.footershowchosevalue')]);
                Setting::updateOrCreate(['option_key' => 'breadcrumbshowchosevalue'], ['option_value' => $request->breadcrumbshowchosevalue ?? config('settings.breadcrumbshowchosevalue')]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Section Show Hide Style Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function SectionTitleStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'menu_title'], ['option_value' => $request->menu_title]);
                Setting::updateOrCreate(['option_key' => 'development_by'], ['option_value' => $request->development_by]);
                Setting::updateOrCreate(['option_key' => 'category_title'], ['option_value' => $request->category_title]);
                Setting::updateOrCreate(['option_key' => 'deals_product_title'], ['option_value' => $request->deals_product_title]);
                Setting::updateOrCreate(['option_key' => 'bands_title'], ['option_value' => $request->bands_title]);
                Setting::updateOrCreate(['option_key' => 'productsinglepagewarningtext'], ['option_value' => $request->productsinglepagewarningtext]);
                Setting::updateOrCreate(['option_key' => 'product_singpage_title'], ['option_value' => $request->product_singpage_title]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Section Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function invoiceNoteStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->file('authority_signature_image')) {
                    $authority_signature_image = $this->imageUpload($request->file('authority_signature_image'), 'images/company/', null, null);
                } else {
                    $authority_signature_image = config('settings.authority_signature_image');
                }
                Setting::updateOrCreate(['option_key' => 'sales_invoice_note'], ['option_value' => $request->sales_invoice_note]);
                Setting::updateOrCreate(['option_key' => 'purchase_invoice_note'], ['option_value' => $request->purchase_invoice_note]);
                Setting::updateOrCreate(['option_key' => 'authority_signature_status'], ['option_value' => $request->authority_signature_status]);
                Setting::updateOrCreate(['option_key' => 'invoicefootertext'], ['option_value' => $request->invoicefootertext]);
                Setting::updateOrCreate(['option_key' => 'authority_signature_image'], ['option_value' => $authority_signature_image]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Invoice Note Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function categorySectionStore(CategoryProductRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'category_product'], ['option_value' => json_encode($request->category_product)]);
                Setting::updateOrCreate(['option_key' => 'category_section'], ['option_value' => json_encode($request->category_section)]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Section setting update successfully !',
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function cartPageStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'cartpagetitle'], ['option_value' => $request->cartpagetitle]);
                Setting::updateOrCreate(['option_key' => 'cartpagewarningtext'], ['option_value' => $request->cartpagewarningtext]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Cart Page Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function accountPageStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'logintitle'], ['option_value' => $request->logintitle]);
                Setting::updateOrCreate(['option_key' => 'loginpageregistertitle'], ['option_value' => $request->loginpageregistertitle]);
                Setting::updateOrCreate(['option_key' => 'loginpageregisterdescription'], ['option_value' => $request->loginpageregisterdescription]);
                Setting::updateOrCreate(['option_key' => 'registerpagewarningtext'], ['option_value' => $request->registerpagewarningtext]);
                Setting::updateOrCreate(['option_key' => 'registerpagefirsttitle'], ['option_value' => $request->registerpagefirsttitle]);
                Setting::updateOrCreate(['option_key' => 'registerpagesecondtitle'], ['option_value' => $request->registerpagesecondtitle]);
                Setting::updateOrCreate(['option_key' => 'registerpagethirdtitle'], ['option_value' => $request->registerpagethirdtitle]);
                Setting::updateOrCreate(['option_key' => 'forgotpagetitle'], ['option_value' => $request->forgotpagetitle]);
                Setting::updateOrCreate(['option_key' => 'forgotpagewarningtext'], ['option_value' => $request->forgotpagewarningtext]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Account Page Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function socalMediaStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'facebookurl'], ['option_value' => $request->facebookurl]);
                Setting::updateOrCreate(['option_key' => 'instagramurl'], ['option_value' => $request->instagramurl]);
                Setting::updateOrCreate(['option_key' => 'youtubeurl'], ['option_value' => $request->youtubeurl]);
                Setting::updateOrCreate(['option_key' => 'linkedinurl'], ['option_value' => $request->linkedinurl]);
                Setting::updateOrCreate(['option_key' => 'twitterurl'], ['option_value' => $request->twitterurl]);
                Setting::updateOrCreate(['option_key' => 'pinteresturl'], ['option_value' => $request->pinteresturl]);
                Setting::updateOrCreate(['option_key' => 'skypeurl'], ['option_value' => $request->skypeurl]);
                Setting::updateOrCreate(['option_key' => 'whatsappurl'], ['option_value' => $request->whatsappurl]);
                Setting::updateOrCreate(['option_key' => 'redditurl'], ['option_value' => $request->redditurl]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Socal Page Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    public function topSellingProduct(TopSellingProductRequest $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {

                Setting::updateOrCreate(['option_key' => 'topsellingproducts'], ['option_value' => json_encode($request->topsellingproducts)]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Top selling products setting update successfully !',
                ]);
            }
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
            if ($request->ajax()) {
                if ($request->file('company_primary_logo')) {
                    $company_primary_logo = $this->imageUploadWithCrop($request->file('company_primary_logo'), 'images/company/dynamic/', null, null);
                } else {
                    $company_primary_logo = config('settings.company_primary_logo');
                }

                if ($request->file('company_secondary_logo')) {
                    $company_secondary_logo = $this->imageUploadWithCrop($request->file('company_secondary_logo'), 'images/company/dynamic/', null, null);
                } else {
                    $company_secondary_logo = config('settings.company_secondary_logo');
                }

                if ($request->file('favicon_first')) {
                    $favicon_first = $this->imageUpload($request->file('favicon_first'), 'images/company/', null, null);
                } else {
                    $favicon_first = config('settings.favicon_first');
                }

                if ($request->file('favicon_second')) {
                    $favicon_second = $this->imageUpload($request->file('favicon_second'), 'images/company/', null, null);
                } else {
                    $favicon_second = config('settings.favicon_second');
                }

                if ($request->file('breadcrumbbackgroundimage')) {
                    $breadcrumbbackgroundimage = $this->imageUpload($request->file('breadcrumbbackgroundimage'), 'images/company/', null, null);
                } else {
                    $breadcrumbbackgroundimage = config('settings.breadcrumbbackgroundimage');
                }


                Setting::updateOrCreate(['option_key' => 'company_name'], ['option_value' => $request->company_name]);
                Setting::updateOrCreate(['option_key' => 'company_email'], ['option_value' => $request->company_email]);
                Setting::updateOrCreate(['option_key' => 'company_cell'], ['option_value' => $request->company_cell]);
                Setting::updateOrCreate(['option_key' => 'company_copy_right'], ['option_value' => $request->company_copy_right]);
                Setting::updateOrCreate(['option_key' => 'currency'], ['option_value' => $request->currency]);
                Setting::updateOrCreate(['option_key' => 'browse_cetegories_title'], ['option_value' => $request->browse_cetegories_title]);
                Setting::updateOrCreate(['option_key' => 'mmenutitle'], ['option_value' => $request->mmenutitle]);
                Setting::updateOrCreate(['option_key' => 'msectiontitle'], ['option_value' => $request->msectiontitle]);
                Setting::updateOrCreate(['option_key' => 'price_min_range'], ['option_value' => $request->price_min_range]);
                Setting::updateOrCreate(['option_key' => 'price_max_range'], ['option_value' => $request->price_max_range]);
                Setting::updateOrCreate(['option_key' => 'deliveryinsidedhake'], ['option_value' => $request->deliveryinsidedhake]);
                Setting::updateOrCreate(['option_key' => 'admincontactmail'], ['option_value' => $request->admincontactmail]);
                Setting::updateOrCreate(['option_key' => 'subcribetitle'], ['option_value' => $request->subcribetitle]);
                Setting::updateOrCreate(['option_key' => 'subsribesdecription'], ['option_value' => $request->subsribesdecription]);
                Setting::updateOrCreate(['option_key' => 'addtocartbtntitle'], ['option_value' => $request->addtocartbtntitle]);
                Setting::updateOrCreate(['option_key' => 'deliveryoutsidedhake'], ['option_value' => $request->deliveryoutsidedhake]);
                Setting::updateOrCreate(['option_key' => 'company_primary_logo'], ['option_value' => $company_primary_logo]);
                Setting::updateOrCreate(['option_key' => 'company_secondary_logo'], ['option_value' => $company_secondary_logo]);
                Setting::updateOrCreate(['option_key' => 'favicon_first'], ['option_value' => $favicon_first]);
                Setting::updateOrCreate(['option_key' => 'favicon_second'], ['option_value' => $favicon_second]);
                Setting::updateOrCreate(['option_key' => 'breadcrumbbackgroundimage'], ['option_value' => $breadcrumbbackgroundimage]);
                Setting::updateOrCreate(['option_key' => 'ordersuccesstext'], ['option_value' => $request->ordersuccesstext]);
                Setting::updateOrCreate(['option_key' => 'homemetatitle'], ['option_value' => $request->homemetatitle]);
                Setting::updateOrCreate(['option_key' => 'homemetadescription'], ['option_value' => $request->homemetadescription]);
                Setting::updateOrCreate(['option_key' => 'homemetakeyword'], ['option_value' => $request->homemetakeyword]);
                Setting::updateOrCreate(['option_key' => 'pixcelsetupcode'], ['option_value' => $request->pixcelsetupcode]);
                Setting::updateOrCreate(['option_key' => 'commingsoonmode'], ['option_value' => $request->commingsoonmode]);
                $this->changeEnvData([
                    'APP_NAME' => str_replace(' ', '-', $request->company_name),
                ]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Company Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function footerSectionStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                if ($request->file('footer_payment_image')) {
                    $footer_payment_image = $this->imageUploadWithCrop($request->file('footer_payment_image'), 'images/company/dynamic/', null, null);
                } else {
                    $footer_payment_image = config('settings.footer_payment_image');
                }

                Setting::updateOrCreate(['option_key' => 'footer_payment_image'], ['option_value' => $footer_payment_image]);
                Setting::updateOrCreate(['option_key' => 'footer_description_text'], ['option_value' => $request->footer_description_text]);
                Setting::updateOrCreate(['option_key' => 'footer_call_us_text'], ['option_value' => $request->footer_call_us_text]);
                Setting::updateOrCreate(['option_key' => 'footer_one_title'], ['option_value' => $request->footer_one_title]);
                Setting::updateOrCreate(['option_key' => 'footer_two_title'], ['option_value' => $request->footer_two_title]);
                Setting::updateOrCreate(['option_key' => 'footer_three_title'], ['option_value' => $request->footer_three_title]);
                Setting::updateOrCreate(['option_key' => 'footer_four_title'], ['option_value' => $request->footer_four_title]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Footer Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function contactInfoStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'contactcontent'], ['option_value' => $request->contactcontent]);
                Setting::updateOrCreate(['option_key' => 'contactmetakeyword'], ['option_value' => $request->contactmetakeyword]);
                Setting::updateOrCreate(['option_key' => 'contactmetatitle'], ['option_value' => $request->contactmetatitle]);
                Setting::updateOrCreate(['option_key' => 'contactmetadescription'], ['option_value' => $request->contactmetadescription]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Contact setting update successfully !',
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function aboutInfoStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'aboutcontent'], ['option_value' => $request->aboutcontent]);
                Setting::updateOrCreate(['option_key' => 'aboutmetakeyword'], ['option_value' => $request->aboutmetakeyword]);
                Setting::updateOrCreate(['option_key' => 'aboutmetatitle'], ['option_value' => $request->aboutmetatitle]);
                Setting::updateOrCreate(['option_key' => 'aboutmetadescription'], ['option_value' => $request->aboutmetadescription]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'About setting update successfully !',
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function typographyIndex()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle(__f('Typography Setting Title'));
            $data['showThemeMenu']               = 'show';
            $data['activeThemeMenu']             = 'active';
            $data['activeTypographySettingMenu'] = 'active';
            $data['breadcrumb']                  = [__f('Admin Dashboard Title') => route('admin.dashboard.index'), __f('Typography Setting Title') => ''];
            return view('setting::typography', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function typographyStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'backgroundtype'], ['option_value' => $request->bacroundtype]);
                if ($request->bacroundtype == 1) {
                    Setting::updateOrCreate(['option_key' => 'singlebackround'], ['option_value' => $request->singlebackround]);
                } else {
                    Setting::updateOrCreate(['option_key' => 'gradientone'], ['option_value' => $request->gradientone]);
                    Setting::updateOrCreate(['option_key' => 'gradienttwo'], ['option_value' => $request->gradienttwo]);
                }
                Setting::updateOrCreate(['option_key' => 'primarycolor'], ['option_value' => $request->primarycolor]);
                Setting::updateOrCreate(['option_key' => 'primarywhitecolor'], ['option_value' => $request->primarywhitecolor]);
                Setting::updateOrCreate(['option_key' => 'secondarycolor'], ['option_value' => $request->secondarycolor]);
                Setting::updateOrCreate(['option_key' => 'gadientcolor'], ['option_value' => $request->gadientcolor]);
                Setting::updateOrCreate(['option_key' => 'primaryredcolor'], ['option_value' => $request->primaryredcolor]);
                Setting::updateOrCreate(['option_key' => 'bordercolor'], ['option_value' => $request->bordercolor]);
                Setting::updateOrCreate(['option_key' => 'hovercolor'], ['option_value' => $request->hovercolor]);
                return response()->json([
                    'status'  => 'success',
                    'message' => __f('Footer Update Success Message'),
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function shopMetaStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'shopmetatitle'], ['option_value' => $request->shopmetatitle]);
                Setting::updateOrCreate(['option_key' => 'shopmetakeyword'], ['option_value' => $request->shopmetakeyword]);
                Setting::updateOrCreate(['option_key' => 'shopmetadescription'], ['option_value' => $request->shopmetadescription]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Shop setting update successfully !',
                ]);
            }
        } else {
            abort(401);
        }
    }


    public function courierIndex()
    {
        if (Gate::allows('isAdmin')) {
            $this->setPageTitle('Courier Setting');
            $data['showThemeMenu']            = 'show';
            $data['activeThemeMenu']          = 'active';
            $data['activeCourierSettingMenu'] = 'active';
            $data['breadcrumb']               = ['Admin Dashboard' => route('admin.dashboard.index'), 'Courier Setting' => ''];
            return view('setting::courier', $data);
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function steadFastStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'steadfast_base_url'], ['option_value' => $request->steadfirstbaseurl]);
                Setting::updateOrCreate(['option_key' => 'steadfast_api_key'], ['option_value' => $request->steadfirstapikey]);
                Setting::updateOrCreate(['option_key' => 'steadfast_secret_key'], ['option_value' => $request->stradfastsecretkey]);

                $this->changeEnvData([
                    'STEADFAST_BASE_URL'   => $request->steadfirstbaseurl,
                    'STEADFAST_API_KEY'    => $request->steadfirstapikey,
                    'STEADFAST_SECRET_KEY' => $request->stradfastsecretkey,
                ]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Stead Fast settings updated successfully!',
                ]);
            }
        } else {
            abort(401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function pathaoStore(Request $request)
    {
        if (Gate::allows('isAdmin')) {
            if ($request->ajax()) {
                Setting::updateOrCreate(['option_key' => 'pathaoapiurl'], ['option_value' => $request->pathaoapiurl]);
                Setting::updateOrCreate(['option_key' => 'pathaoclientid'], ['option_value' => $request->pathaoclientid]);
                Setting::updateOrCreate(['option_key' => 'pathaoclientsecret'], ['option_value' => $request->pathaoclientsecret]);
                Setting::updateOrCreate(['option_key' => 'pathaogranttype'], ['option_value' => $request->pathaogranttype]);
                Setting::updateOrCreate(['option_key' => 'pathaousername'], ['option_value' => $request->pathaousername]);
                Setting::updateOrCreate(['option_key' => 'pathaopassword'], ['option_value' => $request->pathaopassword]);
                Setting::updateOrCreate(['option_key' => 'pathaosendername'], ['option_value' => $request->pathaosendername]);
                Setting::updateOrCreate(['option_key' => 'pathaosenderphone'], ['option_value' => $request->pathaosenderphone]);
                Setting::updateOrCreate(['option_key' => 'pathaostoreid'], ['option_value' => $request->pathaostoreid]);
                Setting::updateOrCreate(['option_key' => 'pathaosecrettoken'], ['option_value' => $request->pathaosecrettoken]);

                $this->changeEnvData([
                    'PATHAO_API_URL'       => $request->pathaoapiurl,
                    'PATHAO_CLIENT_ID'     => $request->pathaoclientid,
                    'PATHAO_CLIENT_SECRET' => $request->pathaoclientsecret,
                    'PATHAO_GRANT_TYPE'    => $request->pathaogranttype,
                    'PATHAO_USERNAME'      => $request->pathaousername,
                    'PATHAO_PASSWORD'      => $request->pathaopassword,
                    'PATHAO_SENDER_NAME'   => $request->pathaosendername,
                    'PATHAO_SENDER_PHONE'  => $request->pathaosenderphone,
                    'PATHAO_STORE_ID'      => $request->pathaostoreid,
                    'PATHAO_SECRET_TOKEN'  => $request->pathaosecrettoken,
                ]);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Pathao settings updated successfully!',
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
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
