@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="container py-4">
    <div class="blog-header mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-2">Blog Posts</h1>
                <p class="text-white opacity-75">Share your thoughts and experiences</p>
            </div>
            @auth
            <a href="{{ route('blog.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> New Post
            </a>
            @endauth
        </div>
    </div>

    @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6">
                    <article class="blog-card">
                        <div class="card bg-dark h-100 border-0">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="post-meta">
                                        <div class="author-info d-flex align-items-center mb-2">
                                            <div class="author-avatar">
                                                <div class="avatar-placeholder">
                                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                <span class="author-name text-white">{{ $post->user->name }}</span>
                                                <div class="post-date text-white opacity-75">
                                                    <small>{{ $post->created_at->format('F d, Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(auth()->id() === $post->user_id)
                                        <div class="post-actions dropdown">
                                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('blog.edit', $post) }}">
                                                        <i class="fas fa-edit me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('blog.destroy', $post) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash-alt me-2"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                <h2 class="card-title h4 text-white mb-3">{{ $post->title }}</h2>
                                
                                <p class="card-text text-white opacity-75 mb-4">
                                    {{ Str::limit($post->content, 150) }}
                                </p>

                                <div class="post-footer d-flex justify-content-between align-items-center">
                                    <a href="{{ route('blog.show', $post) }}" class="btn btn-outline-primary">
                                        Read More <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                    <div class="post-stats text-white opacity-75">
                                        <span class="me-3">
                                            <i class="far fa-comment me-1"></i>
                                            {{ $post->comments->count() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrapper mt-5">
            {{ $posts->links() }}
        </div>
    @else
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon mb-4">
                <i class="fas fa-feather-alt fa-3x text-white opacity-50"></i>
            </div>
            <h3 class="text-white mb-3">No Posts Yet</h3>
            <p class="text-white opacity-75">Be the first to share your thoughts!</p>
            @auth
                <a href="{{ route('blog.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus me-2"></i> Create First Post
                </a>
            @endauth
        </div>
    @endif
</div>

<style>
/* Blog Cards */
.blog-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.blog-card:hover {
    transform: translateY(-4px);
}

.blog-card .card {
    background: linear-gradient(to bottom, #1a1a1a, #141414);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Author Avatar */
.author-avatar {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Post Meta */
.author-name {
    font-size: 0.95rem;
    font-weight: 500;
    display: block;
    line-height: 1.2;
}

.post-date {
    font-size: 0.85rem;
}

/* Card Content */
.card-title {
    font-weight: 600;
    line-height: 1.4;
}

.card-text {
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Buttons */
.btn-primary {
    background-color: #3b82f6;
    border: none;
    padding: 0.5rem 1.25rem;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-outline-primary {
    color: #3b82f6;
    border-color: #3b82f6;
    padding: 0.5rem 1.25rem;
    font-weight: 500;
}

.btn-outline-primary:hover {
    background-color: #3b82f6;
    color: white;
}

/* Dropdown Menu */
.dropdown-menu {
    background-color: #1a1a1a;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.dropdown-item {
    color: #e5e5e5;
    padding: 0.5rem 1rem;
}

.dropdown-item:hover {
    background-color: #242424;
    color: white;
}

/* Pagination */
.pagination {
    --bs-pagination-bg: #1a1a1a;
    --bs-pagination-color: #fff;
    --bs-pagination-border-color: #2d2d2d;
    --bs-pagination-hover-bg: #242424;
    --bs-pagination-hover-color: #fff;
    --bs-pagination-hover-border-color: #3b82f6;
    --bs-pagination-active-bg: #3b82f6;
    --bs-pagination-active-border-color: #3b82f6;
    --bs-pagination-disabled-bg: #1a1a1a;
    --bs-pagination-disabled-color: #6c757d;
    --bs-pagination-disabled-border-color: #2d2d2d;
}

/* Empty State */
.empty-state-icon {
    color: #3b82f6;
    opacity: 0.5;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .blog-header {
        text-align: center;
    }
    
    .blog-header .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection 