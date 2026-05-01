@extends('admin.sidebar')

@section('content')
<div class="report-detail-page">
    <div class="report-detail-header">
        <div>
            <a href="{{ route('admin.dashboard') }}#detail-report" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.show', $reportKey) }}" class="filter-panel">
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control">
        </div>
        <button type="submit" class="filter-btn">
            <i class="fas fa-filter"></i> Apply
        </button>
    </form>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if(count($summary) > 0)
        <div class="summary-grid">
            @foreach($summary as $label => $value)
                <div class="summary-card">
                    <span>{{ $label }}</span>
                    <strong>{{ $value }}</strong>
                </div>
            @endforeach
        </div>
    @endif

    <section class="detail-card">
        <div class="detail-card-header">
            <div>
                <h2>{{ $title }}</h2>
                <p>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>
        </div>

        <div class="table-scroll">
            <table class="detail-table">
                <thead>
                    <tr>
                        @foreach($columns as $column)
                            <th class="{{ ($column['align'] ?? '') === 'right' ? 'cell-right' : '' }}">
                                {{ $column['label'] }}
                            </th>
                        @endforeach
                        <th class="cell-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            @foreach($columns as $column)
                                <td class="{{ ($column['align'] ?? '') === 'right' ? 'cell-right' : '' }}">
                                    {{ $row[$column['key']] ?? '-' }}
                                </td>
                            @endforeach
                            <td class="cell-right">
                                @if(!empty($row['action_url']))
                                    <a href="{{ $row['action_url'] }}" class="row-link">
                                        {{ $row['action_label'] ?? 'View' }}
                                    </a>
                                @else
                                    <span class="muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="empty-cell">
                                No report data for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<style>
    .report-detail-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
    }

    .report-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
    }

    .report-detail-header h1 {
        margin: 10px 0 6px;
        color: var(--primary);
        font-size: 30px;
    }

    .report-detail-header p,
    .detail-card-header p {
        margin: 0;
        color: var(--dark-gray);
    }

    .back-link,
    .row-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent);
        font-weight: 800;
        text-decoration: none;
    }

    .filter-panel {
        display: grid;
        grid-template-columns: repeat(2, minmax(170px, 220px)) auto;
        align-items: end;
        gap: 14px;
        margin-bottom: 20px;
        background: #fff;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        padding: 18px;
        box-shadow: var(--card-shadow);
    }

    .form-group {
        display: grid;
        gap: 6px;
    }

    .form-group label {
        color: var(--dark-gray);
        font-size: 13px;
        font-weight: 800;
    }

    .form-control {
        width: 100%;
        min-height: 42px;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        padding: 9px 12px;
        font: inherit;
        color: var(--primary);
    }

    .filter-btn {
        min-height: 42px;
        border: none;
        border-radius: 8px;
        padding: 9px 16px;
        background: var(--accent);
        color: var(--primary);
        font: inherit;
        font-weight: 900;
        cursor: pointer;
    }

    .alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 8px;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .summary-card,
    .detail-card {
        background: #fff;
        border: 1px solid var(--medium-gray);
        border-radius: 8px;
        box-shadow: var(--card-shadow);
    }

    .summary-card {
        padding: 18px;
        display: grid;
        gap: 6px;
    }

    .summary-card span {
        color: var(--dark-gray);
        font-size: 13px;
        font-weight: 800;
    }

    .summary-card strong {
        color: var(--primary);
        font-size: 24px;
    }

    .detail-card {
        overflow: hidden;
    }

    .detail-card-header {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid var(--medium-gray);
    }

    .detail-card-header h2 {
        margin: 0 0 6px;
        color: var(--primary);
        font-size: 20px;
    }

    .table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .detail-table {
        width: 100%;
        min-width: 760px;
        border-collapse: collapse;
    }

    .detail-table th,
    .detail-table td {
        padding: 13px 16px;
        border-bottom: 1px solid var(--medium-gray);
        text-align: left;
        vertical-align: top;
    }

    .detail-table th {
        color: var(--dark-gray);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0;
    }

    .detail-table .cell-right {
        text-align: right;
    }

    .empty-cell {
        color: var(--dark-gray);
        text-align: center !important;
        padding: 28px !important;
    }

    .muted {
        color: var(--dark-gray);
    }

    @media (max-width: 760px) {
        .report-detail-page {
            padding: 18px;
        }

        .report-detail-header h1 {
            font-size: 24px;
        }

        .filter-panel {
            grid-template-columns: 1fr;
        }

        .detail-card-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endsection
