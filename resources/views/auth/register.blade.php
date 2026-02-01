<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Men's Club</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a0a0a;
            --secondary: #2a2a2a;
            --accent: #d4af37;
            --accent-light: rgba(212, 175, 55, 0.1);
            --light: #fafafa;
            --gray: #6b7280;
            --light-gray: #f3f4f6;
            --border: #e5e7eb;
            --error: #ef4444;
            --success: #10b981;
            --radius: 12px;
            --radius-lg: 16px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--light);
            color: var(--primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(212, 175, 55, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeIn 0.8s ease;
        }

        .logo a {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius);
            transition: var(--transition);
        }

        .logo a:hover {
            transform: translateY(-2px);
            background: var(--accent-light);
        }

        .logo i {
            color: var(--accent);
            font-size: 1.5rem;
        }

        /* Register Container */
        .register-container {
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.6s ease 0.2s both;
        }

        /* Register Card */
        .register-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #f7d794);
        }

        /* Header */
        .register-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .register-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--primary);
        }

        .register-header p {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Form */
        .register-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary);
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .form-label.optional::after {
            content: ' (optional)';
            color: var(--gray);
            font-weight: normal;
        }

        .input-group {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: var(--transition);
            background: white;
            color: var(--primary);
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
            transform: translateY(-1px);
        }

        .form-input.is-invalid {
            border-color: var(--error);
            background: linear-gradient(to right, rgba(239, 68, 68, 0.02), transparent);
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-input:focus + .input-icon {
            color: var(--accent);
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition);
            padding: 0.25rem;
            border-radius: 4px;
        }

        .password-toggle:hover {
            color: var(--accent);
            background: var(--accent-light);
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 0.75rem;
            display: none;
        }

        .strength-meter {
            height: 4px;
            background: var(--light-gray);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: var(--error);
            border-radius: 2px;
            transition: var(--transition);
        }

        .strength-fill.weak {
            width: 33%;
            background: var(--error);
        }

        .strength-fill.medium {
            width: 66%;
            background: #f59e0b;
        }

        .strength-fill.strong {
            width: 100%;
            background: var(--success);
        }

        .strength-text {
            font-size: 0.8rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .strength-text i {
            font-size: 0.7rem;
        }

        /* Error Messages */
        .invalid-feedback {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--error);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideDown 0.3s ease;
        }

        .invalid-feedback::before {
            content: '!';
            width: 18px;
            height: 18px;
            background: var(--error);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            flex-shrink: 0;
        }

        /* Terms & Conditions */
        .terms-group {
            margin: 2rem 0;
            padding: 1.5rem;
            background: var(--light-gray);
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .checkbox-container {
            display: flex;
            gap: 1rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-input {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 5px;
            background: white;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
            margin-top: 0.25rem;
        }

        .checkbox-input:checked {
            background: var(--accent);
            border-color: var(--accent);
        }

        .checkbox-input:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--primary);
            font-size: 12px;
            font-weight: bold;
        }

        .checkbox-label {
            font-size: 0.9rem;
            color: var(--secondary);
            line-height: 1.5;
        }

        .checkbox-label a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            color: var(--primary);
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .submit-button::before {
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

        .submit-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .submit-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 2rem 0;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider span {
            padding: 0 1rem;
        }

        /* Login Link */
        .login-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .login-link p {
            color: var(--gray);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .login-link a:hover {
            background: var(--accent-light);
            text-decoration: underline;
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideInRight 0.4s ease;
            z-index: 1000;
            border-left: 4px solid var(--accent);
        }

        .toast.error {
            border-left-color: var(--error);
        }

        .toast.success {
            border-left-color: var(--success);
        }

        .toast i {
            color: var(--accent);
            font-size: 1.25rem;
        }

        .toast.error i {
            color: var(--error);
        }

        .toast.success i {
            color: var(--success);
        }

        .toast-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            font-size: 0.875rem;
            padding: 0.25rem;
            border-radius: 4px;
            transition: var(--transition);
        }

        .toast-close:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }

            .register-card {
                padding: 2rem 1.5rem;
            }

            .register-header h1 {
                font-size: 1.5rem;
            }

            .logo a {
                font-size: 1.75rem;
                padding: 0.5rem 1rem;
            }

            .terms-group {
                padding: 1rem;
            }

            .toast {
                left: 1rem;
                right: 1rem;
                bottom: 1rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            :root {
                --primary: #ffffff;
                --secondary: #d1d5db;
                --light: #111827;
                --gray: #9ca3af;
                --light-gray: #1f2937;
                --border: #374151;
            }

            .register-card {
                background: #1a1f36;
                border-color: var(--border);
            }

            .form-input {
                background: #111827;
                border-color: var(--border);
                color: var(--primary);
            }

            .form-input:focus {
                background: #111827;
            }

            .checkbox-input {
                background: #111827;
            }

            .terms-group {
                background: #1f2937;
                border-color: var(--border);
            }
        }
    </style>
