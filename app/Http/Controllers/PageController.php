<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Modules\Page\App\Models\Page;
use Illuminate\Support\Facades\Mail;
use Modules\Product\App\Models\Product;
use App\Http\Requests\ContactFromRequest;
use Modules\Brand\App\Models\Brand;
use Modules\Category\App\Models\Category;


class PageController extends Controller
{
    public function contact()
    {
        $this->setPageTitle('Contact Us');
        $data['breadcrumb']  = ['Home' => url('/'), 'Contact Us' => ''];
        $data['metatitle']          = config('settings.contactmetatitle') ?? '';
        $data['metakeywords']       = config('settings.contactmetakeyword') ?? '';
        $data['metadescription']    = config('settings.contactmetadescription') ?? '';
        return view('frontend.pages.contact', $data);
    }

    public function contactFrom(ContactFromRequest $request)
    {
        $admin = config('settings.admincontactmail');
        $message = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'comment' => $request->comment,
        ];
        Mail::to($admin)->send(new ContactMail($message));
        return response()->json([
            'status' => 'success',
            'message' => 'Contact successfuly store,'
        ]);
    }

    public function aboutus()
    {
        $this->setPageTitle('About Us');
        $data['metatitle']          = config('settings.contactmetatitle') ?? '';
        $data['metakeywords']       = config('settings.contactmetakeyword') ?? '';
        $data['metadescription']    = config('settings.contactmetadescription') ?? '';
        $data['breadcrumb']  = ['Home' => url('/'), 'About Us' => ''];
        return view('frontend.pages.abountus', $data);
    }

    public function shop()
    {
        $this->setPageTitle('Shops');
        $data['metatitle']       = config('settings.shopmetatitle') ?? '';
        $data['metakeywords']    = config('settings.shopmetakeyword') ?? '';
        $data['metadescription'] = config('settings.shopmetadescription') ?? '';
        $data['breadcrumb']      = ['Home' => url('/'), 'Shops' => ''];
        $data['categories']      = Category::with(['products'])->has('products')->where('status', '1')->get();
        $data['brands']          = Brand::where('status', '1')->get();
        $data['getproducts']     = Product::with(['categories', 'reviews', 'images'])->where('status', '1')->orderBy('id', 'desc')->paginate(25);
        return view('frontend.pages.shop', $data);
    }

    public function shopCategorySearch(Request $request)
    {
        if ($request->ajax()) {
            $productMaxPrice = Product::max('price');
            $cleanMinPrice = str_replace(config('settings.currency') . '+', "", $request->minPrice);
            $cleanMaxPrice = str_replace(config('settings.currency') . '+', "", $request->maxPrice);
            $cleanMaxPrice = $cleanMaxPrice == 0 ? $productMaxPrice : $cleanMaxPrice;

            $query = Product::with(['reviews', 'images']);

            if (!empty($request->selectedCategories)) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->whereIn('categories.id', $request->selectedCategories);
                });
            }

            if (!empty($request->brandIds)) {
                $query->whereIn('brand_id', $request->brandIds);
            }

            if ($request->minPrice !== null || $request->maxPrice !== null) {
                $query->whereRaw('COALESCE(discount_price, price) BETWEEN ? AND ?', [$cleanMinPrice, $cleanMaxPrice]);
            }
            $query->where('status', '1');
            switch ($request->sortby_category) {
                case 'featured':
                    $query->where('featured', '1')->orderBy('id', 'desc');
                    break;
                case 'bestselling':
                    $query->with('bestselling')
                        ->whereHas('bestselling')
                        ->orderByRaw('(SELECT COUNT(*) FROM order_details WHERE order_details.product_id = products.id) DESC')
                        ->orderBy('id', 'desc');
                    break;
                case 'a_z':
                    $query->orderBy('name', 'asc');
                    break;
                case 'z_a':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_low_high':
                    $query->orderByRaw('COALESCE(discount_price, price) ASC');
                    break;
                case 'price_high_low':
                    $query->orderByRaw('COALESCE(discount_price, price) DESC');
                    break;
                case 'desc':
                    $query->orderBy('id', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }

            $totalProducts = $query->count();
            $data['products'] = $query->get();
            $data['layout'] = $request->layout;
            $getRanderData = view('frontend.pages.shoppagerander', $data)->render();

            return response()->json([
                'status' => 'success',
                'data' => $getRanderData,
                'totalProducts' => $totalProducts,
            ]);
        }
    }

    public function orderSuccess()
    {
        $this->setPageTitle('Order Store Successfuly');
        $data['breadcrumb']      = ['Home' => url('/'), 'Order  Success' => ''];
        return view('frontend.cart.ordersuccess', $data);
    }

    public function singlePage($slug)
    {
        $data['page']       = Page::with(['image', 'product'])->where('slug', $slug)->first();
        $data['breadcrumb'] = ['Home' => url('/'), $data['page']->page_name . ' - Details' => ''];
        $this->setPageTitle($data['page']->page_name . ' - Details');
        return view('frontend.singlepage.singlepage', $data);
    }

    public function shopByBrand($slug)
    {
        $brand               = Brand::where('slug', $slug)->first();
        $data['categories']  = Category::with(['products'])->has('products')->where('status', '1')->get();
        $data['brands']      = Brand::where('status', '1')->get();
        $data['getproducts'] = Product::with(['categories', 'reviews', 'images'])->has('categories')->where('brand_id', $brand->id)->where('status', '1')->get();
        $data['breadcrumb']  = ['Home' => url('/'), $brand->name . ' By Shop' => ''];
        $this->setPageTitle($brand->name . ' By Shop');
        return view('frontend.pages.shopbybrand', $data);
    }
}
