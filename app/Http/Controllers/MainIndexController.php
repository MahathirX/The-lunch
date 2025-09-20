<?php

namespace App\Http\Controllers;

use App\Events\NotificationBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Brand\App\Models\Brand;
use Modules\Category\App\Models\Category;
use Modules\Product\App\Models\Product;
use Modules\Slider\App\Models\Slider;
use Modules\Subscriber\App\Models\Subscriber;

class MainIndexController extends Controller
{
    public function index()
    {
        $this->setPageTitle('Home');
        $slidershowingvalue = 4;
        if (config('settings.slider_show_value_in_home') != null) {
            $slidershowingvalue = config('settings.slider_show_value_in_home');
        }
        $category_product = json_decode(config('settings.category_product'));
        $category_section = json_decode(config('settings.category_section'));
        $data['metatitle'] = config('settings.homemetatitle') ?? '';
        $data['mode'] = config('settings.commingsoonmode') ?? 0;
        $data['top_selling_products'] = json_decode(config('settings.topsellingproducts'));
        $data['topSellingProducts'] = Product::with(['categories', 'reviews', 'images', 'bestselling', 'productattibute'])->whereIn('id', $data['top_selling_products'])->where('status', '1')->orderBy('id', 'desc')->get();
        $data['fristFiveProducts'] = Product::with(['categories', 'reviews', 'images', 'bestselling', 'productattibute'])->where('status', '1')->orderBy('id', 'desc')->take(20)->get();
        $data['metakeywords'] = config('settings.homemetakeyword') ?? '';
        $data['metadescription'] = config('settings.homemetadescription') ?? '';
        $data['brand'] = Brand::where('status', '1')->get();
        $data['categories'] = Category::withCount('products')->where('home_page_show', '1')->where('status', '1')->where('parent_id', '0')->where('submenu_id', '0')->orderBy('order_by', 'asc')->get();
        $data['electronicsProduct'] = Category::with('products')->where('slug', 'electronics')->get();
        $data['furnitureProduct'] = Category::with('products')->where('slug', 'furniture')->get();
        $data['ClothProduct'] = Category::with('products')->where('slug', 'clothing-apparel')->get();
        $data['sliders'] = Slider::where('status', '1')->orderBy('order_by', 'asc')->take($slidershowingvalue)->get();
        if ($category_product != null && collect($category_product)->count() > 0) {
            $data['dealsCategorys'] = Category::with(['products'])->whereIn('id', $category_product)->has('products')->where('status', '1')->get();
        } else {
            $categories = Category::where('status', '1')->take(4)->pluck('id');
            $data['dealsCategorys'] = Category::with(['products'])->whereIn('id', $categories)->has('products')->where('status', '1')->get();
        }

        if ($category_section != null && collect($category_section)->count() > 0) {
            $data['dealsSections'] = Category::with(['products'])->whereIn('id', $category_section)->has('products')->where('status', '1')->get();
        } else {
            $categories = Category::where('status', '1')->take(5)->pluck('id');
            $data['dealsSections'] = Category::with(['products'])->whereIn('id', $categories)->has('products')->where('status', '1')->get();
        }
        if ($data['mode'] == 1) {
            if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2)) {
                return redirect()->route('admin.dashboard.index');
            } else if (Auth::check() && Auth::user()->role_id == 3) {
                return redirect()->route('staff.dashboard.index');
            } else {
                return view('commingsoon.commingsoon');
            }
        } else {
            return view('frontend.home', $data);
        }
    }

    public function show($slug)
    {
        $data['category'] = Category::with(['products'])->where('slug', $slug)->first();
        $data['categories'] = Category::with(['products'])->has('products')->where('status', '1')->get();
        $data['brands'] = Brand::with('products')->where('status', '1')->get();
        $data['breadcrumb'] = ['Home' => url('/'), $data['category']->name ?? 'Category ' . ' Details' => ''];
        $this->setPageTitle($data['category']->name ?? 'Category ' . ' Details');

        if (config('settings.categorydetailspagestylechosevalue') == 1) {
            return view('frontend.categorydetails.categorydetail-one', $data);
        } else if (config('settings.categorydetailspagestylechosevalue') == 2) {
            return view('frontend.categorydetails.categorydetail-two', $data);
        } else {
            return view('frontend.categorydetails.categorydetail-three', $data);
        }

    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity']++;
        } else {
            $cart[$request->product_id] = [
                "name" => $product->name,
                "product_id" => $product->id,
                "quantity" => 1,
                "price" => $product->discount_price ?? $product->price,
                "image" => $product->product_image ?? null
            ];
        }
        session()->put('cart', $cart);

        $countcart = count((array) session('cart'));
        $totalAmout = 0;
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $totalAmout += $details['quantity'] * $details['price'];
            }
        }
        session()->put('cart', $cart);
        $dynamicdata = view('frontend.cart.rendercart')->render();
        $charge = (int) config('settings.deliveryinsidedhake') ?? 0;
        $dynamicartcount = $countcart . ' Items';
        if ($countcart == 0 || $countcart == 1) {
            $dynamicartcount = $countcart . ' Item';
        }

        return response()->json([
            'status' => 'success',
            'message' => __f('Product Add To Cart Successfully Message'),
            'data' => $dynamicdata,
            'subTotal' => $totalAmout,
            'charge' => $charge,
            'dynamicartcount' => $dynamicartcount,
            'totalPrice' => $totalAmout + $charge,
            'countcart' => $countcart,
        ]);
    }

    public function veiwcart(Request $request)
    {
        $this->setPageTitle('Cart View');
        $data['cart'] = session()->get('cart', []);
        $data['breadcrumb'] = ['Home' => url('/'), 'Cart View' => ''];

        if (config('settings.viewcartpageshowchosevalue') == 1) {
            return view('frontend.cartviews.cartview-one', $data);
        } else if (config('settings.viewcartpageshowchosevalue') == 2) {
            return view('frontend.cartviews.cartview-two', $data);
        } else {
            return view('frontend.cartviews.cartview-three', $data);
        }
    }

    public function removecart(Request $request)
    {
        $productId = $request->input('id');
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $countcart = count((array) session('cart'));
        $totalAmout = 0;
        $charge = 0;
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $totalAmout += $details['quantity'] * $details['price'];
            }
            $charge = (int) config('settings.deliveryinsidedhake') ?? 0;
        }

        $dynamicdata = view('frontend.cart.rendercart')->render();
        $dynamicartcount = $countcart . ' Items';
        if ($countcart == 0 || $countcart == 1) {
            $dynamicartcount = $countcart . ' Item';
        }

        return response()->json([
            'status' => 'success',
            'message' => __f('Product Remove To Cart Successfully Message'),
            'data' => $dynamicdata,
            'subTotal' => $totalAmout,
            'charge' => $charge,
            'dynamicartcount' => $dynamicartcount,
            'totalPrice' => $totalAmout + $charge,
            'countcart' => $countcart,
        ]);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        foreach ($request->cart as $item) {
            if (isset($cart[$item['id']])) {
                $cart[$item['id']]['quantity'] = $item['quantity'];
            }
        }
        session()->put('cart', $cart);
        return response()->json([
            'status' => 'success',
            'message' => 'cart updated  successfully!'
        ], 200);
    }

    public function updatecartview(Request $request)
    {
        $cart = session()->get('cart', []);
        if ($cart[$request->id]) {
            $cart[$request->id]['quantity'] = $request->value;
        }
        session()->put('cart', $cart);
        return response()->json([
            'status' => 'success',
            'message' => 'Cart updated successfully!'
        ], 200);
    }

    public function checkout()
    {
        $this->setPageTitle('Check Out');
        $data['breadcrumb'] = ['Home' => url('/'), 'Check Out' => ''];

        if (config('settings.checkoutpageshowchosevalue') == 1) {
            return view('frontend.checkouts.checkout-one', $data);
        } else if (config('settings.checkoutpageshowchosevalue') == 2) {
            return view('frontend.checkouts.checkout-two', $data);
        } else {
            return view('frontend.checkouts.checkout-three', $data);
        }

    }

    public function productshow($id)
    {
        $this->setPageTitle('Product Single');
        $data['breadcrumb'] = ['Home' => url('/'), 'Product Single' => ''];
        $singleProduct = Product::with(['categories', 'reviews', 'images', 'bestselling', 'productattibute', 'brand'])->where('id', $id)->first();
        $data['singleproduct'] = $singleProduct;
        $categoryIds = $singleProduct->categories->pluck('id');
        $data['relatedProducts'] = Product::with(['categories', 'reviews'])
            ->whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->where('id', '!=', $id)
            ->take(6)
            ->get();

        $data['previousProduct'] = Product::where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->first();

        $data['nextProduct'] = Product::where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->first();

        if (config('settings.productdetailspagechosevalue') == 1) {
            return view('frontend.productdetails.productdetail-one', $data);
        } else if (config('settings.productdetailspagechosevalue') == 2) {
            return view('frontend.productdetails.productdetail-two', $data);
        } else {
            return view('frontend.productdetails.productdetail-three', $data);
        }

    }

    public function categorySearch(Request $request)
    {
        if ($request->ajax()) {
            if ($request->searchvalue != null && $request->categoryid != null) {
                if ($request->searchvalue == 'featured') {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->where('featured', '1')->orderBy('id', 'desc')->get();
                } else if ($request->searchvalue == 'bestselling') {
                    $products = Product::with(['category', 'bestselling'])->where('productcategory_id', $request->categoryid)->whereHas('bestselling')->where('status', '1')->orderByRaw('(SELECT COUNT(*) FROM order_details WHERE order_details.product_id = products.id) DESC')->orderBy('id', 'desc')->get();
                } else if ($request->searchvalue == 'a_z') {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->orderBy('name', 'asc')->get();
                } else if ($request->searchvalue == 'z_a') {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->orderBy('name', 'desc')->get();
                } else if ($request->searchvalue == 'price_low_high') {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->orderByRaw('COALESCE(discount_price, price) ASC')->get();
                } else if ($request->searchvalue == 'price_high_low') {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->orderByRaw('COALESCE(discount_price, price) DESC')->get();
                } else if ($request->searchvalue == 'desc') {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->orderBy('id', 'desc')->get();
                } else {
                    $products = Product::with('category')->where('productcategory_id', $request->categoryid)->where('status', '1')->orderBy('id', 'asc')->get();
                }
                $countproducts = count($products) == 0 || count($products) == 1 ? count($products) . ' Product' : count($products) . ' Products';
                $productData = view('frontend.productrender', compact('products'))->render();

                return response()->json([
                    'status' => 'success',
                    'products' => $productData,
                    'countproducts' => $countproducts,
                ]);
            }
        }
    }

    public function productSearch(Request $request)
    {
        if ($request->ajax()) {
            if ($request->searchvalue != '' && $request->searchvalue != null) {
                $getrequest = $request->searchvalue;
                $getsearchproduct = Product::where('name', 'LIKE', "%$getrequest%")->orWhere('price', 'LIKE', "%$getrequest%")->orWhere('discount_price', 'LIKE', "%$getrequest%")->get();
                $getrenderdata = view('frontend.productsearchrender', compact('getsearchproduct'))->render();
                return response()->json([
                    'status' => 'success',
                    'data' => $getrenderdata,
                ]);
            } else {
                return response()->json([
                    'status' => 'error'
                ]);
            }
        }
    }

    public function productViewSearch(Request $request)
    {
        $getrequest = $request->searchvalues;
        $data['getsearchvalue'] = $request->searchvalues;
        $this->setPageTitle('Search Products : ' . $getrequest);
        $data['breadcrumb'] = ['Home' => url('/'), 'Search Products' => ''];
        $data['categories'] = Category::with(['products'])->has('products')->where('status', '1')->get();
        $data['getsearchproduct'] = Product::with(['category', 'reviews', 'images'])->where('name', 'LIKE', "%$getrequest%")->orWhere('price', 'LIKE', "%$getrequest%")->orWhere('discount_price', 'LIKE', "%$getrequest%")->where('status', '1')->get();
        return view('frontend.productviewsearch', $data);
    }

    public function productQuntityUpdate(Request $request)
    {
        $product = Product::findOrFail($request->productid);

        $cart = session()->get('cart', []);
        if (isset($cart[$request->productid])) {
            $cart[$request->productid]['quantity'] = (int) $request->value;
        } else {
            $cart[$request->productid] = [
                "name" => $product->name,
                "product_id" => $product->id,
                "quantity" => (int) $request->value,
                "price" => $product->discount_price ?? $product->price,
                "image" => $product->product_image ?? null,
                "product_varient" => $request->product_varient,
            ];
        }
        session()->put('cart', $cart);
        $countcart = count((array) session('cart'));
        $totalAmout = 0;
        if (session('cart')) {
            foreach (session('cart') as $id => $details) {
                $totalAmout += $details['quantity'] * $details['price'];
            }
        }
        session()->put('cart', $cart);
        $dynamicdata = view('frontend.cart.rendercart')->render();
        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully!',
            'data' => $dynamicdata,
            'totalPrice' => $totalAmout,
            'countcart' => $countcart,
        ]);
    }

    public function generateCSS()
    {
        $backgroundtype = config('settings.backgroundtype');

        if ($backgroundtype == 1) {
            $primaryBackground = config('settings.singlebackround');
        } else {
            $primaryBackground = 'radial-gradient(circle farthest-corner at 10% 20%, ' . config('settings.gradientone') . ' 0%, ' . config('settings.gradienttwo') . ' 90%)';
        }

        $primaryColor = config('settings.primarycolor');
        $primaryWhiteColor = config('settings.primarywhitecolor');
        $primarySecondaryColor = config('settings.secondarycolor');
        $primaryGadientColor = config('settings.gadientcolor');
        $primaryBordercolor = config('settings.bordercolor');
        $primaryHovercolor = config('settings.hovercolor');
        $primaryRedColor = config('settings.primaryredcolor');

        $css = "
        .header-top {
            background: {$primaryBackground} !important;
            color: {$primaryColor};
        }
        .frontend-breadcrumb-bg{
            background: {$primaryBackground} !important;
        }
        .frontend-breadcrumb-bg .breadcrumb-item{
            color : {$primaryWhiteColor} !important;
        }
        .frontend-breadcrumb-bg .breadcrumb-item a{
            color : {$primaryWhiteColor} !important;
            font-weight: 700 !important;
        }
        .frontend-breadcrumb-bg .breadcrumb-item + .breadcrumb-item::before{
            color: {$primaryWhiteColor} !important;
        }

        .header-bottom .menu > li > a::before {
            background-color: {$primaryColor} !important;
        }

        .cart-dropdown .cart-count {
            background: {$primaryRedColor} !important;
        }
        .btn-primary {
            color: {$primaryWhiteColor} !important;
            background-color: {$primaryColor} !important;
            border-color:{$primaryColor} !important;
        }
        .text-third {
            color: {$primarySecondaryColor} !important;
        }


        .nav.nav-border-anim .nav-link::before {
            background-color: {$primaryWhiteColor} !important;
        }

        .product-label.label-sale {
            background:  {$primaryRedColor} !important;
        }
        .btn-product {
            transition: all 0.3s;
            border: 0.1rem solid {$primaryBackground};
            background: {$primaryBackground};
        }
        .btn-product-three-template {
            transition: all 0.3s;
            border: 0.1rem solid {$primaryBackground};
            background: {$primaryBackground};
        }
        .deals-product-four-template .product-prices {
            background: {$primaryBackground};
            color: {$primaryWhiteColor} !important;
        }

        .btn-product:hover, .btn-product:focus {
            transition: all 0.3s;
            color: {$primaryWhiteColor} !important;
            background: {$primaryHovercolor} !important;
            border-color: {$primaryHovercolor} !important;
        }
        .btn-product-three-template:hover, .btn-product-three-template:focus {
            transition: all 0.3s;
            color: {$primaryWhiteColor} !important;
            background: {$primaryHovercolor} !important;
            border-color: {$primaryHovercolor} !important;
        }
         .product-image{
            transition: all 0.9s;
         }
        .product:hover .product-image {
            transition: all 0.9s;
            transform: scale(1.2);
        }
        .btn-product span {
           color: {$primaryWhiteColor} !important;
           font-weight: 700;
           font-family:'Hind Siliguri', serif;
           font-size: 20px;
        }
        .btn-product-three-template span {
           color: {$primaryWhiteColor} !important;
           font-weight: 700;
           font-family:'Hind Siliguri', serif;
           font-size: 20px;
        }
        .btn-product::before {
            color: {$primaryWhiteColor} !important;
        }
        .product-cat {
            color: {$primaryGadientColor} !important;
        }

        .old-price {
            color:  {$primaryGadientColor} !important;
        }
        .new-price {
            color:  {$primaryRedColor} !important;
        }
        .ratings-text {
            color:  {$primaryGadientColor} !important;
        }
        .footer-copyright{
            color:  {$primaryGadientColor} !important;
        }
        .category-dropdown:not(.is-on):hover .dropdown-toggle {
            background-color: {$primaryColor} !important;
        }
        .widget.widget-clean a{
            color: {$primaryColor} !important;
        }
        #filter-price-range {
            color: {$primaryColor} !important;
        }
        .header-top a {
            color: {$primaryWhiteColor} !important;
        }
        .header-top .header-user-name {
             color: {$primaryWhiteColor} !important;
        }
        .header-top .dropdown-menu {
            background: {$primaryBackground} !important;
            color: {$primaryWhiteColor};
        }
        .dropdown-item:focus, .dropdown-item:hover {
            background: {$primaryBackground} !important;
            color: {$primaryWhiteColor};
        }
        .checkout-btn {
            color:  {$primaryWhiteColor} !important;
            border-color: {$primaryColor} !important;
            background:{$primaryBackground} !important;
        }
        .view-cart-bnt {
            color:  {$primaryWhiteColor} !important;
            border-color: {$primaryRedColor} !important;
            background:{$primaryRedColor} !important;
        }
        .checkout-btn:hover{
            color: {$primaryWhiteColor} !important;
            background:{$primaryRedColor} !important;
            border-color: {$primaryRedColor} !important;
            transition: all 0.3s;
        }
        .view-cart-bnt:hover{
            color: {$primaryWhiteColor} !important;
            background:{$primaryHovercolor} !important;
            border-color: {$primaryHovercolor} !important;
            transition: all 0.3s;
        }
        .cart-dropdown:hover .dropdown-toggle, .cart-dropdown.show .dropdown-toggle, .compare-dropdown:hover .dropdown-toggle, .compare-dropdown.show .dropdown-toggle {
            color: {$primaryColor} !important;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary.focus, .btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active, .show > .btn-primary.dropdown-toggle {
            color:  {$primaryWhiteColor} !important;
        }
        #cartremoveForm button {
            background: {$primaryRedColor} !important;
            color: {$primaryWhiteColor} !important;
        }
        .table.table-summary .summary-total td {
            color: {$primaryColor} !important;
        }
        .checkout .form-control:focus {
            border-color:{$primaryColor} !important;
        }
        .contact-form .form-control:focus {
            border-color:{$primaryColor} !important;
        }

        .frontend-footer .footer-copyright{
            color: {$primaryWhiteColor} !important;
        }
        .btn-link {
            color: {$primaryColor} !important;
        }
        .btn-link:hover, .btn-link:focus, .btn-link .btn-link-dark:hover, .btn-link .btn-link-dark:focus {
            color: {$primaryColor} !important;
            border-color:{$primaryColor} !important;
        }
        .frontend-product-title{
            background: {$primaryBackground} !important;
            color: {$primaryWhiteColor} !important;
        }
        .frontend-product-title .title{
            color: {$primaryWhiteColor} !important;
        }
        .cat-block:hover .cat-block-title {
            color: {$primaryColor} !important;
        }

        .product-details-tab .nav.nav-pills .nav-link:hover, .product-details-tab .nav.nav-pills .nav-link:focus {
            color: {$primaryHovercolor} !important;
            border-bottom-color: {$primaryHovercolor} !important;
        }
        #header-category-title-main {
            background: {$primaryBackground} !important;
        }

        .product-review-btn{
            color: {$primaryWhiteColor} !important;
            background: {$primaryColor} !important;
            border-color : {$primaryColor} !important;
        }
        .owl-simple .owl-nav [class*='owl-']:not(.disabled):hover {
            color:  {$primaryColor} !important;
        }
        .product-body {
            text-align: start !important;
        }
        .product-price {
            color:  {$primaryColor} !important;
            display: flex;
            justify-content: start;
            align-items: center;
            font-weight : 600;
        }
        .product-body .product-title{
         font-weight : 600;
        }


        .product-gallery-item::before {
            border: 1px solid {$primaryBordercolor} !important;
        }

        .btn.btn-spinner {
            padding: 1rem .4rem !important;
            font-size: 1.5rem !important;
        }
        .btn.btn-spinner:hover, .btn.btn-spinner:focus {
            color: {$primaryColor} !important;
        }
        .input-group-append .btn, .input-group-prepend .btn {
            z-index: 999999 !important;
        }

        .nav.nav-pills-mobile .nav-link.active, .nav.nav-pills-mobile .nav-link:hover, .nav.nav-pills-mobile .nav-link:focus {
            color: {$primaryColor} !important;
            border-bottom-color: {$primaryColor} !important;
        }
        .mobile-menu-light .mobile-menu li.open > a, .mobile-menu-light .mobile-menu li.active > a {
            color: {$primaryColor} !important;
        }
        .bg-primary{
            background: {$primaryColor} !important;
        }
        .header-3 .header-search-extended .btn, .header-4 .header-search-extended .btn {
            background: {$primaryBackground} !important;
        }

        .cart-dropdown .dropdown-toggle i {
            color: {$primaryColor} !important;
        }
        .cart-dropdown .dropdown-toggle i:hover {
            background: {$primaryRedColor} !important;
            transition: all 0.3s;
            color:{$primaryWhiteColor} !important;
        }
        .header-product-title a{
            color : {$primaryRedColor} !important;
        }
        .header-card-remove-btn {
            color: {$primaryRedColor} !important;
        }
        .menu-vertical i {
            color:{$primaryColor} !important;
        }
        .mobile-cats-menu i {
            color:{$primaryColor} !important;
        }
        #category-menu:hover{
           color: {$primaryRedColor} !important;
            transition: all 0.1s;
        }
        .cart-product-quantity .update-cart {
            background: {$primaryRedColor} !important;
        }
        .checkoutpagecommonbtn:hover {
            background: {$primaryRedColor} !important;
            color :  {$primaryWhiteColor} !important;
            transition: all 0.3s;
        }
        .checkout-confiem-btn{
            background: {$primaryRedColor} !important;
        }
        .checkout-confiem-btn:hover {
            color: white;
            background: {$primaryHovercolor} !important;
        }
        .lastcontent ul li:hover{
            transition: all 0.3s;
            color: white;
            background: {$primaryHovercolor} !important;
        }
        .checkoutproductname{
            color: {$primaryRedColor} !important;
            font-size:14px;
        }
        .checkout .checkoutdeletebnts {
            background: {$primaryRedColor} !important;
        }
        #mmenu-categorie{
            background: rgba(0, 158, 84, 1);;
        }
        .forget-password {
            color: {$primaryRedColor} !important;
        }
        #mmenu-category-call a {
            color: {$primaryRedColor} !important;
            font-size: 19px;
            font-weight: 900 !important;
        }
        .order-success-card{
          background: {$primaryBackground} !important;
        }
        .mobile-menu-toggler {
            color: #df9300 !important;
        }
        .checkoutpagecommonbtn {
            background-image: radial-gradient(circle farthest-corner at 50.4% 50.5%, rgba(251, 32, 86, 1) 0%, rgba(135, 2, 35, 1) 90%);
        }
        .loginpagecommonbtn {
    background-image: radial-gradient(
        circle farthest-corner at 50.4% 50.5%,
        rgba(255, 145, 64, 1) 0%,
        rgba(185, 0, 255, 1) 90%
    );
}

        #dynamicallyColorHeader{
            background : {$primaryBackground} !important;
            color :{$primaryWhiteColor} !important;
        }
        ";
        return response($css)->header('Content-Type', 'text/css');

        // .form-control:focus {
        //     border-color:  {$primaryColor} !important;
        // }
        // .products .product {
        //     border: 1px solid {$primaryBordercolor} !important;
        // }
        // .owl-theme .owl-nav [class*='owl-']{
        //     color: {$primaryColor} !important;
        // }
        // .owl-theme .owl-nav [class*='owl-']:not(.disabled):hover {
        //     border-color: {$primaryWhiteColor} !important;
        //     background: {$primaryColor} !important;
        //     color: {$primaryWhiteColor} !important;
        // }
        // .nav.nav-pills .nav-item.show .nav-link, .nav.nav-pills .nav-item .nav-link.active {
        //     color: {$primaryWhiteColor} !important;
        //     border-bottom-color: {$primaryWhiteColor} !important;
        // }

        // .nav.nav-pills .nav-link {
        //     color:  {$primaryWhiteColor} !important;
        // }
        // .nav.nav-pills .nav-link:hover, .nav.nav-pills .nav-link:focus {
        //     color: {$primaryWhiteColor} !important;
        // }
    }

    public function subcriberStore(Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:subscribers,email'],
        ]);
        Subscriber::create([
            'email' => $request->email,
        ]);
        return back()->with('success', 'Thanks for subscribing!');
    }
}
