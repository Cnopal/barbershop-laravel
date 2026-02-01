@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-left">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-small">
                <i class="fas fa-arrow-left"></i> Back to Customers
            </a>
        </div>
        <div class="header-center">
            <h1 class="page-title">Edit Customer</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i> View Profile
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" id="editCustomerForm">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <!-- Personal Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-user"></i> Personal Information</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $customer->name) }}" 
                                   placeholder="Enter customer's full name"
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
                                   value="{{ old('email', $customer->email) }}" 
                                   placeholder="customer@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group">
                            <label for="phone" class="optional">Phone Number</label>
                            <div class="input-with-icon">
                                <span class="input-icon"><i class="fas fa-phone"></i></span>
                                <input type="tel" id="phone" name="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $customer->phone) }}" 
                                       placeholder="012-3456789">
                            </div>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="form-group">
                            <label for="address" class="optional">Address</label>
                            <textarea id="address" name="address" 
                                      class="form-control @error('address') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Enter customer's address">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Update Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-key"></i> Update Password (Optional)</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Current Password -->
                        <div class="form-group">
                            <label for="current_password" class="optional">Current Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" 
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Enter current password">
                                <button type="button" class="toggle-password" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Required only if changing password</small>
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="password" class="optional">New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Leave blank to keep current password">
                                <button type="button" class="toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="password-strength" id="passwordStrength" style="display: none;">
                                <div class="strength-bar"></div>
                                <span class="strength-text">Password strength: None</span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="optional">Confirm New Password</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Confirm new password">
                                <button type="button" class="toggle-password" data-target="password_confirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Customer Preview -->
                        <div class="customer-preview">
                            <div class="preview-header">
                                <div class="preview-avatar">
                                    <span id="previewInitials">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div class="preview-info">
                                    <h4 id="previewName">{{ $customer->name }}</h4>
                                    <div class="preview-email" id="previewEmail">{{ $customer->email }}</div>
                                </div>
                            </div>
                            <div class="preview-stats">
                                <div class="stat">
                                    <span class="stat-value">{{ $customer->appointments_count ?? 0 }}</span>
                                    <span class="stat-label">Appointments</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-value">
                                        {{ $customer->created_at->diffForHumans(null, true) }}
                                    </span>
                                    <span class="stat-label">Member</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Customer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Use same styles as create page, add these additional styles */
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
    
    .header-left, .header-center {
        display: flex;
        align-items: center;
    }
    
    .header-center {
        flex: 1;
        justify-content: center;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
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
    
    .btn-small {
        padding: 8px 16px;
        font-size: 14px;
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
    
    /* Input with Icon */
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .input-with-icon .form-control {
        padding-left: 40px;
    }
    
    .input-icon {
        position: absolute;
        left: 12px;
        color: var(--dark-gray);
        pointer-events: none;
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
    
    /* Checkbox */
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 5px;
    }
    
    .checkbox-group input[type="checkbox"] {
        display: none;
    }
    
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-weight: 500;
        color: var(--primary-color);
    }
    
    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--medium-gray);
        border-radius: 4px;
        position: relative;
        transition: var(--transition);
    }
    
    .checkbox-group input[type="checkbox"]:checked + .checkbox-label .checkmark {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    .checkmark::after {
        content: '';
        position: absolute;
        display: none;
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .checkbox-group input[type="checkbox"]:checked + .checkbox-label .checkmark::after {
        display: block;
    }
    
    /* Customer Preview */
    .customer-preview {
        background-color: var(--light-gray);
        border-radius: 8px;
        padding: 20px;
        border: 1px solid var(--medium-gray);
        margin-top: 20px;
    }
    
    .preview-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .preview-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .preview-info h4 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .preview-email {
        font-size: 14px;
        color: var(--dark-gray);
    }
    
    .preview-note {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        background-color: white;
        border-radius: 6px;
        font-size: 13px;
        color: var(--dark-gray);
    }
    
    .preview-note i {
        color: var(--accent-color);
    }
    
    /* Form Text */
    .form-text {
        display: block;
        margin-top: 4px;
        font-size: 13px;
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
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: stretch;
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
    .customer-preview {
        background-color: var(--light-gray);
        border-radius: 8px;
        padding: 20px;
        border: 1px solid var(--medium-gray);
        margin-top: 20px;
    }
    
    .preview-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .preview-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: bold;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .preview-info h4 {
        margin: 0 0 5px 0;
        font-size: 18px;
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .preview-email {
        font-size: 14px;
        color: var(--dark-gray);
    }
    
    .preview-stats {
        display: flex;
        gap: 20px;
        padding-top: 15px;
        border-top: 1px solid var(--medium-gray);
    }
    
    .stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    
    .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .stat-label {
        font-size: 12px;
        color: var(--dark-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const currentPasswordInput = document.getElementById('current_password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    // Preview elements
    const previewName = document.getElementById('previewName');
    const previewEmail = document.getElementById('previewEmail');
    const previewInitials = document.getElementById('previewInitials');
    
    // Password strength elements
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    
    // Update preview in real-time
    function updatePreview() {
        // Update name and initials
        if (nameInput.value.trim()) {
            previewName.textContent = nameInput.value;
            const names = nameInput.value.split(' ');
            const initials = names.map(n => n.charAt(0)).join('').toUpperCase().substring(0, 2);
            previewInitials.textContent = initials || '??';
        }
        
        // Update email
        if (emailInput.value.trim()) {
            previewEmail.textContent = emailInput.value;
        }
    }
    
    // Update password strength
    function updatePasswordStrength() {
        const password = passwordInput.value;
        
        if (password.length > 0) {
            passwordStrength.style.display = 'block';
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
        } else {
            passwordStrength.style.display = 'none';
        }
    }
    
    // Toggle password visibility
    function togglePasswordVisibility(targetId) {
        const input = document.getElementById(targetId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        const button = document.querySelector(`[data-target="${targetId}"]`);
        if (button) {
            button.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        }
    }
    
    // Phone number formatting
    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');
        
        if (value.length > 0) {
            if (value.length <= 3) {
                value = value;
            } else if (value.length <= 6) {
                value = value.replace(/(\d{3})(\d{0,3})/, '$1-$2');
            } else {
                value = value.replace(/(\d{3})(\d{3})(\d{0,4})/, '$1-$2$3');
            }
        }
        
        input.value = value;
    }
    
    // Initialize preview
    updatePreview();
    
    // Add event listeners
    nameInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);
    passwordInput.addEventListener('input', updatePasswordStrength);
    
    // Toggle password buttons
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            togglePasswordVisibility(target);
        });
    });
    
    // Phone formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            formatPhoneNumber(this);
        });
    }
    
    // Form validation
    const form = document.getElementById('editCustomerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if password is being changed
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const currentPassword = currentPasswordInput.value;
            
            if (password || confirmPassword) {
                // Password is being changed, check all fields
                if (!currentPassword) {
                    e.preventDefault();
                    alert('Please enter current password to change password');
                    currentPasswordInput.focus();
                    return false;
                }
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('New passwords do not match');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('New password must be at least 6 characters long');
                    passwordInput.focus();
                    return false;
                }
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            submitBtn.disabled = true;
            
            // Re-enable button if form fails to submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }
    
    // Show validation errors if any
    @if($errors->any())
        setTimeout(() => {
            showToast('Please fix the errors in the form', 'error');
        }, 500);
    @endif
    
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
});
</script>
@endsection