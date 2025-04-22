@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 admin-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Manage Comments</h1>
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

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%">Content</th>
                        <th>User</th>
                        <th>Post</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $comment)
                    <tr>
                        <td class="align-middle">
                            <div class="comment-content">{{ $comment->content }}</div>
                        </td>
                        <td class="align-middle">
                            <div class="user-info">
                                <span class="user-name">{{ $comment->user->name }}</span>
                                @if($comment->user->is_admin)
                                    <span class="status-badge admin">Admin</span>
                                @endif
                            </div>
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('blog.show', $comment->post) }}" class="post-link">
                                Post #{{ $comment->post->id }}
                            </a>
                        </td>
                        <td class="align-middle">
                            <div class="date-info">
                                <div>{{ $comment->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $comment->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <form action="{{ route('admin.delete-comment', $comment) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $comments->links() }}
    </div>
</div>
@endsection

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

    .comment-content {
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .user-name {
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.admin {
        background: #845EF7;
        color: white;
    }

    .post-link {
        color: #22B8CF;
        text-decoration: none;
        font-weight: 500;
    }

    .post-link:hover {
        text-decoration: underline;
    }

    .date-info {
        font-size: 0.9rem;
    }

    .date-info small {
        color: rgba(255, 255, 255, 0.5);
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

    .btn-action.danger {
        background: #FA5252;
        color: white;
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