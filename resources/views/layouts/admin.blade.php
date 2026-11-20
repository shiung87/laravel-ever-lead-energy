<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') | Everlead Energy</title>
    @vite(['resources/scss/admin.scss', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body>
<div class="admin-wrapper">
    <aside id="adminSidebar" class="admin-sidebar">
        <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2">
                <span class="bg-warning text-dark rounded-2 px-2 py-1 fw-bold"><i class="fa-solid fa-bolt"></i></span>
                <div>
                    <div class="fw-bold text-white lh-1">EVERLEAD</div>
                    <small class="text-secondary" style="font-size: 0.7rem;">ADMIN PORTAL</small>
                </div>
            </a>
        </div>

        <nav class="nav flex-column py-3 flex-grow-1 overflow-auto">
            @if(auth()->user()->hasPermission('dashboard.view'))
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge-high fa-fw"></i> Dashboard
                </a>
            @endif

            @if(auth()->user()->hasPermission('leads.view'))
                <a class="nav-link {{ request()->routeIs('admin.leads.*') ? 'active' : '' }}" href="{{ route('admin.leads.index') }}">
                    <i class="fa-solid fa-user-check fa-fw"></i> Customer Leads
                </a>
            @endif

            @if(auth()->user()->hasPermission('dealers.view'))
                <a class="nav-link {{ request()->routeIs('admin.dealers.*') ? 'active' : '' }}" href="{{ route('admin.dealers.index') }}">
                    <i class="fa-solid fa-handshake fa-fw"></i> Dealer Applications
                </a>
            @endif

            @if(auth()->user()->hasPermission('enquiries.manage'))
                <a class="nav-link {{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}" href="{{ route('admin.enquiries.index') }}">
                    <i class="fa-solid fa-envelope-open-text fa-fw"></i> Contact Enquiries
                </a>
            @endif

            <hr class="border-secondary border-opacity-25 mx-3 my-2">

            @if(auth()->user()->hasPermission('products.manage'))
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i class="fa-solid fa-car-battery fa-fw"></i> Products
                </a>
            @endif

            @if(auth()->user()->hasPermission('brands.manage'))
                <a class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}">
                    <i class="fa-solid fa-tags fa-fw"></i> Brands & Types
                </a>
            @endif

            @if(auth()->user()->hasPermission('vehicles.manage'))
                <a class="nav-link {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">
                    <i class="fa-solid fa-car fa-fw"></i> Vehicle Compatibility
                </a>
            @endif

            @if(auth()->user()->hasPermission('promotions.manage'))
                <a class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}" href="{{ route('admin.promotions.index') }}">
                    <i class="fa-solid fa-bullhorn fa-fw"></i> Promotions
                </a>
            @endif

            @if(auth()->user()->hasPermission('banners.manage'))
                <a class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                    <i class="fa-solid fa-images fa-fw"></i> Homepage Banners
                </a>
            @endif

            @if(auth()->user()->hasPermission('testimonials.manage'))
                <a class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                    <i class="fa-solid fa-star fa-fw"></i> Testimonials
                </a>
            @endif

            <hr class="border-secondary border-opacity-25 mx-3 my-2">

            @if(auth()->user()->hasPermission('settings.manage'))
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <i class="fa-solid fa-sliders fa-fw"></i> Settings & SEO
                </a>
            @endif

            @if(auth()->user()->hasPermission('users.manage'))
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fa-solid fa-users-gear fa-fw"></i> Users & Roles
                </a>
            @endif

            @if(auth()->user()->hasPermission('audit.view'))
                <a class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}">
                    <i class="fa-solid fa-shield-halved fa-fw"></i> Audit Logs
                </a>
            @endif
        </nav>

        <div class="p-3 border-top border-secondary border-opacity-25">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-light btn-sm w-100">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Public Site
            </a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="btn btn-outline-secondary btn-sm d-lg-none" type="button">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="h5 mb-0 fw-bold text-dark">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none sm:d-block">
                    <div class="fw-semibold small text-dark">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ auth()->user()->role?->name }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <div class="p-4 flex-grow-1">
            <x-alerts />
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
