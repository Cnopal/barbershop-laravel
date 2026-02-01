@extends('staff.sidebar')

@section('page-title', 'Services')

@section('content')
<style>
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .service-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-top: 4px solid var(--accent);
        transition: all 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .service-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .service-description {
        font-size: 13px;
        color: var(--secondary);
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .service-details {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--medium-gray);
    }

    .service-price {
        font-size: 24px;
        font-weight: 700;
        color: var(--accent);
    }

    .service-duration {
        font-size: 13px;
        color: var(--secondary);
        text-align: right;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--secondary);
    }

    .empty-state i {
        font-size: 64px;
        color: var(--medium-gray);
        margin-bottom: 20px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 10px 15px;
        border: 1px solid var(--medium-gray);
        border-radius: 6px;
        text-decoration: none;
        color: var(--primary);
    }

    .pagination a:hover {
        background: var(--light-gray);
    }

    .pagination .active span {
        background: var(--accent);
        color: var(--primary);
        border-color: var(--accent);
    }
</style>

<h1 style="margin: 0 0 30px 0; font-size: 28px;">Services Offered</h1>

@if($services->count() > 0)
    <div class="services-grid">
        @foreach($services as $service)
            <div class="service-card">
                <div class="service-name">{{ $service->name }}</div>
                @if($service->description)
                    <div class="service-description">{{ $service->description }}</div>
                @endif
                <div class="service-details">
                    <div class="service-price">RM {{ number_format($service->price, 2) }}</div>
                    <div class="service-duration">{{ $service->duration }} min</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $services->links() }}
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>No Services Available</h3>
        <p>There are no active services at the moment.</p>
    </div>
@endif
@endsection