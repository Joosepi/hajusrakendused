@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 admin-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Activity Logs</h1>
        <a href="{{ route('admin.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <div class="card bg-dark text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 15%">User</th>
                            <th style="width: 15%">Action</th>
                            <th style="width: 55%">Description</th>
                            <th style="width: 15%">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <span class="user-name">{{ $log->user->name }}</span>
                                    @if($log->user->is_admin)
                                        <span class="status-badge admin">Admin</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="action-badge">{{ $log->action }}</span>
                            </td>
                            <td class="description">{{ $log->description }}</td>
                            <td>
                                <div class="date-info">
                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <nav>
            <ul class="pagination justify-content-center">
                @if ($logs->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">&laquo;</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $logs->previousPageUrl() }}" rel="prev">&laquo;</a>
                    </li>
                @endif

                @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                    @if ($page == $logs->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                @if ($logs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $logs->nextPageUrl() }}" rel="next">&raquo;</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">&raquo;</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>

<style>
.admin-dashboard {
    padding: 2rem 0;
}

/* Card Styling */
.card {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: #1E1E1E;
    border-radius: 8px;
}

/* Table Styling */
.table {
    margin: 0;
}

.table th {
    background: rgba(0, 0, 0, 0.2);
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.875rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table td {
    color: rgba(255, 255, 255, 0.9);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    vertical-align: middle;
}

/* User Info Styling */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-name {
    font-weight: 500;
    color: #fff;
}

/* Badge Styling */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
    background: #845EF7;
    color: white;
}

.action-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 4px;
    font-size: 0.875rem;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    font-weight: 500;
}

/* Description Cell */
.description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9375rem;
}

/* Date Info */
.date-info {
    font-size: 0.875rem;
    line-height: 1.4;
}

.date-info small {
    color: rgba(255, 255, 255, 0.5);
    display: block;
    margin-top: 2px;
}

/* Back Button */
.btn-outline-light {
    border-color: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Updated Pagination Styling */
.pagination {
    margin: 0;
    gap: 0.25rem;
}

.pagination .page-link {
    background-color: #1a1a1a;
    border: 1px solid #2d2d2d;
    color: #fff;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    min-width: 2.25rem;
    text-align: center;
    border-radius: 4px;
    margin: 0 2px;
    text-decoration: none;
}

.pagination .page-item.active .page-link {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
}

.pagination .page-item.disabled .page-link {
    background-color: #1a1a1a;
    border-color: #2d2d2d;
    color: #6c757d;
    pointer-events: none;
}

.pagination .page-link:hover:not(.disabled) {
    background-color: #242424;
    border-color: #3b82f6;
    color: #fff;
}

/* Responsive Table */
.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

@media (max-width: 768px) {
    .admin-dashboard {
        padding: 1rem 0;
    }
    
    .table th, .table td {
        padding: 0.75rem 1rem;
    }
    
    .action-badge {
        padding: 0.2rem 0.5rem;
    }
}
</style>
@endsection

