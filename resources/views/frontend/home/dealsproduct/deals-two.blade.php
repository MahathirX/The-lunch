<div class="pt-3 pb-5 deals-product-two-template">
    <div class="container">
        <div class="heading heading-flex heading-border mb-3">
            <div class="heading-left">
                <h2 class="title">{{ config('settings.deals_product_title') ?? 'Hot Deals Products' }} </h2>
            </div>
            <div class="heading-right">
                <ul class="nav nav-pills nav-border-anim justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="hot-all-link" data-toggle="tab" href="#hot-all-tab" role="tab"
                            aria-controls="hot-all-tab" aria-selected="true">All</a>
                    </li>
                    @forelse ($dealsCategorys as $Category)
                        <li class="nav-item">
                            <a class="nav-link" id="hot-{{ $Category->id }}-link" data-toggle="tab"
                                href="#hot-{{ $Category->id }}-tab" role="tab"
                                aria-controls="hot-{{ $Category->id }}-tab"
                                aria-selected="false">{{ $Category->name ?? '' }}</a>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="tab-content tab-content-carousel">
            <div class="tab-pane p-0 fade show active" id="hot-all-tab" role="tabpanel" aria-labelledby="hot-all-link">
                <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                    data-owl-options='@json(getDealsThreeCarouselOptions())'>
                    @forelse ($dealsCategorys as $Category)
                        @foreach ($Category->products as $product)
                            <div class="product">
                                <figure class="product-media">
                                    @php
                                        $persen = $product->price - $product->discount_price;
                                        $totallpersen = round($persen / 100);
                                    @endphp
                                    @if ($product->discount_price != null)
                                        <span class="product-label label-sale">{{ $totallpersen }} %</span>
                                    @endif
                                    <a href="{{ route('view.product', ['id' => $product->id]) }}">
                                        @if ($product->product_image != null)
                                            <img src="{{ asset($product->product_image) }}" alt="Product image"
                                                class="product-image">
                                        @else
                                            <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                                alt="Product image" class="product-image">
                                        @endif
                                    </a>

                                    <div class="product-action-vertical">
                                        {{-- <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                to
                                                wishlist</span></a>
                                        <a href="#" class="btn-product-icon btn-compare"
                                            title="Compare"><span>Compare</span></a>
                                        <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                            title="Quick view"><span>Quick view</span></a> --}}
                                    </div>

                                    {{-- <div class="product-action">
                                        <button id="add-to-cart-btn" class="btn-product btn-cart"
                                            data-id="{{ $product->id }}" title="Add to cart"><span>add to
                                                cart</span></button>

                                    </div> --}}
                                </figure>

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a
                                            href="{{ route('categories.show', ['slug' => $Category->slug]) }}">{{ $Category->name }}</a>
                                    </div>
                                    <h3 class="product-title"><a title="{{ $product->name }}"
                                            id="template-two-product-name"
                                            href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-template-two-description">{!! $product->description ?? '' !!}</div>
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if ($product->discount_price != null)
                                                    <span class="old-price"><del>{{ config('settings.currency') ?? '৳' }}
                                                            {{ $product->price }}</del></span>
                                                    <span class="new-price">{{ config('settings.currency') ?? '৳' }}
                                                        {{ $product->discount_price }}</span>
                                                @else
                                                    <span class="new-price">{{ config('settings.currency') ?? '৳' }}
                                                        {{ $product->price }}</span>
                                                @endif
                                            </div>
                                            <button id="add-to-cart-btn" class="btn-product-two-template"
                                                data-id="{{ $product->id }}"
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
                                                <i class="fa-solid fa-cart-plus add-to-cart-button-{{ $product->id }}"></i></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            @forelse ($dealsCategorys as $Category)
                <div class="tab-pane p-0 fade" id="hot-{{ $Category->id }}-tab" role="tabpanel"
                    aria-labelledby="hot-{{ $Category->id }}-link">
                    <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                        data-owl-options='@json(getCarouselOptions())'>
                        @foreach ($Category->products as $product)
                            <div class="product">
                                <figure class="product-media">
                                    @php
                                        $persen = $product->price - $product->discount_price;
                                        $totallpersen = round($persen / 100);
                                    @endphp
                                    @if ($product->discount_price != null)
                                        <span class="product-label label-sale">{{ $totallpersen }} %</span>
                                    @endif
                                    <a href="{{ route('view.product', ['id' => $product->id]) }}">
                                        @if ($product->product_image != null)
                                            <img src="{{ asset($product->product_image) }}" alt="Product image"
                                                class="product-image">
                                        @else
                                            <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                                alt="Product image" class="product-image">
                                        @endif
                                    </a>

                                    <div class="product-action-vertical">
                                        {{-- <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                to
                                                wishlist</span></a>
                                        <a href="#" class="btn-product-icon btn-compare"
                                            title="Compare"><span>Compare</span></a>
                                        <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                            title="Quick view"><span>Quick view</span></a> --}}
                                    </div>

                                    {{-- <div class="product-action">
                                        <button id="add-to-cart-btn" class="btn-product btn-cart"
                                            data-id="{{ $product->id }}" title="Add to cart"><span>add to
                                                cart</span></button>

                                    </div> --}}
                                </figure>

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a
                                            href="{{ route('categories.show', ['slug' => $Category->slug]) }}">{{ $Category->name }}</a>
                                    </div>
                                    <h3 class="product-title"><a title="{{ $product->name }}" id="template-two-product-name"
                                            href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-template-two-description">{!! $product->description ?? '' !!}</div>
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if ($product->discount_price != null)
                                                    <span class="old-price"><del>{{ config('settings.currency') ?? '৳' }}
                                                            {{ $product->price }}</del></span>
                                                    <span class="new-price">{{ config('settings.currency') ?? '৳' }}
                                                        {{ $product->discount_price }}</span>
                                                @else
                                                    <span class="new-price">{{ config('settings.currency') ?? '৳' }}
                                                        {{ $product->price }}</span>
                                                @endif
                                            </div>
                                            <button id="add-to-cart-btn" class="btn-product-two-template"
                                                data-id="{{ $product->id }}"
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
                                                <i class="fa-solid fa-cart-plus add-to-cart-button-{{ $product->id }}"></i></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>
<div class="mb-3"></div>
