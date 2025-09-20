@extends('layouts.frontend')
@section('title', $title)
@php
    use Modules\Attribute\App\Models\Attributeoption;
@endphp
@push('styles')
    <style>
        .rate label {
            color: #ccc !important;
        }
    </style>
@endpush
@section('content')
    <div class="page-content">
        <div class="container">
            <div id="product-details-page-two" class="product-details-top">
                <div class="row">
                    <div class="col-md-5">
                        <div class="product-gallery product-gallery-vertical">
                            <div class="row">
                                <figure class="product-main-image">
                                    @if ($singleproduct->product_image != null)
                                        <img id="product-zoom" src="{{ asset($singleproduct->product_image) }}"
                                            data-zoom-image="{{ asset($singleproduct->product_image) }}"
                                            alt="product image">
                                    @else
                                        <img id="product-zoom"
                                            src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                            data-zoom-image="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                            alt="product image">
                                    @endif
                                    @if(config('settings.productdetailsmpdalimageshowchosevalue') == 2 || config('settings.productdetailsmpdalimageshowchosevalue') == null)
                                        @if (count($singleproduct->images) > 0)
                                            <a href="#" id="btn-product-gallery" class="btn-product-gallery">
                                                <i class="icon-arrows"></i>
                                            </a>
                                        @endif
                                    @endif
                                </figure>
                                <div class="product-thumb-scroll-wrapper" id="imageScrollWrapper">
                                    <div id="product-zoom-gallery" class="product-image-gallery px-0">
                                        @forelse ($singleproduct->images as $key => $image)
                                            <a class="product-gallery-item" href="javascript:"
                                                data-image="{{ asset($image->image_path) }}"
                                                data-zoom-image="{{ asset($image->image_path) }}">
                                                <img src="{{ asset($image->image_path) }}" alt="" draggable="false">
                                            </a>
                                        @empty
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="product-details product-details-name-one-wapper">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="product-title-one mb-0">{{ $singleproduct->name ?? '' }}</h4>
                                </div>
                                @if(config('settings.productpreviousnextshowchosevalue') == 2 || config('settings.productpreviousnextshowchosevalue') == null)
                                    <div class="next-preview-wapper">
                                        <a title="Previous Product"
                                            href="{{ $previousProduct ? route('view.product', ['id' => $previousProduct->id]) : 'javascript:' }}"><i
                                                class="fa-solid fa-arrow-left"></i></a>
                                        <a title="Next Product"
                                            href="{{ $nextProduct ? route('view.product', ['id' => $nextProduct->id]) : 'javascript:' }}"><i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                @endif
                            </div>
                            @php
                                $totalReviews = $singleproduct->reviews->count();
                                $averageRating =
                                    $totalReviews > 0 ? $singleproduct->reviews->sum('review') / $totalReviews : 0;
                                $ratingPercentage = ($averageRating / 5) * 100;
                            @endphp
                            <div class="product-price-one">
                                @if ($singleproduct->discount_price != null)
                                    <span class="old-price"><del> <span
                                                class="priceproduct">{{ config('settings.currency') ?? '৳' }}
                                                {{ $singleproduct->price }}</span> </del></span>
                                    <span class="new-price"> <span
                                            class="discountpriceproduct">{{ config('settings.currency') ?? '৳' }}
                                            {{ $singleproduct->discount_price }} </span></span>
                                @else
                                    <span class="new-price"> <span
                                            class="priceproduct">{{ config('settings.currency') ?? '৳' }}
                                            {{ $singleproduct->price }} </span></span>
                                @endif
                            </div>

                            {{-- <div class="product-content mt-1 w-50">
                                @if ($singleproduct->producttype == '1')
                                    @if ($singleproduct && $singleproduct->productattibute != null)
                                        <select name="product_varient" class="form-control" id="product_varient">
                                            @forelse ($singleproduct->productattibute as $attibute)
                                                @php
                                                    $colorname = Attributeoption::where(
                                                        'id',
                                                        $attibute->color_id,
                                                    )->first();
                                                    $sizername = Attributeoption::where(
                                                        'id',
                                                        $attibute->size_id,
                                                    )->first();
                                                @endphp
                                                <option value="{{ $attibute->id ?? '' }}">
                                                    {{ $colorname->attribute_option ?? '' }}-{{ $sizername->attribute_option ?? '' }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    @endif
                                @endif
                            </div> --}}
                            <div class="product-short-description">
                                {!! $singleproduct->short_description ?? '' !!}
                            </div>
                            <div class="product-buy d-flex align-items-center border-bottom pb-3">
                                <div class="cart-product-quantity">
                                    <input type="number" id="product-quantity-update"
                                        class="form-control product-quantity-update" min="1" step="1"
                                        data-discount={{ $singleproduct->discount_price }}
                                        data-price="{{ $singleproduct->price }}" data-id="{{ $singleproduct->id }}"
                                        value="1" readonly />
                                </div>
                                <div class="check-out-btn ms-3">
                                    <div class="addtocardbtnsection">
                                        <button id="add-to-cart-btn" class="btn-product"
                                            data-discount={{ $singleproduct->discount_price }}
                                            data-price="{{ $singleproduct->price }}" data-id="{{ $singleproduct->id }}"
                                            title="{{ config('settings.addtocartbtntitle') ?? '' }}">
                                            <div class="d-none add-to-card-loader-{{ $singleproduct->id }}">
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
                                            <span class="add-to-cart-button-{{ $singleproduct->id }}">{{ config('settings.addtocartbtntitle') ?? '' }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(config('settings.productdetailsbrandshowchosevalue') == 2 || config('settings.productdetailsbrandshowchosevalue') == null)
                                <div class="product-details-one-others my-2">
                                    @if ($singleproduct?->brand)
                                        <div class="d-flex align-items-center">
                                            <p>Brand : </p>
                                            <span class="ms-1">{{ $singleproduct->brand->name ?? '' }} </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            @if(config('settings.productdetailscategoryshowchosevalue') == 2 || config('settings.productdetailscategoryshowchosevalue') == null)
                                <div class="product-details-one-others mt-2">
                                    @if ($singleproduct?->categories->isNotEmpty())
                                        <div class="d-flex align-items-center">
                                            <p>Categories : </p>
                                            <span>
                                                @forelse ($singleproduct->categories as $categorie)
                                                    @if ($loop->last)
                                                       <a href="{{ route('categories.show', $categorie->slug) }}"> {{ $categorie->name ?? '' }}</a>
                                                    @else
                                                        <a href="{{ route('categories.show', $categorie->slug) }}"> {{ $categorie->name ?? '' }}</a> ,
                                                    @endif
                                                @empty
                                                @endforelse
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div class="product-details-one-special-feature">
                                {!! $singleproduct->special_feature ?? '' !!}
                            </div>
                            {{-- <div class="product-content">
                                <div class="d-flex align-items-center" id="productdetailscall">
                                    <img src="{{ asset('frontend/assets/images/phone.gif') }}" alt="call">
                                    <h4 class="color-black-class">{{ config('settings.company_cell') }}</h4>
                                </div>
                            </div> --}}
                            {{-- <div class="product-content">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td
                                                style="padding: 5px 0px; margin: 0px; border-bottom: 1px solid rgb(127, 46, 184);">
                                                <font color="#311873"><b>&nbsp;ঢাকায় ডেলিভারি খরচ</b></font>
                                            </td>
                                            <td
                                                style="padding: 5px 0px; margin: 0px; border-bottom: 1px solid rgb(127, 46, 184);">
                                                <font color="#311873"><span style="font-weight: 700;">৳
                                                        {{ config('settings.deliveryinsidedhake') ?? '' }}</span>
                                                </font>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="padding: 5px 0px; margin: 0px; border-bottom: 1px solid rgb(221, 221, 221);">
                                                <font color="#311873"><b>&nbsp;ঢাকার বাহিরে ডেলিভারি খরচ</b></font>
                                            </td>
                                            <td
                                                style="padding: 5px 0px; margin: 0px; border-bottom: 1px solid rgb(221, 221, 221);">
                                                <font color="#311873"><span style="font-weight: 700;">৳
                                                        {{ config('settings.deliveryoutsidedhake') }}</span>
                                                </font>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="product-details-action">
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="product-details-tab">
                    <ul class="nav nav-pills frontend-product-title-one" role="tablist">
                        <li class="nav-item ">
                            <a class="nav-link active" id="product-desc-link" data-toggle="tab" href="#product-desc-tab"
                                role="tab" aria-controls="product-desc-tab" aria-selected="true">Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab"
                                role="tab" aria-controls="product-review-tab" aria-selected="false">
                                {{ $totalReviews === 0 || $totalReviews === 1 ? 'Review (' . $totalReviews . ')' : 'Reviews (' . $totalReviews . ')' }}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel"
                            aria-labelledby="product-desc-link">
                            <div class="product-desc-content">
                                <h3>Product Information</h3>
                                {!! $singleproduct->description !!}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="product-review-tab" role="tabpanel"
                            aria-labelledby="product-review-link">
                            <button class="btn product-review-btn" style="float: right" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Review</button>
                            <div class="reviews mt-4">
                                @foreach ($singleproduct->reviews as $review)
                                    <div class="review">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <h4><a href="#">{{ $review->user->fname ?? '' }}
                                                        {{ $review->user->lname ?? 'No Name' }}</a></h4>
                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        @php
                                                            $ratingPercentage = ($review->review / 5) * 100;
                                                        @endphp
                                                        <div class="ratings-val"
                                                            style="width: {{ $ratingPercentage }}%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex">
                                                    @foreach ($review->reviewDetails as $detail)
                                                        <img src="{{ asset($detail->image) }}" alt="."
                                                            style="width: 100px;">
                                                    @endforeach
                                                </div>

                                                <div class="review-content">
                                                    <p>{!! $review->review_text !!}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @if (config('settings.productcardchosevalue') == 1)
                    @include(
                        'frontend.productdetails.relatedproducts.relatedproduct-one',
                        $relatedProducts)
                @elseif(config('settings.productcardchosevalue') == 2)
                    @include(
                        'frontend.productdetails.relatedproducts.relatedproduct-two',
                        $relatedProducts)
                @elseif(config('settings.productcardchosevalue') == 3)
                    @include(
                        'frontend.productdetails.relatedproducts.relatedproduct-three',
                        $relatedProducts)
                @elseif(config('settings.productcardchosevalue') == 4)
                    @include(
                        'frontend.productdetails.relatedproducts.relatedproduct-four',
                        $relatedProducts)
                @else
                    @include(
                        'frontend.productdetails.relatedproducts.relatedproduct-five',
                        $relatedProducts)
                @endif
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade product-review-template-one" id="exampleModal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Review From “{{ $singleproduct->name ?? '' }}”
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="reviewCreateForm" action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" name="product_id" value="{{ $singleproduct->id }}">
                        <div class="modal-body p-3">
                            <span class="required mb-0 fs-16px">Rating :</span>
                            <div class="d-flex align-items-center">
                                <div class="rate">
                                    <input type="radio" id="star5" name="review" value="5" />
                                    <label for="star5" title="text"></label>
                                    <input type="radio" id="star4" name="review" value="4" />
                                    <label for="star4" title="text"></label>
                                    <input type="radio" id="star3" name="review" value="3" />
                                    <label for="star3" title="text"></label>
                                    <input type="radio" id="star2" name="review" value="2" />
                                    <label for="star2" title="text"></label>
                                    <input type="radio" id="star1" name="review" value="1" />
                                    <label for="star1" title="text"></label>
                                </div>
                            </div>
                            <span class="error-text review-error text-danger"></span>
                            <div class="mb-2">
                                <span class="required fs-16px">Review </span>
                                <textarea class="form-control" name="review_text" placeholder="Rieview your comment" id="floatingTextarea"></textarea>
                                <span class="error-text review_text-error text-danger"></span>
                            </div>
                            <div>
                                <span for="exampleInputPassword1 fs-16px" class="form-label">Product
                                    Image(Optional)</span>
                                <input type="file" name="image[]" class="form-control" multiple>
                                <span class="error-text image-error text-danger">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary one-product-review-btn">Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- riview modal end --}}
    @endsection
    @push('scripts')
        <script>
            const scrollContainer = document.getElementById('imageScrollWrapper');
            let isDown = false;
            let startX;
            let scrollLeft;

            scrollContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                startX = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
                scrollContainer.classList.add('active');
            });

            scrollContainer.addEventListener('mouseleave', () => {
                isDown = false;
                scrollContainer.classList.remove('active');
            });

            scrollContainer.addEventListener('mouseup', () => {
                isDown = false;
                scrollContainer.classList.remove('active');
            });

            scrollContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offsetLeft;
                const walk = (x - startX) * 1;
                scrollContainer.scrollLeft = scrollLeft - walk;
            });

            document.querySelectorAll('#product-zoom-gallery img').forEach(img => {
                img.addEventListener('dragstart', (e) => e.preventDefault());
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#reviewCreateForm').on('submit', function(e) {
                    e.preventDefault();
                    $('.spinner-border').removeClass('d-none');
                    $('.error-text').text('');
                    let formData = new FormData(this);

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if (res.status == 'success') {
                                flashMessage(res.status, res.message);
                                setTimeout(() => {
                                    location.reload();
                                }, 100);
                            } else if (res.status == 'error') {
                                flashMessage(res.status, res.message);
                                setTimeout(() => {

                                    window.location.href = '/login';
                                }, 100);
                            } else if (res.status == 'warning') {
                                flashMessage(res.status, res.message);
                                setTimeout(() => {


                                }, 100);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                $('.spinner-border').addClass('d-none');
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('.' + key + '-error').text(value[0]);
                                });
                            } else {
                                $('.spinner-border').addClass('d-none');
                                console.log('Something went wrong. Please try again.');
                            }
                        }
                    });
                });

                $(document).on('keyup change', '.product-quantity-update', function() {
                    var discountprice = $(this).data('discount');
                    var price = $(this).data('price');
                    var productid = $(this).data('id');
                    var value = $(this).val();
                    var product_varient = $('#product_varient').val();

                    if (discountprice != null && discountprice != '') {
                        $('.discountpriceproduct').html(discountprice * value);
                        $('.priceproduct').html(price * value);
                    } else {
                        $('.priceproduct').html(price * value);
                    }
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: "{{ route('product.qnt.update') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            productid: productid,
                            value: value,
                            product_varient: product_varient,
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                // $('#cart_count').html(res.countcart);
                                // $('#drop_down_cart_product').html(res.data);
                                // $('#cart-total-price').html(res.totalPrice);
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr);
                        }
                    });
                });

                $(document).on('click', '#add-to-cart-details', function() {
                    var discountprice = $(this).data('discount');
                    var price = $(this).data('price');
                    var productid = $(this).data('id');
                    var value = $('.product-quantity-update').val();
                    var product_varient = $('#product_varient').val();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: "{{ route('product.qnt.update') }}",
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            productid: productid,
                            value: value,
                            product_varient: product_varient,
                        },
                        success: function(res) {
                            if (res.status == 'success') {
                                var route = "{{ route('check.out') }}";
                                // $('#cart_count').html(res.countcart);
                                // $('#drop_down_cart_product').html(res.data);
                                // $('#cart-total-price').html(res.totalPrice);
                                // flashMessage(res.status, res.message);
                                window.location.href = route;
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr);
                        }
                    });
                });

            });
        </script>
    @endpush
