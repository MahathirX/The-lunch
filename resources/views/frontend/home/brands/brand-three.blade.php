@if ($brand && count($brand) > 0)
    <div class="container brand-template-three">
        <h2 class="title title-border position-relative mb-5">{{ config('settings.bands_title') ?? 'Shop by Brands' }}
        </h2>
        <div class="mb-5">
            <div class="slider">
                <div class="slide-track">
                    @foreach ($brand as $brands)
                        <div class="slide">
                            @if ($brands && $brands->slug)
                                <a href="{{ route('shop.by.brand', ['slug' => $brands->slug]) }}" class="brand">
                                    @if ($brands->image != null)
                                        <img src="{{ asset($brands->image) }}" alt="{{ $brands->name }}" height="100" width="250">
                                    @else
                                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                            alt="{{ $brands->name }}" height="100" width="250">
                                    @endif
                                </a>
                            @else
                                <a href="javascript:" class="brand">
                                    @if ($brands->image != null)
                                        <img src="{{ asset($brands->image) }}" alt="{{ $brands->name }}" height="100" width="250">
                                    @else
                                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                            alt="{{ $brands->name }}" height="100" width="250">
                                    @endif
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
