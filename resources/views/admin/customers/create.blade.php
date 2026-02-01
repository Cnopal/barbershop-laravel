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
            <h1 class="page-title">Add New Customer</h1>
        </div>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <form action="{{ route('admin.customers.store') }}" method="POST" id="createCustomerForm">
            @csrf
            
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
                                   value="{{ old('name') }}" 
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
                                   value="{{ old('email') }}" 
                                   placeholder="customer@example.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">This will be used for login and notifications</small>
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group">
                            <label for="phone" class="optional">Phone Number</label>
                            <div class="input-with-icon">
                                <span class="input-icon"><i class="fas fa-phone"></i></span>
                                <input type="tel" id="phone" name="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" 
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
                                      placeholder="Enter customer's address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h3><i class="fas fa-key"></i> Account Information</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Create a password"
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

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password *</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       placeholder="Confirm the password"
                                       required>
                                <button type="button" class="toggle-password" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Auto-generate password option -->
                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" id="generatePassword" name="generate_password">
                                <label for="generatePassword" class="checkbox-label">
                                    <span class="checkmark"></span>
                                    Auto-generate password
                                </label>
                            </div>
                            <small class="form-text text-muted">If checked, a random password will be generated and sent to the customer's email</small>
                        </div>

                        <!-- Customer Preview -->
                        <div class="customer-preview">
                            <div class="preview-header">
                                <div class="preview-avatar">
                                    <span id="previewInitials">?</span>
                                </div>
                                <div class="preview-info">
                                    <h4 id="previewName">Customer Name</h4>
                                    <div class="preview-email" id="previewEmail">email@example.com</div>
                                </div>
                            </div>
                            <div class="preview-note">
                                <i class="fas fa-info-circle"></i>
                                <span>This is how the customer will appear in the system</span>
                            </div>
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
                    <i class="fas fa-plus"></i> Create Customer
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
        --accent-light: #f8f3e6;
        --light-gray: #f8fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success-color: #48bb78;
        --warning-color: #ed8936;
        --danger-color: #f56565;
        --info-color: #4299e1;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius: 12px;
        --border-radius-sm: 8px;
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
        animation: fadeIn 0.5s ease-out;
    }

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

    /* Page Header - Enhanced */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
        padding: 20px 0;
        border-bottom: 2px solid var(--accent-light);
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
        font-weight: 800;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color), #f7d794);
        border-radius: 2px;
    }

    /* Button Styles - Enhanced */
    .btn {
        padding: 12px 28px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 15px;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.3px;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-color) 0%, #e6c158 100%);
        color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
    }

    .btn-secondary {
        background: white;
        color: var(--primary-color);
        border: 2px solid var(--medium-gray);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-secondary:hover {
        background: var(--light-gray);
        border-color: var(--accent-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .btn-small {
        padding: 8px 20px;
        font-size: 14px;
        border-radius: 6px;
    }

    /* Form Container - Enhanced */
    .form-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: 1px solid var(--medium-gray);
        margin-top: 20px;
        position: relative;
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color), #f7d794);
    }

    /* Form Cards - Enhanced */
    .form-card {
        margin-bottom: 30px;
        border: 1px solid var(--medium-gray);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        background: white;
        transition: var(--transition);
    }

    .form-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border-color: var(--accent-color);
    }

    .form-card-header {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        padding: 24px;
        border-bottom: 1px solid var(--medium-gray);
        position: relative;
    }

    .form-card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent-color);
    }

    .form-card-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
    }

    .form-card-header i {
        color: var(--accent-color);
        font-size: 20px;
        background: var(--accent-light);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-card-body {
        padding: 30px;
    }

    /* Form Grid */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    /* Form Groups - Enhanced */
    .form-group {
        margin-bottom: 28px;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--primary-color);
        font-size: 14px;
        letter-spacing: 0.3px;
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
        padding: 14px 16px;
        border-radius: var(--border-radius-sm);
        border: 2px solid var(--medium-gray);
        font-size: 15px;
        transition: var(--transition);
        background: white;
        color: var(--primary-color);
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        transform: translateY(-1px);
    }

    .form-control.is-invalid {
        border-color: var(--danger-color);
        background: linear-gradient(to right, rgba(245, 101, 101, 0.05), transparent);
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(245, 101, 101, 0.1);
    }

    .form-control:disabled {
        background-color: var(--light-gray);
        cursor: not-allowed;
        opacity: 0.7;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
        line-height: 1.6;
        padding: 16px;
    }

    /* Input with Icon - Enhanced */
    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-icon .form-control {
        padding-left: 50px;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        color: var(--dark-gray);
        pointer-events: none;
        font-size: 18px;
    }

    .input-with-icon:focus-within .input-icon {
        color: var(--accent-color);
    }

    /* Password Input - Enhanced */
    .password-input-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        padding: 6px;
        transition: var(--transition);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    .toggle-password:hover {
        color: var(--accent-color);
        background: var(--accent-light);
    }

    .password-strength {
        margin-top: 12px;
        padding: 12px;
        background: var(--light-gray);
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--medium-gray);
        display: none;
    }

    .strength-bar {
        height: 6px;
        background-color: var(--medium-gray);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 8px;
        position: relative;
    }

    .strength-bar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0%;
        background-color: var(--danger-color);
        transition: var(--transition);
        border-radius: 3px;
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
        background-color: var(--info-color);
    }

    .strength-bar.strong::before {
        width: 100%;
        background-color: var(--success-color);
    }

    .strength-text {
        font-size: 13px;
        color: var(--dark-gray);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .strength-text::after {
        content: '';
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 10px;
        background: var(--medium-gray);
        color: var(--primary-color);
        font-weight: 600;
    }

    .strength-bar.weak ~ .strength-text::after {
        content: 'Weak';
        background: var(--danger-color);
        color: white;
    }

    .strength-bar.fair ~ .strength-text::after {
        content: 'Fair';
        background: var(--warning-color);
        color: white;
    }

    .strength-bar.good ~ .strength-text::after {
        content: 'Good';
        background: var(--info-color);
        color: white;
    }

    .strength-bar.strong ~ .strength-text::after {
        content: 'Strong';
        background: var(--success-color);
        color: white;
    }

    /* Checkbox - Enhanced */
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 8px;
    }

    .checkbox-group input[type="checkbox"] {
        display: none;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-weight: 500;
        color: var(--primary-color);
        padding: 8px;
        border-radius: 6px;
        transition: var(--transition);
    }

    .checkbox-label:hover {
        background: var(--accent-light);
    }

    .checkmark {
        width: 22px;
        height: 22px;
        border: 2px solid var(--medium-gray);
        border-radius: 6px;
        position: relative;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .checkbox-group input[type="checkbox"]:checked + .checkbox-label .checkmark {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
    }

    .checkmark::after {
        content: '';
        position: absolute;
        display: none;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        margin-top: -2px;
    }

    .checkbox-group input[type="checkbox"]:checked + .checkbox-label .checkmark::after {
        display: block;
    }

    /* Customer Preview - Enhanced */
    .customer-preview {
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        border-radius: var(--border-radius-sm);
        padding: 28px;
        border: 2px solid var(--accent-light);
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }

    .customer-preview::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-color), #f7d794);
    }

    .preview-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .preview-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent-color) 0%, #e6c158 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: bold;
        font-size: 22px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
    }

    .preview-info h4 {
        margin: 0 0 6px 0;
        font-size: 20px;
        color: var(--primary-color);
        font-weight: 700;
    }

    .preview-email {
        font-size: 14px;
        color: var(--dark-gray);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .preview-email i {
        color: var(--accent-color);
        font-size: 12px;
    }

    .preview-note {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px;
        background: white;
        border-radius: 8px;
        font-size: 13px;
        color: var(--dark-gray);
        border: 1px solid var(--medium-gray);
        margin-top: 15px;
    }

    .preview-note i {
        color: var(--accent-color);
        font-size: 16px;
        flex-shrink: 0;
    }

    /* Form Text */
    .form-text {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--dark-gray);
        line-height: 1.5;
        padding-left: 4px;
    }

    /* Form Actions - Enhanced */
    .form-actions {
        padding: 30px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 20px;
        background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
        position: relative;
    }

    .form-actions::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent-color), transparent);
    }

    /* Invalid Feedback - Enhanced */
    .invalid-feedback {
        display: block;
        margin-top: 6px;
        font-size: 13px;
        color: var(--danger-color);
        padding-left: 4px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .invalid-feedback::before {
        content: '!';
        width: 16px;
        height: 16px;
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }

    /* Toast notification styles - Enhanced */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #2d3748 100%);
        color: white;
        padding: 20px 28px;
        border-radius: 12px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        z-index: 1100;
        display: flex;
        align-items: center;
        gap: 16px;
        animation: toastSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 450px;
        border-left: 4px solid var(--accent-color);
        backdrop-filter: blur(10px);
    }

    @keyframes toastSlideIn {
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

    .toast i {
        color: var(--accent-color);
        font-size: 22px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.1);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast span {
        line-height: 1.5;
        flex: 1;
        font-weight: 500;
    }

    .toast.error i {
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
            gap: 15px;
            padding-bottom: 15px;
        }

        .page-title {
            font-size: 26px;
            text-align: center;
        }

        .page-title::after {
            width: 40px;
            height: 3px;
        }

        .form-card-body {
            padding: 24px;
        }

        .form-actions {
            flex-direction: column;
            padding: 24px;
            gap: 15px;
        }

        .form-actions .btn {
            width: 100%;
        }

        .preview-header {
            flex-direction: column;
            text-align: center;
        }

        .preview-info h4 {
            font-size: 18px;
        }

        .form-control {
            padding: 12px 14px;
        }

        .btn {
            padding: 12px 20px;
        }

        .customer-preview {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .form-group label {
            font-size: 13px;
        }

        .page-title {
            font-size: 24px;
        }

        .form-card-header h3 {
            font-size: 16px;
        }

        .form-card-header i {
            width: 32px;
            height: 32px;
            font-size: 16px;
        }

        .customer-preview {
            padding: 16px;
        }

        .preview-avatar {
            width: 50px;
            height: 50px;
            font-size: 18px;
        }

        .preview-info h4 {
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }

        .btn-small {
            padding: 6px 16px;
        }

        .input-with-icon .form-control {
            padding-left: 45px;
        }

        .input-icon {
            left: 14px;
            font-size: 16px;
        }

        .toggle-password {
            right: 14px;
            padding: 4px;
        }

        .form-row {
            gap: 20px;
        }
    }

    /* Loading animation for submit button */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    /* Focus states for accessibility */
    .btn:focus,
    .form-control:focus,
    .toggle-password:focus {
        outline: 2px solid var(--accent-color);
        outline-offset: 2px;
    }

    /* Disabled state styling */
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn:disabled:hover::before {
        width: 0;
        height: 0;
    }

    /* Selection color */
    ::selection {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const generatePasswordCheckbox = document.getElementById('generatePassword');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    
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
            previewInitials.textContent = initials || '?';
        } else {
            previewName.textContent = 'Customer Name';
            previewInitials.textContent = '?';
        }
        
        // Update email
        if (emailInput.value.trim()) {
            previewEmail.textContent = emailInput.value;
        } else {
            previewEmail.textContent = 'email@example.com';
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
    
    // Handle auto-generate password
    function handleGeneratePassword() {
        if (generatePasswordCheckbox.checked) {
            // Generate random password
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
            let password = '';
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            
            // Set password fields
            passwordInput.value = password;
            confirmPasswordInput.value = password;
            
            // Disable password fields
            passwordInput.disabled = true;
            confirmPasswordInput.disabled = true;
            passwordInput.style.backgroundColor = '#f0f0f0';
            confirmPasswordInput.style.backgroundColor = '#f0f0f0';
            
            // Update strength
            updatePasswordStrength();
        } else {
            // Enable password fields
            passwordInput.disabled = false;
            confirmPasswordInput.disabled = false;
            passwordInput.style.backgroundColor = '';
            confirmPasswordInput.style.backgroundColor = '';
            passwordInput.value = '';
            confirmPasswordInput.value = '';
            passwordStrength.style.display = 'none';
        }
    }
    
    // Toggle password visibility
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        button.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
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
    
    if (generatePasswordCheckbox) {
        generatePasswordCheckbox.addEventListener('change', handleGeneratePassword);
    }
    
    if (togglePassword) {
        togglePassword.addEventListener('click', () => togglePasswordVisibility('password', togglePassword));
    }
    
    if (togglePasswordConfirm) {
        togglePasswordConfirm.addEventListener('click', () => togglePasswordVisibility('password_confirmation', togglePasswordConfirm));
    }
    
    // Phone formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            formatPhoneNumber(this);
        });
    }
    
    // Form validation
    const form = document.getElementById('createCustomerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if passwords match (if not auto-generated)
            if (!generatePasswordCheckbox.checked) {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match');
                    return false;
                }
                
                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long');
                    return false;
                }
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