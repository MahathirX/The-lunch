@extends('layouts.frontend')
@section('title', $title)
@push('styles')
    <style>
        #checkout-section {
            background: #dfdfdf;
        }

        .border-right {
            border-right: 1px solid #fff !important;
        }

        #checkout-section .checkout-section-left-side .lebel-content .form-control,
        #checkout-section .checkout-section-left-side .lebel-content .form-select {
            border-radius: 3px !important;
            padding: 8px 12px !important;
        }

        #checkout-section .checkout-section-left-side .lebel-content .form-control:focus,
        #checkout-section .checkout-section-left-side .lebel-content .form-select:focus {
            box-shadow: unset !important;
            border-color: var(--main-border-focus-color);
        }

        #checkout-section .checkout-section-left-side .lebel-content .select2-container {
            background: white;
            padding: 7px 6px;
            border-radius: 3px !important;
        }

        .select2-container--open .select2-dropdown--below {
            height: 35vh !important;
            overflow: auto !important;
            scroll-behavior: smooth !important;
            scrollbar-color: #fb7e63 white !important;
            scrollbar-width: thin !important;
        }


        #checkout-section .checkout-section-left-side .lebel-content #submit-btn {
            width: 100%;
            padding: 12px 0px;
            border: 0;
            background: #df9300;
            color: #fff;
            border-radius: 3px;
            font-weight: 700;
        }

        #checkout-section .checkout-section-right-side .cart-image-wapper span {
            position: absolute;
            top: -14px;
            right: -10px;
            width: 25px;
            height: 25px;
            background: var(--main-primary-color);
            display: grid;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            color: var(--main-primary-background);
            font-weight: 600;
            font-size: 14px;
        }

        #checkout-section .checkout-section-right-side .cart-image {
            width: 65px;
            height: 65px;
            border-radius: 3px;
        }

        #checkout-section .checkout-section-right-side .cart-product-name,
        #checkout-section .checkout-section-right-side .cart-product-price {
            color: var(--main-paragraph-color);
            margin-left: 10px;
            font-size: 16px;
            font-weight: 500;
        }

        #checkout-section .checkout-section-right-side .form-control {
            border-radius: 4px !important;
            transition: unset !important;
            padding: 14px 10px !important;
        }

        #checkout-section .checkout-section-right-side .form-control:focus {
            box-shadow: unset !important;
            border-color: var(--main-border-focus-color);
        }

        #checkout-section .checkout-section-right-side #coupon-apply-btn {
            width: 100%;
            padding: 6px 50px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            border: none;
            background: #df9300;
            border-radius: 3px;
            color: #fff;
        }
    </style>
