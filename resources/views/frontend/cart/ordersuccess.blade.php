@extends('layouts.frontend')
@section('title', $title)
{{-- @push('styles')
    <style>
        .text-denger {
            color: red;
        }

        :root {
            --primary-color: #28a745;
            --secondary-color: #6c757d;
            --success-bg: #d4edda;
            --success-border: #c3e6cb;
        }

        #order-success-section .success-container {
            background: white;
            border-radius: 6px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            overflow: hidden;
        }

        #order-success-section .success-header {
            background: linear-gradient(135deg,
                    rgba(255, 145, 64, 1),
                    rgba(185, 0, 255, 1));
            color: white;
            padding: 1rem 2rem;
            text-align: center;
            position: relative;
        }

        #order-success-section .success-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        #order-success-section .success-icon {
            font-size: 3rem;
            animation: bounceIn 1s ease-out;
        }

        #order-success-section .order-details {
            padding: 2rem;
        }

        #order-success-section .order-number {
            background: var(--success-bg);
            border: 1px solid var(--success-border);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        #order-success-section .order-item {
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
            transition: background-color 0.3s ease;
        }

        #order-success-section .order-item:hover {
            background-color: #e1e3e6;
            border-radius: 4px;
        }

        #order-success-section .order-item:last-child {
            border-bottom: none;
        }

        #order-success-section .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        #order-success-section .total-section {
            background: #e1e3e6;
            border-radius: 4px;
            padding: 0.5rem 10px;
            margin-top: 2rem;
            width: 50%;
            float: right;
            font-weight: 600;
            color: black;
        }

        #order-success-section .btn-custom {
            background: linear-gradient(135deg, var(--primary-color), #20c997);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        #order-success-section .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        #order-success-section .info-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        #order-success-section .status-badge {
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        #order-success-section .progress-timeline {
            position: relative;
            padding-left: 2rem;
        }

        #order-success-section .progress-step {
            position: relative;
            padding-bottom: 1.5rem;
        }

        #order-success-section .progress-step::before {
            content: '';
            position: absolute;
            left: -2rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            background: var(--primary-color);
            border-radius: 50%;
            z-index: 2;
        }

        #order-success-section .progress-step::after {
            content: '';
            position: absolute;
            left: -1.7rem;
            top: 1.2rem;
            width: 2px;
            height: calc(100% - 0.5rem);
            background: #e9ecef;
            z-index: 1;
        }

        #order-success-section .progress-step:last-child::after {
            display: none;
        }
    </style>
