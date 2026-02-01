@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Add New Barber</h1>
        <a href="{{ route('admin.staffs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Barbers
        </a>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data" id="createBarberForm">
            @csrf
            
            <div class="form-row">
                <!-- Personal Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Name Fields -->
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" 
                                   placeholder="Enter barber's full name"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" 
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" 
                                   placeholder="barber@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" 
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}" 
                                   placeholder="012-3456789"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="form-group">
                            <label for="address">Address *</label>
                            <textarea id="address" name="address" 
                                      class="form-control @error('address') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Enter full address"
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-briefcase"></i> Professional Information</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Position Field -->
                        <div class="form-group">
                            <label for="position">Position *</label>
                            <select id="position" name="position" 
                                    class="form-control @error('position') is-invalid @enderror"
                                    required>
                                <option value="">Select Position</option>
                                <option value="Junior Barber" {{ old('position') == 'Junior Barber' ? 'selected' : '' }}>Junior Barber</option>                        
                                <option value="Senior Barber" {{ old('position') == 'Senior Barber' ? 'selected' : '' }}>Senior Barber</option>                             
                            </select>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role Field -->
                        <div class="form-group">
                            <label for="role">Role *</label>
                            <select id="role" name="role" 
                                    class="form-control @error('role') is-invalid @enderror"
                                    required>
                                <option value="">Select Role</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" 
                                    class="form-control @error('status') is-invalid @enderror"
                                    required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Profile Image Field -->
                        <div class="form-group">
                            <label for="profile_image">Profile Photo</label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="profile_image" name="profile_image" 
                                       class="file-upload @error('profile_image') is-invalid @enderror"
                                       accept="image/*">
                                <div class="file-upload-preview" id="imagePreview">
                                    <i class="fas fa-user-circle"></i>
                                    <span>No image selected</span>
                                </div>
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Recommended: Square image, max 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Card -->
            <div class="form-card">
                <div class="form-card-header">
                    <h3><i class="fas fa-lock"></i> Account Security</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Create a strong password"
                                       required>
                                <button type="button" class="toggle-password" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar"></div>
                                <span class="strength-text">Password strength: None</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Re-enter the password"
                                       required>
                                <button type="button" class="toggle-password" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Barber
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* CSS Variables */
    :root {
        --primary-color: #1a1f36;
        --secondary-color: #4a5568;
        --accent-color: #d4af37;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success-color: #48bb78;
        --warning-color: #ed8936;
        --danger-color: #f56565;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    /* Button Styles */
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 15px;
        text-decoration: none;
    }
    
    .btn-primary {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #c19a2f;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--medium-gray);
    }
    
    .btn-secondary:hover {
        background-color: var(--light-gray);
    }
    
    /* Form Container */
    .form-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }
    
    /* Form Cards */
    .form-card {
        margin-bottom: 25px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        overflow: hidden;
    }
    
    .form-card-header {
        background-color: var(--light-gray);
        padding: 20px;
        border-bottom: 1px solid var(--medium-gray);
    }
    
    .form-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-card-header i {
        color: var(--accent-color);
    }
    
    .form-card-body {
        padding: 25px;
    }
    
    /* Form Grid */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
    
    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
    
    /* Form Groups */
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .form-group label:after {
        content: " *";
        color: var(--danger-color);
    }
    
    .form-group label.optional:after {
        content: "";
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: 6px;
        border: 1px solid var(--medium-gray);
        font-size: 15px;
        transition: var(--transition);
        background-color: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: var(--danger-color);
    }
    
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23718096' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 40px;
    }
    
    /* File Upload */
    .file-upload-wrapper {
        position: relative;
    }
    
    .file-upload {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    
    .file-upload-preview {
        border: 2px dashed var(--medium-gray);
        border-radius: 6px;
        padding: 30px;
        text-align: center;
        background-color: var(--light-gray);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        min-height: 150px;
        justify-content: center;
    }
    
    .file-upload-preview:hover {
        border-color: var(--accent-color);
        background-color: rgba(212, 175, 55, 0.05);
    }
    
    .file-upload-preview i {
        font-size: 48px;
        color: var(--dark-gray);
    }
    
    .file-upload-preview span {
        color: var(--dark-gray);
    }
    
    .file-upload-preview.has-image {
        border-style: solid;
        border-color: var(--success-color);
        background-size: cover;
        background-position: center;
    }
    
    .file-upload-preview.has-image i,
    .file-upload-preview.has-image span {
        display: none;
    }
    
    /* Password Input */
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
        color: var(--dark-gray);
        cursor: pointer;
        padding: 4px;
        transition: var(--transition);
    }
    
    .toggle-password:hover {
        color: var(--primary-color);
    }
    
    .password-strength {
        margin-top: 8px;
    }
    
    .strength-bar {
        height: 4px;
        background-color: var(--medium-gray);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 4px;
    }
    
    .strength-bar::before {
        content: '';
        display: block;
        height: 100%;
        width: 0%;
        background-color: var(--danger-color);
        transition: var(--transition);
    }
    
    .strength-bar.weak::before {
        width: 25%;
        background-color: var(--danger-color);
    }
    
    .strength-bar.fair::before {
        width: 50%;
        background-color: var(--warning-color);
    }
    
    .strength-bar.good::before {
        width: 75%;
        background-color: #4299e1;
    }
    
    .strength-bar.strong::before {
        width: 100%;
        background-color: var(--success-color);
    }
    
    .strength-text {
        font-size: 12px;
        color: var(--dark-gray);
    }
    
    /* Form Actions */
    .form-actions {
        padding: 25px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        background-color: var(--light-gray);
    }
    
    /* Invalid Feedback */
    .invalid-feedback {
        display: block;
        margin-top: 4px;
        font-size: 14px;
        color: var(--danger-color);
    }
    
    /* Form Text */
    .form-text {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        color: var(--dark-gray);
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .page-title {
            font-size: 24px;
        }
        
        .form-card-body {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
        }
    }
    
    @media (max-width: 480px) {
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .form-card-header h3 {
            font-size: 16px;
        }
    }
    
    /* Toast notification styles */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--primary-color);
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }
    
    .toast i {
        color: var(--success-color);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('profile_image');
    const imagePreview = document.getElementById('imagePreview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    showToast('Image size must be less than 2MB', 'error');
                    imageInput.value = '';
                    imagePreview.classList.remove('has-image');
                    imagePreview.style.backgroundImage = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.classList.add('has-image');
                    imagePreview.style.backgroundImage = `url(${e.target.result})`;
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.remove('has-image');
                imagePreview.style.backgroundImage = '';
            }
        });
    }
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    
    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let text = 'None';
            
            // Check password strength
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            // Update UI
            strengthBar.className = 'strength-bar';
            if (strength >= 100) {
                strengthBar.classList.add('strong');
                text = 'Strong';
            } else if (strength >= 75) {
                strengthBar.classList.add('good');
                text = 'Good';
            } else if (strength >= 50) {
                strengthBar.classList.add('fair');
                text = 'Fair';
            } else if (strength >= 25) {
                strengthBar.classList.add('weak');
                text = 'Weak';
            }
            
            strengthText.textContent = `Password strength: ${text}`;
        });
    }
    
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
    
    if (togglePasswordConfirm) {
        togglePasswordConfirm.addEventListener('click', function() {
            const passwordField = document.getElementById('password_confirmation');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    }
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.replace(/(\d{3})(\d{0,3})/, '$1-$2');
                } else {
                    value = value.replace(/(\d{3})(\d{3})(\d{0,4})/, '$1-$2$3');
                }
            }
            
            e.target.value = value;
        });
    }
    
    // Form validation
    const form = document.getElementById('createBarberForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if passwords match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showToast('Passwords do not match', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
            submitBtn.disabled = true;
            
            // Re-enable button if form fails to submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) existingToast.remove();
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const iconColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';
        
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <i class="fas ${icon}" style="color: ${iconColor};"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }
    
    // Show validation errors if any
    @if($errors->any())
        showToast('Please fix the errors in the form', 'error');
    @endif
    
    // Show success message if redirected from other page
    @if(session('success'))
        showToast('{{ session('success') }}');
    @endif
});
</script>
@endsection