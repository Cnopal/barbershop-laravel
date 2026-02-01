@extends('admin.sidebar')

@section('content')
<div class="container">


    <div class="header">
        <h2>Dashboard Overview</h2>
        <div class="user-info">
            <div class="user-avatar">AJ</div>
            <div>
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div style="font-size: 14px; color: var(--dark-gray);">Barbershop Manager</div>
            </div>
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
            <div class="stat-value">24</div>
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
            <div class="stat-value">$1,850</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> 8% from yesterday
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Active Clients</div>
                <div class="stat-icon customers">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">412</div>
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
            <div class="stat-value">8</div>
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
                <div class="chart-placeholder">
                    <i class="fas fa-chart-line"></i>
                    <p>Revenue chart visualization</p>
                </div>
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
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>James Wilson</td>
                        <td>Mike T.</td>
                        <td>10:30 AM</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>Robert Chen</td>
                        <td>Sarah L.</td>
                        <td>11:15 AM</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>David Miller</td>
                        <td>Carlos R.</td>
                        <td>12:00 PM</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>Anthony Garcia</td>
                        <td>Mike T.</td>
                        <td>1:45 PM</td>
                        <td><span class="status confirmed">Confirmed</span></td>
                    </tr>
                    <tr>
                        <td>Thomas Brown</td>
                        <td>Sarah L.</td>
                        <td>2:30 PM</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Activity</div>
            <a href="#" class="card-link">View All</a>
        </div>
        <div class="activity-list">
            <div class="activity-item">
                <div class="activity-icon appointment">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="activity-details">
                    <h4>New appointment booked</h4>
                    <p>Mark Davis booked a haircut with Carlos Rodriguez for tomorrow at 3 PM</p>
                    <div class="activity-time">10 minutes ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon payment">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="activity-details">
                    <h4>Payment received</h4>
                    <p>John Smith completed a $45 payment for beard trim service</p>
                    <div class="activity-time">45 minutes ago</div>
                </div>
            </div>
            <div class="activity-item">
                <div class="activity-icon client">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-details">
                    <h4>New client registered</h4>
                    <p>Alex Johnson created a new account and booked his first appointment</p>
                    <div class="activity-time">2 hours ago</div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <style>
        .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }
    </style>
@endsection