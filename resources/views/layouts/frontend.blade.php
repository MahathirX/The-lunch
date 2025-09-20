<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- AOS Animation CSS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@100..800&family=Hind+Siliguri:wght@300;400;500;600;700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ route('dynamic.style') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {!! config('settings.pixcelsetupcode') !!}
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
            border: 5px solid rgba(217, 217, 217, 0.5);
            border-radius: 50%;
            border-top: 5px solid #ff9140  ;
            width: 50px;
            height: 50px;
            position: absolute;
            left: 50%;
            right: 0;
            top: 50%;
            margin-left: -60px;
            margin-top: -60px;
            /* bottom: 0; */
            -webkit-animation: spin 2s linear infinite;
            -o-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            /* transform: translateX(-50%,-50%); */
            /* transform: translate(-50%, -50%); */
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /*===== Preloader Two =======*/


        .preloader-floating-circles {
            position: relative;
            width: 50px;
            height: 50px;
            margin: auto;
            transform: scale(0.6);
            -o-transform: scale(0.6);
            -ms-transform: scale(0.6);
            -webkit-transform: scale(0.6);
            -moz-transform: scale(0.6);
        }

        .preloader-floating-circles .f_circleG {
            position: absolute;
            background-color: white;
            height: 10px;
            width: 10px;
            border-radius: 7px;
            -o-border-radius: 7px;
            -ms-border-radius: 7px;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
            animation-name: f_fadeG;
            -o-animation-name: f_fadeG;
            -ms-animation-name: f_fadeG;
            -webkit-animation-name: f_fadeG;
            -moz-animation-name: f_fadeG;
            animation-duration: 0.672s;
            -o-animation-duration: 0.672s;
            -ms-animation-duration: 0.672s;
            -webkit-animation-duration: 0.672s;
            -moz-animation-duration: 0.672s;
            animation-iteration-count: infinite;
            -o-animation-iteration-count: infinite;
            -ms-animation-iteration-count: infinite;
            -webkit-animation-iteration-count: infinite;
            -moz-animation-iteration-count: infinite;
            animation-direction: normal;
            -o-animation-direction: normal;
            -ms-animation-direction: normal;
            -webkit-animation-direction: normal;
            -moz-animation-direction: normal;
        }

        .preloader-floating-circles #frotateG_01 {
            left: 0;
            top: 20px;
            animation-delay: 0.2495s;
            -o-animation-delay: 0.2495s;
            -ms-animation-delay: 0.2495s;
            -webkit-animation-delay: 0.2495s;
            -moz-animation-delay: 0.2495s;
        }

        .preloader-floating-circles #frotateG_02 {
            left: 5px;
            top: 5px;
            animation-delay: 0.336s;
            -o-animation-delay: 0.336s;
            -ms-animation-delay: 0.336s;
            -webkit-animation-delay: 0.336s;
            -moz-animation-delay: 0.336s;
        }

        .preloader-floating-circles #frotateG_03 {
            left: 20px;
            top: 0;
            animation-delay: 0.4225s;
            -o-animation-delay: 0.4225s;
            -ms-animation-delay: 0.4225s;
            -webkit-animation-delay: 0.4225s;
            -moz-animation-delay: 0.4225s;
        }

        .preloader-floating-circles #frotateG_04 {
            right: 4px;
            top: 5px;
            animation-delay: 0.509s;
            -o-animation-delay: 0.509s;
            -ms-animation-delay: 0.509s;
            -webkit-animation-delay: 0.509s;
            -moz-animation-delay: 0.509s;
        }

        .preloader-floating-circles #frotateG_05 {
            right: 0;
            top: 20px;
            animation-delay: 0.5955s;
            -o-animation-delay: 0.5955s;
            -ms-animation-delay: 0.5955s;
            -webkit-animation-delay: 0.5955s;
            -moz-animation-delay: 0.5955s;
        }

        .preloader-floating-circles #frotateG_06 {
            right: 5px;
            bottom: 5px;
            animation-delay: 0.672s;
            -o-animation-delay: 0.672s;
            -ms-animation-delay: 0.672s;
            -webkit-animation-delay: 0.672s;
            -moz-animation-delay: 0.672s;
        }

        .preloader-floating-circles #frotateG_07 {
            left: 20px;
            bottom: 0;
            animation-delay: 0.7585s;
            -o-animation-delay: 0.7585s;
            -ms-animation-delay: 0.7585s;
            -webkit-animation-delay: 0.7585s;
            -moz-animation-delay: 0.7585s;
        }

        .preloader-floating-circles #frotateG_08 {
            left: 5px;
            bottom: 4px;
            animation-delay: 0.845s;
            -o-animation-delay: 0.845s;
            -ms-animation-delay: 0.845s;
            -webkit-animation-delay: 0.845s;
            -moz-animation-delay: 0.845s;
        }

        @keyframes f_fadeG {
            0% {
                background-color: black;
            }

            100% {
                background-color: white;
            }
        }

        @-webkit-keyframes f_fadeG {
            0% {
                background-color: black;
            }

            100% {
                background-color: white;
            }
        }

        /* Back To And Cart Top CSS Start  */
        #smail-device-menu {
            position: fixed;
            top: 50%;
            right: 10px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: #ff9140  ;
            color: #fff;
            cursor: pointer;
            padding: 4px 10px;
            border-radius: 2px;
            opacity: 0.7;
            transition: opacity 0.3s, transform 0.3s;
        }

        #smail-device-menu a {
            color: #fff;
        }

        #smail-device-menu #smail-device-cart-count {
            position: absolute;
            top: -20px;
            right: -13px;
            background: #ff9140  ;
            padding: 5px 5px;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            border-radius: 50%;
            color: black;
        }

        #backToTopBtn {
            display: none;
            position: fixed;
            bottom: 15px;
            right: 47px;
            z-index: 99;
            font-size: 18px;
            border: none;
            outline: none;
            background-color: #ff9140  ;
            color: #fff;
            cursor: pointer;
            padding: 2px 12px;
            border-radius: 2px;
            opacity: 0.7;
            transition: opacity 0.3s, transform 0.3s;
        }

        #backToTopBtn:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        @media (max-width: 992px) {
            #smail-device-menu {
                display: block !important;
            }
            #header-one-dropdown-cart {
                display: none !important;
            }
        }
        @media (min-width: 561px) {
            #dropdown-menu-backend-notification {
                width: 365px !important;
            }

        }
        @media (max-width: 560px) {
            #page-loader {
                margin-left: -86px !important;
            }
        }
        /* Back To Top CSS End  */
    </style>
    @stack('styles')
