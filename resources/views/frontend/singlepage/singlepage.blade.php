<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title ?? '--' }} - {{ env('APP_NAME') }}</title>
    <meta name="title" content="@yield('metatitle')">
    <meta name="keywords" content="@yield('metakeywords')">
    <meta name="description" content="@yield('metadescription')">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(config('settings.favicon_first') ?? '') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(config('settings.favicon_second') ?? '') }}">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    {{-- <link rel="shortcut icon" href="{{ asset('frontend/assets/images/icons/favicon.ico') }}"> --}}
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css') }} ">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/owl-carousel/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/magnific-popup/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/jquery.countdown.css') }}">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/5starreview.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/skins/skin.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style/main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins/nouislider/nouislider.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- AOS Animation CSS-->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ route('dynamic.style') }}">
    <style>
        .required::after {
            content: '*';
            color: red;
        }

        #getDataImage,
        #getDataImageTwo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .toast-success {
            background-color: #51A351;
        }

        .toast-error {
            background-color: #BD362F;
        }

        .toast-info {
            background-color: #2F96B4;
        }

        .toast-warning {
            background-color: #F89406;
        }

        #main-page-loader {
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: 9999;
            background-color: #fff;
        }

        #page-loader {
            border: 10px solid rgba(217, 217, 217, 0.5);
            border-radius: 50%;
            border-top: 10px solid #3F3CF3;
            width: 120px;
            height: 120px;
            position: absolute;
            left: 50%;
            right: 0;
            top: 50%;
            margin-left: -60px;
            margin-top: -60px;
            bottom: 0;
            -webkit-animation: spin 2s linear infinite;
            -o-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .new-price-single,
        .old-price-single {
            padding: 10px;
            color: white !important;
            font-weight: 600;
            border-radius: ;
        }

        .new-price-single {
            background: green;
        }

        .old-price-single {
            padding: 10px;
            background: #808080;
            color: white !important;
            font-weight: 600;
            border-radius: ;
        }
    </style>
    @stack('styles')
</head>

