@extends('staff.sidebar')

@section('page-title', 'Customer Feedbacks')

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
    }

    .page-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
    }

    .feedback-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
    }

    .rating-summary {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .rating-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
        color: var(--primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
    }

    .rating-number {
        font-size: 2.25rem;
    }

    .rating-label {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stars {
        color: #fbbf24;
        font-size: 1.25rem;
        letter-spacing: 0.25rem;
    }

    .rating-text {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-top: 0.5rem;
    }

    .feedback-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--secondary);
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .feedback-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
        border-left: 4px solid var(--accent);
        transition: var(--transition);
    }

    .feedback-card:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .feedback-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .feedback-customer {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .feedback-date {
        font-size: 0.875rem;
        color: var(--secondary);
    }

    .feedback-rating {
        font-size: 1rem;
        color: #fbbf24;
        letter-spacing: 0.2rem;
    }

    .feedback-comments {
        font-size: 0.9375rem;
        color: var(--primary);
        line-height: 1.6;
        padding: 1rem;
        background: var(--light-gray);
        border-radius: 8px;
        border-left: 3px solid var(--accent);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--secondary);
        background: white;
        border-radius: 12px;
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

    @media (max-width: 768px) {
        .feedback-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .feedback-stats {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="page-header">
    <h2>Customer Feedbacks</h2>
</div>

@if($totalFeedbacks > 0)
    <div class="feedback-header">
        <div class="rating-summary">
            <div class="rating-circle">
                <div class="rating-number">{{ number_format($averageRating ?? 0, 1) }}</div>
                <div class="rating-label">/5</div>
            </div>
            <div>
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($averageRating ?? 0))
                            <i class="fas fa-star"></i>
                        @elseif ($i - 0.5 <= ($averageRating ?? 0))
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <div class="rating-text">Average Rating</div>
            </div>
        </div>

        <div class="feedback-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $totalFeedbacks }}</div>
                <div class="stat-label">Total Feedbacks</div>
            </div>
        </div>
    </div>

    @foreach($feedbacks as $feedback)
        <div class="feedback-card">
            <div class="feedback-header-content">
                <div>
                    <div class="feedback-customer">{{ $feedback->customer->name }}</div>
                    <div class="feedback-date">{{ $feedback->created_at->format('d F Y, h:i A') }}</div>
                </div>
                @if($feedback->rating)
                    <div class="feedback-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $feedback->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                @endif
            </div>

            @if($feedback->comments)
                <div class="feedback-comments">{{ $feedback->comments }}</div>
            @endif
        </div>
    @endforeach

    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $feedbacks->links() }}
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h3>No Feedbacks Yet</h3>
        <p>You haven't received any customer feedbacks yet.</p>
    </div>
@endif
@endsection