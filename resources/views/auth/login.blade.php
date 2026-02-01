<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Men's Club</title>

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

        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 420px;
            animation: slideUp 0.6s ease 0.2s both;
        }

        /* Login Card */
        .login-card {
            background: white;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #f7d794);
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--primary);
        }

        .login-header p {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Form */
        .login-form {
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

        /* Remember Me */
        .remember-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-input {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 4px;
            background: white;
            cursor: pointer;
            position: relative;
            transition: var(--transition);
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
        }

        .forgot-link {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .forgot-link:hover {
            background: var(--accent-light);
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

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .register-link p {
            color: var(--gray);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .register-link a {
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

        .register-link a:hover {
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

        .toast i {
            color: var(--accent);
            font-size: 1.25rem;
        }

        .toast.error i {
            color: var(--error);
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

            .login-card {
                padding: 2rem 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .logo a {
                font-size: 1.75rem;
                padding: 0.5rem 1rem;
            }

            .remember-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .forgot-link {
                align-self: flex-start;
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

            .login-card {
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

    <div class="login-container">
        <!-- Logo -->
        <div class="logo">
            <a href="/">
                <i class="fas fa-cut"></i>
                Men's Club
            </a>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your account to continue</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                @csrf

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
                               autofocus
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
                    <div class="form-label-group">
                        <label for="password" class="form-label">Password</label>
                    </div>
                    <div class="input-group">
                        <input id="password" 
                               type="password" 
                               class="form-input @error('password') is-invalid @enderror" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="remember-group">
                    <label class="checkbox-container">
                        <input class="checkbox-input" 
                               type="checkbox" 
                               name="remember" 
                               id="remember" 
                               {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox-label">Remember me</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button" id="submitButton">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>or</span>
            </div>

            <!-- Register Link -->
            <div class="register-link">
                <p>Don't have an account?</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i>
                        Create an account
                    </a>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const submitButton = document.getElementById('submitButton');
            const passwordToggle = document.querySelector('.password-toggle');
            const passwordInput = document.getElementById('password');
            
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
            
            // Form submission
            form.addEventListener('submit', function(e) {
                // Validate form
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                let isValid = true;
                let errorMessage = '';
                
                if (!email) {
                    isValid = false;
                    errorMessage = 'Email is required';
                } else if (!isValidEmail(email)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                } else if (!password) {
                    isValid = false;
                    errorMessage = 'Password is required';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    showToast(errorMessage, 'error');
                    return;
                }
                
                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';
                
                // Add ripple effect
                addRippleEffect(e);
            });
            
            // Toggle password visibility
            window.togglePassword = function() {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                const icon = passwordToggle.querySelector('i');
                icon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
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
                
                toastMessage.textContent = message;
                toast.className = `toast ${type}`;
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
            
            // Auto-focus on email field if no errors
            @if(!$errors->any())
                setTimeout(() => {
                    document.getElementById('email').focus();
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