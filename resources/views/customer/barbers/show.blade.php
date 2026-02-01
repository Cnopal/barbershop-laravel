@extends('customer.sidebar')

@section('content')
<div class="barber-detail-page">
    <!-- Back Button -->
    <div class="back-link">
        <a href="{{ route('customer.barbers.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Barbers
        </a>
    </div>

    <!-- Main Content -->
    <div class="detail-container">
        <!-- Left: Profile Section -->
        <div class="profile-section">
            <div class="profile-image-container">
                @if($barber->profile_image)
                    <img src="{{ asset($barber->profile_image) }}" alt="{{ $barber->name }}" class="profile-image">
                @else
                    <div class="profile-placeholder">
                        <i class="fas fa-user-tie"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Details Section -->
        <div class="details-section">
            <!-- Status Badge -->
            <div class="status-badge">
                <span class="badge-active">
                    <i class="fas fa-check-circle"></i> Active & Available
                </span>
            </div>

            <!-- Name & Title -->
            <h1 class="barber-name">{{ $barber->name }}</h1>
            @if($barber->position)
                <p class="barber-title">{{ $barber->position }}</p>
            @endif

            <!-- Stats -->
            <div class="stats-section">
                <div class="stat-item">
                    <div class="stat-number">{{ $appointmentCount }}</div>
                    <div class="stat-label">Total Appointments</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $completedAppointments }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="contact-section">
                <h3>Contact Information</h3>
                <div class="contact-list">
                    @if($barber->email)
                        <div class="contact-info">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $barber->email }}</span>
                        </div>
                    @endif
                    
                    @if($barber->phone)
                        <div class="contact-info">
                            <i class="fas fa-phone"></i>
                            <span>{{ $barber->phone }}</span>
                        </div>
                    @endif

                    @if($barber->address)
                        <div class="contact-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $barber->address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('customer.appointments.create') }}?barber_id={{ $barber->id }}" 
                   class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
                <button class="btn btn-secondary" onclick="shareBarber()">
                    <i class="fas fa-share-alt"></i> Share
                </button>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="info-section">
        <div class="info-card">
            <div class="info-header">
                <i class="fas fa-scissors"></i>
                <h3>Specialties</h3>
            </div>
            <ul class="info-list">
                <li>Professional Haircuts</li>
                <li>Beard Grooming & Shaping</li>
                <li>Luxury Shaving</li>
                <li>Hair Styling</li>
                <li>Color & Treatment</li>
            </ul>
        </div>

        <div class="info-card">
            <div class="info-header">
                <i class="fas fa-star"></i>
                <h3>Why Choose {{ $barber->name }}</h3>
            </div>
            <ul class="info-list">
                <li>Expert with years of experience</li>
                <li>Uses premium products</li>
                <li>Customer-focused approach</li>
                <li>Quick & professional service</li>
                <li>Attention to detail</li>
            </ul>
        </div>

        <div class="info-card">
            <div class="info-header">
                <i class="fas fa-clock"></i>
                <h3>Booking Information</h3>
            </div>
            <div class="info-details">
                <div class="info-item">
                    <span class="label">Available Days:</span>
                    <span class="value">Monday - Saturday</span>
                </div>
                <div class="info-item">
                    <span class="label">Service Duration:</span>
                    <span class="value">30-60 minutes</span>
                </div>
                <div class="info-item">
                    <span class="label">Cancellation:</span>
                    <span class="value">Free up to 24 hours</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Variables */
    :root {
        --primary: #1a1f36;
        --secondary: #4a5568;
        --accent: #d4af37;
        --light: #f8f9fa;
        --dark: #121826;
        --light-gray: #f1f5f9;
        --medium-gray: #e2e8f0;
        --success: #48bb78;
        --warning: #ed8936;
        --danger: #f56565;
        --info: #4299e1;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .barber-detail-page {
        padding: 2rem 1rem;
    }

    /* Back Button */
    .back-link {
        margin-bottom: 2rem;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--accent);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
    }

    .btn-back:hover {
        color: #c19a2f;
        transform: translateX(-4px);
    }

    /* Detail Container */
    .detail-container {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--medium-gray);
        margin-bottom: 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }

    /* Profile Section */
    .profile-section {
        background: linear-gradient(135deg, var(--primary) 0%, #2d3748 100%);
    }

    .profile-image-container {
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8rem;
        color: rgba(255, 255, 255, 0.15);
    }

    /* Details Section */
    .details-section {
        padding: 3rem;
    }

    .status-badge {
        margin-bottom: 1.5rem;
    }

    .badge-active {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(72, 187, 120, 0.15);
        color: var(--success);
        border: 1px solid rgba(72, 187, 120, 0.3);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .barber-name {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .barber-title {
        font-size: 1.125rem;
        color: var(--accent);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    /* Stats Section */
    .stats-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--accent);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Contact Section */
    .contact-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--medium-gray);
    }

    .contact-section h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .contact-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .contact-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: var(--secondary);
        font-size: 0.9375rem;
    }

    .contact-info i {
        color: var(--accent);
        width: 20px;
        text-align: center;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: var(--radius);
        transition: var(--transition);
        border: none;
        cursor: pointer;
        font-size: 0.9375rem;
        font-family: inherit;
        flex: 1;
    }

    .btn-primary {
        background: var(--accent);
        color: var(--primary);
    }

    .btn-primary:hover {
        background: #c19a2f;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .btn-secondary {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
        background: var(--primary);
        color: white;
    }

    /* Info Section */
    .info-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .info-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        border: 1px solid var(--medium-gray);
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-gray);
    }

    .info-header i {
        font-size: 1.5rem;
        color: var(--accent);
    }

    .info-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
    }

    .info-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--secondary);
        font-size: 0.9375rem;
    }

    .info-list li:before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background: var(--accent);
        border-radius: 50%;
    }

    .info-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: var(--light-gray);
        border-radius: 8px;
    }

    .info-item .label {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.875rem;
    }

    .info-item .value {
        color: var(--accent);
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .detail-container {
            grid-template-columns: 1fr;
        }

        .profile-image-container {
            height: 300px;
        }

        .details-section {
            padding: 2rem;
        }
    }

    @media (max-width: 768px) {
        .barber-detail-page {
            padding: 1rem 0.5rem;
        }

        .barber-name {
            font-size: 1.875rem;
        }

        .details-section {
            padding: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .info-section {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .stats-section {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function shareBarber() {
        if (navigator.share) {
            navigator.share({
                title: 'Check out {{ $barber->name }}',
                text: 'Professional barber at Men\'s Club',
                url: window.location.href
            });
        } else {
            alert('Copy this link to share: ' + window.location.href);
        }
    }
</script>
@endsection
