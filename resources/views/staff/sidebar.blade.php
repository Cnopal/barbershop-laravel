<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BarberPro | Staff Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 16px;
        }

        .user-details h4 {
            font-size: 13px;
            margin-bottom: 2px;
        }

        .user-details p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
        }

        .logout-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            font-size: 18px;
            transition: var(--transition);
        }

        .logout-btn:hover {
            color: var(--danger);
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

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
                z-index: 1000;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .top-bar {
                padding: 15px 20px;
            }

            .content-wrapper {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-cut"></i>
            <h1>BarberPro</h1>
        </div>

        <nav class="nav-links">
            <a href="{{ route('staff.dashboard') }}" class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            <div class="nav-label">Services & Schedule</div>
            <a href="{{ route('staff.schedule') }}" class="nav-item {{ request()->routeIs('staff.schedule') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i> Schedule
            </a>
            <a href="{{ route('staff.appointments.index') }}" class="nav-item {{ request()->routeIs('staff.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-briefcase"></i> Appointments
            </a>
            <a href="{{ route('staff.services.index') }}" class="nav-item {{ request()->routeIs('staff.services.*') ? 'active' : '' }}">
                <i class="fas fa-list"></i> Services
            </a>

            <div class="nav-label">Feedback & Profile</div>
            <a href="{{ route('staff.feedbacks.index') }}" class="nav-item {{ request()->routeIs('staff.feedbacks.*') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Feedback
            </a>
            <a href="{{ route('staff.profile.show') }}" class="nav-item {{ request()->routeIs('staff.profile.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profile
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="user-details">
                    <h4>{{ auth()->user()->name }}</h4>
                    <p>{{ auth()->user()->position ?? 'Staff' }}</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <h2 style="margin: 0; color: var(--primary);">@yield('page-title', 'Staff Dashboard')</h2>
            </div>
            <div class="top-bar-right">
                <span style="color: var(--secondary); font-size: 14px;">
                    <i class="fas fa-clock"></i> {{ now()->format('l, d F Y H:i') }}
                </span>
            </div>
        </div>

        <div class="content-wrapper">
            @if (session('success'))
                <div style="padding: 15px 20px; background: #c6f6d5; color: #22543d; border-radius: 8px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="padding: 15px 20px; background: #fed7d7; color: #742a2a; border-radius: 8px; margin-bottom: 20px;">
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
</body>

</html>