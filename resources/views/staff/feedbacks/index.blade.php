@extends('staff.sidebar')

@section('page-title', 'Customer Feedbacks')

@section('content')
<style>
    .feedback-header {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 768px) {
        .feedback-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
    }

    .rating-summary {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .rating-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--accent) 0%, #e6c158 100%);
        color: var(--primary);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .rating-number {
        font-size: 36px;
    }

    .rating-label {
        font-size: 12px;
    }

    .stars {
        color: #fbbf24;
        font-size: 20px;
    }

    .feedback-stats {
        display: flex;
        gap: 30px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--primary);
    }

    .stat-label {
        font-size: 13px;
        color: var(--secondary);
        margin-top: 5px;
    }

    .feedback-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent);
    }

    .feedback-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .feedback-customer {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 5px;
    }

    .feedback-date {
        font-size: 13px;
        color: var(--secondary);
    }

    .feedback-rating {
        font-size: 14px;
        color: #fbbf24;
    }

    .feedback-comments {
        font-size: 15px;
        color: var(--primary);
        line-height: 1.6;
        padding: 15px;
        background: var(--light-gray);
        border-radius: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--secondary);
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

<h1 style="margin: 0 0 30px 0; font-size: 28px;">Customer Feedbacks</h1>

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
                <div style="margin-top: 5px; color: var(--secondary);">Average Rating</div>
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

    <div class="pagination">
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