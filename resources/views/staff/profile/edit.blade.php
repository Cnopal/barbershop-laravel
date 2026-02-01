@extends('staff.sidebar')

@section('page-title', 'Edit Profile')

@section('content')
<div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
        <h2>Edit Staff Profile</h2>
        <p>Update your personal information and profile picture</p>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <h4>Validation Errors</h4>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>
                <h4>Success</h4>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Profile Edit Card -->
    <div class="card">
        <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Profile Picture Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-image"></i> Profile Picture
                </h3>
                <div class="picture-section">
                    <div class="current-avatar">
                        <div class="avatar-circle">
                            <img src="{{ $user->profile_image ? asset($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=d4af37&color=fff&bold=true&size=400' }}"
                                 id="previewImage" alt="Profile Picture">
                        </div>
                        <p class="avatar-text">Current Profile Picture</p>
                    </div>

                    <div class="upload-zone" id="uploadZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag and drop your image here</p>
                        <p class="or-text">or</p>
                        <label class="upload-btn">
                            Click to browse
                            <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display: none;">
                        </label>
                        <p class="file-info">JPG, PNG, GIF up to 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-user"></i> Personal Information
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+60123456789">
                    </div>

                    <div class="form-group">
                        <label for="position">Position</label>
                        <input type="text" id="position" name="position" class="form-control" value="{{ old('position', $user->position) }}" placeholder="Senior Barber">
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Your address">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <!-- Password Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i> Change Password
                </h3>
                <p class="section-description">Leave blank to keep your current password</p>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password">
                            <button type="button" class="toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm password">
                            <button type="button" class="toggle-password" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('staff.profile.show') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
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
        --danger: #f56565;
        --success: #48bb78;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    .profile-container {
        max-width: 900px;
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

    .alert {
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
    }

    .alert i {
        font-size: 1.25rem;
        flex-shrink: 0;
        margin-top: 0.25rem;
    }

    .alert h4 {
        margin: 0 0 0.5rem;
        font-size: 1rem;
    }

    .alert ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .alert-danger {
        background-color: rgba(245, 101, 101, 0.1);
        color: var(--danger);
        border-left: 4px solid var(--danger);
    }

    .alert-success {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .form-section {
        padding: 2rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--accent);
        font-size: 1.25rem;
    }

    .section-description {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 1.5rem;
        margin-top: -0.5rem;
    }

    .picture-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: center;
    }

    .current-avatar {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .avatar-circle {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-text {
        font-size: 0.875rem;
        color: var(--secondary);
        text-align: center;
    }

    .upload-zone {
        border: 2px dashed var(--accent);
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background-color: rgba(212, 175, 55, 0.02);
    }

    .upload-zone:hover {
        background-color: rgba(212, 175, 55, 0.05);
        border-color: #c19a2f;
    }

    .upload-zone.active {
        background-color: rgba(212, 175, 55, 0.1);
        border-color: #c19a2f;
    }

    .upload-zone i {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 0.5rem;
        display: block;
    }

    .upload-zone p {
        margin: 0.5rem 0;
        color: var(--primary);
        font-weight: 500;
    }

    .or-text {
        color: var(--secondary) !important;
        font-size: 0.875rem;
        font-weight: 400;
    }

    .upload-btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: var(--accent);
        color: var(--primary);
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .upload-btn:hover {
        background-color: #c19a2f;
    }

    .file-info {
        font-size: 0.75rem;
        color: var(--secondary);
        margin-top: 0.75rem !important;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border: 2px solid var(--medium-gray);
        border-radius: 6px;
        font-size: 0.9375rem;
        transition: var(--transition);
        color: var(--primary);
        background-color: white;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .error-text {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: block;
    }

    .password-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input-wrapper .form-control {
        width: 100%;
    }

    .toggle-password {
        position: absolute;
        right: 0.75rem;
        background: none;
        border: none;
        color: var(--secondary);
        cursor: pointer;
        padding: 0.5rem;
        transition: var(--transition);
        font-size: 1rem;
    }

    .toggle-password:hover {
        color: var(--accent);
    }

    .form-actions {
        padding: 2rem;
        background-color: var(--light-gray);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-cancel,
    .btn-submit {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.9375rem;
    }

    .btn-submit {
        background-color: var(--accent);
        color: var(--primary);
    }

    .btn-submit:hover {
        background-color: #c19a2f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-cancel {
        background-color: white;
        color: var(--primary);
        border: 2px solid var(--medium-gray);
    }

    .btn-cancel:hover {
        background-color: var(--light-gray);
        border-color: var(--accent);
    }

    @media (max-width: 768px) {
        .picture-section {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    // Handle file upload
    const uploadZone = document.getElementById('uploadZone');
    const profileImage = document.getElementById('profileImage');
    const previewImage = document.getElementById('previewImage');

    profileImage.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Drag and drop
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('active');
    });

    uploadZone.addEventListener('dragleave', function() {
        uploadZone.classList.remove('active');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('active');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            profileImage.files = files;
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
            };
            reader.readAsDataURL(files[0]);
        }
    });

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.dataset.target;
            const input = document.getElementById(target);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
</script>
@endsection