@extends('admin.sidebar')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Customer Management</h1>
        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Customer
        </a>
    </div>

    <!-- Control Bar -->
    <div class="control-bar">
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="searchInput" placeholder="Search customers...">
        </div>
        
        <div class="filter-controls">
            <select class="filter-select" id="sortBy">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="name_asc">Name: A to Z</option>
                <option value="name_desc">Name: Z to A</option>
                <option value="appointments_high">Most Appointments</option>
                <option value="appointments_low">Least Appointments</option>
            </select>
            
            <button class="btn btn-secondary" id="exportBtn">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Customers</div>
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalCustomers }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-line"></i> All Time
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Active This Month</div>
                <div class="stat-icon active">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
           
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Appointments</div>
                <div class="stat-icon appointments">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            
            <div class="stat-change positive">
                <i class="fas fa-chart-line"></i> All Customer Appointments
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Avg. Appointments</div>
                <div class="stat-icon average">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>
           
            <div class="stat-change">
                Per Customer
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="customers-table" id="customersTable">
                <thead>
                    <tr>
                        <th>
                            <div class="table-header">
                                <span>Customer</span>
                                <button class="sort-btn" data-sort="name">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th>
                            <div class="table-header">
                                <span>Contact</span>
                            </div>
                        </th>
                        <th>
                            <div class="table-header">
                                <span>Appointments</span>
                                <button class="sort-btn" data-sort="appointments">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th>
                            <div class="table-header">
                                <span>Last Appointment</span>
                                <button class="sort-btn" data-sort="last_appointment">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th>
                            <div class="table-header">
                                <span>Member Since</span>
                                <button class="sort-btn" data-sort="created_at">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </div>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        @php
                            // $appointmentsCount = $customer->appointments_count ?? 0;
                            // $lastAppointment = $customer->appointments->sortByDesc('appointment_date')->first();
                        @endphp
                        
                        <tr class="customer-row" 
                            data-name="{{ strtolower($customer->name) }}"
                            data-email="{{ strtolower($customer->email) }}"
                            data-phone="{{ $customer->phone ?? '' }}"
                            
                            data-created="{{ $customer->created_at->timestamp }}">

                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($customer->name, 0, 2)) }}
                                    </div>
                                    <div class="customer-details">
                                        <div class="customer-name">{{ $customer->name }}</div>
                                        <div class="customer-email">{{ $customer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="contact-info">
                                    @if($customer->phone)
                                        <div class="contact-item">
                                            <i class="fas fa-phone"></i>
                                            <span>{{ $customer->phone }}</span>
                                        </div>
                                    @endif
                                    @if($customer->address)
                                        <div class="contact-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="truncate-address" title="{{ $customer->address }}">
                                                {{ Str::limit($customer->address, 30) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <td>
                                <div class="appointments-info">
                                   
                                    <span class="appointments-label">appointment(s)</span>
                                   
                                </div>
                            </td>
                            
                            <td>
                              
                            </td>
                            
                            <td>
                                <div class="member-since">
                                    {{ $customer->created_at->format('M d, Y') }}
                                    <div class="member-days">
                                        {{ $customer->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                       class="btn-action view-btn" title="View Customer">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                                       class="btn-action edit-btn" title="Edit Customer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn-action delete-btn" 
                                            title="Delete Customer"
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-users empty-icon"></i>
                                    <h3>No customers found</h3>
                                    <p>Add your first customer to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="pagination-container">
        {{ $customers->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-body">
            <div class="delete-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="delete-message">
                <h3>Delete Customer</h3>
                <p id="deleteMessage">Are you sure you want to delete this customer? This action cannot be undone and will delete all associated appointments.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
            <button class="btn btn-danger" id="confirmDelete">Delete Customer</button>
        </div>
    </div>
</div>

<style>
    /* CSS Variables */
    :root {
        --primary-color: #1a1f36;
        --secondary-color: #4a5568;
        --accent-color: #d4af37;
        --light-gray: #f7fafc;
        --medium-gray: #e2e8f0;
        --dark-gray: #718096;
        --success-color: #48bb78;
        --warning-color: #ed8936;
        --danger-color: #f56565;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition: all 0.3s ease;
    }

    /* Container */
    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 30px;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    /* Button Styles */
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 15px;
        text-decoration: none;
    }
    
    .btn-primary {
        background-color: var(--accent-color);
        color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #c19a2f;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--medium-gray);
    }
    
    .btn-secondary:hover {
        background-color: var(--light-gray);
    }
    
    .btn-small {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    /* Control Bar */
    .control-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .search-container {
        position: relative;
        width: 300px;
    }
    
    .search-input {
        width: 100%;
        padding: 12px 16px 12px 45px;
        border-radius: 8px;
        border: 1px solid var(--medium-gray);
        background-color: white;
        font-size: 15px;
        transition: var(--transition);
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dark-gray);
    }
    
    .filter-controls {
        display: flex;
        gap: 10px;
    }
    
    .filter-select {
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid var(--medium-gray);
        background-color: white;
        font-size: 15px;
        color: var(--primary-color);
        cursor: pointer;
        min-width: 150px;
    }
    
    /* Stats Container */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background-color: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .stat-title {
        color: var(--dark-gray);
        font-size: 15px;
        font-weight: 600;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    
    .stat-icon.total {
        background-color: rgba(212, 175, 55, 0.1);
        color: var(--accent-color);
    }
    
    .stat-icon.active {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }
    
    .stat-icon.appointments {
        background-color: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }
    
    .stat-icon.average {
        background-color: rgba(159, 122, 234, 0.1);
        color: #9f7aea;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--primary-color);
    }
    
    .stat-change {
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--dark-gray);
    }
    
    .stat-change .positive {
        color: var(--success-color);
        font-weight: 600;
    }
    
    .stat-change .negative {
        color: var(--danger-color);
        font-weight: 600;
    }
    
    /* Table Container */
    .table-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 40px;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .customers-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    
    .customers-table th {
        text-align: left;
        padding: 20px;
        background-color: var(--light-gray);
        color: var(--dark-gray);
        font-weight: 600;
        font-size: 14px;
        border-bottom: 1px solid var(--medium-gray);
    }
    
    .customers-table td {
        padding: 20px;
        border-bottom: 1px solid var(--light-gray);
        vertical-align: top;
    }
    
    .customers-table tr:hover {
        background-color: var(--light-gray);
    }
    
    .customers-table tr:last-child td {
        border-bottom: none;
    }
    
    /* Table Header with Sort */
    .table-header {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .sort-btn {
        background: none;
        border: none;
        color: var(--dark-gray);
        cursor: pointer;
        padding: 2px;
        transition: var(--transition);
    }
    
    .sort-btn:hover {
        color: var(--primary-color);
    }
    
    /* Customer Info */
    .customer-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: var(--accent-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-weight: bold;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .customer-details {
        flex: 1;
        min-width: 0;
    }
    
    .customer-name {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .customer-email {
        font-size: 13px;
        color: var(--dark-gray);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Contact Info */
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--secondary-color);
    }
    
    .contact-item i {
        color: var(--accent-color);
        width: 16px;
    }
    
    .truncate-address {
        cursor: help;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Appointments Info */
    .appointments-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .appointments-count {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .appointments-label {
        font-size: 12px;
        color: var(--dark-gray);
    }
    
    .progress-bar {
        height: 4px;
        background-color: var(--medium-gray);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 4px;
    }
    
    .progress-fill {
        height: 100%;
        background-color: var(--accent-color);
        transition: width 0.3s ease;
    }
    
    /* Last Appointment */
    .last-appointment {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .date {
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .time {
        font-size: 12px;
        color: var(--dark-gray);
    }
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        width: fit-content;
    }
    
    .status-badge.completed {
        background-color: rgba(72, 187, 120, 0.1);
        color: var(--success-color);
    }
    
    .status-badge.confirmed {
        background-color: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }
    
    .status-badge.pending {
        background-color: rgba(237, 137, 54, 0.1);
        color: var(--warning-color);
    }
    
    .status-badge.cancelled {
        background-color: rgba(245, 101, 101, 0.1);
        color: var(--danger-color);
    }
    
    .no-appointments {
        color: var(--dark-gray);
        font-style: italic;
        font-size: 14px;
    }
    
    /* Member Since */
    .member-since {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .member-days {
        font-size: 12px;
        color: var(--dark-gray);
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: none;
        background: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        color: var(--dark-gray);
    }
    
    .btn-action:hover {
        background-color: var(--light-gray);
        color: var(--primary-color);
    }
    
    .view-btn:hover {
        background-color: rgba(66, 153, 225, 0.1);
        color: #4299e1;
    }
    
    .edit-btn:hover {
        background-color: rgba(212, 175, 55, 0.1);
        color: var(--accent-color);
    }
    
    .delete-btn:hover {
        background-color: rgba(245, 101, 101, 0.1);
        color: var(--danger-color);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--dark-gray);
    }
    
    .empty-icon {
        font-size: 48px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        margin-bottom: 10px;
        font-size: 20px;
        color: var(--primary-color);
    }
    
    /* Pagination */
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        list-style: none;
        gap: 8px;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        margin: 0;
    }
    
    .pagination li a,
    .pagination li span {
        display: inline-block;
        padding: 8px 16px;
        background-color: white;
        border: 1px solid var(--medium-gray);
        border-radius: 6px;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }
    
    .pagination li.active span {
        background-color: var(--accent-color);
        border-color: var(--accent-color);
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .pagination li a:hover {
        background-color: var(--light-gray);
        transform: translateY(-2px);
    }
    
    /* Style for disabled pagination items */
    .pagination li.disabled span {
        background-color: var(--medium-gray);
        color: var(--dark-gray);
        cursor: not-allowed;
    }
    
    /* Delete Confirmation Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background-color: white;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .modal-body {
        padding: 25px;
    }
    
    .modal-footer {
        padding: 15px 25px 25px;
        border-top: 1px solid var(--medium-gray);
        display: flex;
        justify-content: flex-end;
        gap: 15px;
    }
    
    .delete-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(245, 101, 101, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: var(--danger-color);
        font-size: 36px;
    }
    
    .delete-message {
        text-align: center;
        margin-bottom: 25px;
    }
    
    .delete-message h3 {
        font-size: 22px;
        margin-bottom: 10px;
        color: var(--primary-color);
    }
    
    .delete-message p {
        color: var(--dark-gray);
        line-height: 1.5;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .control-bar {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-container {
            width: 100%;
        }
        
        .filter-controls {
            flex-direction: column;
        }
        
        .filter-select {
            width: 100%;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .customers-table {
            min-width: auto;
        }
        
        .customer-info {
            flex-direction: column;
            text-align: center;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }
    
    @media (max-width: 480px) {
        .btn {
            padding: 10px 16px;
            font-size: 14px;
        }
        
        .customers-table th,
        .customers-table td {
            padding: 15px 10px;
        }
    }
    /* Toast notification styles */
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: var(--primary-color);
        color: white;
        padding: 16px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease;
    }
    
    .toast i {
        color: var(--success-color);
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const searchInput = document.getElementById('searchInput');
    const sortBy = document.getElementById('sortBy');
    const exportBtn = document.getElementById('exportBtn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteMessage = document.getElementById('deleteMessage');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    const customerRows = document.querySelectorAll('.customer-row');
    const tableBody = document.querySelector('#customersTable tbody');
    const sortButtons = document.querySelectorAll('.sort-btn');
    
    let currentDeleteId = null;
    let currentSort = {
        field: 'created_at',
        direction: 'desc'
    };
    
    // Search functionality
    function filterCustomers() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        
        customerRows.forEach(row => {
            const name = row.getAttribute('data-name');
            const email = row.getAttribute('data-email');
            const phone = row.getAttribute('data-phone');
            
            let shouldShow = true;
            
            if (searchTerm) {
                shouldShow = name.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           phone.includes(searchTerm);
            }
            
            row.style.display = shouldShow ? '' : 'none';
        });
    }
    
    // Sort functionality
    function sortCustomers() {
        const rows = Array.from(customerRows).filter(row => row.style.display !== 'none');
        
        rows.sort((a, b) => {
            let valueA, valueB;
            
            switch(currentSort.field) {
                case 'name':
                    valueA = a.querySelector('.customer-name').textContent.toLowerCase();
                    valueB = b.querySelector('.customer-name').textContent.toLowerCase();
                    break;
                    
                case 'appointments':
                    valueA = parseInt(a.getAttribute('data-appointments'));
                    valueB = parseInt(b.getAttribute('data-appointments'));
                    break;
                    
                case 'last_appointment':
                    valueA = parseInt(a.getAttribute('data-last-appointment'));
                    valueB = parseInt(b.getAttribute('data-last-appointment'));
                    break;
                    
                case 'created_at':
                    valueA = parseInt(a.getAttribute('data-created'));
                    valueB = parseInt(b.getAttribute('data-created'));
                    break;
                    
                default:
                    return 0;
            }
            
            if (currentSort.direction === 'asc') {
                return valueA < valueB ? -1 : valueA > valueB ? 1 : 0;
            } else {
                return valueA > valueB ? -1 : valueA < valueB ? 1 : 0;
            }
        });
        
        // Re-order DOM
        rows.forEach(row => tableBody.appendChild(row));
    }
    
    // Table sort buttons
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            const field = this.getAttribute('data-sort');
            
            if (currentSort.field === field) {
                // Toggle direction
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                // New field, default to descending
                currentSort.field = field;
                currentSort.direction = 'desc';
            }
            
            // Update sort indicator
            sortButtons.forEach(btn => {
                btn.innerHTML = '<i class="fas fa-sort"></i>';
            });
            
            this.innerHTML = currentSort.direction === 'asc' 
                ? '<i class="fas fa-sort-up"></i>' 
                : '<i class="fas fa-sort-down"></i>';
            
            sortCustomers();
        });
    });
    
    // Global sort dropdown
    if (sortBy) {
        sortBy.addEventListener('change', function() {
            switch(this.value) {
                case 'newest':
                    currentSort.field = 'created_at';
                    currentSort.direction = 'desc';
                    break;
                case 'oldest':
                    currentSort.field = 'created_at';
                    currentSort.direction = 'asc';
                    break;
                case 'name_asc':
                    currentSort.field = 'name';
                    currentSort.direction = 'asc';
                    break;
                case 'name_desc':
                    currentSort.field = 'name';
                    currentSort.direction = 'desc';
                    break;
                case 'appointments_high':
                    currentSort.field = 'appointments';
                    currentSort.direction = 'desc';
                    break;
                case 'appointments_low':
                    currentSort.field = 'appointments';
                    currentSort.direction = 'asc';
                    break;
            }
            
            sortCustomers();
        });
    }
    
    // Event listeners
    if (searchInput) searchInput.addEventListener('input', filterCustomers);
    
    // Export button
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Simple export to CSV
            exportToCSV();
        });
    }
    
    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentDeleteId = this.getAttribute('data-id');
            const customerName = this.getAttribute('data-name');
            
            deleteMessage.textContent = `Are you sure you want to delete "${customerName}"? This action cannot be undone and will delete all associated appointments.`;
            deleteModal.classList.add('active');
        });
    });
    
    // Cancel delete
    cancelDelete.addEventListener('click', function() {
        deleteModal.classList.remove('active');
        currentDeleteId = null;
    });
    
    // Confirm delete
    confirmDelete.addEventListener('click', function() {
        if (currentDeleteId) {
            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/customers/${currentDeleteId}`;
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
        
        deleteModal.classList.remove('active');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === deleteModal) {
            deleteModal.classList.remove('active');
            currentDeleteId = null;
        }
    });
    
    // Keyboard support for modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && deleteModal.classList.contains('active')) {
            deleteModal.classList.remove('active');
            currentDeleteId = null;
        }
    });
    
    // Export to CSV function
    function exportToCSV() {
        const rows = [];
        const headers = ['Name', 'Email', 'Phone', 'Address', 'Appointments', 'Last Appointment', 'Member Since'];
        
        // Add headers
        rows.push(headers.join(','));
        
        // Add data rows
        customerRows.forEach(row => {
            if (row.style.display !== 'none') {
                const name = row.querySelector('.customer-name').textContent;
                const email = row.querySelector('.customer-email').textContent;
                const phone = row.querySelector('.contact-item:first-child span')?.textContent || '';
                const address = row.querySelector('.contact-item:nth-child(2) span')?.textContent || '';
                const appointments = row.querySelector('.appointments-count').textContent;
                const lastAppointment = row.querySelector('.date')?.textContent || 'No appointments';
                const memberSince = row.querySelector('.member-since').textContent.split('\n')[0].trim();
                
                const rowData = [
                    `"${name}"`,
                    `"${email}"`,
                    `"${phone}"`,
                    `"${address}"`,
                    appointments,
                    `"${lastAppointment}"`,
                    `"${memberSince}"`
                ];
                
                rows.push(rowData.join(','));
            }
        });
        
        // Create and download CSV
        const csvContent = rows.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `customers_${new Date().toISOString().split('T')[0]}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // Show success message if present in session
    @if(session('success'))
        showToast('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) existingToast.remove();
        
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        const iconColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';
        
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = `
            <i class="fas ${icon}" style="color: ${iconColor};"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
    }
});
</script>
@endsection