@endpush --}}
@section('content')
    <!-- Order Success -->
    <section id="order-success-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="success-container">
                        <!-- Success Header -->
                        <div class="success-header">
                            <div class="success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h1 class="h2 text-white mb-0">{{ __f('Order Details Page Order Confirmed Title') }}</h1>
                            <p class="mb-0 text-white">{{ __f('Order Details Page Order Confirmed Description') }}</p>
                        </div>

                        <!-- Order Details -->
                        <div class="order-details">
                            <!-- Order Number -->
                            <div class="order-number">
                                <h3 class="h5 mb-1 text-success">
                                    <i class="fas fa-receipt me-2"></i>{{ __f('Order Details Page Order Title') }}
                                    #ORD-{{ $order->id ?? '' }}
                                </h3>
                                <p class="mb-0 text-muted">{{ __f('Order Details Page Placed On Title') }}
                                    {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                                <span class="status-badge d-inline-block {{ orderStatusBg($order->status ?? '') }}">
                                    <i class="fas fa-clock me-1"></i> {!! orderstatuswithoutlabel($order->status ?? '') !!}
                                </span>
                            </div>

                            <div class="row ">
                                <!-- Order Items -->
                                <div class="col-md-8">
                                    <h4 class="h5 mb-3">
                                        <i
                                            class="fas fa-shopping-bag me-2 text-primary"></i>{{ __f('Order Details Page Order Items Title') }}
                                    </h4>
                                    @php
                                        $subtotal = 0;
                                    @endphp
                                    @if ($order->orderdetails && count($order->orderdetails) > 0)
                                        @forelse ($order->orderdetails as $details)
                                            @php
                                                $subtotal += $details->grandtotal;
                                            @endphp
                                            <div class="order-item d-flex align-items-center">
                                                @if ($details->product->product_image != null)
                                                    <img src="{{ asset($details->product->product_image ?? '') }}"
                                                        alt="Product" class="product-image me-3">
                                                @else
                                                    {{ 'N/A' }}
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        {{ Str::limit($details?->product?->name, 25, '...') }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                                        <span class="text-muted">{{ __f('Order Details Page Qty Title') }}:
                                                            {{ convertToLocaleNumber($details->quantity ?? 0) }}</span>
                                                        <span class="fw-bold">{{ currency() }}
                                                            {{ convertToLocaleNumber($details->grandtotal ?? 0) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-danger text-center">
                                                {{ __f('Order Details Page No Product Found Title') }}</p>
                                        @endforelse
                                    @endif
                                    <!-- Order Summary -->
                                    <div class="total-section mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ __f('Order Details Page Subtotal Title') }}:</span>
                                            <span>{{ currency() }} {{ convertToLocaleNumber($subtotal ?? 0) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>{{ __f('Order Details Page Charge Title') }} :</span>
                                            <span>{{ currency() }}
                                                {{ convertToLocaleNumber($order->charge ?? 0) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-success">
                                            <span>{{ __f('Order Details Page Discount Title') }} :</span>
                                            <span>{{ currency() }}
                                                {{ convertToLocaleNumber($order->discount ?? 0) }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between h5 mb-0">
                                            <strong>{{ __f('Order Details Page Total Title') }}:</strong>
                                            <strong class="text-primary">{{ currency() }}
                                                {{ convertToLocaleNumber($order->amount + $order->charge) }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <!-- Side Information -->
                                <div class="col-md-4">
                                    <!-- Delivery Information -->
                                    <div class="info-card">
                                        <h5 class="h6 mb-1">
                                            <i
                                                class="fas fa-truck me-2 text-primary"></i>{{ __f('Order Details Page Delivery Information Title') }}
                                        </h5>
                                        @php
                                            $startDate = now()->addDays(3)->format('F d,');
                                            $endDate = now()->addDays(5)->format('d - Y');
                                            $estimatedDelivery = $startDate . ' ' . $endDate;
                                        @endphp
                                        <p class="mb-0">
                                            <strong>{{ __f('Order Details Page Estimated Delivery Title') }}:</strong></p>
                                        <p class="text-success">{{ $estimatedDelivery ?? '' }}</p>
                                        <p class="mb-0">
                                            <strong>{{ __f('Order Details Page Delivery Address Title') }}:</strong></p>
                                        <address class="mb-0">
                                            <small>
                                                {{ __f('Order Details Page Name Title') }} :
                                                {{ $order?->user?->fname ?? '' }} {{ $order?->user?->lname ?? '' }}<br>
                                                {{ __f('Order Details Page Address Title') }} :
                                                {{ $order?->user?->house_number ?? $order?->adress }}
                                                {{ $order?->user?->apartment }}<br>
                                                {{-- {{ __f('Order Details Page City Title') }} : {{ $order?->user?->city ?? '' }}<br>
                                              {{ __f('Order Details Page State Title') }} :  {{ $order?->user?->state ?? '' }}<br>
                                              {{ __f('Order Details Page Zip Title') }} :  {{ $order?->user?->zip ?? ''}}<br>
                                              {{ __f('Order Details Page Country Title') }} :  {{ $order?->user?->country ?? ''}}<br> --}}
                                                {{ __f('Order Details Page Phone Title') }} :
                                                {{ $order?->user?->phone ?? '' }}
                                            </small>
                                        </address>
                                    </div>
                                </div>
                            </div>
                            <!-- Action Buttons -->
                            <div class="text-center mt-4">
                                <a target="_blank"
                                    href="{{ route('order.invoice.download', ['order_id' => $order->id ?? 0]) }}"
                                    class="btn btn-outline-primary">
                                    <i
                                        class="fas fa-download me-2"></i>{{ __f('Order Details Page Download Invoice Title') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
@endpush
