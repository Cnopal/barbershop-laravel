<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men's Club | @yield('title', 'Dashboard')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* CSS Variables */
        :root {
            --primary: #1a1f36;
            --secondary: #4a5568;
            --accent: #d4af37;
            --light: #f8f9fa;
            --dark: #121826;
            --light-gray: #f1f5f9;
            --medium-gray: #e2e8f0;
            --surface: #ffffff;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --info: #4299e1;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 12px rgba(26, 31, 54, 0.08);
            --shadow-lg: 0 14px 32px rgba(26, 31, 54, 0.14);
            --shadow-xl: 0 24px 60px rgba(26, 31, 54, 0.18);
            --radius: 10px;
            --sidebar-width: 280px;
            --customer-page-max: 1500px;
            --customer-page-padding: 30px;
            --customer-page-top-padding: 54px;
            --customer-section-gap: 26px;
            --customer-card-gap: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @view-transition {
            navigation: auto;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-y: scroll;
            scrollbar-gutter: stable;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--primary);
            background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%);
            min-height: 100vh;
            display: flex;
        }

        body.sidebar-open {
            overflow: hidden;
        }

        body.customer-nav-pending {
            cursor: progress;
        }

        h1,
        h2,
        h3,
        h4 {
            font-weight: 700;
            line-height: 1.2;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #111827 0%, var(--primary) 58%, var(--dark) 100%);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 18px 0 45px rgba(18, 24, 38, 0.18);
        }

        .sidebar-header {
            padding: 1.35rem 1.25rem 1.1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            margin-bottom: 1.25rem;
            transition: var(--transition);
        }

        .logo:hover {
            color: white;
            transform: translateX(2px);
        }

        .logo i {
            color: var(--accent);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-main {
            font-size: 1.5rem;
            line-height: 1;
        }

        .logo-sub {
            font-size: 0.75rem;
            opacity: 0.7;
            font-weight: 400;
            margin-top: 0.25rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--radius);
            margin-top: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            color: var(--primary);
            flex-shrink: 0;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-info h3 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info p {
            font-size: 0.875rem;
            opacity: 0.7;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.85rem 0;
        }

        .nav-section {
            padding: 0.65rem 1.25rem;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 0.65rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-height: 46px;
            padding: 0.75rem 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            position: relative;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transform: translateX(4px);
        }

        .nav-item:focus-visible,
        .logout-btn:focus-visible,
        .mobile-menu-btn:focus-visible {
            outline: 3px solid rgba(212, 175, 55, 0.38);
            outline-offset: 2px;
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.22) 0%, rgba(212, 175, 55, 0.08) 100%);
            color: white;
            border-color: rgba(212, 175, 55, 0.24);
            box-shadow: inset 0 0 0 1px rgba(212, 175, 55, 0.08);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 1.125rem;
            flex-shrink: 0;
        }

        .nav-item.active i {
            color: var(--accent);
        }

        .nav-text {
            flex: 1;
            font-size: 0.9375rem;
            font-weight: 500;
            line-height: 1.2;
        }

        .badge {
            background: var(--accent);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            min-width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 1.1rem 1.25rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .logout-form {
            margin: 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid rgba(245, 101, 101, 0.2);
            cursor: pointer;
            font: inherit;
        }

        .logout-btn:hover {
            background: rgba(245, 101, 101, 0.2);
            color: white;
            transform: translateX(4px);
        }

        .logout-btn i {
            width: 20px;
            text-align: center;
            font-size: 1.125rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }

        .content-wrapper {
            width: 100%;
            padding: 0;
            max-width: none;
            margin: 0 auto;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.18s ease, transform 0.18s ease;
        }

        body.customer-page-loaded .content-wrapper {
            animation: customerPageEnter 0.22s ease both;
        }

        body.customer-nav-pending .content-wrapper {
            opacity: 0.72;
            transform: translateY(4px);
            pointer-events: none;
        }

        .customer-page {
            width: 100%;
            max-width: var(--customer-page-max);
            margin: 0 auto;
            padding: var(--customer-page-top-padding) var(--customer-page-padding) var(--customer-page-padding) !important;
            color: var(--primary);
        }

        .customer-page .page-header,
        .customer-page .shop-header,
        .customer-page .profile-page-header {
            margin-bottom: var(--customer-section-gap) !important;
        }

        .customer-page-progress {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 3px;
            z-index: 2000;
            pointer-events: none;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.12s ease;
        }

        .customer-page-progress::before {
            content: '';
            display: block;
            width: 42%;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            transform: translateX(-100%);
        }

        .customer-page-progress.active {
            opacity: 1;
        }

        .customer-page-progress.active::before {
            animation: customerProgress 0.85s ease-in-out infinite;
        }

        @keyframes customerProgress {
            to {
                transform: translateX(260%);
            }
        }

        @keyframes customerPageEnter {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 999px;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 1002;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background: #2d3748;
        }

        .mobile-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(18, 24, 38, 0.42);
            backdrop-filter: blur(2px);
            z-index: 999;
        }

        .mobile-sidebar-backdrop.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 4.25rem;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .content-wrapper {
                padding: 0;
            }
        }

        @media (max-width: 768px) {
            :root {
                --customer-page-padding: 20px;
                --customer-page-top-padding: 34px;
                --customer-section-gap: 22px;
                --customer-card-gap: 16px;
            }

            .sidebar {
                width: min(86vw, 280px);
            }

            .content-wrapper {
                padding: 0;
            }

            .customer-page-progress {
                left: 0;
            }
        }

        @media (max-width: 480px) {
            .sidebar-header {
                padding: 1rem;
            }

            .nav-section {
                padding: 1rem;
            }

            .sidebar-footer {
                padding: 1rem;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Active State Management */
        .nav-item:not(.active):hover {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Animation for sidebar items */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .nav-item {
            animation: slideIn 0.3s ease forwards;
            opacity: 0;
        }

        .nav-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .nav-item:nth-child(2) {
            animation-delay: 0.15s;
        }

        .nav-item:nth-child(3) {
            animation-delay: 0.2s;
        }

        .nav-item:nth-child(4) {
            animation-delay: 0.25s;
        }

        .nav-item:nth-child(5) {
            animation-delay: 0.3s;
        }

        .nav-item:nth-child(6) {
            animation-delay: 0.35s;
        }

        .nav-item:nth-child(7) {
            animation-delay: 0.4s;
        }

        .nav-item:nth-child(8) {
            animation-delay: 0.45s;
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }

            .nav-item {
                opacity: 1;
                animation: none;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Open customer navigation">
        <i class="fas fa-bars"></i>
    </button>

    <div class="mobile-sidebar-backdrop" id="sidebarBackdrop" hidden></div>
    <div class="customer-page-progress" id="customerPageProgress" aria-hidden="true"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" aria-label="Customer navigation">
        <div class="sidebar-header">
            <a href="{{ route('customer.dashboard') }}" class="logo">
                <i class="fas fa-cut"></i>
                <div class="logo-text">
                    <span class="logo-main">Men's Club</span>
                    <span class="logo-sub">Customer Portal</span>
                </div>
            </a>

            <div class="user-profile">
                <div class="user-avatar">
                    @if(Auth::check() && Auth::user()->profile_image)
                        <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}" class="avatar-img">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=d4af37&color=fff&bold=true&size=400" alt="{{ Auth::user()->name ?? 'User' }}" class="avatar-img">
                    @endif
                </div>
                <div class="user-info">
                    <h3>{{ Auth::check() ? Auth::user()->name : 'Guest User' }}</h3>
                    <p>{{ Auth::check() ? Auth::user()->email : 'customer@mensclub.com' }}</p>
                </div>
            </div>
        </div>

        <div class="sidebar-nav">
            <!-- Main Navigation -->
            <div class="nav-section">
                <div class="section-title">Dashboard</div>
                <div class="nav-links">
                    <a href="{{ route('customer.dashboard') }}"
                        class="nav-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>

                    <a href="{{ route('customer.appointments.index') }}"
                        class="nav-item {{ request()->routeIs('customer.appointments.index', 'customer.appointments.show', 'customer.appointments.edit', 'customer.appointments.pay', 'customer.appointments.payment.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">My Appointments</span>
                    </a>

                    <a href="{{ route('customer.appointments.create') }}"
                        class="nav-item {{ request()->routeIs('customer.appointments.create', 'customer.appointments.store') ? 'active' : '' }}">
                        <i class="fas fa-calendar-plus"></i>
                        <span class="nav-text">Book Appointment</span>
                    </a>
                    <a href="{{ route('customer.ai-hair.index') }}"
                        class="nav-item {{ request()->routeIs('customer.ai-hair.*') ? 'active' : '' }}">
                        <i class="fas fa-magic"></i>
                        <span class="nav-text">AI Hair Style</span>
                    </a>
                </div>
            </div>

            <!-- Services Section -->
            <div class="nav-section">
                <div class="section-title">Services</div>
                <div class="nav-links">
                    <a href="{{ route('customer.services.index') }}"
                        class="nav-item {{ request()->routeIs('customer.services.*') ? 'active' : '' }}">
                        <i class="fas fa-cut"></i>
                        <span class="nav-text">View Services</span>
                    </a>

                    <a href="{{ route('customer.barbers.index') }}" class="nav-item {{ request()->routeIs('customer.barbers.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Our Barbers</span>
                    </a>
                </div>
            </div>

            <!-- Shop Section -->
            <div class="nav-section">
                <div class="section-title">Shop</div>
                <div class="nav-links">
                    <a href="{{ route('customer.products.index') }}"
                        class="nav-item {{ request()->routeIs('customer.products.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span class="nav-text">Products</span>
                    </a>

                    <a href="{{ route('customer.product-orders.index') }}"
                        class="nav-item {{ request()->routeIs('customer.product-orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="nav-text">My Orders</span>
                    </a>
                </div>
            </div>

            <!-- Account Section -->
            <div class="nav-section">
                <div class="section-title">Account</div>
                <div class="nav-links">
                    <a href="{{ route('customer.profile.show') }}" class="nav-item {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span class="nav-text">Profile</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="sidebar-footer">
            @auth
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="logout-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            @endauth
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const pageProgress = document.getElementById('customerPageProgress');

            if (!mobileMenuBtn || !sidebar || !mainContent) {
                return;
            }

            const icon = mobileMenuBtn.querySelector('i');
            const mobileQuery = window.matchMedia('(max-width: 1024px)');

            function setSidebar(open) {
                sidebar.classList.toggle('active', open);
                mobileMenuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                mobileMenuBtn.setAttribute('aria-label', open ? 'Close customer navigation' : 'Open customer navigation');
                document.body.classList.toggle('sidebar-open', open && mobileQuery.matches);

                if (icon) {
                    icon.classList.toggle('fa-bars', !open);
                    icon.classList.toggle('fa-times', open);
                }

                if (sidebarBackdrop) {
                    sidebarBackdrop.hidden = !open;
                    sidebarBackdrop.classList.toggle('active', open);
                }
            }

            function closeSidebarOnMobile() {
                if (mobileQuery.matches && sidebar.classList.contains('active')) {
                    setSidebar(false);
                }
            }

            function normalizeUrl(url) {
                return `${url.origin}${url.pathname.replace(/\/$/, '')}${url.search}${url.hash}`;
            }

            function isSamePage(url) {
                return normalizeUrl(url) === normalizeUrl(new URL(window.location.href));
            }

            function isTransitionableLink(link) {
                if (!link?.href) {
                    return false;
                }

                const url = new URL(link.href, window.location.href);
                return url.origin === window.location.origin && !link.hasAttribute('download') && link.target !== '_blank';
            }

            function startPageTransition() {
                document.body.classList.add('customer-nav-pending');
                pageProgress?.classList.add('active');
            }

            mobileMenuBtn.addEventListener('click', function () {
                setSidebar(!sidebar.classList.contains('active'));
            });

            sidebarBackdrop?.addEventListener('click', function () {
                setSidebar(false);
            });

            mainContent.addEventListener('click', closeSidebarOnMobile);

            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', closeSidebarOnMobile);
            });

            document.querySelectorAll('.sidebar a[href]').forEach(link => {
                link.addEventListener('click', function (event) {
                    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                        return;
                    }

                    if (!isTransitionableLink(link)) {
                        return;
                    }

                    const targetUrl = new URL(link.href, window.location.href);

                    if (isSamePage(targetUrl)) {
                        event.preventDefault();
                        closeSidebarOnMobile();
                        return;
                    }

                    startPageTransition();
                    closeSidebarOnMobile();
                });

                ['mouseenter', 'focus', 'touchstart'].forEach(eventName => {
                    link.addEventListener(eventName, function () {
                        if (!isTransitionableLink(link) || link.dataset.prefetched === 'true') {
                            return;
                        }

                        const targetUrl = new URL(link.href, window.location.href);
                        if (isSamePage(targetUrl)) {
                            return;
                        }

                        const prefetch = document.createElement('link');
                        prefetch.rel = 'prefetch';
                        prefetch.href = link.href;
                        document.head.appendChild(prefetch);
                        link.dataset.prefetched = 'true';
                    }, { passive: true });
                });
            });

            window.addEventListener('resize', function () {
                if (!mobileQuery.matches) {
                    setSidebar(false);
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && sidebar.classList.contains('active')) {
                    setSidebar(false);
                }
            });

            window.addEventListener('pageshow', function () {
                document.body.classList.remove('customer-nav-pending');
                document.body.classList.add('customer-page-loaded');
                pageProgress?.classList.remove('active');
            });
        });
    </script>
</body>

</html>
