
@forelse ($products as $key=> $product)
    <div class="col-6 col-md-4 col-lg-4 col-xl-3">
        <div class="product">
            <figure class="product-media">
                     @php
                        $persen = $product->price - $product->discount_price;
                        $totallpersen = round($persen / 100);
                    @endphp
                    @if ($product->discount_price != null)
                        <span class="product-label label-sale"> {{ $totallpersen }} %</span>
                    @endif
                <a href="{{ route('view.product', ['id' => $product->id]) }}">
                    @if ($product->product_image != null)
                        <img src="{{ asset($product->product_image) }}" alt="Product image" class="product-image">
                    @else
                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="Product image" class="product-image">
                    @endif

                </a>
                <div class="product-action-vertical">
                    {{-- <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add
                            to wishlist</span></a> --}}
                </div>

                {{-- <div class="product-action action-icon-top">
                    <button id="add-to-cart-btn" class="btn-product btn-cart" data-id="{{ $product->id }}"
                        title="Add to cart"><span>add to
                            cart</span></button>
                </div> --}}
            </figure>

            <div class="product-body">
                <div class="product-cat">
                    <a href="{{ route('categories.show',['slug' => $product->category->slug]) }}">{{ $product->category->name }}</a>
                </div>
                <h3 class="product-title"><a
                        href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                </h3>
                <div class="product-price">
                    @if ($product->discount_price != null)
                        <span class="new-price">{{ $product->discount_price }} {{ config('settings.currency') ?? '৳' }}</span>
                        <span class="old-price"><del> {{ $product->price }} {{ config('settings.currency') ?? '৳' }}</del></span>
                    @else
                        <span class="new-price"> {{ $product->price }} {{ config('settings.currency') ?? '৳' }}</span>
                    @endif
                </div>
                <div class="ratings-container">
                    <div class="ratings">
                        @php
                            $totalReviews = $product->reviews->count();
                            $averageRating = $totalReviews > 0 ? $product->reviews->sum('review') / $totalReviews : 0;
                            $ratingPercentage = ($averageRating / 5) * 100;
                        @endphp
                        <div class="ratings-val" style="width: {{ $ratingPercentage }}%;"></div>
                    </div>
                    <span class="ratings-text">
                        ({{ $totalReviews === 0 || $totalReviews === 1 ? $totalReviews . ' Review' : $totalReviews . ' Reviews' }})
                    </span>
                </div>
                <div class="addtocardbtnsection">
                    <button id="add-to-cart-btn" class="btn-product btn-cart" data-id="{{ $product->id }}"
                        title="{{  config('settings.addtocartbtntitle') ?? '' }}">
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
                        <span class="add-to-cart-button-{{ $product->id }}"> {{  config('settings.addtocartbtntitle') ?? '' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="d-flex justify-content-center align-items-center w-100" style="height: 200px;">
        <h4 class="text-center text-danger">No product Found!</h4>
    </div>
@endforelse
