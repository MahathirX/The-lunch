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
            <div id="product-details-page-three" class="product-details-top">
                <div class="row g-5">
                    <div class="col-md-5">
                        <div class = "product-imgs">
                            <div class = "img-display">
                                <div class = "img-showcase">
                                    @if ($singleproduct->product_image != null)
                                        <img src="{{ asset($singleproduct->product_image) }}" alt="product image">
                                    @else
                                        <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                            alt="product image">
                                    @endif
                                    @forelse ($singleproduct->images->take(6) as $key => $image)
                                        <img src="{{ asset($image->image_path) }}" alt="product image">
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                            <div class = "img-select">
                                <div class = "img-item">
                                    <a href = "javascript:" data-id = "1">
                                        @if ($singleproduct->product_image != null)
                                            <img src="{{ asset($singleproduct->product_image) }}" alt="product image">
                                        @else
                                            <img src="{{ asset('frontend/assets/images/category/default-image.webp') }}"
                                                alt="product image">
                                        @endif
                                    </a>
                                </div>
                                @forelse ($singleproduct->images->take(6) as $key => $image)
                                    <div class = "img-item">
                                        <a href = "javascript:" data-id="{{ $key + 2 }}">
                                            <img src="{{ asset($image->image_path) }}" alt="product image">
                                        </a>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @php
                        $totalReviews = $singleproduct->reviews->count();
                        $averageRating = $totalReviews > 0 ? $singleproduct->reviews->sum('review') / $totalReviews : 0;
                        $ratingPercentage = ($averageRating / 5) * 100;
                    @endphp
                    <div class="col-md-7">
                        <div class="product-content">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="product-title mb-0">{{ $singleproduct->name ?? '' }}</h4>
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
                            <div class="ratings-container d-flex align-items-center">
                                <div class="ratings">
                                    <div class="ratings-val" style="width: {{ $ratingPercentage}}%;"></div>
                                </div>
                                <span class="fs-4 ms-2">{{  $averageRating ?? 0 }}</span>
                                <span class="fs-4 ms-1">({{  $totalReviews ?? 0 }})</span>
                            </div>
                            <div class = "product-prices">
                                @if ($singleproduct->discount_price != null)
                                    <p class="last-price mb-0">Old Price: <span>{{ config('settings.currency') ?? '৳' }} {{ $singleproduct->price ?? 0 }}</span></p>
                                    <p class="new-prices">New Price: <span>{{ config('settings.currency') ?? '৳' }} {{ $singleproduct->discount_price ?? 0 }} (5%)</span></p>
                                @else
                                    <p class="new-prices">Price: <span>{{ config('settings.currency') ?? '৳' }} {{ $singleproduct->price ?? 0 }}</span></p>
                                @endif


                            </div>

                            <div class="product-detail">
                                <h2>about this item: </h2>
                                {!! $singleproduct->short_description ?? '' !!}
                                {!! $singleproduct->special_feature ?? '' !!}
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
                                    <div class="product-details-one-others my-2">
                                        @if ($singleproduct?->categories->isNotEmpty())
                                            <div class="d-flex align-items-center">
                                                <p>Categories : </p>
                                                <span class="ms-1">
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
                            </div>
                            <div class="product-buy d-flex align-items-center border-bottom pb-2 mb-2">
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
                                            <span class="add-to-cart-button-{{ $singleproduct->id }}"><i class="fa-solid fa-cart-plus me-2"></i> {{ config('settings.addtocartbtntitle') ?? '' }}</span></button>
                                    </div>
                                </div>
                            </div>

                            <div class = "social-links">
                                @php
                                    $shareUrl = url()->full();
                                @endphp
                                <p>Share At : </p>
                                @if(config('settings.facebookurl') != null)
                                    <a target="_blank" title="Facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"><i class="fa-brands fa-facebook-f"></i></a>
                                @endif

                                @if(config('settings.linkedinurl') != null)
                                    <a target="_blank" title="Linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}"><i class="fa-brands fa-linkedin-in"></i></a>
                                @endif

                                @if(config('settings.twitterurl') != null)
                                    <a target="_blank" title="Twitter" href="https://twitter.com/intent/tweet?url={{ $shareUrl }}"><i class="fa-brands fa-x-twitter"></i></a>
                                @endif

                                @if(config('settings.pinteresturl') != null)
                                    <a target="_blank" title="Pinterest" href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}"><i class="fa-brands fa-pinterest"></i></a>
                                @endif

                                @if(config('settings.skypeurl') != null)
                                    <a target="_blank" title="Skype" href="https://web.skype.com/share?url={{ $shareUrl }}"><i class="fa-brands fa-skype"></i></a>
                                @endif

                                @if(config('settings.whatsappurl') != null)
                                    <a target="_blank" title="Whatsapp" href="https://wa.me/?text={{ $shareUrl }}"><i class="fa-brands fa-whatsapp"></i></a>
                                @endif

                                @if(config('settings.redditurl') != null)
                                    <a target="_blank" title="Reddit" href="https://www.reddit.com/submit?url={{ $shareUrl }}"><i class="fa-brands fa-reddit"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-details-tab">
                    <ul class="nav nav-pills frontend-product-title-one d-flex justify-content-center" role="tablist">
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
                                                        <div class="ratings-val" style="width: {{ $ratingPercentage }}%;">
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
            const imgs = document.querySelectorAll('.img-select a');
            const imgBtns = [...imgs];
            let imgId = 1;

            imgBtns.forEach((imgItem) => {
                imgItem.addEventListener('click', (event) => {
                    event.preventDefault();
                    imgId = imgItem.dataset.id;
                    slideImage();
                });
            });

            function slideImage() {
                const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

                document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
            }
            window.addEventListener('resize', slideImage);


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
