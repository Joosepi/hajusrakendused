@extends('layouts.app')

@section('title', 'Blog')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar with search and categories -->
        <div class="col-md-3">
            <!-- Search Card -->
            <div class="sidebar-card mb-4">
                <h5 class="text-white mb-3">Search</h5>
                <form action="{{ route('blog.index') }}" method="GET">
                    <input type="text" 
                           name="search" 
                           class="form-control custom-input" 
                           value="{{ request('search') }}" 
                           placeholder="Search posts...">
                </form>
            </div>

            <!-- Categories Card -->
            <div class="sidebar-card">
                <h5 class="text-white mb-3">Categories</h5>
                <div class="category-list">
                    @foreach($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                           class="category-item">
                            {{ $category->name }}
                            <span class="category-count">{{ $category->posts->count() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-white mb-2">Blog Posts</h1>
                    <p class="text-white-50">Share your thoughts and experiences</p>
                </div>
                @auth
                    <a href="{{ route('blog.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> New Post
                    </a>
                @endauth
            </div>

            @if($posts->count() > 0)
                <div class="row g-4">
                    @foreach($posts as $post)
                        <div class="col-md-6">
                            <article class="blog-card">
                                <div class="card bg-dark h-100">
                                    <div class="card-body">
                                        <!-- Categories display -->
                                        <div class="mb-3">
                                            @foreach($post->categories as $category)
                                                <span class="badge bg-primary me-2">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>

                                        <h2 class="card-title h4 text-white mb-3">{{ $post->title }}</h2>
                                        
                                        <p class="card-text text-white-50 mb-4">
                                            {{ Str::limit($post->description, 150) }}
                                        </p>

                                        <div class="post-footer d-flex justify-content-between align-items-center">
                                            <a href="{{ route('blog.show', $post) }}" class="btn btn-outline-primary">
                                                Read More <i class="fas fa-arrow-right ms-2"></i>
                                            </a>
                                            <div class="post-stats text-white-50">
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
    </div>
</div>

<style>
/* Updated Dark theme styles */
.form-control {
    background-color: #212529 !important;
    border-color: #495057 !important;
    color: #fff !important;
}

.form-control:focus {
    background-color: #212529 !important;
    border-color: #6c757d !important;
    box-shadow: 0 0 0 0.2rem rgba(130, 138, 145, 0.25) !important;
    color: #fff !important;
}

.category-item {
    transition: all 0.2s ease;
    text-decoration: none;
}

.category-item:hover {
    background-color: #2c3034 !important;
    border-color: #6c757d !important;
    transform: translateY(-2px);
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Ensure text is visible */
.text-white {
    color: #fff !important;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.5) !important;
}

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

/* Sidebar Cards */
.sidebar-card {
    background-color: #1a1a1a;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Custom Input */
.custom-input {
    background-color: #2d2d2d !important;
    border: none !important;
    color: #fff !important;
    border-radius: 8px !important;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
}

.custom-input:focus {
    background-color: #333 !important;
    box-shadow: none !important;
    outline: none;
}

.custom-input::placeholder {
    color: #6c757d;
}

/* Category List */
.category-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background-color: #2d2d2d;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.category-item:hover {
    background-color: #333;
    transform: translateY(-2px);
    color: #fff;
    text-decoration: none;
}

.category-count {
    background-color: #1a1a1a;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    color: #6c757d;
}
</style>
@endsection 