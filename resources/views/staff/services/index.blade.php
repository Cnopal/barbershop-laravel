@extends('staff.sidebar')

@section('page-title', 'Services')

@section('content')
<style>
    :root {
        --primary: #1a1f36;
        --secondary: #4a5568;
        --accent: #d4af37;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    .page-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    .service-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border-top: 4px solid var(--accent);
        transition: var(--transition);
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .service-header {
        margin-bottom: 1rem;
    }

    .service-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .service-description {
        font-size: 0.875rem;
        color: var(--secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .service-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--medium-gray);
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary);
    }

    .service-price {
        color: var(--accent);
        font-size: 1.5rem;
    }

    .service-duration {
        color: var(--secondary);
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: var(--card-shadow);
    }

    .empty-state i {
        font-size: 3rem;
        color: var(--accent);
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--secondary);
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }

        .service-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-header">
    <h2>Services Offered</h2>
</div>

@if($services->count() > 0)
    <div class="services-grid">
        @foreach($services as $service)
            <div class="service-card">
                <div class="service-header">
                    <div class="service-name">{{ $service->name }}</div>
                    @if($service->description)
                        <div class="service-description">{{ $service->description }}</div>
                    @endif
                </div>
                <div class="service-details">
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-dollar-sign"></i> Price
                        </span>
                        <span class="detail-value service-price">RM {{ number_format($service->price, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">
                            <i class="fas fa-clock"></i> Duration
                        </span>
                        <span class="detail-value service-duration">{{ $service->duration }} min</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $services->links() }}
@else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>No Services Available</h3>
        <p>There are no active services at the moment.</p>
    </div>
@endif
@endsection