<style>
    .intro-slider-four.owl-carousel.owl-loaded {
        max-height: 500px !important;
        overflow: hidden !important;
    }
</style>
<section class="container">
    <div class="row g-5">
        <div class="col-md-9">
            <div class="intro-slider-container my-4">
                <div class="intro-slider intro-slider-four owl-carousel owl-theme owl-nav-inside owl-light"
                    data-toggle="owl"
                    data-owl-options='{ "dots": true, "nav": false, "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "responsive": {  "1200": {  "loop": true, "autoplay": true, "autoplayTimeout": 3000, "animateIn": "fadeIn", "animateOut": "fadeOut", "nav": false, "dots": true  }}}'>
                    @forelse ($sliders as $slider)
                        <div class="intro-slide-four" data-desktop-image="{{ asset($slider->slider_image) }}" data-mobile-image="{{ asset($slider->slider_m_image) }}">
                        </div>
                    @empty
                    @endforelse
                </div>
                <span class="slider-loader"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="my-4" id="slider-four-right-image">
                @if(config('settings.slider_style_four_right_first_image') != null)
                  <img src="{{ asset(config('settings.slider_style_four_right_first_image')) }}" alt="image">  
                @endif
                @if(config('settings.slider_style_four_right_second_image') != null)
                  <img src="{{ asset(config('settings.slider_style_four_right_second_image')) }}" alt="image">  
                @endif
            </div>
        </div>
    </div>
    <div class="mb-4"></div>
</section>
<script>
    $(document).ready(function() {
        $(".intro-slider-four").owlCarousel({
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
                    nav: false,
                    dots: false
                },
                768: {
                    items: 1,
                    nav: true,
                    dots: false
                },
                1200: {
                    items: 1,
                    nav: true,
                    dots: false
                }
            }
        });
    });

    $(document).ready(function() {
        $('.intro-slide-four').each(function() {
            let desktopImage = $(this).data('desktop-image');
            let mobileImage = $(this).data('mobile-image');
            let isMobile = window.innerWidth <= 767;
            $(this).css('background-image', 'url(' + (isMobile ? mobileImage : desktopImage) + ')');
        });

        $(window).resize(function() {
            $('.intro-slide-four').each(function() {
                let desktopImage = $(this).data('desktop-image');
                let mobileImage = $(this).data('mobile-image');
                let isMobile = window.innerWidth <= 767;
                $(this).css('background-image', 'url(' + (isMobile ? mobileImage : desktopImage) + ')');
            });
        });
    });
</script>
