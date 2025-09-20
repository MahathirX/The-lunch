<!-- Mobile Menu -->
@php
    use Modules\Category\App\Models\Category;
    use Modules\Menu\App\Models\Menu;
@endphp
<div class="mobile-menu-overlay"></div>
<div class="mobile-menu-container mobile-menu-light">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="icon-close"></i></span>
        <div class="logo_area text-center">
            <a href="{{ route('index') }}" class="logo">
                <img style="margin: 0 auto" src="{{ asset(config('settings.company_primary_logo')) }}" alt="Logo" width="105"
                    height="25">
            </a>
        </div>
        <form action="{{ route('product.view.search') }}" method="POST" class="mobile-search">
            @csrf
            <label for="mobile-search" class="sr-only">Search</label>
            <input type="search" class="form-control" name="searchvalues" id="frontent_product_search" placeholder="Search product ..." required autocomplete="off">
            <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
        </form>
        {{-- <form action="{{ route('product.view.search') }}" method="POST">
            @csrf
                <label for="q" class="sr-only">Search</label>
                <input type="search" class="form-control" name="searchvalues" id="frontent_product_search" placeholder="Search product ..." required autocomplete="off">
                <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>

        </form> --}}

        <ul class="nav nav-pills-mobile nav-border-anim" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="mobile-menu-link" data-toggle="tab" href="#mobile-menu-tab"
                    role="tab" aria-controls="mobile-menu-tab" aria-selected="true">Menu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="mobile-cats-link" data-toggle="tab" href="#mobile-cats-tab" role="tab"
                    aria-controls="mobile-cats-tab" aria-selected="false">Categories</a>
            </li>
        </ul>

        @php
            $menus = Menu::with('submenus')
                ->where('parent_id', '0')
                ->where('status', '1')
                ->where('position', '0')
                ->orderBy('order_by', 'asc')
                ->get();
        @endphp

        <div class="tab-content">
            <div class="tab-pane fade show active" id="mobile-menu-tab" role="tabpanel"
                aria-labelledby="mobile-menu-link">
                <nav class="mobile-nav">
                    <ul class="mobile-menu">
                        @forelse ($menus->take(6) as $key=>$menu)
                            <li class="{{ $key == 0 ? 'active' : '' }}"><a
                                    href="{{ $menu->url ?? '' }}">{{ $menu->name ?? '' }}</a>
                                @if (count($menu->submenus) > 0)
                                    <ul>
                                        @forelse ($menu->submenus as $submenu)
                                            <li><a href="{{ $submenu->url }}"><i class="icon-long-arrow-right me-2"></i>{{ $submenu->name ?? '' }}</a></li>
                                        @empty
                                        @endforelse
                                    </ul>
                                @endif
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </nav><!-- End .mobile-nav -->
            </div><!-- .End .tab-pane -->
            @php
                $categories = Category::where('status', '1')->where('parent_id','0')->where('submenu_id','0')->get();
            @endphp
            <div class="tab-pane fade" id="mobile-cats-tab" role="tabpanel" aria-labelledby="mobile-cats-link">
                <nav class="mobile-cats-nav">
                    <ul class="mobile-cats-menu">
                        @forelse ($categories as $key=>$categorie)
                            <li><a href="{{ route('categories.show', $categorie->slug) }}" class="d-flex align-items-center"><i
                                        class="icon-long-arrow-right me-2"></i> {{ $categorie->name ?? '' }}</a>
                            </li>
                        @empty
                        @endforelse
                    </ul><!-- End .mobile-cats-menu -->
                </nav><!-- End .mobile-cats-nav -->
            </div><!-- .End .tab-pane -->
        </div><!-- End .tab-content -->

        {{-- <div class="social-icons">
            <a href="{{ config('settings.facebookurl') }}" class="social-icon" target="_blank" title="Facebook"><i class="icon-facebook-f"></i></a>
            <a href="{{ config('settings.instagramurl') }}" class="social-icon" target="_blank" title="Instagram"><i class="icon-instagram"></i></a>
            <a href="{{ config('settings.youtubeurl') }}" class="social-icon" target="_blank" title="Youtube"><i class="icon-youtube"></i></a>
        </div> --}}
        <!-- End .social-icons -->
    </div><!-- End .mobile-menu-wrapper -->
</div>
<!-- End .mobile-menu-container -->
