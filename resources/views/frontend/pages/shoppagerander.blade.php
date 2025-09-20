<div class="products shop_product shop_product_layout{{ $layout ?? '3' }} mb-3 {{ $products ? count($products) > 0 ? '' : 'd-flex justify-content-center' : '' }}
 @if (config('settings.productcardchosevalue') == 2) {{ 'deals-product-two-template' }}
                        @elseif(config('settings.productcardchosevalue') == 3)
                            {{ 'deals-product-three-template' }}
                        @elseif(config('settings.productcardchosevalue') == 4)     
                            {{ 'deals-product-four-template' }}
                        @else
                            {{ 'deals-product-five-template' }} @endif">
    @if (config('settings.productcardchosevalue') == 1)
        @include('frontend.categorydetails.productcards.productcard-one', $products)
    @elseif(config('settings.productcardchosevalue') == 2)
        @include('frontend.categorydetails.productcards.productcard-two', $products)
    @elseif(config('settings.productcardchosevalue') == 3)
        @include('frontend.categorydetails.productcards.productcard-three', $products)
    @elseif(config('settings.productcardchosevalue') == 4)
        @include('frontend.categorydetails.productcards.productcard-four', $products)
    @else
        @include('frontend.categorydetails.productcards.productcard-five', $products)
    @endif
</div>
