@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="container py-4">
    <div class="card bg-dark">
        <div class="card-body">
            <h1 class="card-title text-light mb-4">Create New Post</h1>

            <form action="{{ route('blog.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label text-light">Title</label>
                    <input type="text" 
                           class="form-control bg-darker text-light @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label text-light">Content</label>
                    <textarea class="form-control bg-darker text-light @error('content') is-invalid @enderror" 
                              id="content" 
                              name="content" 
                              rows="10" 
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Post
                    </button>
                    <a href="{{ route('blog.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>
            </form>
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
    background-color: #1a1a1a !important;
    border: 1px solid #2d2d2d !important;
    color: #fff !important;
}

.form-control:focus {
    background-color: #1a1a1a !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
}

.form-label {
    color: #a0a0a0;
    margin-bottom: 0.5rem;
}

.invalid-feedback {
    color: #ef4444;
}
</style>
@endsection 