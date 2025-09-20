@extends('layouts.frontend')
@section('title', $title)
@section('content')
    <div id="check-out-template-two" class="page-content">
        <div class="container checkout">
            <form action="{{ route('oreder.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-7 mb-3">
                        <h4 class="mb-1">{{ __f('Check Out Page Delivery Summary Title') }}</h4>
                        <div class="checkout-card-two">
                            <div class="row ">
                                <div class="col-sm-6">
                                    <label for="name"
                                        class="required">{{ __f('Check Out Page User Name Title') }}</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{  Auth::check() ? Auth::user()->fname :  old('name') }}"
                                        placeholder="{{ __f('Check Out Page User Name Placeholder') }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="required">{{ __f('Check Out Page User Phone Title') }}</label>
                                    <div class="inputWithIcon">
                                        <img src="https://cdn.countryflags.com/thumbs/bangladesh/flag-400.png"
                                            alt="">
                                        <input type="tel" name="phone" class="form-control" required
                                            value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}"
                                            placeholder="{{ __f('Check Out Page User Phone Placeholder') }}">
                                    </div>
                                    @error('phone')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="required">{{ __f('Check Out Page User Email Title') }} </label>
                                    <input type="email" name="email" class="form-control" required
                                        value="{{ Auth::check() ? Auth::user()->email : old('email') }}"
                                        placeholder="{{ __f('Check Out Page User Email Placeholder') }}">
                                    @error('email')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="required">{{ __f('Check Out Page User Shipping Address Title') }}</label>
                                    <input name="address" type="text" class="form-control" value="{{ old('address') }}"
                                        placeholder="{{ __f('Check Out Page User Shipping Address Placeholder') }}">
                                    @error('address')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <label class="required">{{ __f('Check Out Page Delivery Area Title') }}</label>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" checked name="shipping"
                                                id="inside_dhaka" value="{{ config('settings.deliveryinsidedhake') }}">
                                            <label class="form-check-label" for="inside_dhaka">
                                                {{ __f('Check Out Page Delivery Area Inside Dhaka Title') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shipping"
                                                id="outside_dhaka"
                                                value="{{ config('settings.deliveryoutsidedhake') }}">
                                            <label class="form-check-label" for="outside_dhaka">
                                                {{ __f('Check Out Page Delivery Area Outside Dhaka Title') }}
                                            </label>
                                        </div>
                                        @error('shipping')
                                            <p class="text-danger">{{ $message ?? '' }}</p>
                                        @enderror
                                    </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center">
                                @if (count(session('cart', [])) > 0)
                                    <button type="submit" class="btn checkout-confiem-btn">
                                        {{ __f('Check Out Page Order Confirm Title') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <h4 class="mb-1">{{ __f('Check Out Page Order Info Title') }}</h4>
                        <div class="checkout-card-two-second">
                            @if (count(session('cart', [])) > 0)
                                @php
                                    $subtotal = 0;
                                @endphp
                                @foreach (session('cart', []) as $id => $item)
                                    @php
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $subtotal += $itemTotal;
                                    @endphp
                                    <input type="hidden" name="product_id" id=""
                                                    value="{{ $id }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex">
                                            <div class="product-images">
                                                <a href="{{ route('view.product', ['id' => $id]) }}">
                                                    <img src="{{ asset($item['image'] ?? '') }}"
                                                        alt="Product image">
                                                </a>
                                            </div>
                                            <div class="checkout-two-text">
                                                <a
                                                    href="{{ route('view.product', ['id' => $id]) }}">{{ $item['name'] ?? '' }}</a><br>
                                                <span class="quentity">{{ $item['quantity'] ?? '' }}</span> <br>
                                                <span class="price">{{ $item['price'] ?? '' }}</span>
                                            </div>
                                        </div>
                                        <div class="cart-product-quantity">
                                            <input type="number" class="form-control quantity-input"
                                                value="{{ $item['quantity'] }}" min="1" step="1"
                                                data-id="{{ $id }}" required
                                                id="update_quntity_{{ $id }}">
                                            <a href="javascript:void(0);" onclick="updateQuntity({{ $id }})"
                                                class="update-cart"><i class="icon-refresh"></i></a>
                                            <button onclick="deleteCardItem({{ $id }})"
                                                class="checkoutdeletebnts" type="button"><i
                                                    class="icon-close"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="checkouttotalvalue">
                                    <h6> <strong>{{ __f('Check Out Page Product Price Title') }} :
                                        </strong><span>
                                            {{ $subtotal ?? '' }} {{ config('settings.currency') ?? '৳' }}</span>
                                    </h6>
                                    <h6> <strong>{{ __f('Check Out Page Delivery Charge Title') }} : </strong><span
                                            id="delivary_charge">
                                            {{ config('settings.deliveryinsidedhake') }}
                                            {{ config('settings.currency') ?? '৳' }}</span></h6>
                                    <h6> <strong>{{ __f('Check Out Page Total Price Title') }} : </strong><span
                                            id="total_val">
                                            {{ $subtotal + config('settings.deliveryinsidedhake') }}
                                            {{ config('settings.currency') ?? '৳' }}</span></h6>
                                </div>
                                {{-- <div class="table-responsive">
                                    <table class="table table-cart table-mobile table-border">
                                        <thead>
                                            <tr>
                                                <th width="70%" class="text-center">
                                                    {{ __f('Check Out Page Name And Image Title') }}</th>
                                                <th width="30%" class="text-center">
                                                    {{ __f('Check Out Page Quntity Title') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach (session('cart', []) as $id => $item)
                                                @php
                                                    $itemTotal = $item['price'] * $item['quantity'];
                                                    $subtotal += $itemTotal;
                                                @endphp

                                                <tr class="px-3">
                                                    <td class="product-col py-2">
                                                        <div class="product">
                                                            <figure class="product-media">
                                                                <a href="{{ route('view.product', ['id' => $id]) }}">
                                                                    <img src="{{ asset($item['image'] ?? '') }}"
                                                                        alt="Product image">
                                                                </a>
                                                            </figure>
                                                            <a class="checkoutproductname"
                                                                href="{{ route('view.product', ['id' => $id]) }}">{{ $item['name'] }}</a>
                                                        </div>
                                                    </td>
                                                    <td class="quantity-col py-2">
                                                        <div class="cart-product-quantity">
                                                            <input type="number" class="form-control quantity-input"
                                                                value="{{ $item['quantity'] }}" min="1"
                                                                step="1" data-id="{{ $id }}" required
                                                                id="update_quntity_{{ $id }}">
                                                            <a href="javascript:void(0);"
                                                                onclick="updateQuntity({{ $id }})"
                                                                class="update-cart"><i class="icon-refresh"></i></a>
                                                            <button onclick="deleteCardItem({{ $id }})"
                                                                class="checkoutdeletebnts" type="button"><i
                                                                    class="icon-close"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> --}}
                                {{-- <div class="checkouttotalvalue">
                                    <h6> <strong>{{ __f('Check Out Page Product Price Title') }} :
                                        </strong><span>
                                            {{ $subtotal ?? '' }} {{ config('settings.currency') ?? '৳' }}</span>
                                    </h6>
                                    <h6> <strong>{{ __f('Check Out Page Delivery Charge Title') }} : </strong><span
                                            id="delivary_charge">
                                            {{ config('settings.deliveryinsidedhake') }}
                                            {{ config('settings.currency') ?? '৳' }}</span></h6>
                                    <h6> <strong>{{ __f('Check Out Page Total Price Title') }} : </strong><span
                                            id="total_val">
                                            {{ $subtotal + config('settings.deliveryinsidedhake') }}
                                            {{ config('settings.currency') ?? '৳' }}</span></h6>
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="mb-3"></div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).on('change', 'input[name="shipping"]', function() {
        var currency = "{{ config('settings.currency') ?? '' }}";
        let shippingCost = parseFloat($(this).val());
        let subtotal = parseFloat("{{ $subtotal ?? '' }}");
        let total = subtotal + shippingCost;
        $('#total_val').text(currency + ' ' + total);
        $('#delivary_charge').text(currency + ' ' + shippingCost);
    });

    function updateQuntity(id) {
        var value = $('#update_quntity_' + id).val();
        $.ajax({
            url: "{{ route('cart.update.view') }}",
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                id: id,
                value: value,
            }),
            success: function(data) {
                if (data.status === 'success') {
                    flashMessage('success', data.message);
                    location.reload();
                } else {
                    alert('Cart update failed!');
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    function deleteCardItem(id) {
        $.ajax({
            url: "{{ route('cart.remove') }}",
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
            },
            success: function(res) {
                if (res.status === 'success') {
                    flashMessage('success', res.message);
                    location.reload();
                }
            },
            error: function(xhr) {
                console.log('Something went wrong. Please try again.');
            }
        });
    }
</script>
