<div class="container dynamiccategorysection position-relative {{ config('settings.productnavbarpositionchosevalue') == 1 ? '' : 'productnavbarpositiontop' }}"">
    <div class="heading heading-flex heading-border mb-3">
        <div class="heading-left">
            <h2 class="title mb-2">{{ config('settings.product_singpage_title') ?? 'You May Also Like' }}</h2>
        </div>
    </div>
    <div class="tab-content tab-content-carousel">
        <div class="tab-pane p-0 fade show active" id="related-product-new-tab" role="tabpanel" aria-labelledby="related-product-new-link">
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='@json(getCarouselOptions())'>
                @foreach ($relatedProducts as $product)
                    <div class="product">
                        <figure class="product-media">
                            @php
                                $persen = $product->price - $product->discount_price;
                                $totallpersen = round($persen / 100);
                            @endphp
                            @if ($product->discount_price != null)
                                <span class="product-label label-sale">{{ $totallpersen }} %</span>
                            @endif
                            <a href="{{ route('view.product',['id' => $product->id]) }}">
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
                            @if(!empty($product->categories) && count($product->categories) > 0)
                                <div class="product-cat">
                                    <a href="{{ route('categories.show', ['slug' => $product->categories[0]->slug]) }}">
                                        {{ $product->categories[0]->name }}
                                    </a>
                                </div>
                            @endif
                            <h3 class="product-title"><a href="{{ route('view.product',['id' => $product->id]) }}">{{ $product->name }}</a></h3>
                            <div class="product-price">
                                @if ($product->discount_price != null)
                                    <span class="new-price"> {{ $product->discount_price }} {{  config('settings.currency') ?? '৳' }}</span>
                                    <span class="old-price"> <del> {{ $product->price }} {{  config('settings.currency') ?? '৳' }}</del></span>
                                @else
                                    <span class="new-price"> {{ $product->price }} {{  config('settings.currency') ?? '৳' }}</span>
                                @endif
                            </div>
                            <div class="ratings-container">
                                <div class="ratings">
                                    @php
                                        $totalReviews = $product->reviews->count();
                                        $averageRating =
                                            $totalReviews > 0 ? $product->reviews->sum('review') / $totalReviews : 0;
                                        $ratingPercentage = ($averageRating / 5) * 100;
                                    @endphp
                                    <div class="ratings-val" style="width: {{ $ratingPercentage }}%; "></div>
                                </div>
                                <span class="ratings-text">( {{ $totalReviews === 0 || $totalReviews === 1 ? $totalReviews . ' Review' : $totalReviews . ' Reviews' }} )</span>
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
                                    <span class="add-to-cart-button-{{ $product->id }}">{{  config('settings.addtocartbtntitle') ?? '' }}</span></button>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
