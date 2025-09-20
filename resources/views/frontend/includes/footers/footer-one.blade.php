<footer class="footer frontend-footer">
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="widget widget-about">
                        <a href="{{ route('index') }}" class="logo">
                            <img src="{{ asset(config('settings.company_primary_logo')) }}" class="footer-logo"
                                alt="Footer Logo">
                        </a>
                        <p>{!! config('settings.footer_description_text') ?? '' !!}</p>

                        <div class="footer-icons">
                            @if(config('settings.facebookurl') != null)
                                <a title="Facebook" href="{{ config('settings.facebookurl') }}"><i class="fa-brands fa-facebook-f"></i></a>
                            @endif

                            @if(config('settings.instagramurl') != null)
                               <a title="Instagram" href="{{ config('settings.instagramurl') }}"><i  class="fa-brands fa-instagram"></i></a>
                            @endif

                            @if(config('settings.youtubeurl') != null)
                              <a title="Youtube" href="{{ config('settings.youtubeurl') }}"><i class="fa-brands fa-youtube"></i></a>
                            @endif

                            @if(config('settings.linkedinurl') != null)
                              <a title="Linkedin" href="{{ config('settings.linkedinurl') }}"><i class="fa-brands fa-linkedin-in"></i></a>
                            @endif

                            @if(config('settings.twitterurl') != null)
                              <a title="Twitter" href="{{ config('settings.twitterurl') }}"><i class="fa-brands fa-x-twitter"></i></a>
                            @endif

                            @if(config('settings.pinteresturl') != null)
                              <a title="Pinterest" href="{{ config('settings.pinteresturl') }}"><i class="fa-brands fa-pinterest"></i></a>
                            @endif

                            @if(config('settings.skypeurl') != null)
                              <a title="Skype" href="{{ config('settings.skypeurl') }}"><i class="fa-brands fa-skype"></i></a>
                            @endif

                            @if(config('settings.whatsappurl') != null)
                               <a title="Whatsapp" href="{{ config('settings.whatsappurl') }}"><i class="fa-brands fa-whatsapp"></i></a>
                            @endif

                            @if(config('settings.redditurl') != null)
                              <a title="Reddit" href="{{ config('settings.redditurl') }}"><i class="fa-brands fa-reddit"></i></a>
                            @endif
                        </div>
                        <div class="widget-call">
                            <i class="icon-phone"></i>
                            {{ config('settings.footer_call_us_text') }} <br>
                            <a
                                href="tel:{{ config('settings.company_cell') }}">{{ config('settings.company_cell') }}</a>
                        </div>
                        <img src="{{ asset(config('settings.footer_payment_image')) }}" alt="Payment methods"
                            width="272" height="20">
                    </div>
                </div>

                @php
                    $footerone = DB::table('menus')->where('status', '1')->where('position', '1')->get();
                    $footertwo = DB::table('menus')->where('status', '1')->where('position', '2')->get();
                    $footerthree = DB::table('menus')->where('status', '1')->where('position', '3')->get();
                @endphp
                <div class="col-sm-6 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title">{{ config('settings.footer_one_title') }}</h4>

                        <ul class="widget-list">
                            @forelse ($footerone as $footer)
                                <li><a href="{{ $footer->url ?? '' }}">{{ $footer->name ?? '' }}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title">{{ config('settings.footer_two_title') }}</h4>

                        <ul class="widget-list">
                            @forelse ($footertwo as $footert)
                                <li><a href="{{ $footert->url ?? '' }}">{{ $footert->name ?? '' }}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="widget">
                        <h4 class="widget-title">{{ config('settings.footer_three_title') }}</h4>

                        <ul class="widget-list">
                            @forelse ($footerthree as $footerth)
                                <li><a href="{{ $footerth->url ?? '' }}">{{ $footerth->name ?? '' }}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p class="footer-copyright">{{ config('settings.company_copy_right') }}</p>
            <figure class="footer-payments">
                <p class="footer-copyright">{{ config('settings.development_by') }}</p>
            </figure>
        </div>
    </div>
</footer>
