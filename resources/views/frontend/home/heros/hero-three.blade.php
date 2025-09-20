<style>
    .intro-slider-three.owl-carousel.owl-loaded {
        max-height: 400px !important;
        overflow: hidden !important;
    }
</style>
<section class="container">
    <div class="row g-5">
        <div class="col-md-9">
            <div class="intro-slider-container my-4">
                <div class="intro-slider intro-slider-three owl-carousel owl-theme owl-nav-inside owl-light"
                    data-toggle="owl"
                    data-owl-options='{ "dots": true, "nav": false, "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "responsive": {  "1200": {  "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "nav": false, "dots": true  }}}'>
                    @forelse ($sliders as $slider)
                        <div class="intro-slide-three" data-desktop-image="{{ asset($slider->slider_image) }}" data-mobile-image="{{ asset($slider->slider_m_image) }}">
                        </div>
                    @empty
                    @endforelse
                </div>
                <span class="slider-loader"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="my-4" id="slider-three-right-image">
                @if(config('settings.slider_style_three_right_image') != null)
                  <img src="{{ asset(config('settings.slider_style_three_right_image')) }}" alt="image">  
                @endif
            </div>
        </div>
    </div>
    <div class="mb-4"></div>
</section>
<script>
    $(document).ready(function() {
        $(".intro-slider-three").owlCarousel({
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
                    nav: true,
                    dots: true
                },
                1200: {
                    items: 1,
                    nav: true,
                    dots: true
                }
            }
        });
    });

    $(document).ready(function() {
        $('.intro-slide-three').each(function() {
            let desktopImage = $(this).data('desktop-image');
            let mobileImage = $(this).data('mobile-image');
            let isMobile = window.innerWidth <= 767;
            $(this).css('background-image', 'url(' + (isMobile ? mobileImage : desktopImage) + ')');
        });

        $(window).resize(function() {
            $('.intro-slide-three').each(function() {
                let desktopImage = $(this).data('desktop-image');
                let mobileImage = $(this).data('mobile-image');
                let isMobile = window.innerWidth <= 767;
                $(this).css('background-image', 'url(' + (isMobile ? mobileImage : desktopImage) + ')');
            });
        });
    });
</script>
