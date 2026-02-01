@extends('customer.sidebar')


@section('content')
<div class="services-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1>Our Services</h1>
        <p>Discover our premium grooming services designed to enhance your style and confidence</p>
    </div>

    <!-- Services Grid -->
    <div class="services-container">
        @forelse($services as $service)
        <div class="service-card" data-status="{{ $service->status }}">
            <div class="service-header">
                <div class="service-icon">
                    @php
                        $icon = 'fas fa-cut';
                        $nameLower = strtolower($service->name);
                        if (str_contains($nameLower, 'shave') || str_contains($nameLower, 'beard')) {
                            $icon = 'fas fa-razor';
                        } elseif (str_contains($nameLower, 'color') || str_contains($nameLower, 'dye')) {
                            $icon = 'fas fa-paint-brush';
                        } elseif (str_contains($nameLower, 'wash') || str_contains($nameLower, 'shampoo')) {
                            $icon = 'fas fa-shower';
                        } elseif (str_contains($nameLower, 'style') || str_contains($nameLower, 'styling')) {
                            $icon = 'fas fa-spray-can';
                        } elseif (str_contains($nameLower, 'trim')) {
                            $icon = 'fas fa-scissors';
                        } elseif (str_contains($nameLower, 'massage') || str_contains($nameLower, 'treatment')) {
                            $icon = 'fas fa-spa';
                        } elseif (str_contains($nameLower, 'facial')) {
                            $icon = 'fas fa-user-md';
                        }
                    @endphp
                    <i class="{{ $icon }}"></i>
                </div>
                
                <div class="service-info">
                    <h3>{{ $service->name }}</h3>
                    <span class="service-badge {{ $service->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                        {{ $service->status === 'active' ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
            </div>
            
            <div class="service-body">
                @if($service->description)
                <p class="service-description">{{ $service->description }}</p>
                @endif
                
                <div class="service-details">
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Duration
                        </span>
                        <span class="detail-value">
                            @php
                                $duration = $service->duration;
                                if ($duration >= 60) {
                                    $hours = floor($duration / 60);
                                    $minutes = $duration % 60;
                                    echo $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '');
                                } else {
                                    echo $duration . ' minutes';
                                }
                            @endphp
                        </span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">
                            <i class="fas fa-tag"></i> Price
                        </span>
                        <span class="detail-value price">RM{{ number_format($service->price, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <div class="service-footer">
                @if($service->status === 'active')
                <a href="{{ route('customer.appointments.create', ['service' => $service->id]) }}" 
                   class="btn btn-primary book-btn">
                    <i class="fas fa-calendar-plus"></i> Book Now
                </a>
                @else
                <button class="btn btn-secondary" disabled>
                    <i class="fas fa-ban"></i> Currently Unavailable
                </button>
                @endif
                
                <button class="btn btn-outline detail-btn" data-service-id="{{ $service->id }}">
                    <i class="fas fa-info-circle"></i> Details
                </button>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-cut"></i>
            </div>
            <h3>No Services Available</h3>
            <p>Check back soon for our service offerings</p>
        </div>
        @endforelse
    </div>

    <!-- Service Details Modal -->
    <div class="modal" id="serviceModal">
        <div class="modal-content">
            <button class="modal-close" id="modalClose">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="modal-body">
                <div id="serviceDetails">
                    <!-- Dynamic content will be loaded here -->
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

    /* Services Container */
    .services-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    /* Service Card */
    .service-card {
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

    .service-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--accent);
    }

    .service-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, #2d3748 100%);
        color: white;
        position: relative;
    }

    .service-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        color: var(--accent);
        font-size: 1.75rem;
    }

    .service-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .service-info h3 {
        font-size: 1.375rem;
        font-weight: 600;
        color: white;
        line-height: 1.3;
        flex: 1;
    }

    .service-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-active {
        background: rgba(72, 187, 120, 0.2);
        color: var(--success);
        border: 1px solid rgba(72, 187, 120, 0.3);
    }

    .badge-inactive {
        background: rgba(245, 101, 101, 0.2);
        color: var(--danger);
        border: 1px solid rgba(245, 101, 101, 0.3);
    }

    /* Service Body */
    .service-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .service-description {
        color: var(--secondary);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        font-size: 0.9375rem;
    }

    .service-details {
        margin-top: auto;
        background: var(--light-gray);
        border-radius: var(--radius);
        padding: 1.25rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--secondary);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .detail-label i {
        color: var(--accent);
        font-size: 0.875rem;
    }

    .detail-value {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.9375rem;
    }

    .detail-value.price {
        color: var(--accent);
        font-size: 1.25rem;
    }

    /* Service Footer */
    .service-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        gap: 0.75rem;
    }

    /* Button Styles */
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

    .btn-primary:hover:not(:disabled) {
        background: #c19a2f;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .btn-secondary {
        background: var(--light-gray);
        color: var(--secondary);
        border: 1px solid var(--medium-gray);
    }

    .btn-secondary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-outline {
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .btn-outline:hover {
        background: var(--primary);
        color: white;
    }

    /* Book Button */
    .book-btn {
        flex: 2;
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

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        animation: fadeIn 0.3s ease;
    }

    .modal.active {
        display: flex;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        border-radius: var(--radius);
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
        animation: slideIn 0.3s ease;
        position: relative;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--light-gray);
        border: none;
        color: var(--secondary);
        font-size: 1.125rem;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .modal-close:hover {
        background: var(--medium-gray);
        color: var(--primary);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 2rem;
    }

    /* Service Details in Modal */
    #serviceDetails .service-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
    }

    #serviceDetails h3 {
        text-align: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
        color: var(--primary);
    }

    #serviceDetails .status-badge {
        display: inline-block;
        margin: 0 auto 1.5rem;
    }

    #serviceDetails .service-description {
        font-size: 1rem;
        line-height: 1.7;
        color: var(--secondary);
        margin-bottom: 2rem;
    }

    #serviceDetails .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    #serviceDetails .detail-card {
        background: var(--light-gray);
        border-radius: var(--radius);
        padding: 1.25rem;
        text-align: center;
    }

    #serviceDetails .detail-label {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    #serviceDetails .detail-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary);
    }

    #serviceDetails .detail-value.price {
        color: var(--accent);
    }

    #serviceDetails .modal-footer {
        display: flex;
        gap: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }

        .page-header p {
            font-size: 1rem;
        }

        .services-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .service-footer {
            flex-direction: column;
        }

        .book-btn {
            width: 100%;
        }

        #serviceDetails .details-grid {
            grid-template-columns: 1fr;
        }

        #serviceDetails .modal-footer {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            margin-bottom: 2rem;
        }

        .service-header {
            padding: 1.25rem;
        }

        .service-body {
            padding: 1.25rem;
        }

        .service-footer {
            padding: 1.25rem;
        }

        .modal-body {
            padding: 1.5rem;
        }
    }

    /* Loading Animation */
    .service-card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stagger Animation Delay */
    .service-card:nth-child(1) { animation-delay: 0.1s; }
    .service-card:nth-child(2) { animation-delay: 0.2s; }
    .service-card:nth-child(3) { animation-delay: 0.3s; }
    .service-card:nth-child(4) { animation-delay: 0.4s; }
    .service-card:nth-child(5) { animation-delay: 0.5s; }
    .service-card:nth-child(6) { animation-delay: 0.6s; }
    .service-card:nth-child(7) { animation-delay: 0.7s; }
    .service-card:nth-child(8) { animation-delay: 0.8s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Service details modal
    const serviceModal = document.getElementById('serviceModal');
    const modalClose = document.getElementById('modalClose');
    const serviceDetails = document.getElementById('serviceDetails');
    
    // Mock service data (in real app, this would come from backend)
    const servicesData = {
        @foreach($services as $service)
        {{ $service->id }}: {
            id: {{ $service->id }},
            name: "{{ $service->name }}",
            description: "{{ $service->description }}",
            price: "{{ number_format($service->price, 2) }}",
            duration: {{ $service->duration }},
            status: "{{ $service->status }}",
            durationText: function() {
                const duration = this.duration;
                if (duration >= 60) {
                    const hours = Math.floor(duration / 60);
                    const minutes = duration % 60;
                    return hours + 'h' + (minutes > 0 ? ' ' + minutes + 'm' : '');
                }
                return duration + ' minutes';
            },
            statusText: function() {
                return this.status === 'active' ? 'Available' : 'Unavailable';
            },
            statusClass: function() {
                return this.status === 'active' ? 'badge-active' : 'badge-inactive';
            },
            iconClass: function() {
                const nameLower = this.name.toLowerCase();
                if (nameLower.includes('shave') || nameLower.includes('beard')) {
                    return 'fas fa-razor';
                } else if (nameLower.includes('color') || nameLower.includes('dye')) {
                    return 'fas fa-paint-brush';
                } else if (nameLower.includes('wash') || nameLower.includes('shampoo')) {
                    return 'fas fa-shower';
                } else if (nameLower.includes('style') || nameLower.includes('styling')) {
                    return 'fas fa-spray-can';
                } else if (nameLower.includes('trim')) {
                    return 'fas fa-scissors';
                } else if (nameLower.includes('massage') || nameLower.includes('treatment')) {
                    return 'fas fa-spa';
                } else if (nameLower.includes('facial')) {
                    return 'fas fa-user-md';
                }
                return 'fas fa-cut';
            }
        },
        @endforeach
    };
    
    // Detail button click handler
    document.querySelectorAll('.detail-btn').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-service-id');
            const service = servicesData[serviceId];
            
            if (service) {
                // Populate modal with service details
                serviceDetails.innerHTML = `
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div class="service-icon" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto 1.5rem; background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%); color: var(--primary);">
                            <i class="${service.iconClass()}"></i>
                        </div>
                        <h3>${service.name}</h3>
                        <span class="service-badge ${service.statusClass()}" style="display: inline-block; margin-bottom: 1.5rem;">
                            ${service.statusText()}
                        </span>
                    </div>
                    
                    <div class="service-description">
                        ${service.description || 'No description available.'}
                    </div>
                    
                    <div class="details-grid">
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="fas fa-clock"></i> Duration
                            </div>
                            <div class="detail-value">${service.durationText()}</div>
                        </div>
                        
                        <div class="detail-card">
                            <div class="detail-label">
                                <i class="fas fa-tag"></i> Price
                            </div>
                            <div class="detail-value price">RM${service.price}</div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        ${service.status === 'active' ? `
                        <a href="/customer/appointments/create?service=${service.id}" class="btn btn-primary" style="flex: 2;">
                            <i class="fas fa-calendar-plus"></i> Book This Service
                        </a>
                        ` : `
                        <button class="btn btn-secondary" disabled style="flex: 2;">
                            <i class="fas fa-ban"></i> Currently Unavailable
                        </button>
                        `}
                        <button class="btn btn-outline" id="closeModalBtn">
                            <i class="fas fa-times"></i> Close
                        </button>
                    </div>
                `;
                
                // Open modal
                serviceModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Add close button event listener
                document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
            }
        });
    });
    
    // Close modal function
    function closeModal() {
        serviceModal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close modal handlers
    modalClose.addEventListener('click', closeModal);
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === serviceModal) {
            closeModal();
        }
    });
    
    // Keyboard support for modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && serviceModal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Service card hover effects
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Filter by status (optional feature)
    const filterButtons = document.createElement('div');
    filterButtons.className = 'filter-buttons';
    filterButtons.style.cssText = `
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    `;
    
    filterButtons.innerHTML = `
        <button class="btn btn-outline active" data-filter="all">
            All Services
        </button>
        <button class="btn btn-outline" data-filter="active">
            Available
        </button>
        <button class="btn btn-outline" data-filter="inactive">
            Unavailable
        </button>
    `;
    
    // Insert filter buttons after page header
    const pageHeader = document.querySelector('.page-header');
    pageHeader.insertAdjacentElement('afterend', filterButtons);
    
    // Filter functionality
    document.querySelectorAll('[data-filter]').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter service cards
            document.querySelectorAll('.service-card').forEach(card => {
                const status = card.getAttribute('data-status');
                
                if (filter === 'all' || 
                    (filter === 'active' && status === 'active') || 
                    (filter === 'inactive' && status === 'inactive')) {
                    card.style.display = 'flex';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
</script>
@endsection
