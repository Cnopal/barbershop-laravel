@extends('customer.sidebar')

@section('content')
    <div class="profile-wrapper">
        <!-- Header -->
        <header class="profile-header">
            <div class="header-content">
                <div class="breadcrumb">
                    <a href="{{ url('/') }}" class="breadcrumb-link">Home</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">Profile</span>
                </div>
                <h1 class="header-title">My Profile</h1>
                <p class="header-subtitle">Your personal dashboard</p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="profile-content">
            <!-- Profile Overview Card -->
            <div class="profile-overview-card">
                <!-- Avatar & Quick Info -->
                <div class="overview-header">
                    <div class="avatar-wrapper">
                        <div class="avatar-circle">
                            <img src="{{ $user->profile_image ? asset($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=d4af37&color=fff&bold=true&size=400' }}"
                                alt="{{ $user->name }}" class="avatar-image">
                            <div class="avatar-badge">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="overview-info">
                        <h2 class="user-name">{{ $user->name }}</h2>
                        <p class="user-email">{{ $user->email }}</p>
                        <div class="user-stats">
                           
                            <div class="stat-divider"></div>
                            <div class="stat-item">
                                <span class="stat-value">{{ $user->created_at->format('M Y') }}</span>
                                <span class="stat-label">Joined</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="details-grid">
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="detail-content">
                            <h3 class="detail-title">Phone</h3>
                            <p class="detail-value {{ !$user->phone ? 'detail-empty' : '' }}">
                                {{ $user->phone ?? 'Add phone number' }}
                            </p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-content">
                            <h3 class="detail-title">Address</h3>
                            <p class="detail-value {{ !$user->address ? 'detail-empty' : '' }}">
                                {{ $user->address ?? 'Add address' }}
                            </p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-content">
                            <h3 class="detail-title">Last Updated</h3>
                            <p class="detail-value">
                                {{ $user->updated_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="detail-content">
                            <h3 class="detail-title">Account Status</h3>
                            <div class="status-badge active">
                                <i class="fas fa-circle"></i>
                                Active
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-section">
                    <a href="{{ route('customer.profile.edit') }}" class="action-button edit-button">
                        <i class="fas fa-pen"></i>
                        Edit Profile
                    </a>
                    <a href="{{ route('customer.appointments.index') }}" class="action-button appointments-button">
                        <i class="fas fa-calendar-check"></i>
                        View Appointments
                    </a>
                </div>
            </div>

            <!-- Quick Stats -->
            
        </main>
    </div>

    <style>
        .profile-wrapper {
            --primary: #1a1a1a;
            --secondary: #666666;
            --accent: #d4af37;
            --accent-light: rgba(212, 175, 55, 0.1);
            --light: #f8f9fa;
            --border: #eaeaea;
            --card-bg: #ffffff;
            --radius: 16px;
            --radius-sm: 8px;
            --shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            --shadow-hover: 0 8px 24px rgba(0, 0, 0, 0.08);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            min-height: 100vh;
        }

        /* Header */
        .profile-header {
            margin-bottom: 3rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .breadcrumb-link {
            color: var(--secondary);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-link:hover {
            color: var(--accent);
        }

        .breadcrumb-separator {
            color: var(--border);
        }

        .breadcrumb-current {
            color: var(--primary);
            font-weight: 500;
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .header-subtitle {
            font-size: 1rem;
            color: var(--secondary);
            margin: 0;
            font-weight: 400;
        }

        /* Profile Overview Card */
        .profile-overview-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 2.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .profile-overview-card:hover {
            box-shadow: var(--shadow-hover);
        }

        /* Overview Header */
        .overview-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border);
        }

        .avatar-wrapper {
            flex-shrink: 0;
        }

        .avatar-circle {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            padding: 4px;
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }

        .avatar-badge {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 28px;
            height: 28px;
            background: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            border: 2px solid white;
        }

        .overview-info {
            flex: 1;
        }

        .user-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .user-email {
            font-size: 1rem;
            color: var(--secondary);
            margin: 0 0 1.5rem 0;
        }

        .user-stats {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .stat-divider {
            width: 1px;
            height: 24px;
            background: var(--border);
        }

        /* Details Grid */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .detail-card {
            background: var(--light);
            border-radius: var(--radius-sm);
            padding: 1.5rem;
            border: 1px solid var(--border);
            transition: var(--transition);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .detail-card:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
        }

        .detail-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--secondary);
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 1rem;
            color: var(--primary);
            margin: 0;
            line-height: 1.4;
        }

        .detail-empty {
            color: var(--secondary);
            font-style: italic;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            background: #10b98110;
            color: #10b981;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge i {
            font-size: 0.5rem;
        }

        /* Action Section */
        .action-section {
            display: flex;
            gap: 1rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
        }

        .action-button {
            padding: 0.875rem 1.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.95rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .edit-button {
            background: var(--accent);
            color: white;
        }

        .edit-button:hover {
            background: #c19a2f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        .appointments-button {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--border);
        }

        .appointments-button:hover {
            background: var(--light);
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 1.5rem;
            border: 1px solid var(--border);
            transition: var(--transition);
        }

        .stat-card:hover {
            border-color: var(--accent);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            color: var(--accent);
        }

        .stat-header h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }

        .stat-content {
            padding-left: 2.25rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 0.25rem 0;
        }

        .stat-text {
            font-size: 0.875rem;
            color: var(--secondary);
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-wrapper {
                padding: 1.5rem 1rem;
            }

            .header-title {
                font-size: 2rem;
            }

            .profile-overview-card {
                padding: 1.5rem;
            }

            .overview-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .user-stats {
                justify-content: center;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .action-section {
                flex-direction: column;
            }

            .action-button {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .profile-overview-card {
                padding: 1.25rem;
            }

            .avatar-circle {
                width: 80px;
                height: 80px;
            }

            .user-name {
                font-size: 1.5rem;
            }

            .detail-card {
                padding: 1.25rem;
            }
        }

        /* Dark Mode */
        @media (prefers-color-scheme: dark) {
            :root {
                --primary: #ffffff;
                --secondary: #a0a0a0;
                --light: #1a1a1a;
                --border: #2a2a2a;
                --card-bg: #222222;
            }

            .profile-wrapper {
                background: #111111;
            }

            .detail-card,
            .stat-card {
                background: #1a1a1a;
            }

            .appointments-button:hover {
                background: #2a2a2a;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-overview-card,
        .stat-card {
            animation: fadeIn 0.6s ease-out;
        }

        .profile-overview-card {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.3s;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Smooth hover animations
            const detailCards = document.querySelectorAll('.detail-card');
            detailCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    const icon = card.querySelector('.detail-icon');
                    icon.style.transform = 'rotate(-5deg) scale(1.1)';
                    icon.style.transition = 'transform 0.3s ease';
                });

                card.addEventListener('mouseleave', () => {
                    const icon = card.querySelector('.detail-icon');
                    icon.style.transform = 'rotate(0) scale(1)';
                });
            });

            // Avatar interaction
            const avatarImage = document.querySelector('.avatar-image');
            if (avatarImage) {
                avatarImage.addEventListener('mouseenter', () => {
                    avatarImage.style.transform = 'scale(1.05) rotate(2deg)';
                    avatarImage.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                });

                avatarImage.addEventListener('mouseleave', () => {
                    avatarImage.style.transform = 'scale(1) rotate(0)';
                });
            }

            // Button ripple effect
            const actionButtons = document.querySelectorAll('.action-button');
            actionButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const ripple = document.createElement('span');
                    const rect = button.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.3);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                    `;

                    button.style.position = 'relative';
                    button.style.overflow = 'hidden';
                    button.appendChild(ripple);

                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Add ripple animation CSS
            if (!document.querySelector('#ripple-style')) {
                const style = document.createElement('style');
                style.id = 'ripple-style';
                style.textContent = `
                    @keyframes ripple {
                        to {
                            transform: scale(4);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            // Smooth scroll for empty fields
            const emptyFields = document.querySelectorAll('.detail-empty');
            emptyFields.forEach(field => {
                field.addEventListener('click', function () {
                    const editButton = document.querySelector('.edit-button');
                    if (editButton) {
                        editButton.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });

                        // Add pulse effect to edit button
                        editButton.style.animation = 'pulse 0.5s ease';
                        setTimeout(() => {
                            editButton.style.animation = '';
                        }, 500);
                    }
                });
            });

            // Add pulse animation CSS
            if (!document.querySelector('#pulse-style')) {
                const style = document.createElement('style');
                style.id = 'pulse-style';
                style.textContent = `
                    @keyframes pulse {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.05); }
                        100% { transform: scale(1); }
                    }
                `;
                document.head.appendChild(style);
            }
        });
    </script>
@endsection