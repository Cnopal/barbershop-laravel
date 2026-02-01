<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberPro | Admin Dashboard</title>
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
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
            z-index: 1000;
            transition: transform 0.3s ease;
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
            white-space: nowrap;
        }

        .logo-short {
            display: none;
        }

        .nav-links {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--accent) transparent;
        }

        .nav-links::-webkit-scrollbar {
            width: 4px;
        }

        .nav-links::-webkit-scrollbar-thumb {
            background-color: var(--accent);
            border-radius: 4px;
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
            font-size: 18px;
            margin-right: 15px;
            width: 24px;
            text-align: center;
        }

        .nav-item span {
            font-size: 15px;
            font-weight: 500;
            white-space: nowrap;
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            justify-content: center;
            align-items: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 30px;
            min-height: 100vh;
            overflow-y: auto;
            transition: margin-left 0.3s ease;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h2 {
            font-size: 28px;
            font-weight: 700;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: bold;
            font-size: 18px;
        }

        .user-name {
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-title {
            color: var(--dark-gray);
            font-size: 15px;
            font-weight: 600;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .stat-icon.appointments {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .stat-icon.revenue {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--accent);
        }

        .stat-icon.customers {
            background-color: rgba(66, 153, 225, 0.1);
            color: #4299e1;
        }

        .stat-icon.barbers {
            background-color: rgba(159, 122, 234, 0.1);
            color: #9f7aea;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Tables and Charts */
        .content-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            overflow-x: auto;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
        }

        .card-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        thead {
            border-bottom: 2px solid var(--medium-gray);
        }

        th {
            text-align: left;
            padding: 12px 10px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 14px;
        }

        td {
            padding: 15px 10px;
            border-bottom: 1px solid var(--medium-gray);
            font-weight: 500;
        }

        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }

        .status.confirmed {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .status.pending {
            background-color: rgba(237, 137, 54, 0.1);
            color: var(--warning);
        }

        .status.cancelled {
            background-color: rgba(245, 101, 101, 0.1);
            color: var(--danger);
        }

        .chart-container {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-placeholder {
            width: 100%;
            height: 100%;
            background-color: var(--light-gray);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--dark-gray);
        }

        .chart-placeholder i {
            font-size: 50px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Recent Activity */
        .activity-list {
            display: flex;
            flex-direction: column;
        }

        .activity-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid var(--medium-gray);
            align-items: flex-start;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .activity-icon.appointment {
            background-color: rgba(72, 187, 120, 0.1);
            color: var(--success);
        }

        .activity-icon.payment {
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--accent);
        }

        .activity-icon.client {
            background-color: rgba(66, 153, 225, 0.1);
            color: #4299e1;
        }

        .activity-details h4 {
            font-size: 15px;
            margin-bottom: 5px;
        }

        .activity-details p {
            font-size: 14px;
            color: var(--dark-gray);
        }

        .activity-time {
            font-size: 13px;
            color: var(--dark-gray);
            margin-top: 5px;
        }

        .sidebar-footer {
            padding: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #f56565;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
            background: none;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 15px;
        }

        .logout-btn i {
            margin-right: 12px;
            font-size: 18px;
        }

        .logout-btn:hover {
            background-color: rgba(245, 101, 101, 0.15);
            color: #fff;
        }

        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Responsive Design */
        /* Large tablets and small laptops */
        @media (max-width: 1200px) {
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .content-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .card {
                padding: 20px;
            }
        }

        /* Tablets */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }
            
            .menu-toggle {
                display: flex;
            }
            
            .header {
                margin-top: 20px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .stat-value {
                font-size: 28px;
            }
        }

        /* Mobile devices */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                margin-bottom: 30px;
            }
            
            .header h2 {
                font-size: 24px;
            }
            
            .user-info {
                width: 100;
                justify-content: flex-start;
            }
            
            .stats-container {
                gap: 15px;
            }
            
            .stat-card {
                padding: 20px 15px;
            }
            
            .stat-value {
                font-size: 24px;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            .card-title {
                font-size: 18px;
            }
            
            th, td {
                padding: 10px 5px;
                font-size: 13px;
            }
            
            .status {
                padding: 4px 8px;
                font-size: 12px;
            }
            
            .activity-item {
                flex-direction: column;
            }
            
            .activity-icon {
                margin-bottom: 10px;
                margin-right: 0;
            }
        }

        /* Small mobile devices */
        @media (max-width: 480px) {
            .header h2 {
                font-size: 20px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-value {
                font-size: 22px;
            }
            
            .card {
                padding: 15px;
            }
            
            .chart-container {
                height: 250px;
            }
            
            .logo h1 {
                font-size: 20px;
            }
            
            .nav-item {
                padding: 12px 15px;
            }
            
            .nav-item span {
                font-size: 14px;
            }
        }

        /* Very small devices */
        @media (max-width: 360px) {
            .main-content {
                padding: 10px;
            }
            
            .header {
                gap: 15px;
            }
            
            .stats-container {
                gap: 10px;
            }
            
            .stat-card {
                padding: 12px;
            }
            
            .stat-value {
                font-size: 20px;
            }
            
            .stat-title {
                font-size: 13px;
            }
        }

        /* Landscape mode for mobile */
        @media (max-height: 600px) and (orientation: landscape) {
            .sidebar {
                overflow-y: auto;
            }
            
            .nav-item {
                padding: 10px 25px;
            }
            
            .logo {
                padding: 0 25px 15px;
                margin-bottom: 15px;
            }
        }

        /* Print styles */
        @media print {
            .sidebar, .menu-toggle {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                padding: 0;
            }
            
            .stat-card:hover {
                transform: none;
            }
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

        <div class="nav-links">
            <a href="{{ route('admin.dashboard') }}" class="nav-item active">
                <i class="fas fa-chart-bar"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.staffs.index') }}" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Staff</span>
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-item">
                <i class="fas fa-users"></i>
                <span>Customer</span>
            </a>
            <a href="{{ route('admin.appointments.index') }}" class="nav-item">
                <i class="fas fa-cash-register"></i>
                <span>Appointment</span>
            </a>
            <a href="{{ route('admin.services.index') }}" class="nav-item">
                <i class="fas fa-user-tie"></i>
                <span>Service</span>
            </a>
            
            <a href="#" class="nav-item">
                <i class="fas fa-cash-register"></i>
                <span>Gallery</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
      

        <!-- yield content -->
        @yield('content')
    </div>

    <script>
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
            item.addEventListener('click', function() {
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
        
        // Update the current time in the dashboard
        function updateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('en-US', options);
            const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            
            // Update time display if we have an element for it
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = `${dateString} at ${timeString}`;
            }
        }
        
        // Update time on page load and every minute
        updateTime();
        setInterval(updateTime, 60000);
        
        // Simple animation for stat cards on hover (only on non-touch devices)
        if (!('ontouchstart' in window || navigator.maxTouchPoints)) {
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        }
        
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
        
        // Add touch-friendly table scrolling on mobile
        document.querySelectorAll('table').forEach(table => {
            if ('ontouchstart' in window) {
                table.parentElement.style.overflowX = 'auto';
                table.parentElement.style.WebkitOverflowScrolling = 'touch';
            }
        });
    </script>
</body>

</html>