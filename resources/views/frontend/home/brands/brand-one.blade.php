@if($brand && count($brand) > 0)
    <div class="container brand-template-one  {{ config('settings.brandnavbarpositionchosevalue') == 1 ? '' : 'brandnavbarpositiontop' }}">
    <h2 class="title title-border position-relative mb-5">{{ config('settings.bands_title') ?? 'Shop by Brands' }}</h2>
    <div class="owl-carousel mb-5 owl-simple" data-toggle="owl"
        data-owl-options='@json(getBrandCarouselOptions())'>
        @foreach ($brand as $brands)
            @if ($brands && $brands->slug)
                <a href="{{ route('shop.by.brand', ['slug' => $brands->slug]) }}" class="brand">
                    @if ($brands->image != null)
                        <img src="{{ asset($brands->image) }}" alt="{{ $brands->name }}">
                    @else
                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="{{ $brands->name }}">
                    @endif
                </a>
            @else
                <a href="javascript:" class="brand">
                    @if ($brands->image != null)
                        <img src="{{ asset($brands->image) }}" alt="{{ $brands->name }}">
                    @else
                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="{{ $brands->name }}">
                    @endif
                </a>
            @endif
        @endforeach
    </div>
</div>
@endif