</head>

<body>
    <div id="main-page-loader">
        <div id="page-loader"></div>
    </div>
    <div class="page-wrapper">
        <!-- Header Start  -->
        @if (config('settings.navbarshowchosevalue') == 1 || config('settings.navbarshowchosevalue') == null)
            @if (config('settings.headerchosevalue') == 1)
                @include('frontend.includes.headers.header-one')
            @elseif(config('settings.headerchosevalue') == 2)
                @include('frontend.includes.headers.header-two')
            @elseif(config('settings.headerchosevalue') == 3)
                @include('frontend.includes.headers.header-three-for-computer')
            @else
                @include('frontend.includes.headers.header-five-for-daraz')
            @endif
        @endif
        <!-- Header End  -->
        <main class="main">
            @isset($breadcrumb)
                @if (config('settings.breadcrumbshowchosevalue') == 1 || config('settings.breadcrumbshowchosevalue') == null)
                    @if (config('settings.breadcrumchosevalue') == 1)
                        @include('frontend.includes.breadcrumbs.breadcrumb-one')
                    @elseif(config('settings.breadcrumchosevalue') == 2)
                        @include('frontend.includes.breadcrumbs.breadcrumb-two')
                    @else
                        @include('frontend.includes.breadcrumbs.breadcrumb-three')
                    @endif
                @endif
            @endisset
            @yield('content')
        </main><!-- End .main -->
        <!-- Login Modal Start -->
        @if (config('settings.footershowchosevalue') == 1 || config('settings.footershowchosevalue') == null)
            @if (config('settings.footerchosevalue') == 1)
                @include('frontend.includes.footers.footer-one')
            @elseif(config('settings.footerchosevalue') == 2)
                @include('frontend.includes.footers.footer-two')
            @elseif(config('settings.footerchosevalue') == 3)
                @include('frontend.includes.footers.footer-three')
            @elseif(config('settings.footerchosevalue') == 4)
                @include('frontend.includes.footers.footer-four')
            @else
                @include('frontend.includes.footers.footer-five')
            @endif
        @endif
        <!-- Login Modal End -->
    </div><!-- End .page-wrapper -->
    {{-- <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button> --}}

    <!-- Login Modal Start -->
    {{-- @include('components.models.register') --}}
    <!-- Login Modal End -->

    <!-- Login Modal Start -->
    @include('frontend.includes.mmenu')
    <!-- Login Modal End -->


    <!-- Home Modal Start -->
    {{-- @include('components.models.homemodal') --}}
    <!-- Home Modal End -->
    <!-- Footer Start  -->
    @include('frontend.includes.cartoffcanvas')

    {{-- <div id="smail-device-menu" class="d-none">
        <a title="Cart" class="position-relative" type="button" data-bs-toggle="offcanvas"
            data-bs-target=".cartOffcanvas" aria-controls="cartOffcanvas">
            <i class="fa-solid fa-cart-arrow-down"></i>
            <span id="smail-device-cart-count"> {{ count((array) session('cart')) }}</span>
        </a>
    </div> --}}
    <button id="backToTopBtn" title="Go to top">↑</button>


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
                }, 10);
            }
        }

        let backToTopBtn = $('#backToTopBtn');
        $(window).scroll(function() {
            if ($(this).scrollTop() > 200) {
                backToTopBtn.fadeIn();
            } else {
                backToTopBtn.fadeOut();
            }
        });
        backToTopBtn.click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
            return false;
        });

        $(document).on('keyup', '#frontent_product_search', function(e) {
            var searchvalues = $(this).val();
            $.ajax({
                url: "{{ route('product.search') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    searchvalue: searchvalues,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#showSearchProducts').html('');
                        $('#showSearchProducts').html(res.data);
                    } else {
                        $('#showSearchProducts').html('');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Plugins JS File -->
    <script src="{{ asset('frontend/assets/js/jquery.min.js') }} "></script>
    <script>
        $(document).on('click', '#add-to-cart-btn', function(e) {

            const productId = $(this).data("id");
            $('.badge-second-' + productId).css('opacity', 1);
            $('.add-to-card-loader-' + productId).removeClass('d-none');
            $('.add-to-cart-button-' + productId).addClass('d-none');
            var myCartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
            document.addEventListener("DOMContentLoaded", function() {
                var el = document.getElementById('offcanvasExample');
                if (el) {
                    var myOffcanvas = new bootstrap.Offcanvas(el);
                    myOffcanvas.show();
                }
            });

            $.ajax({
                url: "{{ route('add.to.cart') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                },
                success: function(res) {
                    flashMessage(res.status, res.message);
                    $('#header_two_cart_count').html(res.countcart);
                    $('#smail-device-cart-count').html(res.countcart);
                    $('.badge-second-' + productId).removeAttr('style');
                    $('.add-to-card-loader-' + productId).addClass('d-none');
                    $('.add-to-cart-button-' + productId).removeClass('d-none');
                    $('#offcanvas-dynamic-data').html('');
                    $('#offcanvas-dynamic-data').html(res.data);
                    $('#offcanvas-dynamic-subtotal').html(res.subTotal);
                    $('#offcanvas-dynamic-charge').html(res.charge);
                    $('#offcanvas-dynamic-total').html(res.totalPrice);
                    $('#offcanvas-dynamic-cart-count').html(res.dynamicartcount);
                    if ($(window).width() > 992) {
                        myCartOffcanvas.show();
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        $(document).on('submit', '#cartremoveFormHeader', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = formData.get('id');
            $('.remove-to-card-loader-' + id).removeClass('d-none');
            $('.remove-to-card-btn-' + id).addClass('d-none');
            console.log(id);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        flashMessage('success', res.message);
                        $('#header_two_cart_count').html(res.countcart);
                        $('#offcanvas-dynamic-data').html('');
                        $('#offcanvas-dynamic-data').html(res.data);
                        $('#offcanvas-dynamic-subtotal').html(res.subTotal);
                        $('#offcanvas-dynamic-charge').html(res.charge);
                        $('#offcanvas-dynamic-total').html(res.totalPrice);
                        $('#offcanvas-dynamic-cart-count').html(res.dynamicartcount);
                        $('#smail-device-cart-count').html(res.countcart);
                    }
                },
                error: function(xhr) {
                    console.log('Something went wrong. Please try again.');
                }
            });
        });
    </script>
    {{-- <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }} "></script> --}}
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
        AOS.init();
    </script>
    @stack('scripts')
</body>

</html>