@endpush
@section('content')
    <section id="checkout-section">
        <div class="container">
            <div class="checkout-section-wapper">
                <div class="row g-5">
                    <div class="col-md-7 p-5 border-right" id="check-out-from">
                        <form id="checkoutForm" action="{{ route('order.store.frontend.two') }}" method="POST">
                            @csrf
                            <input type="hidden" id="checkout_coupon_id" name="coupon_id" value="">
                            <div class="checkout-section-left-side">
                                <div class="lebel-section">
                                    <h5>{{ __f('Check Out Page Contact Title') }}</h5>
                                </div>
                                {{-- <div class="lebel-content mb-4">

                                </div> --}}
                                {{-- <div class="lebel-section">
                                    <h5>{{ __f('Check Out Page Billing details Title') }}</h5>
                                </div> --}}
                                <div class="lebel-content mb-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page Full Name Placeholder') }} *"
                                                name="fname" class="form-control"
                                                value="{{ Auth::check() ? Auth::user()->fname ?? '' : '' }}">
                                            <p class="text-danger fname-error mb-0"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="email"
                                                placeholder="{{ __f('Check Out Page Email Address Placeholder') }} *"
                                                name="email" class="form-control"
                                                value="{{ Auth::check() ? Auth::user()->email ?? '' : '' }}">
                                            <p class="text-danger email-error mb-0"></p>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page Last Name Placeholder') }} *"
                                                name="lname" class="form-control" value="{{ Auth::check() ? Auth::user()->lname ?? '' : '' }}">
                                                <p class="text-denger lname-error mb-0"></p>
                                        </div> --}}
                                        {{-- <div class="col-md-12">
                                            <select name="country" id="select" class="form-control selectpiker">
                                                @include('frontend.includestwo.country')
                                            </select>
                                        </div> --}}
                                        <div class="col-md-12">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page Phone Placeholder') }}*" name="phone"
                                                class="form-control"
                                                value="{{ Auth::check() ? Auth::user()->phone ?? '' : '' }}">
                                            <p class="text-danger phone-error mb-0"></p>
                                        </div>

                                        <div class="col-md-12">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page House Number Placeholder') }}  *"
                                                name="house_number" class="form-control"
                                                value="{{ Auth::check() ? Auth::user()->house_number ?? '' : '' }}">
                                            <p class="text-danger house_number-error mb-0"></p>
                                        </div>
                                        {{-- <div class="col-md-12">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page Apartment Suite Unit Placeholder') }} (optional)"
                                                name="apartment" class="form-control" value="{{ Auth::check() ? Auth::user()->apartment ?? '' : '' }}">
                                        </div> --}}
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-4">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page Town / City Placeholder') }} *"
                                                name="city" class="form-control" value="{{ Auth::check() ? Auth::user()->city ?? '' : '' }}">
                                                <p class="text-denger city-error mb-0"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page State / County Placeholder') }} *"
                                                name="state" class="form-control" value="{{ Auth::check() ? Auth::user()->state ?? '' : '' }}">
                                                <p class="text-denger state-error mb-0"></p>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text"
                                                placeholder="{{ __f('Check Out Page Postcode / ZIP Placeholder') }} *"
                                                name="zip" class="form-control" value="{{ Auth::check() ? Auth::user()->zip ?? '' : '' }}">
                                                <p class="text-denger zip-error mb-0"></p>
                                        </div>
                                    </div> --}}

                                </div>
                                {{-- <div class="lebel-section">
                                    <h5>{{ __f('Check Out Page Additional information Title') }}</h5>
                                </div>
                                <div class="lebel-content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <textarea type="text" placeholder="{{ __f('Check Out Page Notes Placeholder') }}" name="note"
                                                class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="lebel-section">
                                    <h5>{{ __f('Check Out Page Additional information Title') }}</h5>
                                </div>
                                <div class="lebel-content mb-2">
                                    <div class="row">
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
                                </div>
                                <div class="lebel-section">
                                    <h5>{{ __f('Check Out Page Payment Title') }}</h5>
                                </div>
                                <div class="lebel-content mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cod" id="cod"
                                            checked>
                                        <label class="form-check-label" for="cod">
                                            {{ __f('Check Out Page COD Title') }}
                                        </label>
                                    </div>
                                </div>
                                @php
                                    $btnsubtotal = 0;
                                @endphp
                                @if (count(session('cart', [])) > 0)
                                    @foreach (session('cart', []) as $id => $item)
                                        @php
                                            $itemTotal = $item['price'] * $item['quantity'];
                                            $btnsubtotal += $itemTotal;
                                        @endphp
                                    @endforeach
                                @endif
                                <div class="lebel-content mb-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="d-flex align-items-center justify-content-center" id="submit-btn"
                                                type="submit">
                                                <div class="spinner-border me-2 order-from-spinner d-none" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <i
                                                    class="fa-solid fa-lock me-2"></i>{{ __f('Check Out Page Place Order Button Title') }}

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-5 p-5" id="check-out-cart">
                        <div class="checkout-section-right-side">
                            @if (count(session('cart', [])) > 0)
                                @php
                                    $subtotal = 0;
                                @endphp
                                @foreach (session('cart', []) as $id => $item)
                                    @php
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $subtotal += $itemTotal;
                                    @endphp
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative cart-image-wapper">
                                                    <a href="{{ route('view.product', ['id' => $id]) }}">
                                                        <img class="cart-image"
                                                            src="{{ $item['image'] ? asset($item['image']) : asset('uploads/images/page/image/blank-image_833.jpg') }}"
                                                            alt="image">
                                                    </a>
                                                    <span>{{ $item['quantity'] ?? '' }}</span>
                                                </div>
                                                <a href="{{ route('view.product', ['id' => $id]) }}">
                                                    <p class="cart-product-name mb-0">
                                                        {{ Str::limit($item['name'], 25, '...') ?? '' }}</p>
                                                </a>
                                            </div>
                                            <p class="cart-product-price mb-0">{{ currency() ?? '' }}
                                                {{ $itemTotal ?? 0.0 }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            <div>
                                <form id="couponMatchForm" action="{{ route('match-coupon') }}" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-md-9">
                                            <input type="text" placeholder="Coupon Code" name="code"
                                                class="form-control" autocomplete="off">
                                            <p class="text-danger code-error"></p>
                                        </div>
                                        <div class="col-md-3">
                                            <div>
                                                <button type="submit" id="coupon-apply-btn">
                                                    <span id="loading-apply"
                                                        class="d-none">{{ __f('Check Out Page Applying Title') }}...</span>
                                                    <span id="loading">{{ __f('Check Out Page Apply Title') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <input type="hidden" id="hidden_sub_total_amount" value="{{ $subtotal ?? '' }}">
                            <input type="hidden" id="hidden_discount_total_amount" value="0">
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <h6>{{ __f('Check Out Page Subtotal Title') }}</h6>
                                <h6>{{ currency() ?? '' }} {{ $subtotal ?? 0.0 }}</h6>
                            </div>
                            <div id="check-out-discount-amount-main"
                                class="d-flex justify-content-between align-items-center mt-2 d-none">
                                <h6>{{ __f('Check Out Page Discount Amount Title') }}</h6>
                                <h6>{{ currency() ?? '' }} <span
                                        id="checkout-coupon-use-discount-amount">{{ $subtotal ?? 0.0 }}</span></h6>
                            </div>
                            <div id="check-out-discount-amount-main"
                                class="d-flex justify-content-between align-items-center mt-2">
                                <h6>{{ __f('Check Out Page Delivery Charge Title') }}</h6>
                                <h6>{{ currency() ?? '' }} <span
                                        id="checkout-deivary-charge">{{ config('settings.deliveryinsidedhake') ?? '' }}</span>
                                </h6>
                            </div>
                            @php
                                $subtotalWithValue = $subtotal ?? 0;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <h5>{{ __f('Check Out Page Total Title') }}</h5>
                                <h5>{{ currency() ?? '' }} <span
                                        id="checkout-coupon-use-grand-total">{{ $subtotalWithValue + config('settings.deliveryinsidedhake') }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).on('change', 'input[name="shipping"]', function() {
            let shippingCost = parseFloat($(this).val());
            let subtotal = parseFloat("{{ $subtotal ?? 0 }}");
            let total = subtotal + shippingCost;
            var hiddendiscountamount = $('#hidden_discount_total_amount').val();
            $('#checkout-deivary-charge').text(shippingCost);
            $('#checkout-coupon-use-grand-total').text(total - hiddendiscountamount);
            $('#submit-btn-total-amount').text(total - hiddendiscountamount);
        });

        $(document).ready(function() {
            $('#checkoutForm').on('submit', function(e) {
                e.preventDefault();
                $('.order-from-spinner').removeClass('d-none');
                $('.error-text').text('');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            $('.order-from-spinner').addClass('d-none');
                            setTimeout(() => {
                                window.location.href = window.location.href =
                                    "/order-success/" + res.order_id;
                            }, 100);
                        } else {
                            flashMessage(res.status, res.message);
                            $('.order-from-spinner').addClass('d-none');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.order-from-spinner').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.order-from-spinner').addClass('d-none');
                        }
                    }
                });
            });

            $('#couponMatchForm').on('submit', function(e) {
                e.preventDefault();
                $('#loading-apply').removeClass('d-none');
                $('#loading').addClass('d-none');
                $('.error-text').text('');
                let formData = new FormData(this);
                var hiddenamount = $('#hidden_sub_total_amount').val();
                var hiddendiscountamount = $('#hidden_discount_total_amount').val();
                let shippingCost = parseFloat($('input[name="shipping"]').val());

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            $('#checkout_coupon_id').val(res.coupon_id);
                            $('#loading').removeClass('d-none');
                            flashMessage(res.status, res.message);
                            $('#loading-apply').addClass('d-none');
                            $('#submit-btn-total-amount').html((res.amount + shippingCost));
                            $('#checkout-coupon-use-grand-total').html('');
                            $('#checkout-coupon-use-discount-amount').html('');
                            $('#checkout-coupon-use-grand-total').html((res.amount +
                                shippingCost));
                            $('#check-out-discount-amount-main').removeClass('d-none');
                            $('#checkout-coupon-use-discount-amount').html(res.discount);
                            $('#hidden_discount_total_amount').val(res.discount);
                        } else {
                            $('#checkout_coupon_id').val(null);
                            $('#loading').removeClass('d-none');
                            flashMessage(res.status, res.message);
                            $('#loading-apply').addClass('d-none');
                            $('#checkout-coupon-use-grand-total').html('');
                            $('#submit-btn-total-amount').html((res.amount + shippingCost));
                            $('#check-out-discount-amount-main').addClass('d-none');
                            $('#checkout-coupon-use-grand-total').html((res.amount +
                                shippingCost));
                            $('#hidden_discount_total_amount').val(0);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('#loading').removeClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $('#loading-apply').addClass('d-none');
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
