@extends('admin.sidebar')

@section('content')
<div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
        <h2>Edit Admin Profile</h2>
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
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                alt="{{ $user->name }}" id="previewImage">
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
                               placeholder="admin@example.com"
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
                               placeholder="Your phone number">
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address Field -->
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" 
                               value="{{ old('address', $user->address) }}" 
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="Your address">
                        @error('address')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i> Security
                </h3>
                <p class="section-description">Leave password fields empty to keep your current password</p>
                <div class="form-grid">
                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password" name="password" 
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Leave empty to keep current password">
                            <button type="button" class="toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="form-control"
                                   placeholder="Confirm your password">
                            <button type="button" class="toggle-password" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
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
        --light: #f8f9fa;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success: #48bb78;
        --danger: #f56565;
        --warning: #ed8936;
        --radius: 12px;
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
        margin-bottom: 1.5rem;
        padding: 1rem;
        border-radius: var(--radius);
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
        border: 1px solid #fecaca;
        color: #7f1d1d;
    }

    .alert-success {
        background-color: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert i {
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .alert h4 {
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .alert ul {
        margin-left: 1.5rem;
    }

    .card {
        background: white;
        border-radius: var(--radius);
        padding: 2rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--accent);
    }

    .section-description {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 1rem;
        font-style: italic;
    }

    .picture-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 1rem;
    }

    .current-avatar {
        text-align: center;
    }

    .avatar-circle {
        width: 150px;
        height: 150px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        overflow: hidden;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-label {
        font-size: 0.875rem;
        color: var(--secondary);
    }

    .upload-area {
        border: 2px dashed var(--medium-gray);
        border-radius: var(--radius);
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .upload-area:hover {
        border-color: var(--accent);
        background-color: var(--light-gray);
    }

    .upload-area i {
        font-size: 2.5rem;
        color: var(--accent);
        margin-bottom: 0.5rem;
    }

    .upload-area h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .upload-area p {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 1rem;
    }

    .upload-input {
        display: none;
    }

    .upload-help {
        display: block;
        font-size: 0.75rem;
        color: var(--dark-gray);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }

    .required {
        color: var(--danger);
    }

    .form-control {
        padding: 0.75rem 1rem;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        font-size: 0.9375rem;
        font-family: inherit;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .form-error {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .password-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input-wrapper .form-control {
        flex: 1;
        padding-right: 2.5rem;
    }

    .toggle-password {
        position: absolute;
        right: 0.75rem;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--secondary);
        font-size: 1rem;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toggle-password:hover {
        color: var(--accent);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        text-decoration: none;
        transition: var(--transition);
        font-family: inherit;
    }

    .btn-primary {
        background-color: var(--accent);
        color: var(--primary);
    }

    .btn-primary:hover {
        background-color: #c19a2f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-secondary {
        background-color: var(--medium-gray);
        color: var(--primary);
    }

    .btn-secondary:hover {
        background-color: var(--dark-gray);
        color: white;
    }

    @media (max-width: 768px) {
        .card {
            padding: 1.5rem;
        }

        .picture-section {
            grid-template-columns: 1fr;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
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
        uploadArea.style.backgroundColor = 'var(--light-gray)';
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.style.borderColor = 'var(--medium-gray)';
        uploadArea.style.backgroundColor = 'transparent';
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--medium-gray)';
        uploadArea.style.backgroundColor = 'transparent';
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
