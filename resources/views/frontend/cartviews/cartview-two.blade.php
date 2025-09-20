@extends('layouts.frontend')
@section('title', $title)
@php
    use Modules\Product\App\Models\ProductAttribute;
@endphp
@push('scripts')
    <style>
        .cart-product-quantity {
            display: flex;
            align-items: center;
        }

        .cart-product-quantity .quantity-input {
            min-width: 75px;
        }

        .cart-product-quantity .update-cart {
            margin-left: 5px;
            background: red;
            padding: 3px 10px;
            margin-right: 5px;
            color: white;
            font-size: 19px;
            border-radius: 3px;
        }
    </style>
@endpush
@section('content')
    <div id="cart-view-two" class="page-content">
        <div class="cart">
            <div class="container">
                <div class="heading mb-2 text-center mt-2 main-title">
                    <h2>{{ config('settings.cartpagetitle') ?? '' }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive cart-view-two-wapper">
                            @if (count($cart) > 0)
                                <table class="table table-cart table-mobile table-border">
                                    <thead>
                                        <tr>
                                            <th width="30%" class="text-center">
                                                {{ __f('View Cart Page Table Name And Image Title') }}</th>
                                            <th width="20%">{{ __f('View Cart Page Table Price Title') }}</th>
                                            <th width="20%">{{ __f('View Cart Page Table Quntity Title') }}</th>
                                            <th width="30%" class="text-center">
                                                {{ __f('View Cart Page Table Total Title') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($cart as $id => $item)
                                            <tr>
                                                <td class="product-col text-center py-2">
                                                    <div class="product">
                                                        <figure class="product-media">
                                                            <a href="{{ route('view.product', ['id' => $id]) }}">
                                                                <img src="{{ asset($item['image'] ?? '') }}"
                                                                    alt="Product image">
                                                                <p class="ms-2">{{ $item['name'] ?? '' }}</p>
                                                            </a>
                                                        </figure>
                                                    </div>
                                                </td>
                                                <td class="total-col py-2">
                                                    {{ $item['price'] }}
                                                    {{ config('settings.currency') ?? '৳' }}</td>
                                                <td class="quantity-col text-center py-2">
                                                    <div class="cart-product-quantity">
                                                        <input type="number" class="form-control quantity-input"
                                                            value="{{ $item['quantity'] }}" min="1" step="1"
                                                            data-id="{{ $id }}" required
                                                            id="update_quntity_{{ $id }}">
                                                        <a href="javascript:void(0);"
                                                            onclick="updateQuntity({{ $id }})"
                                                            class="update-cart"><span></span><i
                                                                class="icon-refresh"></i></a>
                                                        <form id="cartremoveForm" action="{{ route('cart.remove') }}"
                                                            method="POST" class="mb-0">
                                                            @csrf
                                                            <input type="hidden" name="id"
                                                                value="{{ $id }}">
                                                            <button type="submit"><i class="icon-close"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                                <td class="total-col text-center py-2">
                                                    {{ $item['price'] * $item['quantity'] }}
                                                    {{ config('settings.currency') ?? '৳' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="cart-view-two-last-wapper">
                                    <div class="viewcartsales">
                                        <strong>{{ __f('View Cart Page Product Price Title') }} : </strong> <span>
                                            {{ array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) }}
                                            {{ config('settings.currency') ?? '৳' }}</span><br>
                                        <strong>{{ __f('View Cart Page Delivery Charge Title') }} : </strong><span> {{ config('settings.deliveryinsidedhake') ?? '' }}
                                            {{ config('settings.currency') ?? '৳' }}</span><br>
                                        <strong>{{ __f('View Cart Page Total Price Title') }} :</strong><span>
                                            {{ array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart)) + config('settings.deliveryinsidedhake') ?? '' }}
                                            {{ config('settings.currency') ?? '৳' }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-end mt-4">
                                        <a href="{{ route('check.out') }}" class="btn checkoutpagecommonbtn">{{ __f('View Cart Page Checkout Title') }}</a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <p class="text-center text-muted">{{ __f('View Cart Page Your Cart Empty Title') }}</p>
    @endif
@endsection
@push('scripts')
    <script>
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
    </script>
    <script>
        $('input[name="shipping"]').on('change', function() {
            const subtotal = parseFloat($('#total_subtotal').text());
            const shippingCost = parseFloat($(this).val());
            const total = subtotal + shippingCost;
            $('#gand_total').text(total.toFixed(2));
        });
        $(document).on('submit', '#cartremoveForm', function(e) {
            e.preventDefault();
            const projectRedirectUrl = "{{ route('view.cart') }}";
            const formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        flashMessage('success', res.message);
                        window.location.href = projectRedirectUrl;
                    }
                },
                error: function(xhr) {
                    console.log('Something went wrong. Please try again.');
                }
            });
        });
    </script>
@endpush
