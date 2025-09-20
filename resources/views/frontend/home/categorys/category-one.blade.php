<div class="container">
    <h2 class="title text-center mb-2">{{ config('settings.category_title') ?? 'Explore Popular Categories' }}</h2>
    <div class="cat-blocks-container category-one-template">
        <div class="row justify-content-center">
            <div class="tab-content tab-content-carousel">
                <div class="tab-pane p-0 fade show active" id="category-all-tab" role="tabpanel" aria-labelledby="category-all-link">
                    <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                        data-owl-options='{
                            "nav": false,
                            "dots": true,
                            "margin": 20,
                            "loop": true,
                            "autoplay": true,
                            "autoplayTimeout": 3000,
                            "responsive": {
                                "0": {
                                    "items":1
                                },
                                "480": {
                                    "items":2
                                },
                                "768": {
                                    "items":3
                                },
                                "992": {
                                    "items":4
                                },
                                "1280": {
                                    "items":6,
                                    "nav": false
                                }
                            }
                        }'>
                        @foreach ($categories as $category)
                            <div class="col-12 col-sm-12 col-lg-12 ">
                                <a href="{{ route('categories.show', $category->slug) }}"
                                    class="cat-block popular-categories">
                                    <figure class="px-3 pt-1">
                                        <span>
                                            @if($category->image != null)
                                                <img src="{{ asset($category->image) }}" alt="Category image">
                                            @else
                                                <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}" alt="Category image">
                                            @endif
                                        </span>
                                    </figure>
                                    <h3 class="cat-block-title mt-2"> {{ $category->name }} </h3>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-3"></div>
