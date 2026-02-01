@extends('admin.sidebar')

@section('content')
    <div class="container">


        <div class="header">
            <h2>Dashboard Overview</h2>
            <div class="user-info">
                <a href="{{ route('admin.profile.show') }}" class="profile-link-header">
                    <div class="user-avatar">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset(Auth::user()->profile_image) }}" alt="{{ Auth::user()->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->name, strrpos(Auth::user()->name, ' ') + 1, 1) }}
                        @endif
                    </div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div style="font-size: 14px; color: var(--dark-gray);">Barbershop Manager</div>
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
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 12% from yesterday
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Revenue Today</div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-value">RM{{ number_format($revenueToday, 2) }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Revenue This Month</div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="stat-value">RM{{ number_format($monthlyRevenue, 2) }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
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
                    <i class="fas fa-arrow-up"></i> 5 new this week
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
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i> 1 on leave
                </div>
            </div>
        </div>

        <!-- Charts & Recent Appointments -->
        <div class="content-row">
            <!-- Revenue Chart -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Weekly Revenue</div>
                    <a href="#" class="card-link">View Report</a>
                </div>
                <div class="chart-container">
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
                    <a href="#" class="card-link">View All</a>
                </div>
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

        <!-- Recent Activity -->
        
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
    </style>
    <style>
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
        }
    </style>
@endsection