<body>
    @php
        use Modules\Attribute\App\Models\Attributeoption;
    @endphp
    <div id="main-page-loader">
        <div id="page-loader"></div>
    </div>
    <div class="page-wrapper">
        <div class="cta cta-horizontal cta-horizontal-box bg-primary">
            <div class="container">
                <div class="row align-items-center">
                    <h1 class="text-white text-center">{!! $page->page_heading ?? '' !!}</h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center mt-4">
                {!! $page->page_link ?? '' !!}
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center mt-4">
                <h2 class="title text-center mb-2 frontend-product-title">{!! $page->slider_title ?? '' !!}</h2>
                <div class="tab-content tab-content-carousel">
                    <div class="tab-pane p-0 fade show active" id="single-page-new-tab" role="tabpanel"
                        aria-labelledby="single-page-new-link">
                        <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow"
                            data-toggle="owl"
                            data-owl-options='{
                                "nav": false,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
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
                                        "items":5,
                                        "nav": true
                                    }
                                }
                            }'>

                            @foreach ($page->image as $image)
                                <div class="product">
                                    <figure class="product-media">
                                        <a href="javascript:">
                                            <img src="{{ asset($image->image) }}" alt="image" class="image">
                                        </a>
                                    </figure>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container ">
            <div class="row align-items-center mt-4 text-center">
                {!! $page->product_overview ?? '' !!}
            </div>
            <div class="row align-items-center mt-2 text-center">
                <div class="d-flex align-items-center justify-content-center">
                    <div>
                        @if ($page->new_price != null)
                            <span class="new-price-single">Discount Price :
                                <span>{{ $page->new_price }} {{ config('settings.currency') ?? '৳' }}</span></span>
                            <span class="old-price-single"><del>Regular Price :

                                    <span>{{ $page->old_price }} {{ config('settings.currency') ?? '৳' }}</span></del></span>
                        @else
                            <span class="new-price-single"> <span
                                    >{{ $page->new_price }} {{ config('settings.currency') ?? '৳' }}</span></span>
                        @endif
                        <br>
                        <div class="d-flex align-items-center justify-content-center mt-3">
                            <img style="width: 50px" src="{{ asset('frontend/assets/images/phone.gif') }}"
                                alt="call">
                            {{ $page->phone ?? '' }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="container">
            <div class="row align-items-center mt-4">
                <h2 class="title text-center mb-2 frontend-product-title">{!! $page->slider_title ?? '' !!}</h2>
                <form id="landingPageOrderFrom" action="{{ route('landing.page.order.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row border p-4 mb-2">
                        <input type="hidden" name="product_id" id="" value="{{ $page->product->id }}">
                        <div class="col-lg-7">
                            <h2 class="checkout-title">Shipping Details</h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="name" class="required">Name </label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label class="required">Email </label>
                                    <input type="text" name="email" class="form-control" required
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="required">Phone </label>
                                    <input type="text" name="phone" class="form-control" required
                                        value="{{ old('phone') }}">
                                    @error('phone')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>

                                <div class="col-sm-6">
                                    <label class="required">Address</label>
                                    <input name="address" type="text" class="form-control"
                                        value="{{ old('address') }}">
                                    @error('address')
                                        <p class="text-danger">{{ $message ?? '' }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="col-md-12">
                                <div class="product-details">
                                    <h1 class="product-title">{{ $page->product->name }}</h1>
                                    @php
                                        $totalReviews = $page->product->reviews->count();
                                        $averageRating =
                                            $totalReviews > 0
                                                ? $page->product->reviews->sum('review') / $totalReviews
                                                : 0;
                                        $ratingPercentage = ($averageRating / 5) * 100;
                                    @endphp
                                    <div class="ratings-container d-block">
                                        <div class="ratings">
                                            <div class="ratings-val" style="width: {{ $ratingPercentage }}%;"></div>
                                        </div>
                                        <a class="ratings-text" href="#" id="review-link">(
                                            {{ $totalReviews === 0 || $totalReviews === 1 ? $totalReviews . ' Review' : $totalReviews . ' Reviews' }}
                                            )</a>
                                    </div>

                                    <div class="product-price d-block">
                                        @if ($page->product->discount_price != null)
                                            <span class="new-price"><span
                                                    class="discountpriceproduct">{{ $page->product->discount_price }} {{ config('settings.currency') ?? '৳' }}</span></span>
                                            <span class="old-price"><del>
                                                    <span
                                                        class="priceproduct">{{ $page->product->price }} {{ config('settings.currency') ?? '৳' }}</span></del></span>
                                        @else
                                            <span class="new-price"> <span
                                                    class="priceproduct">{{ $page->product->price }} {{ config('settings.currency') ?? '৳' }}</span></span>
                                        @endif
                                    </div>

                                    <div class="product-buy d-flex align-items-center">
                                        <div class="cart-product-quantity">
                                            <input type="number" name="product_qnt" class="form-control product-quantity-update"
                                                min="1" step="1"
                                                data-discount={{ $page->product->discount_price }}
                                                data-price="{{ $page->product->price }}"
                                                data-id="{{ $page->product->id }}" value="1" readonly />
                                        </div>
                                    </div>

                                    <div class="product-content mt-1 w-50">
                                        @if ($page->product->producttype == '1')
                                            @if ($page->product && $page->product->productattibute != null)
                                                <select name="product_varient" class="form-control"
                                                    id="product_varient">
                                                    @forelse ($page->product->productattibute as $attibute)
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
                                    </div>
                                    <tr class="summary-shipping-row">
                                        <td colspan="2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="free-shipping" value="{{ config('settings.deliveryinsidedhake') ?? '' }}"
                                                    name="shipping" class="custom-control-input">
                                                <label class="custom-control-label" for="free-shipping"> ঢাকার
                                                    ভিতরে   {{ config('settings.deliveryinsidedhake') ?? '' }} {{ config('settings.currency') ?? '৳' }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="summary-shipping-row">
                                        <td colspan="2">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="express-shipping" value=" {{ config('settings.deliveryoutsidedhake') ?? '' }}"
                                                    name="shipping" class="custom-control-input">
                                                <label class="custom-control-label" for="express-shipping">ঢাকার
                                                    বাইরে   {{ config('settings.deliveryoutsidedhake') }} {{ config('settings.currency') ?? '৳' }}</label>
                                            </div>
                                        </td>
                                    </tr>
                                        <p class="text-danger text-left shipping-error"></p>
                                    <div class="product-content w-50">
                                        <div class="addtocardbtnsection">
                                            <button id="add-to-cart-details" class="btn-product btn-cart text-white">
                                                <div class="spinner-border text-light me-2 d-none" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div><span>Submit</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @include('frontend.includes.footer')
        {{-- <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button> --}}
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function flashMessage(status, message) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            switch (status) {
                case 'success':
                    toastr.success(message);
                    break;

                case 'error':
                    toastr.error(message);
                    break;

                case 'info':
                    toastr.info(message);
                    break;

                case 'warning':
                    toastr.warning(message);
                    break;
            }
        }

        @if (Session::get('success'))
            flashMessage('success', "{{ Session::get('success') }}")
        @elseif (Session::get('error'))
            flashMessage('error', "{{ Session::get('error') }}")
        @elseif (Session::get('info'))
            flashMessage('info', "{{ Session::get('info') }}")
        @elseif (Session::get('warning'))
            flashMessage('warning', "{{ Session::get('warning') }}")
        @endif
    </script>
    <script>
        document.onreadystatechange = function() {
            var state = document.readyState;
            if (state == "interactive") {
                document.getElementById("mainbody").style.visibility = "hidden";
            } else if (state == "complete") {
                setTimeout(function() {
                    document.getElementById("interactive");
                    document.getElementById("main-page-loader").style.visibility = "hidden";
                    document.getElementById("mainbody").style.visibility = "visible";
                }, 500);
            }
        }
    </script>
    <!-- Toastr JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Plugins JS File -->
    <script src="{{ asset('frontend/assets/js/jquery.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/jquery.hoverIntent.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/jquery.waypoints.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/superfish.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/bootstrap-input-spinner.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/jquery.plugin.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/jquery.countdown.min.js') }} "></script>
    <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.elevateZoom.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wNumb.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nouislider.min.js') }}"></script>
    <!-- Main JS File -->
    <script src="{{ asset('frontend/assets/js/main.js') }} "></script>
    <!-- AOS Animation JS-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
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
        });
        $(document).ready(function() {
            $('#landingPageOrderFrom').on('submit', function(e) {
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
                            $('.error-text').text('');
                            $('.spinner-border').addClass('d-none');
                            $('#landingPageOrderFrom')[0].reset();
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
        });
    </script>
</body>

</html>
