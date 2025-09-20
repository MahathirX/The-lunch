<style>
    .navbar-expand .dropdown-toggle::after {
        display: none !important;
    }
    #view_website_btn {
        font-size: 15px !important;
        margin-left: 3px !important;
    }
    #dropdown-menu-backend-notification{
        max-height: 70vh;
        overflow: auto;
        scroll-behavior: smooth;
        scrollbar-color: #3b7ddd #233346;
        scrollbar-width: thin;
    }
    .dropdown-menu-footer .common-div{
        width: 50%;
        text-align: center;
        padding: 8px;
    }
    .dropdown-menu-footer .first-div {
        background: #3b7ddd;
    }
    .dropdown-menu-footer .second-div {
        background: #233346;
    }
    .dropdown-menu-footer .common-div a{
        font-size: 15px;
        color: white;
        font-weight: 500;
    }
</style>
<div>
    <nav class="navbar navbar-expand navbar-light navbar-bg px-3">
        <a class="sidebar-toggle js-sidebar-toggle">
            <i class="hamburger align-self-center"></i>
        </a>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align align-items-center">
                @php
                    $languages = DB::table('languages')->where('status', '1')->get();
                @endphp
                <li class="nav-item dropdown">
                    <form action="{{ route('language.change') }}" method="POST" class="mb-0">
                        @csrf
                        <select name="locale" onchange="this.form.submit()" class="form-select">
                            @forelse ($languages as $lang)
                                <option value="{{ $lang->code }}" {{ app()->getLocale() === $lang->code ? 'selected' : '' }}>
                                    {{ $lang->name }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                    </form>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-icon" title="View Website" target="_blank" href="{{ route('index') }}">
                        <i class="align-middle" data-feather="globe"></i><span id="view_website_btn">{{ __f('View Website Title') }}</span>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                        <div class="position-relative">
                            <i class="align-middle" data-feather="bell"></i>
                            <span class="indicator" id="admin_notification_count_one">{{ formatNumber(Auth::user()->unreadNotifications->count())}}</span>
                        </div>
                    </a>
                    <div id="dropdown-menu-backend-notification" class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
                        <div class="dropdown-menu-header">
                            <span id="admin_notification_count_two">{{ formatNumber(Auth::user()->unreadNotifications->count())}}</span> New Notifications
                        </div>
                        <div class="list-group" id="admin_notification">
                            @if(Auth::user()->unreadNotifications && count(Auth::user()->unreadNotifications) > 0)
                                @forelse (Auth::user()->unreadNotifications as $notification)
                                    <a title="{{ __f('Read Notification Titile') }}" href="{{ route('admin.dashboard.notification.read',['id' => $notification->id]) }}" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                {!! $notification->data['type'] == 'new_order' ? '<i class="fa-solid fa-bag-shopping text-success"></i>' : '<i class="fa-solid fa-user text-primary"></i>' !!}
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">{{ $notification->data['title'] ?? 'New Notification' }}</div>
                                                <div class="text-muted small mt-1">{{ $notification->data['message'] ?? 'New Notification' }}</div>
                                                <div class="text-muted small mt-1">{{ $notification->created_at->diffForHumans()  }}</div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-danger text-center">{{ __f('No Notification Message') }}</p>
                                @endforelse
                            @else
                             <p class="text-danger text-center">{{ __f('No Notification Message') }}</p>
                            @endif
                        </div>
                        <div class="dropdown-menu-footer d-flex align-items-center p-0">
                            <div class="show-all-btn common-div first-div">
                                <a href="{{ route('admin.dashboard.notification.view') }}">{{ __f('Show All Notifications Title') }}</a>
                            </div>
                            <div class="mark-as-btn common-div second-div">
                                <a href="{{ route('admin.dashboard.mark.as.read') }}">{{ __f('Mark As Read Title') }}</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                        <i class="align-middle" data-feather="settings"></i>
                    </a>

                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->avater ? asset(Auth::user()->avater) : asset('backend/assets/img/avatars/blank_user.png') }}"
                            class="avatar img-fluid rounded me-1" alt="user image" /> <span
                            class="text-dark">{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('admin.dashboard.profile') }}"><i class="align-middle me-1"
                                data-feather="user"></i>{{ __f('Profile Title') }} </a>

                        <a class="dropdown-item" href="{{ route('admin.dashboard.password.change') }}"><i class="align-middle me-1"
                                data-feather="key"></i>{{ __f('Admin Password Change Title') }} </a>
                        <a class="dropdown-item" onclick="document.getElementById('logout-form').submit()"><i
                                class="align-middle me-1" data-feather="log-out"></i>{{ __f('Log out Title') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
