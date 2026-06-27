<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men's Club | @yield('title', 'Dashboard')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        :root {
            --primary: #0a0a0a;
            --secondary: #2a2a2a;
            --accent: #d4af37;
            --accent-light: #f8e5a0;
            --light: #fafafa;
            --dark: #0f0f0f;
            --light-gray: #f5f5f5;
            --medium-gray: #e6e6e6;
            --surface: #ffffff;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --info: #4299e1;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.10);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.14);
            --radius: 8px;
            --topbar-height: 86px;
            --customer-page-max: 1280px;
            --customer-page-padding: 32px;
            --customer-page-top-padding: 48px;
            --customer-section-gap: 28px;
            --customer-card-gap: 18px;
            --transition: all 0.28s ease;
        }

        @view-transition { navigation: auto; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { overflow-y: scroll; scrollbar-gutter: stable; }

        body {
            min-height: 100vh;
            color: var(--primary);
            background:
                radial-gradient(circle at 10% 0%, rgba(212, 175, 55, 0.13), transparent 28rem),
                linear-gradient(180deg, #ffffff 0%, #f5f5f5 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
        }

        body.menu-open,
        body.modal-open { overflow: hidden; }
        body.customer-nav-pending { cursor: progress; }

        h1, h2, h3, h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: 0;
        }

        a { color: inherit; }
        button, input, textarea, select { font: inherit; }

        .top-navbar {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 4rem);
            max-width: 1400px;
            height: 86px;
            z-index: 1000;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 18px;
            padding: 0.45rem 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.66);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px) saturate(180%);
        }

        .brand-link {
            display: inline-flex;
            flex-direction: column;
            justify-content: center;
            min-width: 150px;
            color: var(--primary);
            text-decoration: none;
            line-height: 1.05;
        }

        .brand-main {
            font-family: 'Playfair Display', serif;
            font-size: 1.55rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .brand-sub {
            margin-top: 0.2rem;
            color: rgba(10, 10, 10, 0.56);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            min-width: 0;
            height: 44px;
        }

        .nav-link,
        .account-link,
        .logout-btn {
            text-decoration: none;
            border: 0;
            background: transparent;
            cursor: pointer;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            padding: 0.55rem 0.85rem;
            border-radius: 12px;
            color: rgba(10, 10, 10, 0.72);
            font-size: 0.94rem;
            font-weight: 600;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1;
            letter-spacing: 0;
            transition: var(--transition);
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
            background: rgba(212, 175, 55, 0.12);
        }

        .nav-link.book-link {
            color: var(--primary);
            background: linear-gradient(135deg, var(--accent), #c19a2f);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.20);
        }

        .nav-link.book-link:hover,
        .nav-link.book-link.active {
            background: linear-gradient(135deg, var(--accent), #b99028);
        }

        .top-navbar .nav-link,
        .top-navbar .account-trigger,
        .top-navbar .mobile-menu-btn,
        .top-navbar .drawer-close {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            line-height: 1 !important;
            letter-spacing: 0 !important;
        }

        .top-navbar .nav-link {
            font-size: 0.94rem !important;
            font-weight: 600 !important;
        }

        .nav-actions {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.65rem;
        }

        .account-menu { position: relative; }

        .account-trigger,
        .mobile-menu-btn,
        .drawer-close {
            border: 0;
            cursor: pointer;
            background: transparent;
        }

        .account-trigger {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 50%;
            background: rgba(10, 10, 10, 0.06);
            transition: var(--transition);
        }

        .account-trigger:hover,
        .account-trigger[aria-expanded="true"] {
            background: rgba(212, 175, 55, 0.14);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.12);
            transform: translateY(-1px);
        }

        .account-avatar {
            width: 100%;
            height: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #c19a2f);
        }

        .account-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .account-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: min(290px, calc(100vw - 32px));
            display: none;
            overflow: hidden;
            border: 1px solid rgba(10, 10, 10, 0.08);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(18px);
        }

        .account-dropdown.active { display: block; }

        .account-head {
            padding: 1rem;
            border-bottom: 1px solid rgba(10, 10, 10, 0.08);
        }

        .account-head strong,
        .account-head span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .account-head strong { color: var(--primary); font-weight: 700; }
        .account-head span { margin-top: 0.25rem; color: rgba(10, 10, 10, 0.56); font-size: 0.86rem; }
        .account-links { display: grid; gap: 0.2rem; padding: 0.45rem; }

        .account-link,
        .logout-btn {
            display: flex;
            align-items: center;
            height: 40px;
            width: 100%;
            padding: 0.65rem 0.75rem;
            border-radius: 8px;
            color: var(--primary);
            font-size: 0.94rem;
            font-weight: 600;
            text-align: left;
            transition: var(--transition);
        }

        .account-link:hover,
        .account-link.active,
        .logout-btn:hover { background: rgba(212, 175, 55, 0.12); }
        .logout-btn { color: #9b2c2c; }
        .logout-form { margin: 0; }

        .mobile-menu-btn {
            display: none;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: 12px;
            color: var(--primary);
            background: rgba(10, 10, 10, 0.06);
            transition: var(--transition);
        }

        .mobile-menu-btn:hover { background: rgba(212, 175, 55, 0.14); }

        .mobile-menu-btn i { font-size: 1.1rem; }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        .drawer-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1190;
            display: none;
            background: rgba(0, 0, 0, 0.48);
            backdrop-filter: blur(8px);
        }

        .drawer-backdrop.active { display: block; }

        .mobile-drawer {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 1200;
            width: min(88vw, 360px);
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(20px);
            transition: transform 0.28s ease;
        }

        .mobile-drawer.active { transform: translateX(0); }

        .drawer-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.15rem;
            border-bottom: 1px solid rgba(10, 10, 10, 0.08);
        }

        .drawer-close {
            height: 40px;
            padding: 0.55rem 0.9rem;
            border-radius: 12px;
            color: var(--primary);
            background: rgba(10, 10, 10, 0.06);
            font-weight: 700;
        }

        .drawer-user {
            padding: 1rem 1.15rem;
            border-bottom: 1px solid rgba(10, 10, 10, 0.08);
            background: rgba(245, 245, 245, 0.82);
        }

        .drawer-user strong,
        .drawer-user span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .drawer-user strong { color: var(--primary); font-weight: 700; }
        .drawer-user span { color: rgba(10, 10, 10, 0.56); font-size: 0.86rem; }

        .drawer-nav {
            display: grid;
            gap: 0.25rem;
            padding: 0.8rem;
            overflow-y: auto;
        }

        .drawer-nav .nav-link {
            justify-content: flex-start;
            width: 100%;
            border-radius: 8px;
            min-height: 44px;
            padding: 0.75rem 0.9rem;
        }

        .drawer-footer {
            margin-top: auto;
            padding: 0.8rem;
            border-top: 1px solid rgba(10, 10, 10, 0.08);
        }

        .main-content {
            min-height: 100vh;
            padding-top: calc(var(--topbar-height) + 1.25rem);
        }

        .content-wrapper {
            width: 100%;
            padding: 0;
            max-width: none;
            margin: 0 auto;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.18s ease;
        }

        body.customer-page-loaded .content-wrapper { animation: customerPageEnter 0.18s ease both; }
        body.customer-nav-pending .content-wrapper { pointer-events: none; }

        .customer-page {
            width: 100%;
            max-width: var(--customer-page-max);
            margin: 0 auto;
            padding: var(--customer-page-top-padding) var(--customer-page-padding) var(--customer-page-padding) !important;
            color: var(--primary);
        }

        .customer-page .page-header,
        .customer-page .shop-header,
        .customer-page .profile-page-header { margin-bottom: var(--customer-section-gap) !important; }

        .customer-page-progress {
            position: fixed;
            top: 0;
            left: 0;
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

        .customer-page-progress.active { opacity: 1; }
        .customer-page-progress.active::before { animation: customerProgress 0.85s ease-in-out infinite; }
        @keyframes customerProgress { to { transform: translateX(260%); } }
        @keyframes customerPageEnter { from { opacity: 0.96; } to { opacity: 1; } }

        html.modal-open,
        body.modal-open,
        html:has(.main-content .modal:is(.active, .show)),
        body:has(.main-content .modal:is(.active, .show)) { overflow: hidden; }

        body.modal-open .main-content,
        body.modal-open .content-wrapper,
        body:has(.main-content .modal:is(.active, .show)) .main-content,
        body:has(.main-content .modal:is(.active, .show)) .content-wrapper { transform: none !important; animation: none !important; }

        .main-content .modal {
            position: fixed !important;
            inset: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            margin: 0 !important;
            padding: clamp(12px, 2vw, 20px) !important;
            overflow: hidden !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 4000 !important;
        }

        .main-content .modal.active,
        .main-content .modal.show { display: flex !important; }

        .main-content .modal-content {
            height: auto !important;
            margin: 0 !important;
            max-height: calc(100vh - 32px) !important;
            max-height: calc(100dvh - 32px) !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            transform: none;
        }

        .main-content .modal-header,
        .main-content .modal-footer { flex-shrink: 0 !important; }
        .main-content .modal-body { min-height: 0 !important; overflow: hidden !important; overscroll-behavior: none !important; }

        .nav-link:focus-visible,
        .account-trigger:focus-visible,
        .account-link:focus-visible,
        .logout-btn:focus-visible,
        .mobile-menu-btn:focus-visible,
        .drawer-close:focus-visible {
            outline: 3px solid rgba(212, 175, 55, 0.35);
            outline-offset: 2px;
        }

        @media (max-width: 1160px) {
            .desktop-nav,
            .account-menu { display: none; }
            .mobile-menu-btn { display: inline-flex; }
            .top-navbar { grid-template-columns: auto 1fr auto; }
            .nav-actions { justify-self: end; }
        }

        @media (max-width: 768px) {
            :root {
                --topbar-height: 76px;
                --customer-page-padding: 20px;
                --customer-page-top-padding: 30px;
                --customer-section-gap: 22px;
                --customer-card-gap: 16px;
            }

            .top-navbar {
                top: 0.65rem;
                width: calc(100% - 1.2rem);
                height: 58px;
                padding: 0.35rem 0.65rem;
                border-radius: 18px;
            }

            .brand-main { font-size: 1.35rem; }
            .brand-sub { display: none; }
            .main-content { padding-top: calc(var(--topbar-height) + 0.5rem); }
        }

        @media (max-width: 440px) {
            :root { --customer-page-padding: 16px; }
            .brand-link { min-width: 0; }
            .brand-main { max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>

<body>
    @php
        $mainNavItems = [
            ['label' => 'Dashboard', 'route' => 'customer.dashboard', 'active' => ['customer.dashboard']],
            ['label' => 'Appointments', 'route' => 'customer.appointments.index', 'active' => ['customer.appointments.index', 'customer.appointments.show', 'customer.appointments.edit', 'customer.appointments.pay', 'customer.appointments.payment.*']],
            ['label' => 'Book', 'route' => 'customer.appointments.create', 'active' => ['customer.appointments.create', 'customer.appointments.store'], 'book' => true],
            ['label' => 'Queue', 'route' => 'customer.walk-ins.index', 'active' => ['customer.walk-ins.*']],
            ['label' => 'Services', 'route' => 'customer.services.index', 'active' => ['customer.services.*']],
            ['label' => 'Barbers', 'route' => 'customer.barbers.index', 'active' => ['customer.barbers.*']],
            ['label' => 'AI Hair', 'route' => 'customer.ai-hair.index', 'active' => ['customer.ai-hair.*']],
            ['label' => 'Shop', 'route' => 'customer.products.index', 'active' => ['customer.products.*', 'customer.product-orders.*']],
        ];

        $accountItems = [
            ['label' => 'My Orders', 'route' => 'customer.product-orders.index', 'active' => ['customer.product-orders.*']],
            ['label' => 'Profile', 'route' => 'customer.profile.show', 'active' => ['customer.profile.*']],
        ];

        $customerName = Auth::check() ? Auth::user()->name : 'Guest User';
        $customerEmail = Auth::check() ? Auth::user()->email : 'customer@mensclub.com';
        $customerFirstName = strtok($customerName, ' ') ?: $customerName;
    @endphp

    <div class="customer-page-progress" id="customerPageProgress" aria-hidden="true"></div>

    <header class="top-navbar" aria-label="Customer navigation">
        <a href="{{ route('customer.dashboard') }}" class="brand-link">
            <span class="brand-main">Men's Club</span>
            <span class="brand-sub">Customer Portal</span>
        </a>

        <nav class="desktop-nav">
            @foreach($mainNavItems as $item)
                <a href="{{ route($item['route']) }}" class="nav-link {{ !empty($item['book']) ? 'book-link' : '' }} {{ request()->routeIs(...$item['active']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="nav-actions">
            <div class="account-menu" id="accountMenu">
                <button class="account-trigger" id="accountTrigger" type="button" aria-expanded="false" aria-controls="accountDropdown" aria-label="Open account menu">
                    <span class="account-avatar">
                        @if(Auth::check() && Auth::user()->profile_image)
                            <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ $customerName }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($customerName) }}&background=d4af37&color=0a0a0a&bold=true&size=160" alt="{{ $customerName }}">
                        @endif
                    </span>
                </button>
                <div class="account-dropdown" id="accountDropdown">
                    <div class="account-head">
                        <strong>{{ $customerName }}</strong>
                        <span>{{ $customerEmail }}</span>
                    </div>
                    <div class="account-links">
                        @foreach($accountItems as $item)
                            <a href="{{ route($item['route']) }}" class="account-link {{ request()->routeIs(...$item['active']) ? 'active' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                        @auth
                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit" class="logout-btn">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="account-link">Login</a>
                        @endauth
                    </div>
                </div>
            </div>

            <button class="mobile-menu-btn" id="mobileMenuBtn" type="button" aria-controls="mobileDrawer" aria-expanded="false" aria-label="Open menu">
                <i class="fas fa-bars" aria-hidden="true"></i>
                <span class="sr-only">Menu</span>
            </button>
        </div>
    </header>

    <div class="drawer-backdrop" id="drawerBackdrop" hidden></div>
    <aside class="mobile-drawer" id="mobileDrawer" aria-label="Mobile customer navigation" aria-hidden="true">
        <div class="drawer-head">
            <a href="{{ route('customer.dashboard') }}" class="brand-link">
                <span class="brand-main">Men's Club</span>
                <span class="brand-sub">Customer Portal</span>
            </a>
            <button class="drawer-close" id="drawerClose" type="button" aria-label="Close menu">
                <i class="fas fa-times" aria-hidden="true"></i>
                <span class="sr-only">Close</span>
            </button>
        </div>

        <div class="drawer-user">
            <strong>{{ $customerName }}</strong>
            <span>{{ $customerEmail }}</span>
        </div>

        <nav class="drawer-nav">
            @foreach($mainNavItems as $item)
                <a href="{{ route($item['route']) }}" class="nav-link {{ !empty($item['book']) ? 'book-link' : '' }} {{ request()->routeIs(...$item['active']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            @foreach($accountItems as $item)
                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs(...$item['active']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="drawer-footer">
            @auth
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="account-link">Login</a>
            @endauth
        </div>
    </aside>

    <main class="main-content" id="mainContent">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const syncModalViewportLock = function () {
                const hasOpenModal = Boolean(document.querySelector('.modal.active, .modal.show'));
                document.documentElement.classList.toggle('modal-open', hasOpenModal);
                document.body.classList.toggle('modal-open', hasOpenModal);
            };

            syncModalViewportLock();

            new MutationObserver(syncModalViewportLock).observe(document.body, {
                subtree: true,
                childList: true,
                attributes: true,
                attributeFilter: ['class'],
            });

            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenuIcon = mobileMenuBtn?.querySelector('i');
            const mobileDrawer = document.getElementById('mobileDrawer');
            const drawerClose = document.getElementById('drawerClose');
            const drawerBackdrop = document.getElementById('drawerBackdrop');
            const mainContent = document.getElementById('mainContent');
            const accountMenu = document.getElementById('accountMenu');
            const accountTrigger = document.getElementById('accountTrigger');
            const accountDropdown = document.getElementById('accountDropdown');
            const pageProgress = document.getElementById('customerPageProgress');

            function setDrawer(open) {
                mobileDrawer?.classList.toggle('active', open);
                drawerBackdrop?.classList.toggle('active', open);
                document.body.classList.toggle('menu-open', open);
                mobileDrawer?.setAttribute('aria-hidden', open ? 'false' : 'true');
                mobileMenuBtn?.setAttribute('aria-expanded', open ? 'true' : 'false');
                mobileMenuBtn?.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                mobileMenuIcon?.classList.toggle('fa-bars', !open);
                mobileMenuIcon?.classList.toggle('fa-times', open);
                if (drawerBackdrop) drawerBackdrop.hidden = !open;
            }

            function setAccount(open) {
                accountDropdown?.classList.toggle('active', open);
                accountTrigger?.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            function normalizeUrl(url) {
                return `${url.origin}${url.pathname.replace(/\/$/, '')}${url.search}${url.hash}`;
            }

            function isSamePage(url) {
                return normalizeUrl(url) === normalizeUrl(new URL(window.location.href));
            }

            function isTransitionableLink(link) {
                if (!link?.href) return false;
                const url = new URL(link.href, window.location.href);
                return url.origin === window.location.origin && !link.hasAttribute('download') && link.target !== '_blank';
            }

            function startPageTransition() {
                document.body.classList.add('customer-nav-pending');
                pageProgress?.classList.add('active');
            }

            mobileMenuBtn?.addEventListener('click', function () {
                setDrawer(!mobileDrawer?.classList.contains('active'));
                setAccount(false);
            });

            drawerClose?.addEventListener('click', function () { setDrawer(false); });
            drawerBackdrop?.addEventListener('click', function () { setDrawer(false); });

            accountTrigger?.addEventListener('click', function (event) {
                event.stopPropagation();
                setAccount(!accountDropdown?.classList.contains('active'));
                setDrawer(false);
            });

            document.addEventListener('click', function (event) {
                if (!accountMenu?.contains(event.target)) setAccount(false);
            });

            mainContent?.addEventListener('click', function () {
                setDrawer(false);
                setAccount(false);
            });

            document.querySelectorAll('a[href]').forEach(link => {
                link.addEventListener('click', function (event) {
                    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                    if (!isTransitionableLink(link)) return;

                    const targetUrl = new URL(link.href, window.location.href);
                    if (isSamePage(targetUrl)) {
                        event.preventDefault();
                        setDrawer(false);
                        setAccount(false);
                        return;
                    }

                    startPageTransition();
                    setDrawer(false);
                    setAccount(false);
                });

                ['mouseenter', 'focus', 'touchstart'].forEach(eventName => {
                    link.addEventListener(eventName, function () {
                        if (!isTransitionableLink(link) || link.dataset.prefetched === 'true') return;
                        const targetUrl = new URL(link.href, window.location.href);
                        if (isSamePage(targetUrl)) return;

                        const prefetch = document.createElement('link');
                        prefetch.rel = 'prefetch';
                        prefetch.href = link.href;
                        document.head.appendChild(prefetch);
                        link.dataset.prefetched = 'true';
                    }, { passive: true });
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setDrawer(false);
                    setAccount(false);
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
