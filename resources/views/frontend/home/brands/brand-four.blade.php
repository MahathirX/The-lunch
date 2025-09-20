@if ($brand && count($brand) > 0)
    <div class="container brand-template-four">
        <h2 class="title title-border position-relative mb-5">{{ config('settings.bands_title') ?? 'Shop by Brands' }}</h2>
        <div>
            <div class="slider">
                <div class="slide-track-rtl">
                    @php $oddBrands = $brand->filter(function($item, $key) { return $key % 2 != 0; }); @endphp
                    @foreach ($oddBrands as $brands)
                        <div class="slide">
                            <a href="{{ $brands && $brands->slug ? route('shop.by.brand', ['slug' => $brands->slug]) : 'javascript:' }}" class="brand">
                                @if ($brands->image != null)
                                   <img src="{{ asset($brands->image) }}" alt="{{ $brands->name }}" height="100" width="250">
                                @else 
                                    <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="{{ $brands->name }}" height="100" width="250">
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mb-5">
            <div class="slider">
                <div class="slide-track-ltr">
                    @php $evenBrands = $brand->filter(function($item, $key) { return $key % 2 == 0; }); @endphp
                    @foreach ($evenBrands as $brands)
                        <div class="slide">
                            <a href="{{ $brands && $brands->slug ? route('shop.by.brand', ['slug' => $brands->slug]) : 'javascript:' }}" class="brand">
                                @if ($brands->image != null)
                                   <img src="{{ asset($brands->image) }}" alt="{{ $brands->name }}" height="100" width="250">
                                @else 
                                    <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="{{ $brands->name }}" height="100" width="250">
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif