@extends('staff.sidebar')

@section('page-title', 'Edit Profile')

@section('content')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid var(--medium-gray);
        margin-top: 20px;
        max-width: 700px;
    }

    .form-card {
        padding: 30px;
    }

    .form-card-header {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        padding: 24px;
        border-bottom: 1px solid var(--medium-gray);
        margin-bottom: 30px;
        border-radius: 12px 12px 0 0;
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-header i {
        color: var(--accent);
        font-size: 20px;
    }

    .form-group {
        margin-bottom: 28px;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--primary);
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border-radius: 8px;
        border: 2px solid var(--medium-gray);
        font-size: 15px;
        transition: all 0.3s ease;
        background: white;
        color: var(--primary);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--dark-gray);
    }

    .form-actions {
        padding: 30px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        border-radius: 0 0 12px 12px;
    }

    .btn {
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
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

    .btn-secondary {
        background: white;
        color: var(--primary);
        border: 2px solid var(--medium-gray);
    }

    .btn-secondary:hover {
        background: var(--light-gray);
        border-color: var(--accent);
    }

    .tabs {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        border-bottom: 2px solid var(--medium-gray);
    }

    .tab {
        padding: 15px 0;
        cursor: pointer;
        color: var(--secondary);
        font-weight: 600;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .tab.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h1 style="margin: 0; font-size: 28px;">Edit Profile</h1>
    <a href="{{ route('staff.profile.show') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div style="max-width: 700px;">
    <div class="tabs">
        <div class="tab active" data-tab="personal">Personal Information</div>
        <div class="tab" data-tab="password">Change Password</div>
    </div>

    <div class="tab-content active" id="personal">
        <div class="form-container">
            <div class="form-card-header">
                <h3><i class="fas fa-user-circle"></i> Update Personal Information</h3>
            </div>
            <form action="{{ route('staff.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-card">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <div style="color: var(--danger); margin-top: 6px; font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')
                            <div style="color: var(--danger); margin-top: 6px; font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g., +60123456789">
                    </div>

                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position" class="form-control" value="{{ old('position', auth()->user()->position) }}" placeholder="e.g., Senior Barber">
                    </div>

                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea id="address" name="address" class="form-control" rows="3" placeholder="Your address">{{ old('address', auth()->user()->address) }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('staff.profile.show') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="tab-content" id="password">
        <div class="form-container">
            <div class="form-card-header">
                <h3><i class="fas fa-lock"></i> Change Password</h3>
            </div>
            <form action="{{ route('staff.profile.change-password') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-card">
                    <div class="form-group">
                        <label for="current_password">Current Password *</label>
                        <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <div style="color: var(--danger); margin-top: 6px; font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password *</label>
                        <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                        <small class="form-text">Minimum 8 characters</small>
                        @error('new_password')
                            <div style="color: var(--danger); margin-top: 6px; font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password *</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('staff.profile.show') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const tabName = this.dataset.tab;

        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        document.querySelectorAll('.tab').forEach(t => {
            t.classList.remove('active');
        });

        document.getElementById(tabName).classList.add('active');
        this.classList.add('active');
    });
});
</script>
@endsection