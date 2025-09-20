<style>
    #product_image {
        width: 90px;
        height: 80px;
    }
</style>
<ul>
    @forelse ($getsearchproduct as $product)
        <li>
            <a href="{{ route('view.product', ['id' => $product->id]) }}">
                <div class="d-flex">
                    @if ($product->product_image != null)
                        <img id="product_image" src="{{ asset($product->product_image) }}"
                        alt="{{ $product->name ?? 'image' }}">
                    @else
                        <img id="product_image" src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                        alt="{{ $product->name ?? 'image' }}">
                    @endif
                    
                    <div class="product_search_price">
                        <h4>{{ $product->name ?? '' }}</h4>
                        @if ($product->discount_price != null)
                            <p><span> {{ $product->discount_price }} {{ config('settings.currency') ?? '৳' }} <del> {{ $product->price }} {{ config('settings.currency') ?? '৳' }}</del></p>
                        @else
                            <p><span>{{ $product->price }} {{ config('settings.currency') ?? '৳' }}</span></p>
                        @endif
                    </div>
                </div>
            </a>
        </li>
    @empty
        <li class="text-danger">
            <p>No Product Found !</p>
        </li>
    @endforelse
</ul>
