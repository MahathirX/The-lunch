<div
    class="container dynamiccategorysection deals-product-five-template position-relative {{ config('settings.productnavbarpositionchosevalue') == 1 ? '' : 'productnavbarpositiontop' }}">
    <div class="heading heading-flex heading-border mb-3">
        <div class="heading-left">
            <h2 class="title mb-2">{{ $sections->name ?? '' }}</h2>
        </div>
    </div>

    <div class="tab-content tab-content-carousel">
        <div class="tab-pane p-0 fade show active" id="{{ $sections->id }}-new-tab" role="tabpanel"
            aria-labelledby="{{ $sections->id }}-new-link">
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='@json(getCarouselOptions())'>
                @foreach ($sections->products as $product)
                    <div class="product product-card">
                        <figure class="product-media">
                            @if ($product->brand != null)
                                <span class="product-label label-sale">{{ $product->brand->name ?? '' }}</span>
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
                        </figure>

                        <div class="product-body">
                            <div class="product-cat">
                                <a
                                    href="{{ route('categories.show', ['slug' => $sections->slug]) }}">{{ $sections->name }}</a>
                            </div>
                            <h3 class="product-title"><a
                                    href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="product-template-five-description">{!! $product->description ?? '' !!}</div>
                            <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                <div class="product-prices">
                                    @if ($product->discount_price != null)
                                        <span class="old-price"><del>{{ config('settings.currency') ?? '৳' }}
                                                {{ $product->price }}</del></span> <br>
                                        <span class="new-price">{{ config('settings.currency') ?? '৳' }}
                                            {{ $product->discount_price }}</span>
                                    @else
                                        <span class="new-price">{{ config('settings.currency') ?? '৳' }}
                                            {{ $product->price }}</span>
                                    @endif
                                </div>
                                <div class="addtocardbtnsection">
                                    <button id="add-to-cart-btn"
                                        class="btn-product-three-template btn-product-four-template"
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
                                        <span class="add-to-cart-button-{{ $product->id }}">{{ config('settings.addtocartbtntitle') ?? '' }}
                                            <i class="fa-solid fa-bag-shopping ms-2"></i></span></button>
                                </div>
                            </div>
                            <div class="ratings-container mb-0 d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="ratings">
                                        @php
                                            $totalReviews = $product->reviews->count();
                                            $averageRating =
                                                $totalReviews > 0
                                                    ? $product->reviews->sum('review') / $totalReviews
                                                    : 0;
                                            $ratingPercentage = ($averageRating / 5) * 100;
                                        @endphp
                                        <div class="ratings-val" style="width: {{ $ratingPercentage }}%; ">
                                        </div>
                                    </div>
                                    <span class="ratings-text">
                                        ({{ $totalReviews === 0 || $totalReviews === 1 ? $totalReviews . ' Review' : $totalReviews . ' Reviews' }})
                                    </span>
                                </div>
                                <div>
                                    @if ($product->product_stock_qty != null && $product->product_stock_qty > 0)
                                        <span class="instock">In Stock</span>
                                    @else
                                        <span class="outofstock">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
<div class="mb-3"></div>
