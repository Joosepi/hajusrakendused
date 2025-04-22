@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 admin-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Manage Users</h1>
        <a href="{{ route('admin.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Posts</th>
                        <th>Comments</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">{{ $user->email }}</td>
                        <td class="align-middle">{{ $user->posts_count }}</td>
                        <td class="align-middle">{{ $user->comments_count }}</td>
                        <td class="align-middle">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="align-middle">
                            @if($user->is_admin)
                                <span class="status-badge admin">Admin</span>
                            @else
                                <span class="status-badge user">User</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($user->id !== auth()->id())
                                <div class="action-buttons">
                                    <form action="{{ route('admin.toggle-admin', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action {{ $user->is_admin ? 'warning' : 'success' }}">
                                            {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.delete-user', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="current-user">Current User</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

@push('styles')
<style>
    .admin-dashboard {
        padding: 2rem 0;
    }

    .admin-card {
        background: #1E1E1E;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

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
        border: none;
    }

    .table td {
        color: rgba(255, 255, 255, 0.9);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .status-badge.admin {
        background: #845EF7;
        color: white;
    }

    .status-badge.user {
        background: #22B8CF;
        color: white;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        padding: 0.4rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .btn-action:hover {
        opacity: 0.9;
    }

    .btn-action.success {
        background: #40C057;
        color: white;
    }

    .btn-action.warning {
        background: #FAB005;
        color: white;
    }

    .btn-action.danger {
        background: #FA5252;
        color: white;
    }

    .current-user {
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.875rem;
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>
@endpush
@endsection 