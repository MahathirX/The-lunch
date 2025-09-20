@php
    use Modules\Category\App\Models\Category;
    use Modules\Menu\App\Models\Menu;
@endphp


@push('styles')
<style>
    /* শুধু মোবাইলের জন্য */
    @media (max-width: 767px) {
        /* পুরানো search আর right section hide */
        .header-middle .header-center,
        .header-middle .header-right {
            display: none !important;
        }

        /* Mobile search bar (bottom-bar এর উপরে থাকবে) */
        .mobile-search-bar {
            display: block;
            width: 100%;
            padding: 10px;
            position: fixed;
            bottom: 55px; /* bottom-bar এর একটু উপরে */
            left: 0;
            background: #fff;
            border-top: 1px solid #ddd;
            z-index: 998;
        }

        .mobile-search-bar form {
            display: flex;
            gap: 6px;
        }

        .mobile-search-bar input {
            flex: 1;
        }

        /* নিচের bottom menu */
        .mobile-bottom-bar {
            display: flex;
            justify-content: space-around;
            align-items: center;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #fff;
            border-top: 1px solid #ddd;
            z-index: 999;
            padding: 8px 0;
        }

        .mobile-bottom-bar a,
        .mobile-bottom-bar button {
            text-align: center;
            font-size: 14px;
            color: #333;
            background: none;
            border: none;
        }

        .mobile-bottom-bar i {
            font-size: 20px;
            display: block;
            margin-bottom: 2px;
        }
    }

    /* Desktop এ নতুন জিনিসগুলো hide হবে */
    @media (min-width: 768px) {
        .mobile-bottom-bar,
        .mobile-search-bar {
            display: none !important;
        }
    }
</style>

@endpush
<header class="header-second-template header header-intro-clearance header-4">

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
                        <div class="header-search-wrapper-two-main">
                            <div class="header-search-wrapper-two search-wrapper-wide">
                                <label for="q" class="sr-only">Search</label>
                                <input type="search" class="form-control" name="searchvalues"
                                    id="frontent_product_search" placeholder="Search product ..." required
                                    autocomplete="off">
                            </div>
                            <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                    <div id="showSearchProducts">

                    </div>
                </div>
            </div>

            <div class="header-right">
                <div class="template-two-login-register">
                    <div class="cart-section">
                        <a title="Cart" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas"
                            aria-controls="cartOffcanvas"><i class="icon-shopping-cart"></i></a>
                        <span id="header_two_cart_count" class="header_two_cart_count">{{ count((array) session('cart')) }}</span>
                    </div>
                    <span class="seperator me-2">| </span>
                    <div class="cart-section">
                        <div id="user-account">
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" id="useraccountdropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-user"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="useraccountdropdown">
                                    @auth
                                        @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                                            <li><a class="dropdown-item" href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                                        @else
                                            <li><a class="dropdown-item" href="{{ route('user.dashboard.index') }}">Dashboard</a></li>
                                        @endif
                                        <li><a class="dropdown-item" onclick="document.getElementById('logout-form').submit()"
                                                style="cursor: pointer"> Log out</a> </li>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('login') }}">Sign in </a></li>
                                        <li><a class="dropdown-item" href="{{ route('user.register') }}">Sign up </a></li>
                                    @endauth
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- @auth
                        @if ((Auth::check() && Auth::user()->role_id == 1) || (Auth::check() && Auth::user()->role_id == 2))
                            <a href="{{ route('admin.dashboard.index') }}"> Dashboard</a>
                        @elseif (Auth::check() && Auth::user()->role_id == 3)
                            <a href="{{ route('staff.dashboard.index') }}"> Dashboard</a>
                        @elseif (Auth::check() && Auth::user()->role_id == 8)
                            <a href="{{ route('user.dashboard.index') }}"> Dashboard</a>
                        @endif

                        <a class="ms-1" onclick="document.getElementById('logout-form').submit()" style="cursor: pointer"> / Log out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}">Sign In / </a>
                        <a href="{{ route('user.register') }}">Sign Up </a>
                    @endauth --}}

                </div>
            </div>
        </div>
    </div>
    @php
        $categories = Category::where('status', '1')->where('parent_id', '0')->where('submenu_id', '0')->get();
    @endphp
    <div class="header-bottom sticky-header" id="dynamicallyColorHeaderTwo">
        <div class="container">
            {{-- <div class="header-left">
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
            </div> --}}

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
                            <li class="{{ $key == 0 ? 'active' : '' }}"><a class="header-second-main-menu"
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
                                                                    <li><a class="header-second-submenu-menu"
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
