@extends('admin.sidebar')

@section('content')
<div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
        <h2>Admin Profile</h2>
        <p>Your account information</p>
    </div>

    <!-- Profile Card -->
    <div class="profile-card">
        <!-- Profile Header -->
        <div class="card-header">
            <div class="avatar-section">
                <div class="avatar-large">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=d4af37&color=fff&bold=true&size=400" alt="{{ Auth::user()->name }}">
                    @endif
                </div>
                <div class="user-details">
                    <h3>{{ Auth::user()->name }}</h3>
                    <p class="role">Administrator</p>
                </div>
            </div>
            <a href="{{ route('admin.profile.edit') }}" class="btn-edit">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <!-- Email -->
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-content">
                    <label>Email Address</label>
                    <p>{{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Phone -->
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-content">
                    <label>Phone Number</label>
                    <p>{{ Auth::user()->phone ?? 'Not provided' }}</p>
                </div>
            </div>

            <!-- Address -->
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                    <label>Address</label>
                    <p>{{ Auth::user()->address ?? 'Not provided' }}</p>
                </div>
            </div>

            <!-- Member Since -->
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="info-content">
                    <label>Member Since</label>
                    <p>{{ Auth::user()->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <h3 class="section-title">System Statistics</h3>
        <div class="stats-grid">
            <!-- Total Staff -->
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ \App\Models\User::where('role', 'staff')->where('status', 'active')->count() }}</h4>
                    <p>Active Staff</p>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ \App\Models\User::where('role', 'customer')->where('status', 'active')->count() }}</h4>
                    <p>Active Customers</p>
                </div>
            </div>

            <!-- Total Appointments -->
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ \App\Models\Appointment::count() }}</h4>
                    <p>Total Appointments</p>
                </div>
            </div>

            <!-- Total Services -->
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-cut"></i>
                </div>
                <div class="stat-info">
                    <h4>{{ \App\Models\Service::count() }}</h4>
                    <p>Services Available</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #1a1f36;
        --secondary: #4a5568;
        --accent: #d4af37;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    .profile-container {
        max-width: 1000px;
    }

    .profile-header {
        margin-bottom: 2rem;
    }

    .profile-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .profile-header p {
        font-size: 1rem;
        color: var(--secondary);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary) 0%, #2d3748 100%);
        color: white;
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .avatar-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-details h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .role {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--accent);
        color: var(--primary);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9375rem;
    }

    .btn-edit:hover {
        background-color: #c19a2f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }

    .info-card {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        background-color: var(--light-gray);
        border-radius: 8px;
        transition: var(--transition);
    }

    .info-card:hover {
        background-color: var(--medium-gray);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-content label {
        display: block;
        font-size: 0.875rem;
        color: var(--secondary);
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-content p {
        font-size: 1rem;
        color: var(--primary);
        font-weight: 500;
    }

    .stats-section {
        margin-top: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1.5rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        display: flex;
        gap: 1.5rem;
        align-items: center;
        transition: var(--transition);
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--primary);
        flex-shrink: 0;
    }

    .stat-info h4 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-info p {
        font-size: 0.875rem;
        color: var(--secondary);
    }

    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            text-align: center;
        }

        .avatar-section {
            flex-direction: column;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stat-box {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
