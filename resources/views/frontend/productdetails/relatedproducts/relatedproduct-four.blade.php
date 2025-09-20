<div
    class="container dynamiccategorysection deals-product-four-template  {{ config('settings.productnavbarpositionchosevalue') == 1 ? '' : 'productnavbarpositiontop' }}"">
    <div class="heading heading-flex heading-border mb-3">
        <div class="heading-left">
            <h2 class="title mb-2 position-relative">{{ config('settings.product_singpage_title') ?? 'You May Also Like' }}</h2>
        </div>
    </div>

    <div class="tab-content tab-content-carousel">
        <div class="tab-pane p-0 fade show active" id="related-product-new-tab" role="tabpanel"
            aria-labelledby="related-product-new-link">
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='@json(getCarouselOptions())'>

                @foreach ($relatedProducts as $product)
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="product-prices">
                                    @if ($product->discount_price != null)
                                        <span>{{ config('settings.currency') ?? '৳' }}
                                            {{ $product->discount_price }}</span>
                                    @else
                                        <span>{{ config('settings.currency') ?? '৳' }}
                                            {{ $product->price }}</span>
                                    @endif
                                </div>
                                @if(!empty($product->categories) && count($product->categories) > 0)
                                    <div class="product-cat">
                                        <a href="{{ route('categories.show', ['slug' => $product->categories[0]->slug]) }}">
                                            {{ $product->categories[0]->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <h3 class="product-title text-center"><a title="{{ $product->name }}"
                                    href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="product-template-four-description">{!! $product->description ?? '' !!}</div>
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
                                    <span class="add-to-cart-button-{{ $product->id }}">{{ config('settings.addtocartbtntitle') ?? '' }}</span></button>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
<div class="mb-3"></div>
