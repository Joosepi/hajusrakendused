@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
    <div class="blog-post card bg-dark">
        <div class="card-body">
            <h1 class="text-light mb-4">{{ $post->title }}</h1>
            <div class="text-secondary mb-3">
                Posted on {{ $post->created_at->format('F d, Y') }}
            </div>
            <div class="blog-content text-light">
                {!! $post->content !!}
            </div>
            
            <div class="mt-4">
                @auth
                    @if(auth()->user()->isAdmin() || auth()->id() === $post->user_id)
                        <div class="d-flex gap-2">
                            <a href="{{ route('blog.edit', $post) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('blog.destroy', $post) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section mt-4">
        <div class="card bg-dark">
            <div class="card-body">
                <h3 class="text-light mb-4">Comments</h3>
                
                @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control bg-darker text-light" 
                                rows="3" placeholder="Add a comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fas fa-comment me-1"></i> Add Comment
                    </button>
                </form>
                @endauth

                <div class="comments-list">
                    @foreach($post->comments as $comment)
                        <div class="comment card bg-darker mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="comment-content text-light">
                                        {{ $comment->content }}
                                    </div>
                                    @auth
                                        <form action="{{ route('comments.destroy', $comment) }}" 
                                              method="POST" 
                                              class="ms-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Delete this comment?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endauth
                                </div>
                                <div class="text-secondary mt-2 small">
                                    Posted on {{ $comment->created_at->format('F d, Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-darker {
    background-color: #1a1a1a !important;
    border-color: #2d2d2d !important;
}

.card {
    border: 1px solid #2d2d2d;
}

.form-control {
    background-color: #1a1a1a;
    border: 1px solid #2d2d2d;
    color: #fff;
}

.form-control:focus {
    background-color: #1a1a1a;
    border-color: #3b82f6;
    color: #fff;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
}

.comment {
    transition: transform 0.2s ease;
}

.comment:hover {
    transform: translateY(-2px);
}
</style>
@endsection 