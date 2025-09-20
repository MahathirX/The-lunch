<div class="container">
    <h2 class="title mb-2">{{ config('settings.category_title') ?? 'Explore Popular Categories' }}</h2>
    <div class="cat-blocks-container category-four-template">
        <div class="row justify-content-center">
                @foreach ($categories->take(6) as $category)
                    <div class="col-12 col-md-2 ">
                        <a href="{{ route('categories.show', $category->slug) }}" class="category-four-template-wapper">
                            <figure class="mb-0" >
                                <span>
                                    @if ($category->image != null)
                                        <img src="{{ asset($category->image) }}" alt="Category image">
                                    @else
                                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                            alt="Category image">
                                    @endif
                                </span>
                            </figure>
                            <h3 class="cat-block-title mt-1"> {{ $category->name }} </h3>
                        </a>
                    </div>
                @endforeach
        </div>
    </div>
</div>
<div class="mb-3"></div>
