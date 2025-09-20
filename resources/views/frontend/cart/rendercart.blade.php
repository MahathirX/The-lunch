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
