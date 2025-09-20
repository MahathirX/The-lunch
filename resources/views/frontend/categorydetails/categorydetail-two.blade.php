@extends('layouts.frontend')
@section('title', $title)
@section('content')
    @php
        $navbarposition = 'categorydetails-two-template';
        if(config('settings.categorydetailsnavbarposition') == 2 || config('settings.categorydetailsnavbarposition') == null){
            $navbarposition = 'categorydetails-one-template';
        }else{
            $navbarposition = 'categorydetails-two-template';
        }
    @endphp
    <div class="page-content" id="{{ $navbarposition ?? '' }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="toolbox">
                        <div class="toolbox-left" id="small-device-search">
                            <a href="#" class="sidebar-toggler"><i class="icon-bars"></i>Filters</a>
                        </div>
                        <div class="toolbox-left">
                            <div class="toolbox-info" id="totalProducts">
                                @isset($category->products)
                                    {{ count($category->products) == 0 || count($category->products) == 1 ? count($category->products) . ' Product' : count($category->products) . ' Products' }}
                                @else
                                    {{ ' 0 Product' }}
                                @endisset
                            </div>
                        </div>
                        <div class="toolbox-right">
                            @if (config('settings.categorydetailssortbyfilltershowchosevalue') == 2 ||
                                    config('settings.categorydetailssortbyfilltershowchosevalue') == null)
                                <div class="toolbox-sort">
                                    <label for="sortby">Sort by:</label>
                                    <div class="select-custom">
                                        <select name="sortby" id="sortby_category" class="form-control">
                                            <option value="" selected="selected">Select Sort By</option>
                                            {{-- <option value="featured">Featured</option> --}}
                                            <option value="bestselling">Best selling</option>
                                            <option value="a_z">Alphabetically, A-Z</option>
                                            <option value="z_a">Alphabetically, Z-A</option>
                                            <option value="price_low_high">Price, low to high</option>
                                            <option value="price_high_low">Price, high to low</option>
                                            <option value="desc">Date, old to new</option>
                                            <option value="asc">Date, new to old</option>
                                        </select>
                                    </div>
                                </div>
                            @endif

                            @if (config('settings.categorydetailscardstyleshowchosevalue') == 2 ||
                                    config('settings.categorydetailscardstyleshowchosevalue') == null)
                                <div class="toolbox-layout">
                                    <a href="javascript:" class="btn-layout" id="layout_one">
                                        <svg width="16" height="10">
                                            <rect x="0" y="0" width="4" height="4" />
                                            <rect x="6" y="0" width="10" height="4" />
                                            <rect x="0" y="6" width="4" height="4" />
                                            <rect x="6" y="6" width="10" height="4" />
                                        </svg>
                                    </a>

                                    <a href="javascript:" class="btn-layout" id="layout_two">
                                        <svg width="10" height="10">
                                            <rect x="0" y="0" width="4" height="4" />
                                            <rect x="6" y="0" width="4" height="4" />
                                            <rect x="0" y="6" width="4" height="4" />
                                            <rect x="6" y="6" width="4" height="4" />
                                        </svg>
                                    </a>

                                    <a href="javascript:" class="btn-layout active" id="layout_three">
                                        <svg width="16" height="10">
                                            <rect x="0" y="0" width="4" height="4" />
                                            <rect x="6" y="0" width="4" height="4" />
                                            <rect x="12" y="0" width="4" height="4" />
                                            <rect x="0" y="6" width="4" height="4" />
                                            <rect x="6" y="6" width="4" height="4" />
                                            <rect x="12" y="6" width="4" height="4" />
                                        </svg>
                                    </a>

                                    <a href="javascript:" class="btn-layout" id="layout_four">
                                        <svg width="22" height="10">
                                            <rect x="0" y="0" width="4" height="4" />
                                            <rect x="6" y="0" width="4" height="4" />
                                            <rect x="12" y="0" width="4" height="4" />
                                            <rect x="18" y="0" width="4" height="4" />
                                            <rect x="0" y="6" width="4" height="4" />
                                            <rect x="6" y="6" width="4" height="4" />
                                            <rect x="12" y="6" width="4" height="4" />
                                            <rect x="18" y="6" width="4" height="4" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div id="dynamicproduct">
                        <div
                            class="products shop_product shop_product_layout3 mb-3 {{ $category ? count($category->products) > 0 ? '' : 'd-flex justify-content-center' : '' }}
                        @if (config('settings.productcardchosevalue') == 2) {{ 'deals-product-two-template' }}
                        @elseif(config('settings.productcardchosevalue') == 3)
                            {{ 'deals-product-three-template' }}
                        @elseif(config('settings.productcardchosevalue') == 4)     
                            {{ 'deals-product-four-template' }}
                        @else
                            {{ 'deals-product-five-template' }} @endif
                        ">
                            @isset($category->products)
                                @php
                                    $products = $category->products;
                                @endphp
                                @if (config('settings.productcardchosevalue') == 1)
                                    @include(
                                        'frontend.categorydetails.productcards.productcard-one',
                                        $products)
                                @elseif(config('settings.productcardchosevalue') == 2)
                                    @include(
                                        'frontend.categorydetails.productcards.productcard-two',
                                        $products)
                                @elseif(config('settings.productcardchosevalue') == 3)
                                    @include(
                                        'frontend.categorydetails.productcards.productcard-three',
                                        $products)
                                @elseif(config('settings.productcardchosevalue') == 4)
                                    @include(
                                        'frontend.categorydetails.productcards.productcard-four',
                                        $products)
                                @else
                                    @include(
                                        'frontend.categorydetails.productcards.productcard-five',
                                        $products)
                                @endif
                            @else
                                <p class="text-center text-danger">No Product Found !</p>
                            @endisset
                        </div>
                    </div>
                </div>
                <aside class="col-lg-3 order-lg-first" id="large-device-search">
                    <div class="sidebar sidebar-shop">
                        <div class="widget widget-clean">
                            <label>Filters:</label>
                            <a href="javascript:" class="sidebar-filter-clear">Clean All</a>
                        </div>

                        @if (config('settings.categorydetailsfilltershowchosevalue') == 2 ||
                                config('settings.categorydetailsfilltershowchosevalue') == null)
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true"
                                        aria-controls="widget-1">
                                        Category
                                    </a>
                                </h3>
                                <div class="collapse show" id="widget-1">
                                    <div class="widget-body">
                                        <div class="filter-items filter-items-count">
                                            @forelse ($categories as $categorie)
                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="selectedcategory[]"
                                                            class="custom-control-input" id="cat-{{ $categorie->id }}"
                                                            value="{{ $categorie->id }}">
                                                        <label class="custom-control-label"
                                                            for="cat-{{ $categorie->id }}">{{ $categorie->name ?? '' }}</label>
                                                    </div>
                                                    <span
                                                        class="item-count">{{ count($categorie->products) ?? '0' }}</span>
                                                </div>
                                            @empty
                                                <p class="text-danger text-center">No Category Found</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (config('settings.categorydetailsbrandfilltershowchosevalue') == 2 ||
                                config('settings.categorydetailsbrandfilltershowchosevalue') == null)
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-4" role="button" aria-expanded="true"
                                        aria-controls="widget-4">
                                        Brand
                                    </a>
                                </h3>

                                <div class="collapse show" id="widget-4">
                                    <div class="widget-body">
                                        <div class="filter-items filter-items-count">
                                            @forelse ($brands as $brand)
                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            name="selectboxbrand[]" id="brand-{{ $brand->id }}"
                                                            value="{{ $brand->id }}">
                                                        <label class="custom-control-label"
                                                            for="brand-{{ $brand->id }}">{{ $brand->name ?? '' }}</label>
                                                    </div>
                                                    <span class="item-count">{{ count($brand->products) ?? '0' }}</span>
                                                </div>
                                            @empty
                                                <p class="text-danger text-center">No Brand Found !</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (config('settings.categorydetailspricefilltershowchosevalue') == 2 ||
                                config('settings.categorydetailspricefilltershowchosevalue') == null)
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true"
                                        aria-controls="widget-5">
                                        Price
                                    </a>
                                </h3>

                                <div class="collapse show" id="widget-5">
                                    <div class="widget-body">
                                        <div class="filter-price">
                                            <div class="filter-price-text">
                                                Price Range:
                                                <span id="filter-price-range"></span>
                                            </div>
                                            <div id="price-slider"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </aside>
                <div class="sidebar-filter-overlay"></div>
                <aside class="sidebar-shop sidebar-filter">
                    <div class="sidebar-filter-wrapper">
                        <div class="widget widget-clean">
                            <label id="close-small-fillter"><i class="icon-close"></i>Filters</label>
                            <a href="#" class="sidebar-filter-clear">Clean All</a>
                        </div>
                        @if (config('settings.categorydetailsfilltershowchosevalue') == 2 ||
                                config('settings.categorydetailsfilltershowchosevalue') == null)
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true"
                                        aria-controls="widget-1">
                                        Category
                                    </a>
                                </h3>

                                <div class="collapse show" id="widget-1">
                                    <div class="widget-body">
                                        <div class="filter-items filter-items-count">
                                            @forelse ($categories as $categorie)
                                                <div class="filter-item">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="smailldevicesearch[]"
                                                            class="custom-control-input" id="scat-{{ $categorie->id }}"
                                                            value="{{ $categorie->id }}">
                                                        <label class="custom-control-label"
                                                            for="scat-{{ $categorie->id }}">{{ $categorie->name ?? '' }}</label>
                                                    </div>
                                                    <span
                                                        class="item-count">{{ count($categorie->products) ?? '0' }}</span>
                                                </div>
                                            @empty
                                                <p class="text-danger text-center">No Category Found</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-2" role="button" aria-expanded="true"
                                    aria-controls="widget-2">
                                    Size
                                </a>
                            </h3>

                            <div class="collapse show" id="widget-2">
                                <div class="widget-body">
                                    <div class="filter-items">
                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="size-1">
                                                <label class="custom-control-label" for="size-1">XS</label>
                                            </div>
                                        </div>

                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="size-2">
                                                <label class="custom-control-label" for="size-2">S</label>
                                            </div>
                                        </div>

                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" checked
                                                    id="size-3">
                                                <label class="custom-control-label" for="size-3">M</label>
                                            </div>
                                        </div>

                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" checked
                                                    id="size-4">
                                                <label class="custom-control-label" for="size-4">L</label>
                                            </div>
                                        </div>

                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="size-5">
                                                <label class="custom-control-label" for="size-5">XL</label>
                                            </div>
                                        </div>

                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="size-6">
                                                <label class="custom-control-label" for="size-6">XXL</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget widget-collapsible">
                            <h3 class="widget-title">
                                <a data-toggle="collapse" href="#widget-3" role="button" aria-expanded="true"
                                    aria-controls="widget-3">
                                    Colour
                                </a>
                            </h3>

                            <div class="collapse show" id="widget-3">
                                <div class="widget-body">
                                    <div class="filter-colors">
                                        <a href="#" style="background: #b87145;"><span class="sr-only">Color
                                                Name</span></a>
                                        <a href="#" style="background: #f0c04a;"><span class="sr-only">Color
                                                Name</span></a>
                                        <a href="#" style="background: #333333;"><span class="sr-only">Color
                                                Name</span></a>
                                        <a href="#" class="selected" style="background: #cc3333;"><span
                                                class="sr-only">Color Name</span></a>
                                        <a href="#" style="background: #3399cc;"><span class="sr-only">Color
                                                Name</span></a>
                                        <a href="#" style="background: #669933;"><span class="sr-only">Color
                                                Name</span></a>
                                        <a href="#" style="background: #f2719c;"><span class="sr-only">Color
                                                Name</span></a>
                                        <a href="#" style="background: #ebebeb;"><span class="sr-only">Color
                                                Name</span></a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        @if (config('settings.categorydetailsbrandfilltershowchosevalue') == 2 ||
                                config('settings.categorydetailsbrandfilltershowchosevalue') == null)
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-4" role="button" aria-expanded="true"
                                        aria-controls="widget-4">
                                        Brand
                                    </a>
                                </h3>
                                <div class="collapse show" id="widget-4">
                                    <div class="widget-body">
                                        <div class="filter-items">
                                            <div class="filter-item">
                                                @forelse ($brands as $brand)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                            name="selectboxbrandsmail[]" id="mbrand-{{ $brand->id }}"
                                                            value="{{ $brand->id }}">
                                                        <label class="custom-control-label"
                                                            for="mbrand-{{ $brand->id }}">{{ $brand->name ?? '' }}</label>
                                                    </div>
                                                @empty
                                                    <p class="text-danger text-center">No Brand Found !</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (config('settings.categorydetailspricefilltershowchosevalue') == 2 ||
                                config('settings.categorydetailspricefilltershowchosevalue') == null)
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title">
                                    <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true"
                                        aria-controls="widget-5">
                                        Price
                                    </a>
                                </h3>
                                <div class="collapse show" id="widget-5">
                                    <div class="widget-body">
                                        <div class="filter-price">
                                            <div class="filter-price-text">
                                                Price Range:
                                                <span id="filter-price-range"></span>
                                            </div>

                                            <div id="price-slider-smail"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var CategoryIds = [];
        var brandIds = [];
        var layout = 3;
        var minPrice = 0;
        var maxPrice = 0;
        var sortby_category = '';
        var csrfToken = $('meta[name="csrf-token"]').attr('content');


        if (typeof noUiSlider === 'object') {
            var currency = "{{ config('settings.currency') ?? '৳' }} ";
            var minrange = parseInt("{{ config('settings.price_min_range') ?? '0' }}", 10);
            var maxrange = parseInt("{{ config('settings.price_max_range') ?? '1000' }}", 10);
            var priceSlider = document.getElementById('price-slider');
            var priceSliderSmail = document.getElementById('price-slider-smail');
            noUiSlider.create(priceSlider, {
                start: [minrange, maxrange],
                connect: true,
                step: 50,
                margin: 200,
                range: {
                    'min': 0,
                    'max': maxrange
                },
                tooltips: true,
                format: wNumb({
                    decimals: 0,
                    suffix: currency,
                })
            });
            noUiSlider.create(priceSliderSmail, {
                start: [minrange, maxrange],
                connect: true,
                step: 50,
                margin: 200,
                range: {
                    'min': 0,
                    'max': maxrange
                },
                tooltips: true,
                format: wNumb({
                    decimals: 0,
                    suffix: currency,
                })
            });

            priceSlider.noUiSlider.on('update', function(values, handle) {
                $('#filter-price-range').text(values.join(' - '));
            });
            priceSliderSmail.noUiSlider.on('update', function(values, handle) {
                $('#filter-price-range').text(values.join(' - '));
            });

            priceSlider.noUiSlider.on('change', function(values) {
                minPrice = values[0];
                maxPrice = values[1];
                callToSearchFunction();
            });
            priceSliderSmail.noUiSlider.on('change', function(values) {
                minPrice = values[0];
                maxPrice = values[1];
                $('body').removeClass('sidebar-filter-active');
                $('.sidebar-toggler').removeClass('active');
                callToSearchFunction();
            });
        }

        $(document).ready(function() {
            $('#layout_one').on('click', function() {
                $(this).addClass('active');
                $('#layout_two').removeClass('active');
                $('#layout_three').removeClass('active');
                $('#layout_four').removeClass('active');
                $('.shop_product').addClass('shop_product_layout1');
                $('.shop_product').removeClass('shop_product_layout2');
                $('.shop_product').removeClass('shop_product_layout3');
                $('.shop_product').removeClass('shop_product_layout4');
                layout = 1;
            });
            $('#layout_two').on('click', function() {
                $('#layout_one').removeClass('active');
                $(this).addClass('active');
                $('#layout_three').removeClass('active');
                $('#layout_four').removeClass('active');
                $('.shop_product').addClass('shop_product_layout2');
                $('.shop_product').removeClass('shop_product_layout1');
                $('.shop_product').removeClass('shop_product_layout3');
                $('.shop_product').removeClass('shop_product_layout4');
                layout = 2;
            });
            $('#layout_three').on('click', function() {
                $('#layout_one').removeClass('active');
                $('#layout_two').removeClass('active');
                $(this).addClass('active');
                $('#layout_four').removeClass('active');
                $('.shop_product').addClass('shop_product_layout3');
                $('.shop_product').removeClass('shop_product_layout1');
                $('.shop_product').removeClass('shop_product_layout2');
                $('.shop_product').removeClass('shop_product_layout4');
                layout = 3;
            });
            $('#layout_four').on('click', function() {
                $('#layout_one').removeClass('active');
                $('#layout_two').removeClass('active');
                $('#layout_three').removeClass('active');
                $(this).addClass('active');
                $('.shop_product').addClass('shop_product_layout4');
                $('.shop_product').removeClass('shop_product_layout1');
                $('.shop_product').removeClass('shop_product_layout2');
                $('.shop_product').removeClass('shop_product_layout3');
                layout = 4;
            });

            // $('.sidebar-filter-clear').on('click', function() {
            //     window.location.reload();
            // });
        })

        $(document).ready(function() {
            $('input[name="smailldevicesearch[]"]').on('change', function() {
                CategoryIds = [];
                $('input[name="smailldevicesearch[]"]:checked').each(function() {
                    let value = $(this).val();
                    if (!CategoryIds.includes(value)) {
                        CategoryIds.push(value);
                    }
                });
                $('body').removeClass('sidebar-filter-active');
                $('.sidebar-toggler').removeClass('active');
                callToSearchFunction();
            });

            $('input[name="selectedcategory[]"]').on('change', function() {
                CategoryIds = [];
                $('input[name="selectedcategory[]"]:checked').each(function() {
                    let value = $(this).val();
                    if (!CategoryIds.includes(value)) {
                        CategoryIds.push(value);
                    }
                });
                callToSearchFunction();
            });
        });

        $(document).ready(function() {
            $('input[name="selectboxbrandsmail[]"]').on('change', function() {
                brandIds = [];
                $('input[name="selectboxbrandsmail[]"]:checked').each(function() {
                    let value = $(this).val();
                    if (!brandIds.includes(value)) {
                        brandIds.push(value);
                    }
                });
                $('body').removeClass('sidebar-filter-active');
                $('.sidebar-toggler').removeClass('active');
                callToSearchFunction();
            });

            $('input[name="selectboxbrand[]"]').on('change', function() {
                brandIds = [];
                $('input[name="selectboxbrand[]"]:checked').each(function() {
                    let value = $(this).val();
                    if (!brandIds.includes(value)) {
                        brandIds.push(value);
                    }
                });
                callToSearchFunction();
            });
        });

        $(document).ready(function() {
            $('#sortby_category').on('change', function() {
                sortby_category = $(this).val();
                callToSearchFunction();
            });
        });

        function callToSearchFunction() {
            $.ajax({
                url: "{{ route('shop.category.search') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    selectedCategories: CategoryIds,
                    layout: layout,
                    minPrice: minPrice,
                    maxPrice: maxPrice,
                    sortby_category: sortby_category,
                    brandIds: brandIds,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#dynamicproduct').html("");
                        $('#totalProducts').html("");
                        $('#dynamicproduct').html(res.data);
                        if (res.totalProducts == 0 || res.totalProducts == 1) {
                            $('#totalProducts').html(res.totalProducts + ' Product');
                        } else {
                            $('#totalProducts').html(res.totalProducts + ' Products');
                        }
                    }
                },
                error: function(xhr) {
                    console.log('Something went wrong. Please try again.');
                }
            });
        }
    </script>
@endpush