</head>
<body>
    <!-- Toast Notification -->
    <div id="toast" class="toast" style="display: none;">
        <i class="fas fa-exclamation-circle"></i>
        <span id="toastMessage"></span>
        <button class="toast-close" onclick="hideToast()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="register-container">
        <!-- Logo -->
        <div class="logo">
            <a href="/">
                <i class="fas fa-cut"></i>
                Men's Club
            </a>
        </div>

        <!-- Register Card -->
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <h1>Join Men's Club</h1>
                <p>Create your account to book appointments and access premium features</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="register-form" id="registerForm">
                @csrf

                <!-- Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <input id="name" 
                               type="text" 
                               class="form-input @error('name') is-invalid @enderror" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autocomplete="name" 
                               autofocus
                               placeholder="John Doe">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <input id="email" 
                               type="email" 
                               class="form-input @error('email') is-invalid @enderror" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email"
                               placeholder="you@example.com">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" 
                               type="password" 
                               class="form-input @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="••••••••">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-meter">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-text" id="strengthText">
                            <i class="fas fa-info-circle"></i>
                            <span>Password strength: <span id="strengthLevel">None</span></span>
                        </div>
                    </div>
                    
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input id="password-confirm" 
                               type="password" 
                               class="form-input" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="••••••••">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="invalid-feedback" id="passwordMatchError" style="display: none;">
                        Passwords do not match
                    </div>
                </div>

                <!-- Phone Number (Optional) -->
                <div class="form-group">
                    <label for="phone" class="form-label optional">Phone Number</label>
                    <div class="input-group">
                        <input id="phone" 
                               type="tel" 
                               class="form-input @error('phone') is-invalid @enderror" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               autocomplete="tel"
                               placeholder="+60 12-345 6789">
                        <i class="fas fa-phone input-icon"></i>
                    </div>
                    @error('phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Terms & Conditions -->
               

                <!-- Submit Button -->
                <button type="submit" class="submit-button" id="submitButton">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>Already have an account?</span>
            </div>

            <!-- Login Link -->
            <div class="login-link">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign in to your account
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitButton = document.getElementById('submitButton');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password-confirm');
            const passwordMatchError = document.getElementById('passwordMatchError');
            const passwordStrength = document.getElementById('passwordStrength');
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');
            const strengthLevel = document.getElementById('strengthLevel');
            
            // Show validation errors if any
            @if($errors->any())
                setTimeout(() => {
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.focus();
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    // Show toast with error count
                    const errorCount = document.querySelectorAll('.is-invalid').length;
                    if (errorCount > 0) {
                        showToast(`Please check the form for ${errorCount} error${errorCount > 1 ? 's' : ''}`, 'error');
                    }
                }, 100);
            @endif
            
            // Password strength checker
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
            
            // Confirm password match checker
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            
            // Password strength calculation
            function checkPasswordStrength(password) {
                if (!password) {
                    passwordStrength.style.display = 'none';
                    return;
                }
                
                passwordStrength.style.display = 'block';
                
                let strength = 0;
                let feedback = '';
                let level = 'None';
                
                // Length check
                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;
                
                // Complexity checks
                if (/[A-Z]/.test(password)) strength++; // Uppercase
                if (/[a-z]/.test(password)) strength++; // Lowercase
                if (/[0-9]/.test(password)) strength++; // Numbers
                if (/[^A-Za-z0-9]/.test(password)) strength++; // Special characters
                
                // Determine strength level
                if (strength <= 2) {
                    level = 'Weak';
                    strengthFill.className = 'strength-fill weak';
                    feedback = 'Add more characters and complexity';
                } else if (strength <= 4) {
                    level = 'Medium';
                    strengthFill.className = 'strength-fill medium';
                    feedback = 'Good, but could be stronger';
                } else {
                    level = 'Strong';
                    strengthFill.className = 'strength-fill strong';
                    feedback = 'Excellent password';
                }
                
                strengthLevel.textContent = level;
                strengthText.innerHTML = `<i class="fas fa-info-circle"></i> Password strength: <span>${level}</span> • ${feedback}`;
            }
            
            // Password match checker
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (!confirmPassword) {
                    passwordMatchError.style.display = 'none';
                    confirmPasswordInput.classList.remove('is-invalid');
                    return;
                }
                
                if (password !== confirmPassword) {
                    passwordMatchError.style.display = 'flex';
                    confirmPasswordInput.classList.add('is-invalid');
                } else {
                    passwordMatchError.style.display = 'none';
                    confirmPasswordInput.classList.remove('is-invalid');
                }
            }
            
            // Form submission
            form.addEventListener('submit', function(e) {
                // Validate form
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password-confirm').value;
                const terms = document.getElementById('terms').checked;
                
                let isValid = true;
                let errorMessage = '';
                
                // Name validation
                if (!name.trim()) {
                    isValid = false;
                    errorMessage = 'Name is required';
                } else if (name.trim().length < 2) {
                    isValid = false;
                    errorMessage = 'Name must be at least 2 characters';
                }
                
                // Email validation
                else if (!email) {
                    isValid = false;
                    errorMessage = 'Email is required';
                } else if (!isValidEmail(email)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
                
                // Password validation
                else if (!password) {
                    isValid = false;
                    errorMessage = 'Password is required';
                } else if (password.length < 8) {
                    isValid = false;
                    errorMessage = 'Password must be at least 8 characters';
                }
                
                // Password match validation
                else if (password !== confirmPassword) {
                    isValid = false;
                    errorMessage = 'Passwords do not match';
                }
                
                // Terms validation
                else if (!terms) {
                    isValid = false;
                    errorMessage = 'You must agree to the terms and conditions';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    showToast(errorMessage, 'error');
                    
                    // Scroll to first error
                    const firstError = document.querySelector('.is-invalid') || 
                                      (password !== confirmPassword ? confirmPasswordInput : null) ||
                                      (!terms ? document.getElementById('terms') : null);
                    
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                    
                    return;
                }
                
                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
                
                // Add ripple effect
                addRippleEffect(e);
            });
            
            // Toggle password visibility
            window.togglePassword = function(fieldId) {
                const input = document.getElementById(fieldId);
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                
                // Find the toggle button for this field
                const toggleButtons = document.querySelectorAll('.password-toggle');
                toggleButtons.forEach(button => {
                    if (button.onclick.toString().includes(fieldId)) {
                        const icon = button.querySelector('i');
                        icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                    }
                });
            }
            
            // Email validation
            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            // Show toast notification
            window.showToast = function(message, type = 'error') {
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toastMessage');
                const toastIcon = toast.querySelector('i');
                
                toastMessage.textContent = message;
                toast.className = `toast ${type}`;
                
                // Set icon based on type
                if (type === 'error') {
                    toastIcon.className = 'fas fa-exclamation-circle';
                } else if (type === 'success') {
                    toastIcon.className = 'fas fa-check-circle';
                } else {
                    toastIcon.className = 'fas fa-info-circle';
                }
                
                toast.style.display = 'flex';
                
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    hideToast();
                }, 5000);
            }
            
            // Hide toast
            window.hideToast = function() {
                const toast = document.getElementById('toast');
                toast.style.display = 'none';
            }
            
            // Add ripple effect to button click
            function addRippleEffect(event) {
                const button = event.target.closest('.submit-button');
                if (!button) return;
                
                const ripple = document.createElement('span');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = event.clientX - rect.left - size / 2;
                const y = event.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.3);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                `;
                
                button.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            }
            
            // Add ripple CSS if not already added
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
            
            // Auto-focus on name field if no errors
            @if(!$errors->any())
                setTimeout(() => {
                    document.getElementById('name').focus();
                }, 300);
            @endif
            
            // Handle Enter key press
            form.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.target.closest('.password-toggle')) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>
</body>
</html>