<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Men's Club | Premium Barber Services</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,500,600,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->

    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0a0a0a;
            --secondary: #1a1a1a;
            --accent: #d4af37;
            --accent-light: #f8e5a0;
            --light: #fafafa;
            --dark: #0f0f0f;
            --gray: #2a2a2a;
            --light-gray: #f5f5f5;
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.1);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 20px 60px rgba(0, 0, 0, 0.15);
            --radius: 16px;
            --radius-lg: 24px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--primary);
            background-color: var(--light);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.1;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Navigation - Modern Glass Morphism */
        .navbar {
            position: fixed;
            top: 1rem;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 4rem);
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-lg);
            z-index: 1000;
            padding: 0.4rem 2rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
        }

        .navbar.scrolled {
            top: 0.5rem;
            width: calc(100% - 2rem);
            border-radius: 12px;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            transition: var(--transition);
            background: linear-gradient(135deg, transparent 0%, rgba(212, 175, 55, 0.05) 100%);
        }

        .logo:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, transparent 0%, rgba(212, 175, 55, 0.1) 100%);
        }

        .logo i {
            color: var(--accent);
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .nav-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            opacity: 0.8;
        }

        .nav-link:hover {
            opacity: 1;
            background: rgba(212, 175, 55, 0.1);
        }

        .nav-link.active {
            opacity: 1;
            background: rgba(212, 175, 55, 0.15);
            font-weight: 600;
        }

        /* AI Button with Animation */
        .btn-ai {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-ai::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-ai:hover::before {
            left: 100%;
        }

        .btn-ai:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-ai i {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        /* Login Modal */
        .login-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .login-modal.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background: white;
            padding: 3rem;
            border-radius: var(--radius-lg);
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 0.4s ease 0.1s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .modal-content p {
            color: var(--gray);
            margin-bottom: 2rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .modal-btn.primary {
            background: var(--accent);
            color: var(--primary);
        }

        .modal-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        .modal-btn.secondary {
            background: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }

        .modal-btn.secondary:hover {
            background: var(--primary);
            color: white;
        }

        /* Hero Section - Modern Gradient */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dde2e6 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60%;
            height: 100%;
            background: linear-gradient(45deg, var(--accent) 0%, #c19a2f 100%);
            clip-path: polygon(40% 0, 100% 0, 100% 100%, 20% 100%);
            opacity: 0.95;
        }

        .hero-content {
            max-width: 600px;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 4.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            line-height: 1.1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--gray) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            max-width: 500px;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Modern Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 2rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-family: inherit;
            position: relative;
            overflow: hidden;
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
            width: 200px;
            height: 100px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            color: var(--primary);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Sections */
        .section {
            padding: 8rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-title p {
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* Services Grid - Modern Cards */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .service-card {
            background: white;
            border-radius: var(--radius);
            padding: 2.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(212, 175, 55, 0.1);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #f7d794);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .service-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary);
            font-size: 1.75rem;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
        }

        .service-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .service-card p {
            color: var(--gray);
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .price {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: var(--accent);
            font-size: 1.5rem;
            font-family: 'Inter', sans-serif;
        }

        /* Barbers Grid */
        .barbers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .barber-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
        }

        .barber-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .barber-img {
            height: 320px;
            background: linear-gradient(45deg, var(--dark), var(--gray));
            position: relative;
            overflow: hidden;
        }

        .barber-img::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        }

        .barber-info {
            padding: 2rem;
        }

        .barber-info h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .barber-position {
            color: var(--accent);
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        /* Testimonials - Glass Morphism */
        .testimonials {
            background: linear-gradient(135deg, var(--dark) 0%, #0a0a0a 100%);
            position: relative;
            overflow: hidden;
        }

        .testimonials::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><path d="M30 0L60 30L30 60L0 30L30 0Z" fill="rgba(212, 175, 55, 0.03)" /></svg>');
        }

        .testimonials .section-title h2 {
            background: linear-gradient(135deg, white 0%, var(--accent-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .testimonials .section-title p {
            color: rgba(255, 255, 255, 0.7);
        }

        .testimonial-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: var(--radius);
            padding: 2.5rem;
            border: 1px solid var(--glass-border);
            transition: var(--transition);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 2rem;
            font-size: 1.1rem;
            line-height: 1.8;
            color: white;
            position: relative;
            padding-left: 1.5rem;
        }

        .testimonial-text::before {
            content: '"';
            position: absolute;
            left: 0;
            top: -10px;
            font-size: 3rem;
            color: var(--accent);
            font-family: 'Playfair Display', serif;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .author-info h4 {
            color: white;
            margin-bottom: 0.25rem;
        }

        .author-info p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        /* CTA Section */
        .cta-section {
            text-align: center;
            background: linear-gradient(135deg, var(--light-gray) 0%, #f0f0f0 100%);
            border-radius: var(--radius-lg);
            padding: 6rem 2rem;
            margin: 4rem auto;
            max-width: 800px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #f7d794);
        }

        .cta-section h2 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cta-section p {
            color: var(--gray);
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 6rem 0 3rem;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #f7d794);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .footer-logo i {
            color: var(--accent);
        }

        .footer-links h4 {
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            color: white;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a,
        .footer-links li {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--accent);
            padding-left: 5px;
        }

        .footer-links i {
            color: var(--accent);
            width: 20px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.9rem;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .mobile-menu-btn:hover {
            background: rgba(212, 175, 55, 0.1);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero h1 {
                font-size: 3.5rem;
            }

            .hero::before {
                width: 50%;
                clip-path: polygon(30% 0, 100% 0, 100% 100%, 10% 100%);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1.5rem;
            }

            .navbar {
                width: calc(100% - 2rem);
                top: 0.5rem;
                border-radius: 12px;
            }

            .mobile-menu-btn {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 80px;
                left: 1rem;
                right: 1rem;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 2rem;
                border-radius: var(--radius);
                box-shadow: var(--shadow-lg);
                transform: translateY(-20px);
                opacity: 0;
                visibility: hidden;
                transition: var(--transition);
                border: 1px solid rgba(212, 175, 55, 0.1);
            }

            .nav-links.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            .hero {
                text-align: center;
                padding-top: 120px;
            }

            .hero::before {
                display: none;
            }

            .hero-buttons {
                justify-content: center;
            }

            .section {
                padding: 4rem 0;
            }

            .section-title h2 {
                font-size: 2.5rem;
            }

            .cta-section {
                margin: 2rem 1rem;
                padding: 4rem 1.5rem;
            }

            .cta-section h2 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2.75rem;
            }

            .btn {
                padding: 0.875rem 1.5rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .service-card,
            .testimonial-card {
                padding: 2rem;
            }
        }

        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #c19a2f 0%, var(--accent) 100%);
        }
    </style>
</head>

<body>
    <!-- Login Modal -->
    <div class="login-modal" id="loginModal">
        <div class="modal-content">
            <h3><i class="fas fa-robot"></i> AI Stylist Access</h3>
            <p>Login to access our AI-powered hairstyle recommender.</p>
            <div class="modal-buttons">
                <a href="{{ route('login') }}" class="modal-btn primary">Login</a>
                <button class="modal-btn secondary" id="closeModal">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container nav-content">
            <a href="/" class="logo">
                <i class="fas fa-cut"></i>
                Men's Club
            </a>

            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>

            <div class="nav-links" id="navLinks">
                <a href="#home" class="nav-link active">Home</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="#barbers" class="nav-link">Barbers</a>
                <a href="#testimonials" class="nav-link">Testimonials</a>

                <!-- AI Button -->
                <button class="btn-ai" id="aiButton">
                    <i class="fas fa-robot"></i>
                    AI Stylist
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="nav-link">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Book</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content fade-in">
                <h1>Crafting Excellence in Every Strand</h1>
                <p>Where tradition meets innovation. Experience barbering elevated through precision cuts, premium
                    products, and personalized service that exceeds expectations.</p>
                <div class="hero-buttons">
                    @if (Route::has('login'))
                        @auth
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-calendar-alt"></i> Book
                            </a>
                            <a href="#" class="btn btn-secondary">
                                <i class="fas fa-robot"></i> Try AI Stylist
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-secondary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section" id="services">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Signature Services</h2>
                <p>Crafted to perfection, our services blend traditional techniques with modern innovation.</p>
            </div>

            <div class="services-grid">
                <div class="service-card fade-in">
                    <div class="service-icon">
                        <i class="fas fa-cut"></i>
                    </div>
                    <h3>Precision Haircut</h3>
                    <p>Masterfully crafted haircuts combining classic barbering techniques with contemporary styling for
                        a sharp, polished look.</p>
                    <div class="price">RM 45</div>
                </div>

                <div class="service-card fade-in">
                    <div class="service-icon">
                        <i class="fas fa-razor"></i>
                    </div>
                    <h3>Beard Sculpting</h3>
                    <p>Expert beard grooming, shaping, and conditioning using premium products for a refined, masculine
                        appearance.</p>
                    <div class="price">RM 35</div>
                </div>

                <div class="service-card fade-in">
                    <div class="service-icon">
                        <i class="fas fa-spray-can"></i>
                    </div>
                    <h3>Luxury Shave</h3>
                    <p>The ultimate grooming experience featuring hot towel preparation, premium shaving creams, and a
                        straight razor finish.</p>
                    <div class="price">RM 60</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Barbers Section -->
    <section class="section" id="barbers">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Master Barbers</h2>
                <p>Meet our artisans who transform hair into works of art with precision and passion.</p>
            </div>

            <div class="barbers-grid">
                <div class="barber-card fade-in">
                    <div class="barber-img"></div>
                    <div class="barber-info">
                        <h3>Alexander Chen</h3>
                        <div class="barber-position">Master Barber & Founder</div>
                        <p>15 years of expertise specializing in precision cutting and modern hair design.</p>
                    </div>
                </div>

                <div class="barber-card fade-in">
                    <div class="barber-img"></div>
                    <div class="barber-info">
                        <h3>Marcus Rodriguez</h3>
                        <div class="barber-position">Beard Artisan</div>
                        <p>Specialist in beard sculpting, grooming, and styling with an artistic approach.</p>
                    </div>
                </div>

                <div class="barber-card fade-in">
                    <div class="barber-img"></div>
                    <div class="barber-info">
                        <h3>David Kensington</h3>
                        <div class="barber-position">Senior Stylist</div>
                        <p>Contemporary styling expert focusing on personalized grooming solutions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section testimonials" id="testimonials">
        <div class="container">
            <div class="section-title fade-in">
                <h2>Client Excellence</h2>
                <p>Discover why discerning gentlemen choose Men's Club for their grooming needs.</p>
            </div>

            <div class="services-grid">
                <div class="testimonial-card fade-in">
                    <div class="testimonial-text">
                        The attention to detail and craftsmanship is unparalleled. Every visit feels like a premium
                        experience. The best barber service in the city.
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">AJ</div>
                        <div class="author-info">
                            <h4>Adam Johnson</h4>
                            <p>Executive Director, Tech Corp</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card fade-in">
                    <div class="testimonial-text">
                        Consistently exceptional service. The AI stylist feature helped me discover a new look that
                        perfectly suits my face shape and lifestyle.
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">MR</div>
                        <div class="author-info">
                            <h4>Michael Roberts</h4>
                            <p>Creative Director, Fashion House</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <div class="container">
        <div class="cta-section fade-in">
            <h2>Begin Your Transformation</h2>
            <p>Experience the future of grooming with our premium services and AI-powered style recommendations.</p>
            <div class="hero-buttons">
                @if (Route::has('login'))
                @auth
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-calendar-alt"></i> Book Appointment
                    </a>
                    <button class="btn btn-secondary" id="aiButton2">
                        <i class="fas fa-robot"></i> Try AI Stylist
                    </button>
                @else
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Join Men's Club
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <i class="fas fa-sign-in-alt"></i> Member Login
                </a>
                @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div>
                    <div class="footer-logo">
                        <i class="fas fa-cut"></i>
                        Men's Club
                    </div>
                    <p>Redefining men's grooming through innovation, craftsmanship, and personalized service.</p>
                </div>

                <div class="footer-links">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="#services"><i class="fas fa-cut"></i> Precision Haircuts</a></li>
                        <li><a href="#services"><i class="fas fa-razor"></i> Beard Grooming</a></li>
                        <li><a href="#services"><i class="fas fa-spray-can"></i> Luxury Shaves</a></li>
                        <li><a href="#services"><i class="fas fa-magic"></i> AI Stylist</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#home"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="#about"><i class="fas fa-info-circle"></i> About</a></li>
                        <li><a href="#contact"><i class="fas fa-envelope"></i> Contact</a></li>
                        <li><a href="#careers"><i class="fas fa-briefcase"></i> Careers</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Connect</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Kuala Lumpur City Centre</li>
                        <li><i class="fas fa-phone"></i> +60 12-345 6789</li>
                        <li><i class="fas fa-envelope"></i> concierge@mensclub.com</li>
                        <li><i class="fas fa-clock"></i> 9AM-9PM Daily</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 Men's Club, Develop by Naufal Hamdan. Crafting excellence since 2015. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Elements
            const navbar = document.getElementById('navbar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navLinks = document.getElementById('navLinks');
            const aiButton = document.getElementById('aiButton');
            const aiButton2 = document.getElementById('aiButton2');
            const loginModal = document.getElementById('loginModal');
            const closeModal = document.getElementById('closeModal');
            const authStatus = @json(auth()->check());

            // Navbar scroll effect
            let lastScroll = 0;
            window.addEventListener('scroll', function () {
                const currentScroll = window.pageYOffset;

                // Add/remove scrolled class for navbar
                if (currentScroll > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }

                lastScroll = currentScroll;
            });

            // Mobile Menu Toggle
            mobileMenuBtn.addEventListener('click', function () {
                navLinks.classList.toggle('active');
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');

                // Close other open menus
                loginModal.classList.remove('active');
            });

            // AI Button Click Handler
            function handleAIClick(e) {
                e.preventDefault();

                @if(auth()->check())
                    // If logged in, redirect to AI stylist page
                    window.location.href = '/ai-stylist';
                @else
                    // If not logged in, show login modal
                    loginModal.classList.add('active');
                    navLinks.classList.remove('active');
                    mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                @endif
            }

            // Attach click handlers to both AI buttons
            if (aiButton) {
                aiButton.addEventListener('click', handleAIClick);
            }

            if (aiButton2) {
                aiButton2.addEventListener('click', handleAIClick);
            }

            // Close modal
            if (closeModal) {
                closeModal.addEventListener('click', function () {
                    loginModal.classList.remove('active');
                });
            }

            // Close modal when clicking outside
            loginModal.addEventListener('click', function (e) {
                if (e.target === loginModal) {
                    loginModal.classList.remove('active');
                }
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    if (this.getAttribute('href') === '#') return;

                    e.preventDefault();
                    const targetId = this.getAttribute('href');

                    if (targetId === '#home') {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            const offset = 100;
                            const elementPosition = targetElement.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - offset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }
                    }

                    // Close mobile menu after clicking
                    navLinks.classList.remove('active');
                    mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                    mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                });
            });

            // Update active nav link on scroll
            const sections = document.querySelectorAll('section[id]');
            window.addEventListener('scroll', function () {
                let current = '';

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;

                    if (scrollY >= (sectionTop - 150)) {
                        current = section.getAttribute('id');
                    }
                });

                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });

            // Fade in animation on scroll
            const fadeElements = document.querySelectorAll('.fade-in');

            const fadeInOnScroll = function () {
                fadeElements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementVisible = 150;

                    if (elementTop < window.innerHeight - elementVisible) {
                        element.classList.add('visible');
                    }
                });
            };

            window.addEventListener('scroll', fadeInOnScroll);
            fadeInOnScroll(); // Initial check

            // Add click animation to buttons
            document.querySelectorAll('.btn, .btn-ai').forEach(button => {
                button.addEventListener('click', function (e) {
                    // Create ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.5);
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                    `;

                    this.appendChild(ripple);

                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Add CSS for ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Initialize with active section
            if (window.location.hash) {
                const targetElement = document.querySelector(window.location.hash);
                if (targetElement) {
                    setTimeout(() => {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
            }
        });
    </script>
</body>

</html>