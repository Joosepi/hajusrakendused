@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">Admin Dashboard</h1>
        <div class="d-flex align-items-center">
            <span class="text-white-50 me-3">
                <i class="far fa-clock me-1"></i>
                {{ now()->format('l, F j, Y \a\t h:i A') }}
            </span>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="row g-4 mb-4">
        <!-- Users Card -->
        <div class="col-md-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Users</h6>
                            <h2 class="mb-0">{{ $analytics['users']['total'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted">+{{ $analytics['users']['new_today'] }} today</span>
                        <span class="ms-2 {{ $analytics['users']['growth'] > 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas fa-{{ $analytics['users']['growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($analytics['users']['growth']) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Card -->
        <div class="col-md-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Posts</h6>
                            <h2 class="mb-0">{{ $analytics['posts']['total'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-file-alt fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted">+{{ $analytics['posts']['new_today'] }} today</span>
                        <span class="ms-2 {{ $analytics['posts']['growth'] > 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas fa-{{ $analytics['posts']['growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($analytics['posts']['growth']) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Card -->
        <div class="col-md-4">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Comments</h6>
                            <h2 class="mb-0">{{ $analytics['comments']['total'] }}</h2>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-comments fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted">+{{ $analytics['comments']['new_today'] }} today</span>
                        <span class="ms-2 {{ $analytics['comments']['growth'] > 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fas fa-{{ $analytics['comments']['growth'] > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($analytics['comments']['growth']) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- User Management -->
        <div class="col-xl-8">
            <div class="card bg-dark text-white">
                <div class="card-header bg-darker d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Management</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-light btn-sm">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="status-badge {{ $user->is_admin ? 'admin' : 'user' }}">
                                            {{ $user->is_admin ? 'Admin' : 'User' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.toggle-admin', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-action {{ $user->is_admin ? 'warning' : 'success' }}">
                                                    {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">Current User</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-xl-4">
            <div class="card bg-dark text-white">
                <div class="card-header bg-darker d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Activity</h5>
                    <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-light btn-sm">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body activity-scroll p-0">
                    <div class="activity-timeline p-3">
                        @foreach($recentActivity as $activity)
                        <div class="activity-item">
                            <div class="activity-content">
                                <div class="d-flex justify-content-between">
                                    <span class="activity-user">{{ $activity->user->name }}</span>
                                    <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mb-0">{{ $activity->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Backup -->
    <div class="mt-4">
        <div class="card bg-dark text-white">
            <div class="card-header bg-darker d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Database Backup</h5>
                <span class="text-muted">Last backup: {{ $lastBackup ?? 'Never' }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.backup') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Create Backup
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Base styles */
.container-fluid {
    background: #121212;
    min-height: 100vh;
    padding-top: 1.5rem;
    padding-bottom: 1.5rem;
}

/* Card styles */
.card {
    background: #1E1E1E;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
}

.card-header {
    background: #141414 !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 1rem 1.5rem;
}

/* Stats cards */
.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.1);
}

/* Table styles */
.table-dark {
    background: transparent;
    margin: 0;
}

.table-dark th {
    background: rgba(0, 0, 0, 0.2);
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table-dark td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

/* Activity feed */
.activity-scroll {
    height: 400px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #4A5568 #1E1E1E;
}

.activity-item {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 0.75rem;
}

/* Status badges */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.875rem;
}

.status-badge.admin {
    background: rgba(132, 94, 247, 0.2);
    color: #845EF7;
}

.status-badge.user {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.7);
}

/* Buttons */
.btn-action {
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
    border: none;
    font-size: 0.875rem;
    font-weight: 500;
}

.btn-action.success {
    background: rgba(56, 178, 172, 0.2);
    color: #38B2AC;
}

.btn-action.warning {
    background: rgba(229, 62, 62, 0.2);
    color: #E53E3E;
}

/* Scrollbar styling */
.activity-scroll::-webkit-scrollbar {
    width: 6px;
}

.activity-scroll::-webkit-scrollbar-track {
    background: #1E1E1E;
}

.activity-scroll::-webkit-scrollbar-thumb {
    background: #4A5568;
    border-radius: 3px;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}

/* Base text colors */
.card, .card-body, .table, td, th, h1, h2, h3, h4, h5, h6, p, span, div {
    color: #ffffff !important;
}

/* Analytics cards */
.analytics-card h2, .analytics-card h3 {
    color: #ffffff !important;
}

/* Table text */
.table-dark {
    color: #ffffff !important;
}

.table-dark td, .table-dark th {
    color: #ffffff !important;
}

/* Status badges with better contrast */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.875rem;
}

.status-badge.admin {
    background: #845EF7;
    color: #ffffff !important;
}

.status-badge.user {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff !important;
}

/* Action buttons */
.btn-action {
    color: #ffffff !important;
}

.btn-action.success {
    background: #38B2AC;
}

.btn-action.warning {
    background: #E53E3E;
}

/* Activity items */
.activity-user, .activity-time, .activity-description {
    color: #ffffff !important;
}

.activity-time {
    opacity: 0.7;
}

/* Growth indicators */
.text-success {
    color: #38B2AC !important;
}

.text-danger {
    color: #E53E3E !important;
}

/* View All buttons */
.btn-outline-light {
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.2);
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff !important;
}

/* Make Admin buttons */
.make-admin-btn {
    background: #38B2AC;
    color: #ffffff !important;
    border: none;
    padding: 0.4rem 0.75rem;
    border-radius: 6px;
}

/* Current user text */
.text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Stats numbers and labels */
.stat-info h2, .stat-info h6 {
    color: #ffffff !important;
}

/* Database backup section */
.backup-section h5, .backup-section span {
    color: #ffffff !important;
}

/* Ensure all icons are white */
.fas, .far, .fab {
    color: #ffffff;
}

/* Header text */
.dashboard-header h1 {
    color: #ffffff !important;
}

/* Search box text */
input[type="text"], input[type="search"] {
    color: #ffffff !important;
}

input[type="text"]::placeholder, input[type="search"]::placeholder {
    color: rgba(255, 255, 255, 0.5) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize datetime display
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Search functionality
    const searchInput = document.getElementById('dashboardSearch');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('.users-table tbody tr');
        const activityItems = document.querySelectorAll('.activity-item');

        // Search in users table
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });

        // Search in activity feed
        activityItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Backup form handling
    const backupForm = document.getElementById('backupForm');
    const backupButton = document.getElementById('backupButton');

    backupForm.addEventListener('submit', function(e) {
        backupButton.disabled = true;
        backupButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Backup...';
    });
});
</script>
@endsection 