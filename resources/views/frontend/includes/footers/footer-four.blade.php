<footer id="frontend-footer-four" class="footer frontend-footer">
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="row align-items-center justify-content-center">
                    <h2 class="cta-title text-white text-center">{{ config('settings.subcribetitle') ?? '' }}</h2>
                    <p class="cta-desc text-white text-center">{{ config('settings.subsribesdecription') ?? '' }}</p>
                </div>
                <div class="row align-items-center justify-content-center border-bottom">
                    <div class="col-3xl-5col mb-4">
                        <form method="POST" action="{{ route('frontend.subscriber.store') }}">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control form-control-white"
                                    placeholder="Enter your Email Address" aria-label="Email Address"
                                    value="{{ old('email') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-white-2" type="submit"><span>Subscribe</span><i
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
            <div class="row mt-4 border-bottom">
                <div class="col-sm-6 col-lg-6">
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
                @endphp
                <div class="col-sm-6 col-lg-2">
                    <div class="widget">
                        <h3 class="widget-title">{{ config('settings.footer_one_title') }}</h3>
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
                        <h3 class="widget-title">{{ config('settings.footer_two_title') }}</h3>
                        <ul class="widget-list">
                            @forelse ($footertwo as $footert)
                                <li><a href="{{ $footert->url ?? '' }}">{{ $footert->name ?? '' }}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-2">
                    <div class="widget">
                        <h3 class="widget-title">{{ config('settings.footer_three_title') }}</h3>
                        <ul class="widget-list">
                            @forelse ($footerthree as $footerth)
                                <li><a href="{{ $footerth->url ?? '' }}">{{ $footerth->name ?? '' }}</a></li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="footer-bottom-four">
                    <p class="footer-copyright text-center mt-3">{{ config('settings.company_copy_right') }}</p>
                </div>
            </div>
        </div>
    </div>
</footer>
