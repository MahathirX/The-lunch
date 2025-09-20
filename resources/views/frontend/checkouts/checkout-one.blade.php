@extends('layouts.frontend')
@section('title', $title)
@section('content')
    <div id="check-out-template-one" class="page-content">
        <div class="container checkout">
            @php
                $checkoutfromposition = '';
                if(config('settings.checkoutpagefrompositionchosevalue') == 1){
                    $checkoutfromposition = 'checkoutsectionordertwo';
                }
            @endphp
            <form action="{{ route('oreder.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div id="{{ $checkoutfromposition }}" class="col-12 col-lg-4 mb-3">
                        <div class="checkout-card">
                            <div class="row ">
                                <div class="col-sm-12">
                                    <label for="name" class="required">{{ __f('Check Out Page User Name Title') }}</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ Auth::check() ? Auth::user()->fname :  old('name') }}" placeholder="{{ __f('Check Out Page User Name Placeholder') }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-12">
                                    <label class="required">{{ __f('Check Out Page User Email Title') }} </label>
                                    <input type="email" name="email" class="form-control" required placeholder="{{ __f('Check Out Page User Email Placeholder') }}" value="{{ Auth::check() ? Auth::user()->email : old('email') }}">
                                    @error('email')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="required">{{ __f('Check Out Page User Phone Title') }}</label>
                                    <div class="inputWithIcon">
                                        <img src="https://cdn.countryflags.com/thumbs/bangladesh/flag-400.png" alt="">
                                        <input type="tel" name="phone" class="form-control" required
                                        placeholder="{{ __f('Check Out Page User Phone Placeholder') }}" value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}">
                                    </div>
                                    @error('phone')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="required">{{ __f('Check Out Page User Shipping Address Title') }}</label>
                                    <input name="address" type="text" class="form-control" value="{{ old('address') }}" placeholder="{{ __f('Check Out Page User Shipping Address Placeholder') }}">
                                    @error('address')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-12">
                                <label class="required">{{ __f('Check Out Page Delivery Area Title') }}</label>
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
                            {{-- <div class="row mb-4">
                                <div class="col-sm-12">
                                    <label class="required">{{ __f('Check Out Page Delivery Area Title') }}</label>
                                    <select name="shipping" class="form-select">
                                        <option value=" {{ config('settings.deliveryinsidedhake') }}">{{ __f('Check Out Page Delivery Area Inside Dhaka Title') }}
                                        </option>
                                        <option value="{{ config('settings.deliveryoutsidedhake') }}">{{ __f('Check Out Page Delivery Area Outside Dhaka Title') }}
                                        </option>
                                    </select>
                                    @error('shipping')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="row">
                                @if (count(session('cart', [])) > 0)
                                    <button type="submit" class="btn checkout-confiem-btn">
                                        {{ __f('Check Out Page Order Confirm Title') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="summary-info">
                            <h3 class="summary-title">{{ __f('Check Out Page Order Info Title') }} </h3>
                            <div class=" px-3 py-4">
                                @if (count(session('cart', [])) > 0)
                                    @php
                                        $subtotal = 0;
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="table table-cart table-mobile table-border">
                                            <thead>
                                                <tr>
                                                    <th width="70%" class="text-center">{{ __f('Check Out Page Name And Image Title') }}</th>
                                                    <th width="30%" class="text-center">{{ __f('Check Out Page Quntity Title') }}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach (session('cart', []) as $id => $item)
                                                    @php
                                                        $itemTotal = $item['price'] * $item['quantity'];
                                                        $subtotal += $itemTotal;
                                                    @endphp
                                                    <input type="hidden" name="product_id" id=""
                                                        value="{{ $id }}">
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
                                    </div>
                                    <div class="checkouttotalvalue">
                                        <h6> <strong>{{ __f('Check Out Page Product Price Title') }} :
                                            </strong><span>
                                                {{ $subtotal ?? '' }} {{ config('settings.currency') ?? '৳' }}</span>
                                        </h6>
                                        <h6> <strong>{{ __f('Check Out Page Delivery Charge Title') }} : </strong><span id="delivary_charge">
                                                {{ config('settings.deliveryinsidedhake') }}
                                                {{ config('settings.currency') ?? '৳' }}</span></h6>
                                        <h6> <strong>{{ __f('Check Out Page Total Price Title') }} : </strong><span id="total_val">
                                                {{ $subtotal + config('settings.deliveryinsidedhake') }}
                                                {{ config('settings.currency') ?? '৳' }}</span></h6>
                                    </div>
                                @endif
                            </div>
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
        let subtotal = parseFloat("{{ $subtotal ?? 0 }}");
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
