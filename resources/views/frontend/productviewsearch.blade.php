@extends('layouts.frontend')
@section('title', $title)
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="product-layout-all">
                    @foreach ($getsearchproduct as $key => $product)
                        <div class="product">
                            <div class="product-thumb card h-100">
                                <figure class="product-media">
                                    @php
                                        $percent = $product->price - $product->discount_price;
                                        $totalPercent = round(($percent / $product->price) * 100);
                                    @endphp
                                    @if ($product->discount_price != null)
                                        <div class="product-labels">
                                            <span
                                                class="product-label label-sale product-label-diagonal">{{ $totalPercent }}%</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('view.product', ['id' => $product->id]) }}">
                                        @if ($product->product_image != null)
                                            <img src="{{ asset($product->product_image) }}" alt="Product image"
                                            class="product-image">
                                        @else
                                            <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="Product image"
                                            class="product-image">
                                        @endif

                                    </a>
                                </figure>

                                <div class="product-body">
                                    <h3 class="product-title">
                                        <a href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-price">
                                        @if ($product->discount_price != null)
                                            <span class="new-price">
                                                {{ $product->discount_price }} {{ config('settings.currency') ?? '৳' }}</span>
                                            <span class="old-price"><del>
                                                    {{ $product->price }} {{ config('settings.currency') ?? '৳' }}</del></span>
                                        @else
                                            <span class="new-price">
                                                {{ $product->price }} {{ config('settings.currency') ?? '৳' }}</span>
                                        @endif
                                    </div>
                                    <div class="addtocardbtnsection">
                                        <button id="add-to-cart-btn" class="btn-product" data-id="{{ $product->id }}"
                                            title="{{ config('settings.addtocartbtntitle') ?? '' }}">
                                            <div class="d-none add-to-card-loader-{{ $product->id }}">
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
                                            <span class="add-to-cart-button-{{ $product->id }}">{{ config('settings.addtocartbtntitle') ?? 'Add to Cart' }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
