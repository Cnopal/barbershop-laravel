<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarberPro | Staff Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|playfair-display:500,600,700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #1a1f36;
            --secondary: #4a5568;
            --accent: #d4af37;
            --light-gray: #f7fafc;
            --medium-gray: #e2e8f0;
            --dark-gray: #718096;
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--primary);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: var(--primary);
            color: white;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--card-shadow);
            position: fixed;
            left: 0;
            top: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 25px;
        }

        .logo i {
            font-size: 28px;
            color: var(--accent);
            margin-right: 12px;
        }

        .logo h1 {
            font-size: 22px;
            font-weight: 700;
        }

        .nav-links {
            flex: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: var(--accent);
        }

        .nav-item i {
            margin-right: 15px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .nav-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.5);
            padding: 15px 25px 10px;
            font-weight: 600;
            margin-top: 10px;
        }

        /* Staff Profile Section */
        .staff-profile-section {
            padding: 15px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            margin-bottom: 0;
        }

        .profile-link {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            transition: var(--transition);
            padding: 10px;
            border-radius: 8px;
        }

        .profile-link:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(212, 175, 55, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .profile-role {
            font-size: 11px;
            opacity: 0.8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 25px;
            margin-top: 0;
        }

        .logout-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            font-size: 18px;
            transition: var(--transition);
            width: 100%;
            padding: 12px 15px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
        }

        .logout-btn:hover {
            background-color: rgba(245, 101, 101, 0.15);
            color: #f56565;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .top-bar {
            background: white;
            padding: 20px 30px;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .content-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
        }

        /* Mobile Menu Button */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            justify-content: center;
            align-items: center;
        }

        /* Sidebar overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        html.modal-open,
        body.modal-open,
        html:has(.content-wrapper .modal:is(.active, .show)),
        body:has(.content-wrapper .modal:is(.active, .show)) {
            overflow: hidden;
        }

        body.modal-open .main-content,
        body.modal-open .content-wrapper,
        body:has(.content-wrapper .modal:is(.active, .show)) .main-content,
        body:has(.content-wrapper .modal:is(.active, .show)) .content-wrapper {
            transform: none !important;
            animation: none !important;
        }

        .content-wrapper .modal {
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

        .content-wrapper .modal.active,
        .content-wrapper .modal.show {
            display: flex !important;
        }

        .content-wrapper .modal-content {
            height: auto !important;
            margin: 0 !important;
            max-height: calc(100vh - 32px) !important;
            max-height: calc(100dvh - 32px) !important;
            overflow: hidden !important;
            display: flex !important;
            flex-direction: column !important;
            transform: none;
        }

        .content-wrapper .modal-header,
        .content-wrapper .modal-footer {
            flex-shrink: 0 !important;
        }

        .content-wrapper .modal-body {
            min-height: 0 !important;
            overflow: hidden !important;
            overscroll-behavior: none !important;
        }

        /* Mobile layout */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1001;
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .menu-toggle {
                display: flex;
            }

            .content-wrapper {
                padding: 20px;
            }
        }

        /* Tablets */
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }

            .top-bar {
                padding: 15px 20px;
            }
        }

        /* Small mobile */
        @media (max-width: 480px) {
            .logo h1 {
                font-size: 18px;
            }

            .nav-item {
                padding: 12px 15px;
                font-size: 14px;
            }

            .nav-item i {
                font-size: 16px;
            }

            .content-wrapper {
                padding: 12px;
            }

            .menu-toggle {
                width: 45px;
                height: 45px;
                top: 15px;
                right: 15px;
            }
        }

        /* Landing Glass Side Navigation Refresh */
        :root {
            --primary: #0a0a0a;
            --secondary: #2a2a2a;
            --accent: #d4af37;
            --accent-soft: #f7e8ad;
            --light-gray: #f7f5ef;
            --medium-gray: #e8e2d6;
            --dark-gray: #706b63;
            --sidebar-width: 286px;
            --sidebar-offset: 18px;
            --sidebar-gap: 24px;
            --layout-sidebar-space: calc(var(--sidebar-width) + var(--sidebar-offset) + var(--sidebar-gap));
            --surface: rgba(255, 255, 255, 0.74);
            --card-shadow: 0 18px 46px rgba(10, 10, 10, 0.08);
            --sidebar-shadow: 16px 0 46px rgba(10, 10, 10, 0.10);
            --transition: all 0.22s ease;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: 0;
        }

        body {
            background:
                radial-gradient(circle at 8% 0%, rgba(212, 175, 55, 0.14), transparent 28rem),
                radial-gradient(circle at 100% 12%, rgba(10, 10, 10, 0.04), transparent 24rem),
                linear-gradient(180deg, #ffffff 0%, #f4f1eb 100%) !important;
            color: var(--primary);
        }

        .sidebar {
            top: var(--sidebar-offset) !important;
            left: var(--sidebar-offset) !important;
            width: var(--sidebar-width) !important;
            height: calc(100vh - 36px) !important;
            height: calc(100dvh - 36px) !important;
            padding: 18px 14px !important;
            border: 1px solid rgba(255, 255, 255, 0.72) !important;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.76) !important;
            color: var(--primary) !important;
            box-shadow: var(--sidebar-shadow) !important;
            backdrop-filter: blur(22px) saturate(180%);
            overflow: hidden;
        }

        .logo {
            min-height: 72px;
            margin: 0 4px 16px !important;
            padding: 14px !important;
            gap: 12px;
            border: 1px solid rgba(212, 175, 55, 0.14) !important;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.90), rgba(248, 229, 160, 0.22));
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.08);
        }

        .logo i {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0 !important;
            border-radius: 10px;
            background: rgba(212, 175, 55, 0.13);
            color: var(--accent) !important;
            font-size: 20px !important;
            flex-shrink: 0;
        }

        .logo h1 {
            display: flex;
            flex-direction: column;
            gap: 4px;
            color: var(--primary);
            font-family: 'Playfair Display', serif;
            font-size: 1.48rem !important;
            font-weight: 700 !important;
            line-height: 1;
            white-space: nowrap;
        }

        .logo h1::after {
            color: rgba(10, 10, 10, 0.52);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.74rem;
            font-weight: 600;
            line-height: 1;
        }

        .admin-profile-section,
        .staff-profile-section {
            margin: 0 4px 18px !important;
            padding: 0 0 18px !important;
            border-top: 0 !important;
            border-bottom: 1px solid rgba(10, 10, 10, 0.08) !important;
            flex-shrink: 0;
        }

        .profile-link {
            min-height: 80px;
            padding: 12px !important;
            border: 1px solid rgba(10, 10, 10, 0.08);
            border-radius: 10px !important;
            background: rgba(255, 255, 255, 0.72);
            color: var(--primary) !important;
            box-shadow: 0 10px 26px rgba(10, 10, 10, 0.04);
        }

        .profile-link:hover {
            background: rgba(248, 229, 160, 0.28) !important;
            border-color: rgba(212, 175, 55, 0.34);
            transform: translateY(-1px);
        }

        .profile-avatar {
            width: 48px !important;
            height: 48px !important;
            border: 0 !important;
            background: linear-gradient(135deg, var(--accent), #c19a2f);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.13);
        }

        .profile-name {
            color: var(--primary);
            font-size: 0.92rem !important;
            font-weight: 800 !important;
        }

        .profile-role {
            color: rgba(10, 10, 10, 0.54);
            font-size: 0.78rem !important;
            font-weight: 600;
        }

        .nav-links {
            display: flex !important;
            flex: 1;
            flex-direction: column;
            gap: 6px;
            padding: 0 4px 12px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(212, 175, 55, 0.52) transparent;
        }

        .nav-links::-webkit-scrollbar { width: 4px; }
        .nav-links::-webkit-scrollbar-thumb {
            background-color: rgba(212, 175, 55, 0.52);
            border-radius: 999px;
        }

        .nav-label {
            padding: 14px 0 6px !important;
            margin: 0 !important;
            color: rgba(10, 10, 10, 0.45) !important;
            font-size: 0.70rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.10em !important;
            text-transform: uppercase;
        }

        .nav-item {
            position: relative;
            width: 100%;
            min-height: 44px;
            gap: 14px;
            padding: 0 14px !important;
            border: 1px solid transparent !important;
            border-radius: 8px;
            background: transparent !important;
            color: rgba(10, 10, 10, 0.68) !important;
            font-size: 0.94rem;
            font-weight: 650;
            line-height: 1;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(248, 229, 160, 0.38) !important;
            color: var(--primary) !important;
            border-color: rgba(212, 175, 55, 0.40) !important;
        }

        .nav-item.active {
            box-shadow: inset 3px 0 0 var(--accent), 0 8px 20px rgba(212, 175, 55, 0.08);
        }

        .nav-item i {
            width: 22px !important;
            margin-right: 0 !important;
            color: rgba(10, 10, 10, 0.55);
            font-size: 1rem !important;
            text-align: center;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .nav-item:hover i,
        .nav-item.active i {
            color: var(--accent) !important;
        }

        .nav-item span {
            font-size: 0.94rem !important;
            font-weight: 650 !important;
        }

        .sidebar-footer {
            margin: 0 4px !important;
            padding: 12px 0 0 !important;
            border-top: 1px solid rgba(10, 10, 10, 0.08) !important;
        }

        .sidebar-footer form {
            display: block !important;
            width: 100%;
        }

        .logout-btn {
            min-height: 44px;
            padding: 0 14px !important;
            gap: 14px;
            border-radius: 8px !important;
            color: #9b2c2c !important;
            background: rgba(245, 101, 101, 0.08) !important;
            font-size: 0.94rem !important;
            font-weight: 750 !important;
            line-height: 1;
        }

        .logout-btn i {
            width: 22px;
            margin-right: 0 !important;
            color: #b91c1c;
            font-size: 1rem !important;
            text-align: center;
        }

        .logout-btn:hover {
            color: #7f1d1d !important;
            background: rgba(245, 101, 101, 0.14) !important;
        }

        .menu-toggle {
            border: 1px solid rgba(10, 10, 10, 0.08) !important;
            border-radius: 14px !important;
            background: rgba(255, 255, 255, 0.92) !important;
            color: var(--primary) !important;
            box-shadow: 0 14px 34px rgba(10, 10, 10, 0.14) !important;
            backdrop-filter: blur(16px);
        }

        .sidebar-overlay {
            background: rgba(10, 10, 10, 0.34) !important;
            backdrop-filter: blur(6px);
        }

        @media (max-width: 992px) {
            .sidebar {
                top: 12px !important;
                left: 12px !important;
                width: min(88vw, 312px) !important;
                height: calc(100vh - 24px) !important;
                height: calc(100dvh - 24px) !important;
                border-radius: 20px;
                transform: translateX(calc(-100% - 24px));
                z-index: 1001;
                transition: transform 0.28s ease;
            }

            .sidebar.active { transform: translateX(0) !important; }
            .menu-toggle { display: flex !important; }
            .sidebar-overlay.active { display: block !important; opacity: 1 !important; }
        }

        @media (max-width: 480px) {
            .sidebar { padding: 12px !important; }
            .logo h1 { font-size: 1.35rem !important; }
            .nav-item { min-height: 42px; padding: 0 12px !important; }
        }
        .logo h1::after { content: 'Staff Portal'; }

        .main-content {
            margin-left: var(--layout-sidebar-space) !important;
            width: calc(100% - var(--layout-sidebar-space)) !important;
            background: transparent !important;
        }

        .content-wrapper {
            padding: 32px !important;
            background: transparent !important;
        }

        .content-wrapper .card,
        .content-wrapper .stat-card,
        .content-wrapper .table-container,
        .content-wrapper .form-container,
        .content-wrapper .dashboard-card {
            border: 1px solid rgba(10, 10, 10, 0.06);
            border-radius: 12px !important;
            box-shadow: var(--card-shadow) !important;
        }

        @media (max-width: 992px) {
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 0 !important;
            }

            .content-wrapper { padding: 24px !important; }
        }

        @media (max-width: 768px) {
            .content-wrapper { padding: 18px !important; }
        }
</style>
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Overlay for mobile sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-cut"></i>
            <h1>Men's Club</h1>
        </div>

        <div class="staff-profile-section">
            <a href="{{ route('staff.profile.show') }}" class="profile-link">
                <div class="profile-avatar">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=d4af37&color=1a1f36&bold=true&size=200"
                            alt="{{ Auth::user()->name }}">
                    @endif
                </div>
                <div class="profile-info">
                    <p class="profile-name">{{ Auth::user()->name }}</p>
                    <p class="profile-role">{{ Auth::user()->position ?? 'Staff Member' }}</p>
                </div>
            </a>
        </div>

        <nav class="nav-links">
            <!-- <div class="nav-label">Dashboard</div> -->
            <a href="{{ route('staff.dashboard') }}"
                class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <!-- <div class="nav-label">Schedule</div> -->
            <a href="{{ route('staff.schedule') }}"
                class="nav-item {{ request()->routeIs('staff.schedule') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i> Schedule
            </a>
            <a href="{{ route('staff.appointments.index') }}"
                class="nav-item {{ request()->routeIs('staff.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Appointments
            </a>
            <a href="{{ route('staff.walk-ins.index') }}"
                class="nav-item {{ request()->routeIs('staff.walk-ins.*') ? 'active' : '' }}">
                <i class="fas fa-list-ol"></i> Walk-in Queue
            </a>
            <!-- <div class="nav-label">Services</div> -->
            <a href="{{ route('staff.services.index') }}"
                class="nav-item {{ request()->routeIs('staff.services.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Services
            </a>
            <!-- <div class="nav-label">Shop</div> -->
            <a href="{{ route('staff.products.index') }}"
                class="nav-item {{ request()->routeIs('staff.products.*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i> Products
            </a>
            <!-- <div class="nav-label">Sales</div> -->
            <a href="{{ route('staff.product-orders.index') }}"
                class="nav-item {{ request()->routeIs('staff.product-orders.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Product Orders
            </a>
            <a href="{{ route('staff.pos.index') }}"
                class="nav-item {{ request()->routeIs('staff.pos.*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i> P.O.S
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-wrapper">
            @if (session('success'))
                <div
                    style="padding: 15px 20px; background: #c6f6d5; color: #22543d; border-radius: 8px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    style="padding: 15px 20px; background: #fed7d7; color: #742a2a; border-radius: 8px; margin-bottom: 20px;">
                    <strong><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                    <ul style="margin-top: 10px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        (function () {
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
        })();

        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const menuIcon = menuToggle.querySelector('i');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : 'auto';

            // Change menu icon
            if (sidebar.classList.contains('active')) {
                menuIcon.className = 'fas fa-times';
            } else {
                menuIcon.className = 'fas fa-bars';
            }
        }

        menuToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking on a nav item on mobile
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function () {
                if (window.innerWidth <= 992) {
                    toggleSidebar();
                }

                // Update active nav item
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Close sidebar on escape key press
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                toggleSidebar();
            }
        });

        // Handle window resize
        function handleResize() {
            // Auto-close sidebar on resize to desktop if it was open
            if (window.innerWidth > 992 && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                menuIcon.className = 'fas fa-bars';
                document.body.style.overflow = 'auto';
            }

            // Update menu toggle visibility
            if (window.innerWidth <= 992) {
                menuToggle.style.display = 'flex';
            } else {
                menuToggle.style.display = 'none';
            }
        }

        // Initial check on load
        window.addEventListener('load', handleResize);
        window.addEventListener('resize', handleResize);
    </script>
</body>

</html>
