@extends('layouts.frontend')
@section('title', $title)
@section('metatitle', $metatitle)
@section('metakeywords', $metakeywords)
@section('metadescription', $metadescription)
@section('content')
    <!-- Hero  Section Start -->
    @if (config('settings.herosectionshowchosevalue') == 1 || config('settings.herosectionshowchosevalue') == null)
        @if (config('settings.sliderchosevalue') == 1)
            @include('frontend.home.heros.hero-one')
        @elseif(config('settings.sliderchosevalue') == 2)
            @include('frontend.home.heros.hero-two')
        @elseif(config('settings.sliderchosevalue') == 3)
            @include('frontend.home.heros.hero-three')
        @elseif(config('settings.sliderchosevalue') == 4)
            @include('frontend.home.heros.hero-four')
        @else
            @include('frontend.home.heros.hero-five')
        @endif
    @endif
    <!-- Hero  Section End -->

    @if (config('settings.categoriessectionshowchosevalue') == 1 || config('settings.categoriessectionshowchosevalue') == null)
        <!-- Category Start -->
        @if (config('settings.categorychosevalue') == 1)
            @include('frontend.home.categorys.category-one')
        @elseif(config('settings.categorychosevalue') == 2)
            @include('frontend.home.categorys.category-two')
        @elseif(config('settings.categorychosevalue') == 3)
            @include('frontend.home.categorys.category-three')
        @else
            @include('frontend.home.categorys.category-four')
        @endif
    @endif
    <!-- Category End -->



    @if (config('settings.newproductsectionshowchosevalue') == 1 || config('settings.newproductsectionshowchosevalue') == null)
        <!-- Deals Producct Start -->
        @if (config('settings.productcardchosevalue') == 1)
            @include('frontend.home.dealsproduct.deals-one')
        @elseif(config('settings.productcardchosevalue') == 2)
            @include('frontend.home.dealsproduct.deals-two')
        @elseif(config('settings.productcardchosevalue') == 3)
            @include('frontend.home.dealsproduct.deals-three')
        @elseif(config('settings.productcardchosevalue') == 4)
            @include('frontend.home.dealsproduct.deals-four')
        @else
            @include('frontend.home.dealsproduct.deals-five')
        @endif
    @endif
    <!-- Deals Producct End -->


    <!-- Daynamic Category Start-->
    @if (config('settings.dynamicproductsectionshowchosevalue') == 1 || config('settings.dynamicproductsectionshowchosevalue') == null)
        @if ($dealsSections)
            @forelse ($dealsSections as $key => $sections)
                @php
                    $pluskey = $key + 3000;
                @endphp
                @if (config('settings.productcardchosevalue') == 1)
                    @include('frontend.home.daynamiccategory.daynamiccategory-one', [
                        'sections' => $sections,
                        'pluskey' => $pluskey,
                    ])
                @elseif(config('settings.productcardchosevalue') == 2)
                    @include('frontend.home.daynamiccategory.daynamiccategory-two', [
                        'sections' => $sections,
                        'pluskey' => $pluskey,
                    ])
                @elseif(config('settings.productcardchosevalue') == 3)
                    @include('frontend.home.daynamiccategory.daynamiccategory-three', [
                        'sections' => $sections,
                        'pluskey' => $pluskey,
                    ])
                @elseif(config('settings.productcardchosevalue') == 4)
                    @include('frontend.home.daynamiccategory.daynamiccategory-four', [
                        'sections' => $sections,
                        'pluskey' => $pluskey,
                    ])
                @else
                    @include('frontend.home.daynamiccategory.daynamiccategory-five', [
                        'sections' => $sections,
                        'pluskey' => $pluskey,
                    ])
                @endif
            @empty
            @endforelse
        @endif
    @endif
    <!-- Daynamic Category Start-->



    @if (config('settings.brandsectionshowchosevalue') == 1 || config('settings.brandsectionshowchosevalue') == null)
        <!-- Brands  Start -->
        @if (config('settings.brandchosevalue') == 1)
            @include('frontend.home.brands.brand-one')
        @elseif(config('settings.brandchosevalue') == 2)
            @include('frontend.home.brands.brand-two')
        @elseif(config('settings.brandchosevalue') == 3)
            @include('frontend.home.brands.brand-three')
        @else
            @include('frontend.home.brands.brand-four')
        @endif
    @endif
    <!-- Brands  End -->

    @if (config('settings.subscribesectionshowchosevalue') == 1 || config('settings.subscribesectionshowchosevalue') == null)
        <!-- Newsletter  Start -->
        @if (config('settings.subcribechosevalue') == 1)
            @include('frontend.home.newsletters.newsletter-one')
        @elseif(config('settings.subcribechosevalue') == 2)
            @include('frontend.home.newsletters.newsletter-two')
        @else
            @include('frontend.home.newsletters.newsletter-three')
        @endif
    @endif
    <!-- Newsletter  End -->
@endsection
