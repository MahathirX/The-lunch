<style>

/*===== Offcanvas =======*/
#cartOffcanvas .offcanvas-header{
	background: #dadada;
}
#cartOffcanvas .btn-close:focus {
	box-shadow: unset !important;
}
#cartOffcanvas .offcanvas-body {
	scrollbar-width: thin !important;
    scroll-behavior: smooth !important;
    scrollbar-color: #F29434 #fff !important;
}
#cartOffcanvas .product-image-container-header img{
	width: 50px;
	border-radius: 5px;
}
#cartOffcanvas .header-product-name a {
	text-transform: capitalize;
	color: black;
	font-size: 17px;
}
#cartOffcanvas .offcanvas-card-remove-btn{
	border: none;
	background: none;
	color: #F29434;
}
#cartOffcanvas .dropdown-cart-totals {
	background: var(--main-primary-section-background);
    padding: 0px 15px 0px 5px;
}

#cartOffcanvas .dropdown-cart-totals .product-header-total-main {
	border-bottom: 1px solid #fff;
	padding: 10px 5px;
	display: flex;
	justify-content: space-between;
	align-items: center;
}

#cartOffcanvas .dropdown-cart-action{
	background: #F29434;
;
}
#cartOffcanvas .dropdown-cart-action .offcanvas-cart-btn {
	color: #fff;
	width: 50%;
	text-align: center;
	border-right: 1px solid #fff;
	border-radius: 0;
    font-weight: 500;
}

#cartOffcanvas .dropdown-cart-action .offcanvas-checkout-btn {
	text-align: center;
	border-radius: 0;
    width: 48%;
    font-weight: 500;
}
</style>
<div class="offcanvas offcanvas-end cartOffcanvas" tabindex="-1" id="cartOffcanvas">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel" class="mb-0"><i class="fa-solid fa-list me-2"></i>
            <span id="offcanvas-dynamic-cart-count">
                {{ count((array) session('cart')) }}
                @if (count((array) session('cart')) == 1 || count((array) session('cart')) == 0)
                    {{ 'Item' }}
                @else
                    {{ 'Items' }}
                @endif
            </span>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body" id="offcanvas-dynamic-data">
        @php
            $totalAmout = 0;
        @endphp
        @if (count((array) session('cart')) > 0)
            <div class="dropdown-cart-products" id="drop_down_cart_product">
                @if (session('cart'))
                    @foreach (session('cart') as $id => $details)
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td class="py-0" style="width:15%">
                                        <figure class="product-image-container-header">
                                            <a href="javascript:">
                                                @if ($details['image'] != null)
                                                    <img src="{{ asset($details['image'] ?? '') }}" alt="product image">
                                                @else
                                                    <img src="{{ asset('backend/assets/img/avatars/blank image.jpg') }}"
                                                        alt="product image">
                                                @endif
                                            </a>
                                        </figure>
                                    </td>
                                    <td class="py-0" style="width:55%">
                                        <div class="product-cart-details ms-2">
                                            <h5 class="header-product-name mb-0">
                                                <a href="{{ route('view.product', ['id' => $id ?? 0]) }}"
                                                    href="javascript:">{{ Str::limit($details['name'], 15, '...') ?? '' }}</a>
                                            </h5>
                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">{{ $details['quantity'] ?? 0 }}</span>
                                                x
                                                {{ config('settings.currency') ?? '৳' }}
                                                {{ $details['price'] ?? '' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-0" style="width:20%">
                                        <div class="product-cart-details mt-2">
                                            {{ config('settings.currency') ?? '৳' }}
                                            {{ $details['quantity'] * $details['price'] }}
                                        </div>
                                    </td>
                                    <td class="py-0" style="width:10%">
                                        <div class="mt-2">
                                            <form id="cartremoveFormHeader" action="{{ route('cart.remove') }}"
                                                method="POST" class="mb-0">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button class="offcanvas-card-remove-btn" type="submit">
                                                    <div class="d-none remove-to-card-loader-{{ $id }}">
                                                        <div class="preloader-floating-circles">
                                                        <div class="f_circleG" id="frotateG_01"></div>
                                                        <div class="f_circleG" id="frotateG_02"></div>
                                                        <div class="f_circleG" id="frotateG_03"></div>
                                                        <div class="f_circleG" id="frotateG_04"></div>
                                                        <div class="f_circleG" id="frotateG_05"></div>
                                                        <div class="f_circleG" id="frotateG_06"></div>
                                                        <div class="f_circleG" id="frotateG_07"></div>
                                                        <div class="f_circleG" id="frotateG_08"></div>
                                                        </div>
                                                    </div>
                                                    <i class="fa-solid fa-trash remove-to-card-btn-{{ $id }}"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $totalAmout += $details['quantity'] * $details['price'];
                                @endphp
                            </tbody>
                        </table>
                    @endforeach
                @endif
            </div>
        @else
            <p class="text-danger text-center">{{ __f('Offcanvas Shopping Cart Empty Title') }} </p>
        @endif
    </div>
    <div class="offcanvas-footer">
        <div class="dropdown-cart-totals">
            <div class="product-header-total-main">
                <span class="product-header-total">{{ __f('Check Out Page Subtotal Title') }} : </span>
                <span class="product-header-total">
                    {{ config('settings.currency') ?? '৳' }}
                    <span id="offcanvas-dynamic-subtotal">{{ $totalAmout ?? '' }}</span> </span>
            </div>
            <div class="product-header-total-main">
                <span class="product-header-total">{{ __f('Check Out Page Delivery Charge Title') }} : </span>
                <span class="product-header-total">
                    {{ config('settings.currency') ?? '৳' }}
                    @if (count((array) session('cart')) > 0)
                        <span id="offcanvas-dynamic-charge"> {{ config('settings.deliveryinsidedhake') ?? '' }}</span>
                    @else
                        <span id="offcanvas-dynamic-charge">{{ '0' }}</span>
                    @endif
                </span>
            </div>
            <div class="product-header-total-main">
                <span class="product-header-total">{{ __f('Check Out Page Total Title') }} : </span>
                <span class="product-header-total">
                    {{ config('settings.currency') ?? '৳' }}
                    @if (count((array) session('cart')) > 0)
                        <span id="offcanvas-dynamic-total">{{ $totalAmout + (int) config('settings.deliveryinsidedhake') ?? 0 }}</span>
                    @else
                        <span id="offcanvas-dynamic-total">{{ '0' }}</span>
                    @endif

                </span>
            </div>
        </div>
        <div class="dropdown-cart-action">
            <a href="{{ route('index') }}"
                class="btn offcanvas-cart-btn">{{ __f('Offcanvas Continue Shopping Title') }}</a>
            <a href="{{ route('check.out') }}"
                class="btn offcanvas-checkout-btn"><span>{{ __f('Offcanvas Check Out Title') }}</span><i
                    class="icon-long-arrow-right"></i></a>
        </div>
    </div>
</div>
