@extends('staff.sidebar')

@section('page-title', 'My Profile')

@section('content')
<style>
    .profile-container {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
    }

    @media (max-width: 992px) {
        .profile-container {
            grid-template-columns: 1fr;
        }
    }

    .profile-sidebar {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent) 0%, #e6c158 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 48px;
        font-weight: 700;
        color: var(--primary);
    }

    .profile-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .profile-role {
        font-size: 13px;
        color: var(--secondary);
        margin-bottom: 20px;
    }

    .profile-stats {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding-top: 20px;
        border-top: 1px solid var(--medium-gray);
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-label {
        font-size: 12px;
        color: var(--secondary);
        margin-top: 3px;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--medium-gray);
    }

    .btn {
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent) 0%, #e6c158 100%);
        color: var(--primary);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .detail-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .detail-title i {
        color: var(--accent);
        font-size: 20px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .detail-row {
            grid-template-columns: 1fr;
        }
    }

    .detail-item {
        padding: 15px;
        background: var(--light-gray);
        border-radius: 8px;
    }

    .detail-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary);
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 4px;
        background: #c6f6d5;
        color: #22543d;
        font-size: 13px;
    }
</style>

<div class="profile-container">
    <div class="profile-sidebar">
        <div class="profile-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
        <div class="profile-name">{{ auth()->user()->name }}</div>
        <div class="profile-role">{{ auth()->user()->position ?? 'Staff' }}</div>

        <div class="action-buttons">
            <a href="{{ route('staff.profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="profile-main">
        <div class="detail-card">
            <div class="detail-title"><i class="fas fa-user-circle"></i> Personal Information</div>

            <div class="detail-row">
                <div class="detail-item">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value">{{ auth()->user()->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value">{{ auth()->user()->phone ?? '-' }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Position</div>
                    <div class="detail-value">{{ auth()->user()->position ?? '-' }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <div class="detail-label">Address</div>
                    <div class="detail-value">{{ auth()->user()->address ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-title"><i class="fas fa-lock"></i> Account Settings</div>

            <div class="detail-row">
                <div class="detail-item">
                    <div class="detail-label">Role</div>
                    <div class="detail-value">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge">{{ ucfirst(auth()->user()->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <div class="detail-label">Member Since</div>
                    <div class="detail-value">{{ auth()->user()->created_at->format('d F Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ auth()->user()->updated_at->format('d F Y, h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection