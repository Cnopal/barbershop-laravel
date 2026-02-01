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
            --success: #48bb78;
            --warning: #ed8936;
            --danger: #f56565;
            --info: #4299e1;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--primary);
            background-color: var(--light);
            min-height: 100vh;
            display: flex;
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
            width: 280px;
            background: var(--primary);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: var(--transition);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            margin-bottom: 2rem;
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

        .nav-section {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(212, 175, 55, 0.2) 0%, rgba(212, 175, 55, 0.1) 100%);
            color: white;
            border-left: 3px solid var(--accent);
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
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: var(--radius);
            transition: var(--transition);
            background: rgba(245, 101, 101, 0.1);
            border: 1px solid rgba(245, 101, 101, 0.2);
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
            margin-left: 280px;
            min-height: 100vh;
            transition: var(--transition);
        }

        .content-wrapper {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
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
            border-radius: var(--radius);
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 1001;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background: #2d3748;
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
            }

            .mobile-menu-btn {
                display: flex;
            }

            .content-wrapper {
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 260px;
            }

            .content-wrapper {
                padding: 1rem;
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
    </style>
</head>

<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="logo">
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
                    class="nav-item {{ request()->routeIs('customer.appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-text">My Appointments</span>
                </a>

                <a href="{{ route('customer.appointments.create') }}"
                    class="nav-item {{ request()->routeIs('customer.appointments.create') ? 'active' : '' }}">
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
                    class="nav-item {{ request()->routeIs('customer.services.index') ? 'active' : '' }}">
                    <i class="fas fa-cut"></i>
                    <span class="nav-text">View Services</span>
                </a>

                <a href="{{ route('customer.barbers.index') }}" class="nav-item {{ request()->routeIs('customer.barbers.index') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Our Barbers</span>
                </a>
            </div>
        </div>

        <!-- Account Section -->
        <div class="nav-section">
            <div class="section-title">Account</div>
            <div class="nav-links">
                <a href="{{ route('customer.profile.show')  }}" class="nav-item {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit"></i>
                    <span class="nav-text">Edit Profile</span>
                </a>
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

            // Mobile menu toggle
            mobileMenuBtn.addEventListener('click', function () {
                sidebar.classList.toggle('active');
                const icon = this.querySelector('i');

                if (sidebar.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                    document.body.style.overflow = 'hidden';
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                    document.body.style.overflow = '';
                }
            });

            // Close sidebar when clicking outside on mobile
            if (window.innerWidth <= 1024) {
                mainContent.addEventListener('click', function () {
                    if (sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                        mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                        document.body.style.overflow = '';
                    }
                });
            }

            // Active navigation item highlight
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }

                item.addEventListener('click', function (e) {
                    // Remove active class from all items
                    navItems.forEach(nav => nav.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');

                    // Close sidebar on mobile after clicking
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.remove('active');
                        mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                        mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                        document.body.style.overflow = '';
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    sidebar.classList.remove('active');
                    mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                    mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    document.body.style.overflow = '';
                }
            });

            // Smooth hover effects
            const navLinks = document.querySelectorAll('.nav-item:not(.active)');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateX(4px)';
                });

                link.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateX(0)';
                });
            });

            // Sidebar scroll effect
            let lastScrollTop = 0;
            sidebar.addEventListener('scroll', function () {
                const scrollTop = this.scrollTop;
                const sidebarHeader = document.querySelector('.sidebar-header');

                if (scrollTop > lastScrollTop && scrollTop > 50) {
                    sidebarHeader.style.opacity = '0.8';
                    sidebarHeader.style.transform = 'translateY(-10px)';
                } else {
                    sidebarHeader.style.opacity = '1';
                    sidebarHeader.style.transform = 'translateY(0)';
                }

                lastScrollTop = scrollTop;
            });

            // Auto-close sidebar on mobile when clicking logout
            const logoutBtn = document.querySelector('.logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function () {
                    if (window.innerWidth <= 1024) {
                        setTimeout(() => {
                            sidebar.classList.remove('active');
                            mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                            mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                            document.body.style.overflow = '';
                        }, 100);
                    }
                });
            }
        });
    </script>
</body>

</html>