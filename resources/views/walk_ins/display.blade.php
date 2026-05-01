<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Men's Club Queue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a1f36;
            --accent: #d4af37;
            --muted: #718096;
            --line: rgba(255, 255, 255, 0.14);
            --panel: rgba(255, 255, 255, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: #fff;
            background: #111827;
            overflow-x: hidden;
        }

        .display-page {
            min-height: 100vh;
            padding: 34px;
            display: grid;
            grid-template-rows: auto 1fr auto;
            gap: 26px;
            background:
                linear-gradient(rgba(17, 24, 39, 0.9), rgba(17, 24, 39, 0.96)),
                url("https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=1800&q=80") center/cover;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand i {
            color: var(--accent);
            font-size: 34px;
        }

        .brand h1 {
            font-size: 34px;
            letter-spacing: 0;
        }

        .clock {
            text-align: right;
            color: rgba(255, 255, 255, 0.78);
            font-weight: 700;
        }

        .clock strong {
            display: block;
            color: var(--accent);
            font-size: 26px;
        }

        .queue-grid {
            display: grid;
            grid-template-columns: minmax(300px, 0.85fr) 1.15fr;
            gap: 26px;
            min-height: 0;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 8px;
            background: var(--panel);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 24px;
            border-bottom: 1px solid var(--line);
        }

        .panel-header h2 {
            font-size: 24px;
        }

        .serving-list,
        .waiting-list,
        .recent-list {
            display: grid;
            gap: 14px;
            padding: 22px;
        }

        .serving-card {
            padding: 24px;
            border-radius: 8px;
            background: rgba(212, 175, 55, 0.16);
            border: 1px solid rgba(212, 175, 55, 0.42);
        }

        .serving-card .number {
            color: var(--accent);
            font-size: 54px;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 14px;
        }

        .customer {
            font-size: 24px;
            font-weight: 800;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
            color: rgba(255, 255, 255, 0.76);
            font-size: 15px;
        }

        .waiting-row,
        .recent-row {
            display: grid;
            grid-template-columns: 120px 1fr 120px;
            gap: 16px;
            align-items: center;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.07);
        }

        .waiting-row .number,
        .recent-row .number {
            color: var(--accent);
            font-size: 28px;
            font-weight: 900;
        }

        .wait {
            justify-self: end;
            color: #fff;
            font-weight: 800;
        }

        .empty {
            padding: 42px 22px;
            text-align: center;
            color: rgba(255, 255, 255, 0.72);
        }

        .bottom-panel {
            display: grid;
            grid-template-columns: 1fr;
        }

        .recent-list {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .recent-row {
            grid-template-columns: 95px 1fr;
        }

        .recent-row .status {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        @media (max-width: 980px) {
            .queue-grid {
                grid-template-columns: 1fr;
            }

            .display-page {
                padding: 22px;
            }

            .waiting-row {
                grid-template-columns: 1fr;
            }

            .wait {
                justify-self: start;
            }
        }
    </style>
</head>
<body>
    <main class="display-page">
        <header class="topbar">
            <div class="brand">
                <i class="fas fa-cut"></i>
                <div>
                    <h1>Men's Club Queue</h1>
                    <p>Walk-in service board</p>
                </div>
            </div>
            <div class="clock">
                <strong id="timeNow">{{ now('Asia/Kuala_Lumpur')->format('h:i A') }}</strong>
                <span>{{ now('Asia/Kuala_Lumpur')->format('l, d M Y') }}</span>
            </div>
        </header>

        <section class="queue-grid">
            <div class="panel">
                <div class="panel-header">
                    <h2>Now Serving</h2>
                    <i class="fas fa-bell"></i>
                </div>
                <div class="serving-list">
                    @forelse($serving as $queue)
                        <article class="serving-card">
                            <div class="number">{{ $queue->queue_code }}</div>
                            <div class="customer">{{ $queue->display_customer_name }}</div>
                            <div class="meta">
                                <span><i class="fas fa-user-tie"></i> {{ $queue->barber->name ?? 'Any barber' }}</span>
                                <span><i class="fas fa-scissors"></i> {{ $queue->service->name ?? 'Walk-in service' }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="empty">No customer is being served right now.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h2>Waiting Queue</h2>
                    <i class="fas fa-list-ol"></i>
                </div>
                <div class="waiting-list">
                    @forelse($waiting->take(12) as $queue)
                        <article class="waiting-row">
                            <div class="number">{{ $queue->queue_code }}</div>
                            <div>
                                <div class="customer">{{ $queue->display_customer_name }}</div>
                                <div class="meta">
                                    <span>{{ $queue->barber->name ?? 'Any barber' }}</span>
                                    <span>{{ $queue->service->name ?? 'Walk-in service' }}</span>
                                </div>
                            </div>
                            <div class="wait">{{ $queue->formatted_wait }}</div>
                        </article>
                    @empty
                        <div class="empty">Queue is clear.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="panel bottom-panel">
            <div class="panel-header">
                <h2>Recent</h2>
                <span>Completed or skipped today</span>
            </div>
            <div class="recent-list">
                @forelse($recent as $queue)
                    <article class="recent-row">
                        <div class="number">{{ $queue->queue_code }}</div>
                        <div>
                            <div class="customer">{{ $queue->display_customer_name }}</div>
                            <div class="status">{{ $queue->status_label }}</div>
                        </div>
                    </article>
                @empty
                    <div class="empty">No recent queue activity yet.</div>
                @endforelse
            </div>
        </section>
    </main>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('timeNow').textContent = now.toLocaleTimeString('en-MY', {
                hour: '2-digit',
                minute: '2-digit',
            });
        }

        updateClock();
        setInterval(updateClock, 30000);
        setTimeout(() => window.location.reload(), 20000);
    </script>
</body>
</html>
