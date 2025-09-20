@forelse ($products as $product)
    <div class="product">
        <figure class="product-media">
            <a href="{{ route('view.product', ['id' => $product->id]) }}">
                @if ($product->product_image != null)
                    <img src="{{ asset($product->product_image) }}" alt="Product image" class="product-image">
                @else
                    <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="Product image"
                        class="product-image">
                @endif
            </a>
        </figure>

        <div class="product-body">
            <h3 class="product-title text-center"><a
                    href="{{ route('view.product', ['id' => $product->id]) }}">{{ $product->name }}</a>
            </h3>
            <div class="product-template-three-description">{!! $product->description ?? '' !!}</div>
            <div class="product-prices text-center mb-1">
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
            <div class="addtocardbtnsection">
                <button id="add-to-cart-btn" class="btn-product-three-template" data-id="{{ $product->id }}"
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
@empty
<p class="text-danger">Product Not Found !</p>
@endforelse
