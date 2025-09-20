@php
    use Modules\Category\App\Models\Category;
@endphp
<style>
    .intro-slider-one.owl-carousel.owl-loaded {
        max-height: 250px !important;
        overflow: hidden !important;
    }

    /* @media only screen and (max-width: 767px) {
        .intro-slider-container {
            display: none !important;
        }
    } */
</style>
<div class="container intro-slider-container my-4">
    <div class="intro-slider intro-slider-one owl-carousel owl-theme owl-nav-inside owl-light" data-toggle="owl"
        data-owl-options='{ "dots": true, "nav": false, "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "responsive": {  "1200": {  "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "nav": false, "dots": true  }}}'>
        @forelse ($sliders as $slider)
            <div class="intro-slide" data-desktop-image="{{ asset($slider->slider_image) }}"
                data-mobile-image="{{ asset($slider->slider_m_image) }}">
                {{-- <div class="container intro-content">
                <div class="row justify-content-end">
                    <div class="col-auto col-sm-7 col-md-6 col-lg-5">
                        <h3 class="intro-subtitle text-third">{{ $slider->title  ?? ''}}</h3>
                        <h1 class="intro-title">{{ Str::limit($slider->sub_title ?? '' , 35, '...') }}</h1>
                        <div class="intro-price">
                            <sup class="intro-old-price">$ {{$slider->regular_price ?? '' }}</sup>
                            <span class="text-third">
                                $ {{ $slider->discount_price ?? '' }}<sup>{{ $slider->discount_price_sub ?? '' }}</sup>
                            </span>
                        </div>
                        <a href="{{ $slider->btn_url }}" class="btn btn-primary btn-round" target="{{ $slider->btn_target == 1 ? '_blank' : '' }}">
                            <span>{{ $slider->btn_text }}</span>
                            <i class="icon-long-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div> --}}
            </div>
        @empty
        @endforelse
    </div>
    <span class="slider-loader"></span>
</div>
<div class="mb-4"></div>
<script>
    $(document).ready(function() {
        $(".intro-slider").owlCarousel({
            dots: true,
            loop: true,
            autoplay: true,
            autoplayTimeout: 3000,
            nav: false,
            rtl: false,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            responsive: {
                0: {
                    items: 0,
                    nav: false,
                    dots: true
                },
                768: {
                    items: 1,
                    nav: false,
                    dots: true
                },
                1200: {
                    items: 1,
                    nav: false,
                    dots: true
                }
            }
        });
    });

    $(document).ready(function() {
        $('.intro-slide').each(function() {
            let desktopImage = $(this).data('desktop-image');
            let mobileImage = $(this).data('mobile-image');
            let isMobile = window.innerWidth <= 767;
            $(this).css('background-image', 'url(' + (isMobile ? mobileImage : desktopImage) + ')');
        });

        $(window).resize(function() {
            $('.intro-slide').each(function() {
                let desktopImage = $(this).data('desktop-image');
                let mobileImage = $(this).data('mobile-image');
                let isMobile = window.innerWidth <= 767;

                $(this).css('background-image', 'url(' + (isMobile ? mobileImage :
                    desktopImage) + ')');
            });
        });
    });
</script>
