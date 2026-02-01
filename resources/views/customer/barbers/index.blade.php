@extends('customer.sidebar')

@section('content')
<div class="barbers-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Our Expert Barbers</h1>
        <p>Meet our professional barbers ready to give you the perfect look</p>
    </div>

    <!-- Barbers Grid -->
    <div class="barbers-container">
        @forelse($barbers as $barber)
        <div class="barber-card" data-status="active">
            <!-- Profile Image -->
            <div class="barber-image">
                @if($barber->profile_image)
                    <img src="{{ asset($barber->profile_image) }}" alt="{{ $barber->name }}" class="barber-img">
                @else
                    <div class="barber-placeholder">
                        <i class="fas fa-user-tie"></i>
                    </div>
                @endif
                
                <!-- Active Badge -->
                <div class="barber-badge">
                    <span class="badge-active">
                        <i class="fas fa-check-circle"></i> Active
                    </span>
                </div>
            </div>

            <!-- Barber Info -->
            <div class="barber-header">
                <h3>{{ $barber->name }}</h3>
                @if($barber->position)
                <p class="barber-position">{{ $barber->position }}</p>
                @endif
            </div>

            <!-- Contact Details -->
            <div class="barber-body">
                <div class="contact-items">
                    @if($barber->email)
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $barber->email }}</span>
                    </div>
                    @endif
                    
                    @if($barber->phone)
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>{{ $barber->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer with Button -->
            <div class="barber-footer">
                <a href="{{ route('customer.barbers.show', $barber->id) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i> View Profile
                </a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>No Barbers Available</h3>
            <p>Check back soon for our barber team</p>
        </div>
        @endforelse
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

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 0 1rem;
    }

    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .page-header p {
        font-size: 1.125rem;
        color: var(--secondary);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Barbers Container */
    .barbers-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    /* Barber Card */
    .barber-card {
        background: white;
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--medium-gray);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .barber-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }

    /* Barber Image */
    .barber-image {
        position: relative;
        height: 280px;
        background: linear-gradient(135deg, var(--primary) 0%, #2d3748 100%);
        overflow: hidden;
    }

    .barber-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .barber-card:hover .barber-img {
        transform: scale(1.05);
    }

    .barber-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        color: rgba(255, 255, 255, 0.15);
    }

    /* Badge */
    .barber-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .badge-active {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(72, 187, 120, 0.2);
        color: var(--success);
        border: 1px solid rgba(72, 187, 120, 0.3);
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Barber Header */
    .barber-header {
        padding: 1.5rem 1.5rem 0.75rem;
    }

    .barber-header h3 {
        font-size: 1.375rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .barber-position {
        color: var(--accent);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Barber Body */
    .barber-body {
        padding: 1rem 1.5rem;
        flex: 1;
    }

    .contact-items {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--secondary);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .contact-item i {
        color: var(--accent);
        width: 18px;
        text-align: center;
    }

    /* Barber Footer */
    .barber-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        gap: 0.75rem;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
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

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        color: var(--secondary);
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
        color: var(--accent);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: var(--primary);
    }

    .empty-state p {
        font-size: 1.125rem;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.875rem;
        }

        .page-header p {
            font-size: 1rem;
        }

        .barbers-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .barber-image {
            height: 240px;
        }
    }
</style>
@endsection
