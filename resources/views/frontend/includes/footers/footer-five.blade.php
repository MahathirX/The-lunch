<footer id="frontend-footer-five" class="footer frontend-footer">
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-lg-4">
                    <div class="widget widget-about">
                        <a href="{{ route('index') }}" class="logo">
                            <img src="{{ asset(config('settings.company_primary_logo')) }}" class="footer-logo"
                                alt="Footer Logo">
                        </a>
                        <p>{!! config('settings.footer_description_text') ?? '' !!}</p>
                        <div class="footer-icons">
                            @if (config('settings.facebookurl') != null)
                                <a id="facebookurl" title="Facebook" href="{{ config('settings.facebookurl') }}"><i
                                        class="fa-brands fa-facebook-f"></i></a>
                            @endif

                            @if (config('settings.instagramurl') != null)
                                <a id="instagramurl" title="Instagram" href="{{ config('settings.instagramurl') }}"><i
                                        class="fa-brands fa-instagram"></i></a>
                            @endif

                            @if (config('settings.youtubeurl') != null)
                                <a id="youtubeurl" title="Youtube" href="{{ config('settings.youtubeurl') }}"><i
                                        class="fa-brands fa-youtube"></i></a>
                            @endif

                            @if (config('settings.linkedinurl') != null)
                                <a id="linkedinurl" title="Linkedin" href="{{ config('settings.linkedinurl') }}"><i
                                        class="fa-brands fa-linkedin-in"></i></a>
                            @endif

                            @if (config('settings.twitterurl') != null)
                                <a id="twitterurl" title="Twitter" href="{{ config('settings.twitterurl') }}"><i
                                        class="fa-brands fa-x-twitter"></i></a>
                            @endif

                            @if (config('settings.pinteresturl') != null)
                                <a id="pinteresturl" title="Pinterest" href="{{ config('settings.pinteresturl') }}"><i
                                        class="fa-brands fa-pinterest"></i></a>
                            @endif

                            @if (config('settings.skypeurl') != null)
                                <a id="skypeurl" title="Skype" href="{{ config('settings.skypeurl') }}"><i
                                        class="fa-brands fa-skype"></i></a>
                            @endif

                            @if (config('settings.whatsappurl') != null)
                                <a id="whatsappurl" title="Whatsapp" href="{{ config('settings.whatsappurl') }}"><i
                                        class="fa-brands fa-whatsapp"></i></a>
                            @endif

                            @if (config('settings.redditurl') != null)
                                <a id="redditurl" title="Reddit" href="{{ config('settings.redditurl') }}"><i
                                        class="fa-brands fa-reddit"></i></a>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $footerone = DB::table('menus')->where('status', '1')->where('position', '1')->get();
                    $footertwo = DB::table('menus')->where('status', '1')->where('position', '2')->get();
                    $footerthree = DB::table('menus')->where('status', '1')->where('position', '3')->get();
                    $footerfour = DB::table('menus')->where('status', '1')->where('position', '4')->get();
                @endphp
                <div class="col-sm-6 col-lg-2">
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

                <div class="col-sm-6 col-lg-2">
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

                <div class="col-sm-6 col-lg-4">
                    <div class="widget">
                        <h4 class="widget-title">{{ config('settings.footer_three_title') }}</h4>
                        <div class="row align-items-center justify-content-center">
                            <p class="cta-desc text-white mb-0">{{ config('settings.subcribetitle') ?? '' }}</p>
                            <p class="cta-desc text-white mb-0">{{ config('settings.subsribesdecription') ?? '' }}</p>
                        </div>
                        <div class="row align-items-center justify-content-center mt-2">
                            <div class="col-12">
                                <form method="POST" action="{{ route('frontend.subscriber.store') }}">
                                    @csrf
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control form-control-white"
                                            placeholder="Enter your Email Address" aria-label="Email Address"
                                            value="{{ old('email') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-white-2"
                                                type="submit"><span>Subscribe</span><i
                                                    class="icon-long-arrow-right"></i></button>
                                        </div>
                                    </div>
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p class="footer-copyright">{{ config('settings.company_copy_right') }}</p>
            <figure class="footer-payments">
                @forelse ($footerfour as $footert)
                    <a href="{{ $footert->url ?? '' }}">{{ $footert->name ?? '' }}</a>
                @empty
                @endforelse
            </figure>
        </div>
    </div>
</footer>
