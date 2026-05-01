@extends('admin.sidebar')

@section('content')
    <div class="container">


        <div class="header">
            <h2>Dashboard Overview</h2>
            <div class="user-info">
                <a href="{{ route('admin.profile.show') }}" class="profile-link-header">
                    <div class="user-avatar">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1) }}
                        @endif
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role-label">Barbershop Manager</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Today's Appointments</div>
                    <div class="stat-icon appointments">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $todayAppointments }}</div>
                <div class="stat-change positive">Scheduled bookings today</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Today's Walk-ins</div>
                    <div class="stat-icon appointments">
                        <i class="fas fa-list-ol"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $todayWalkIns }}</div>
                <div class="stat-change positive">Queue entries today</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Sales Today</div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">RM{{ number_format($revenueToday, 2) }}</div>
                <div class="stat-breakdown">
                    @foreach($salesBreakdownToday as $label => $amount)
                        <span>{{ $label }} RM{{ number_format($amount, 2) }}</span>
                    @endforeach
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Sales This Month</div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="stat-value">RM{{ number_format($monthlyRevenue, 2) }}</div>
                <div class="stat-breakdown">
                    @foreach($salesBreakdownMonth as $label => $amount)
                        <span>{{ $label }} RM{{ number_format($amount, 2) }}</span>
                    @endforeach
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Active Clients</div>
                    <div class="stat-icon customers">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $activeClients }}</div>
                <div class="stat-change positive">
                    {{ $newCustomersThisMonth }} new this month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">New Customers</div>
                    <div class="stat-icon customers">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $newCustomersThisMonth }}</div>
                <div class="stat-change positive">
                    <a href="{{ route('admin.reports.show', 'new-customers') }}" class="mini-link">View Details</a>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Available Barbers</div>
                    <div class="stat-icon barbers">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $availableBarbers }}</div>
                <div class="stat-change positive">Active staff accounts</div>
            </div>
        </div>

        <!-- Charts & Recent Appointments -->
        <div class="content-row">
            <!-- Revenue Chart -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Weekly Sales</div>
                    <a href="{{ route('admin.reports.show', 'sales') }}" class="card-link">View Details</a>
                </div>
                <div class="chart-container dashboard-table-scroll">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Revenue (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weeklyRevenue as $day)
                                <tr>
                                    <td>{{ $day->date }}</td>
                                    <td>RM{{ number_format($day->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent Appointments</div>
                    <a href="{{ route('admin.appointments.index') }}" class="card-link">View All</a>
                </div>
                <div class="dashboard-table-scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Barber</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->customer->name ?? '-' }}</td>
                                    <td>{{ $appointment->barber->name ?? '-' }}</td>
                                    <td>{{ $appointment->appointment_date->format('Y-m-d') }}</td>
                                    <td>{{ $appointment->start_time }}</td>
                                    <td><span class="status {{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <section id="detail-report" class="report-section">
            <div class="report-header">
                <div>
                    <h3>Monthly Detailed Report</h3>
                    <p>Appointments, walk-ins, products, services, staff, and customer growth for {{ now('Asia/Kuala_Lumpur')->format('F Y') }}.</p>
                </div>
            </div>

            <div class="report-grid">
                <article class="report-card">
                    <div class="report-card-header">
                        <h4><i class="fas fa-calendar-check"></i> Appointment Status</h4>
                        <a href="{{ route('admin.reports.show', 'appointment-status') }}" class="detail-link">View Details</a>
                    </div>
                    <table class="report-table">
                        <tbody>
                            @foreach($appointmentStatusCounts as $label => $count)
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </article>

                <article class="report-card">
                    <div class="report-card-header">
                        <h4><i class="fas fa-box"></i> Top Selling Products</h4>
                        <a href="{{ route('admin.reports.show', 'top-products') }}" class="detail-link">View Details</a>
                    </div>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellingProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>RM{{ number_format($product->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No product sales this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </article>

                <article class="report-card">
                    <div class="report-card-header">
                        <h4><i class="fas fa-scissors"></i> Top Services</h4>
                        <a href="{{ route('admin.reports.show', 'top-services') }}" class="detail-link">View Details</a>
                    </div>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topServices as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->total_bookings }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2">No service activity this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </article>

                <article class="report-card wide">
                    <div class="report-card-header">
                        <h4><i class="fas fa-user-tie"></i> Staff Performance</h4>
                        <a href="{{ route('admin.reports.show', 'staff-performance') }}" class="detail-link">View Details</a>
                    </div>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Appointments</th>
                                <th>Walk-ins</th>
                                <th>Service Revenue</th>
                                <th>Product Revenue</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffPerformance as $staff)
                                <tr>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->appointment_jobs }}</td>
                                    <td>{{ $staff->walk_in_jobs }}</td>
                                    <td>RM{{ number_format($staff->service_revenue, 2) }}</td>
                                    <td>RM{{ number_format($staff->product_revenue, 2) }}</td>
                                    <td>RM{{ number_format($staff->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6">No staff sales activity this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </article>

                <article class="report-card">
                    <div class="report-card-header">
                        <h4><i class="fas fa-chart-pie"></i> Revenue By Service</h4>
                        <a href="{{ route('admin.reports.show', 'revenue-by-service') }}" class="detail-link">View Details</a>
                    </div>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Appointments</th>
                                <th>Walk-ins</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueByService as $service)
                                <tr>
                                    <td>{{ $service->name }}</td>
                                    <td>RM{{ number_format($service->appointment_revenue, 2) }}</td>
                                    <td>RM{{ number_format($service->walk_in_revenue, 2) }}</td>
                                    <td>RM{{ number_format($service->total_revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4">No service revenue this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </article>

                <article class="report-card">
                    <div class="report-card-header">
                        <h4><i class="fas fa-cash-register"></i> Revenue By Product</h4>
                        <a href="{{ route('admin.reports.show', 'revenue-by-product') }}" class="detail-link">View Details</a>
                    </div>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueByProduct as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>RM{{ number_format($product->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No product revenue this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </article>
            </div>
        </section>
        
    </div>

    <style>
        .profile-link-header {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .profile-link-header:hover {
            opacity: 0.8;
        }

        .profile-link-header .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #c19a2f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: bold;
            font-size: 18px;
            flex-shrink: 0;
            border: 2px solid var(--medium-gray);
            overflow: hidden;
        }

        .profile-link-header .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .user-role-label {
            font-size: 14px;
            color: var(--dark-gray);
        }

        .stat-breakdown {
            display: grid;
            gap: 4px;
            margin-top: 10px;
            color: var(--dark-gray);
            font-size: 12px;
            font-weight: 700;
        }

        .mini-link,
        .detail-link {
            color: var(--accent);
            font-weight: 800;
            text-decoration: none;
        }

        .dashboard-table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .report-section {
            margin-top: 30px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-end;
            margin-bottom: 16px;
        }

        .report-header h3 {
            margin: 0 0 6px;
            color: var(--primary);
            font-size: 22px;
        }

        .report-header p {
            margin: 0;
            color: var(--dark-gray);
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .report-card {
            background: #fff;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            padding: 18px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            min-width: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .report-card.wide {
            grid-column: span 3;
        }

        .report-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .report-card h4 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-size: 16px;
        }

        .detail-link {
            flex-shrink: 0;
            font-size: 13px;
        }

        .report-card h4 i {
            color: var(--accent);
        }

        .report-table {
            width: 100%;
            min-width: 520px;
            border-collapse: collapse;
            font-size: 13px;
        }

        .report-card.wide .report-table {
            min-width: 820px;
        }

        .report-table th,
        .report-table td {
            padding: 10px 8px;
            border-bottom: 1px solid var(--medium-gray);
            text-align: left;
            vertical-align: top;
        }

        .report-table th {
            color: var(--dark-gray);
            font-size: 12px;
            text-transform: uppercase;
        }

        .report-table td:last-child,
        .report-table th:last-child {
            text-align: right;
            font-weight: 700;
        }

        @media (max-width: 1100px) {
            .content-row {
                grid-template-columns: 1fr;
            }

            .report-grid {
                grid-template-columns: 1fr 1fr;
            }

            .report-card.wide {
                grid-column: span 2;
            }
        }

        @media (max-width: 760px) {
            .container {
                padding: 18px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header h2 {
                font-size: 24px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 18px;
            }

            .stat-value {
                font-size: 26px;
                overflow-wrap: anywhere;
            }

            .content-row {
                gap: 18px;
            }

            .card {
                padding: 18px;
            }

            .report-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .report-grid {
                grid-template-columns: 1fr;
            }

            .report-card.wide {
                grid-column: span 1;
            }

            .report-card {
                padding: 16px;
            }

            .report-card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
    <style>
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 30px;
        }

        @media (max-width: 760px) {
            .container {
                padding: 18px;
            }
        }
    </style>
@endsection
