@php
    use Modules\Category\App\Models\Category;
    use Modules\Menu\App\Models\Menu;
@endphp
<header class="header header-intro-clearance header-4">

    <div class="header-middle p-2">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="{{ route('index') }}" class="logo desk_logo">
                    <img src="{{ asset(config('settings.company_primary_logo')) }}" alt="Logo" width="105"
                        height="25">
                </a>
            </div>

            <div class="header-center">
                <div class="header-search header-search-visible d-none d-lg-block">
                    {{-- <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a> --}}
                    <form action="{{ route('product.view.search') }}" method="POST">
                        @csrf
                        <div class="header-search-wrapper header-search-wrapper-one search-wrapper-wide">
                            <label for="q" class="sr-only">Search</label>
                            <input type="search" class="form-control" name="searchvalues" id="frontent_product_search"
                                placeholder="Search product ..." required autocomplete="off">
                            <button class="btn" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                    <div id="showSearchProducts">

                    </div>
                </div>
            </div>
            <div class="header-right">

                <div class="dropdown cart-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static" >
                        <div class="icon">
                            <i class="icon-user"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="background: transparent; box-shadow:none!important;">
                        <div class="dropdown-cart-products" id="drop_down_cart_product" style="background: #fff">
                            <ul class="p-2 m-0">
                                @auth
                                <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                        data-bs-toggle="dropdown">
                                        <span
                                            class="header-user-name">{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</span>
                                        </a>
                                        @if(Auth::check() && Auth::user()->role_id == 1 || Auth::check() && Auth::user()->role_id == 2)
                                        <a class="dropdown-item" href="{{ route('admin.dashboard.index') }}"><i
                                            class="align-middle me-1" data-feather="home"></i> Dashboard</a>
                                        @elseif (Auth::check() && Auth::user()->role_id == 3)
                                        <a class="dropdown-item" href="{{ route('staff.dashboard.index') }}"><i
                                            class="align-middle me-1" data-feather="home"></i> Dashboard</a>
                                        @endif

                                        <a class="dropdown-item" onclick="document.getElementById('logout-form').submit()"
                                            style="cursor: pointer"><i class="align-middle me-1" data-feather="log-out"></i>
                                            Log out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">Sign in </a>
                                    <a class="dropdown-item" href="{{ route('user.register') }}">Sign up </a>
                                </li>
                            @endauth

                            </ul>

                        </div>
                    </div>
                </div>
                <div class="dropdown cart-dropdown" id="header-one-dropdown-cart">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static" >
                        <div class="icon">
                            <i title="Cart" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas"
                           class="icon-shopping-cart"></i>
                            <span id="header_two_cart_count" id="cart_count" class="header_two_cart_count cart-count">{{ count((array) session('cart')) }}</span>
                        </div>
                    </a>
                </div>
            </div>


        </div>
    </div>
    @php
        $categories = Category::where('status', '1')->where('parent_id','0')->where('submenu_id','0')->get();
    @endphp
    <div class="header-bottom sticky-header" id="dynamicallyColorHeader">
        <div class="container">
            <div class="header-left">
                <div class="dropdown category-dropdown">
                    <a href="#" style="color: #fff!important" class="dropdown-toggle fw-bold" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" data-display="static" title="Browse Categories">
                        {{ config('settings.browse_cetegories_title') ?? 'Browse Categories ' }}<i class="fa-solid fa-caret-down fs-2"></i>
                    </a>
                    <div class="dropdown-menu">
                        <nav class="side-nav">
                            <ul class="menu-vertical sf-arrows">
                                @forelse ($categories as $key=>$categorie)
                                    <li><a href="{{ route('categories.show', $categorie->slug) }}"><i
                                                class="icon-long-arrow-right"></i> {{ $categorie->name ?? '' }}</a>
                                    </li>
                                @empty
                                @endforelse
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            @php
                $menus = Menu::with('submenus')
                    ->where('parent_id', '0')
                    ->where('status', '1')
                    ->where('position', '0')
                    ->orderBy('order_by', 'asc')
                    ->get();
            @endphp
            <div class="header-center">
                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        @forelse ($menus->take(6) as $key=>$menu)
                            <li class="{{ $key == 0 ? 'active' : '' }}"><a
                                    href="{{ $menu->url ?? '' }}">{{ $menu->name ?? '' }}</a>
                                @if (count($menu->submenus) > 0)
                                    <div class="megamenu megamenu-md">
                                        <div class="row no-gutters">
                                            <div class="col-md-12">
                                                <div class="menu-col">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <ul id="submenu">
                                                                @forelse ($menu->submenus as $submenu)
                                                                    <li><a
                                                                            href="{{ $submenu->url }}">{{ $submenu->name ?? '' }}</a>
                                                                    </li>
                                                                @empty
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </li>
                        @empty

                        @endforelse
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>
