@extends('customer.sidebar')

@section('content')
<div class="profile-wrapper">
    <!-- Header -->
    <header class="profile-header">
        <div class="header-content">
            <div class="breadcrumb">
                <a href="{{ route('customer.dashboard') }}" class="breadcrumb-link">Dashboard</a>
                <span class="breadcrumb-separator">/</span>
                <a href="{{ route('customer.profile.show') }}" class="breadcrumb-link">Profile</a>
                <span class="breadcrumb-separator">/</span>
                <span class="breadcrumb-current">Edit</span>
            </div>
            <h1 class="header-title">Edit Profile</h1>
            <p class="header-subtitle">Update your personal information</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="profile-content">
        <!-- Alert Messages -->
        @if($errors->any())
            <div class="alert alert-danger">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="alert-content">
                    <h4 class="alert-title">Validation Errors</h4>
                    <ul class="alert-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <h4 class="alert-title">Success</h4>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Edit Form Card -->
        <div class="profile-overview-card">
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
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
                                    alt="{{ $user->name }}" class="avatar-image" id="previewImage">
                            </div>
                            <p class="avatar-label">Current picture</p>
                        </div>
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h4>Upload new picture</h4>
                            <p>Drag and drop or click to browse</p>
                            <input type="file" id="profile_image" name="profile_image" class="upload-input" accept="image/*">
                            <span class="upload-help">JPG, PNG or GIF (Max 2MB)</span>
                        </div>
                    </div>
                    @error('profile_image')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h3>
                    <div class="form-grid">
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter your full name"
                                   required>
                            @error('name')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="your.email@example.com"
                                   required>
                            @error('email')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="012-3456789">
                            @error('phone')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="form-group full-width">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" 
                                      class="form-control @error('address') is-invalid @enderror"
                                      placeholder="Enter your address"
                                      rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="fas fa-lock"></i> Change Password
                    </h3>
                    <p class="section-help">Leave blank if you don't want to change your password</p>
                    <div class="form-grid">
                        <!-- New Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Leave blank to keep current password">
                                <button type="button" class="toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-help">Minimum 8 characters</small>
                            @error('password')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Re-enter your password">
                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="action-section">
                    <a href="{{ route('customer.profile.show') }}" class="action-button cancel-button">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="reset" class="action-button reset-button">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                    <button type="submit" class="action-button edit-button">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
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
        --danger: #ef4444;
        --success: #22c55e;
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

    /* Alert */
    .alert {
        margin-bottom: 2rem;
        padding: 1.25rem;
        border-radius: var(--radius-sm);
        border: 1px solid;
        display: flex;
        gap: 1rem;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .alert-danger {
        background-color: #fee2e2;
        border-color: #fecaca;
        color: #7f1d1d;
    }

    .alert-success {
        background-color: #dcfce7;
        border-color: #bbf7d0;
        color: #15803d;
    }

    .alert-icon {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
    }

    .alert-title {
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        font-size: 0.95rem;
    }

    .alert-list {
        list-style: disc;
        margin-left: 1.5rem;
        font-size: 0.9rem;
        margin: 0;
        padding: 0;
    }

    .alert-list li {
        margin-bottom: 0.25rem;
    }

    .alert-content p {
        margin: 0;
        font-size: 0.95rem;
    }

    /* Profile Card */
    .profile-overview-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        padding: 2.5rem;
        border: 1px solid var(--border);
        transition: var(--transition);
    }

    .profile-overview-card:hover {
        box-shadow: var(--shadow-hover);
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2.5rem;
        border-bottom: 1px solid var(--border);
    }

    .form-section:last-of-type {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--accent);
    }

    .section-help {
        font-size: 0.875rem;
        color: var(--secondary);
        margin: 0.5rem 0 1rem 0;
    }

    /* Picture Section */
    .picture-section {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 2rem;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .current-avatar {
        text-align: center;
    }

    .avatar-circle {
        position: relative;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        padding: 3px;
        margin: 0 auto 1rem;
    }

    .avatar-image {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
    }

    .avatar-label {
        color: var(--secondary);
        font-size: 0.875rem;
        margin: 0;
    }

    /* Upload Area */
    .upload-area {
        border: 2px dashed var(--border);
        border-radius: var(--radius-sm);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
        background-color: var(--light);
    }

    .upload-area:hover {
        border-color: var(--accent);
        background-color: var(--accent-light);
    }

    .upload-area i {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 0.75rem;
    }

    .upload-area h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0 0 0.25rem 0;
    }

    .upload-area p {
        font-size: 0.875rem;
        color: var(--secondary);
        margin: 0 0 1rem 0;
    }

    .upload-input {
        display: none;
    }

    .upload-help {
        font-size: 0.75rem;
        color: var(--secondary);
        display: block;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    /* Form Group */
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--primary);
    }

    .required {
        color: var(--danger);
    }

    /* Form Control */
    .form-control {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 0.95rem;
        color: var(--primary);
        transition: var(--transition);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        font-family: inherit;
    }

    /* Password Input Wrapper */
    .password-input-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--secondary);
        font-size: 1rem;
        transition: var(--transition);
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toggle-password:hover {
        color: var(--accent);
    }

    /* Form Error & Help */
    .form-error {
        font-size: 0.75rem;
        color: var(--danger);
    }

    .form-help {
        font-size: 0.75rem;
        color: var(--secondary);
    }

    /* Action Section */
    .action-section {
        display: flex;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border);
        justify-content: flex-end;
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
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .cancel-button {
        background: transparent;
        color: var(--secondary);
        border: 1px solid var(--border);
    }

    .cancel-button:hover {
        background: var(--light);
        border-color: var(--secondary);
    }

    .reset-button {
        background: transparent;
        color: var(--secondary);
        border: 1px solid var(--border);
    }

    .reset-button:hover {
        background: var(--light);
        border-color: var(--secondary);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-title {
            font-size: 1.75rem;
        }

        .profile-overview-card {
            padding: 1.5rem;
        }

        .picture-section {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .action-section {
            flex-wrap: wrap;
        }

        .action-button {
            flex: 1;
            min-width: 150px;
            justify-content: center;
        }
    }
</style>

<script>
    // Upload Area
    const uploadArea = document.getElementById('uploadArea');
    const uploadInput = document.getElementById('profile_image');
    const previewImage = document.getElementById('previewImage');

    uploadArea.addEventListener('click', () => uploadInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--accent)';
        uploadArea.style.backgroundColor = 'var(--accent-light)';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = 'var(--border)';
        uploadArea.style.backgroundColor = 'var(--light)';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--border)';
        uploadArea.style.backgroundColor = 'var(--light)';
        uploadInput.files = e.dataTransfer.files;
        handleFileSelect();
    });

    uploadInput.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        const file = uploadInput.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
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
