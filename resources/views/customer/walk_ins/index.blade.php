@extends('customer.sidebar')

@section('title', 'Walk-in Queue')

@section('content')
<div class="customer-page customer-queue-page">
    <div class="page-header">
        <div>
            <h1>My Walk-in Queue</h1>
            <p>Track your walk-in turn when staff links the queue to your account.</p>
        </div>
        <a href="{{ route('walk-ins.display') }}" class="btn btn-outline" target="_blank">
            <i class="fas fa-tv"></i> Queue Screen
        </a>
    </div>

    @if($activeQueue)
        <section class="active-queue-card status-{{ $activeQueue->status }}">
            <div class="queue-code">
                <span>Your Number</span>
                <strong>{{ $activeQueue->queue_code }}</strong>
            </div>
            <div class="queue-info">
                <h2>{{ $activeQueue->status === \App\Models\WalkInQueue::STATUS_SERVING ? 'You are being served' : 'Please wait for your turn' }}</h2>
                <div class="info-grid">
                    <div>
                        <span>Status</span>
                        <strong>{{ $activeQueue->status_label }}</strong>
                    </div>
                    <div>
                        <span>Estimated Wait</span>
                        <strong>{{ $activeQueue->formatted_wait }}</strong>
                    </div>
                    <div>
                        <span>Barber</span>
                        <strong>{{ $activeQueue->barber->name ?? 'Any barber' }}</strong>
                    </div>
                    <div>
                        <span>Service</span>
                        <strong>{{ $activeQueue->service->name ?? 'Walk-in service' }}</strong>
                    </div>
                    <div>
                        <span>Price</span>
                        <strong>RM{{ number_format($activeQueue->price, 2) }}</strong>
                    </div>
                    @if($activeQueue->recipient_age !== null)
                        <div>
                            <span>Age</span>
                            <strong>{{ $activeQueue->recipient_age }} years old{{ $activeQueue->hasChildRate() ? ' - child rate' : '' }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @else
        <section class="empty-queue">
            <i class="fas fa-list-ol"></i>
            <h2>No active walk-in queue</h2>
            <p>If you already checked in at the counter, ask staff to link the queue to your registered account.</p>
        </section>
    @endif

    <section class="history-panel">
        <div class="section-header">
            <h2>Recent Queue History</h2>
        </div>
        <div class="history-list">
            @forelse($history as $queue)
                <article class="history-row">
                    <div>
                        <strong>{{ $queue->queue_code }}</strong>
                        <span>{{ $queue->queue_date->format('d M Y') }}</span>
                    </div>
                    <div>{{ $queue->service->name ?? 'Walk-in service' }} · RM{{ number_format($queue->price, 2) }}</div>
                    <div>{{ $queue->barber->name ?? 'Any barber' }}</div>
                    <span class="status-pill status-{{ $queue->status }}">{{ $queue->status_label }}</span>
                </article>
            @empty
                <p class="muted">No walk-in queue history yet.</p>
            @endforelse
        </div>
    </section>
</div>

<style>
    .customer-queue-page .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
    }

    .customer-queue-page h1 {
        font-size: 32px;
        margin: 0 0 8px;
    }

    .customer-queue-page p {
        color: #718096;
        margin: 0;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 800;
        text-decoration: none;
    }

    .btn-outline {
        color: #1a1f36;
        border: 1px solid #1a1f36;
        background: #fff;
    }

    .active-queue-card,
    .empty-queue,
    .history-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.08);
    }

    .active-queue-card {
        display: grid;
        grid-template-columns: minmax(220px, 300px) 1fr;
        gap: 24px;
        padding: 28px;
        margin-bottom: 24px;
        border-left: 6px solid #d4af37;
    }

    .active-queue-card.status-serving {
        border-left-color: #4299e1;
    }

    .queue-code {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 170px;
        padding: 24px;
        border-radius: 8px;
        background: #1a1f36;
        color: #fff;
    }

    .queue-code span {
        color: rgba(255, 255, 255, 0.72);
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .queue-code strong {
        color: #d4af37;
        font-size: 44px;
    }

    .queue-info h2 {
        margin: 0 0 20px;
        font-size: 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .info-grid div {
        padding: 16px;
        border-radius: 8px;
        background: #f8fafc;
    }

    .info-grid span,
    .history-row span,
    .muted {
        color: #718096;
    }

    .info-grid strong {
        display: block;
        margin-top: 5px;
        color: #1a1f36;
    }

    .empty-queue {
        text-align: center;
        padding: 54px 24px;
        margin-bottom: 24px;
    }

    .empty-queue i {
        color: #d4af37;
        font-size: 48px;
        margin-bottom: 18px;
    }

    .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-header h2 {
        margin: 0;
        font-size: 20px;
    }

    .history-list {
        padding: 16px;
        display: grid;
        gap: 10px;
    }

    .history-row {
        display: grid;
        grid-template-columns: 150px 1fr 1fr auto;
        align-items: center;
        gap: 14px;
        padding: 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .history-row strong,
    .history-row span {
        display: block;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .status-waiting {
        background: #fefcbf;
        color: #744210;
    }

    .status-serving {
        background: #bee3f8;
        color: #2c5282;
    }

    .status-completed {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-skipped {
        background: #fed7d7;
        color: #742a2a;
    }

    @media (max-width: 860px) {
        .active-queue-card,
        .history-row,
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
