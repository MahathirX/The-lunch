<style>
    .intro-slider-four.owl-carousel.owl-loaded {
        max-height: 500px !important;
        overflow: hidden !important;
    }
</style>
<section class="container">
        <div class="intro-slider-container my-4">
            <div class="intro-slider intro-slider-five owl-carousel owl-theme owl-nav-inside owl-light" data-toggle="owl"
                data-owl-options='{ "dots": true, "nav": false, "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "responsive": {  "1200": {  "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "nav": false, "dots": true  }}}'>
                @forelse ($sliders as $slider)
                    <div class="intro-slide-five" data-desktop-image="{{ asset($slider->slider_image) }}"
                        data-mobile-image="{{ asset($slider->slider_m_image) }}">
                    </div>
                @empty
                @endforelse
            </div>
            <span class="slider-loader"></span>
        </div>
    <div class="mb-4"></div>
</section>
<script>
    $(document).ready(function() {
        $(".intro-slider-five").owlCarousel({
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
                    items: 1,
                    nav: true,
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
        $('.intro-slide-five').each(function() {
            let desktopImage = $(this).data('desktop-image');
            let mobileImage = $(this).data('mobile-image');
            let isMobile = window.innerWidth <= 767;
            $(this).css('background-image', 'url(' + (isMobile ? mobileImage : desktopImage) + ')');
        });

        $(window).resize(function() {
            $('.intro-slide-five').each(function() {
                let desktopImage = $(this).data('desktop-image');
                let mobileImage = $(this).data('mobile-image');
                let isMobile = window.innerWidth <= 767;
                $(this).css('background-image', 'url(' + (isMobile ? mobileImage :
                    desktopImage) + ')');
            });
        });
    });
</script>
