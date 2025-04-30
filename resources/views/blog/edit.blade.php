@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container py-4">
    <div class="card bg-dark">
        <div class="card-body">
            <h1 class="card-title text-white mb-4">Edit Post</h1>

            <form method="POST" action="{{ route('blog.update', $post) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="title" class="form-label text-white">Title</label>
                    <input type="text" 
                           class="form-control bg-dark text-white border-secondary @error('title') is-invalid @enderror" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $post->title) }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label text-white">Content</label>
                    <textarea class="form-control bg-dark text-white border-secondary @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="10" 
                              required>{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('blog.show', $post) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control {
    background-color: #1a1a1a !important;
    border-color: #2d2d2d !important;
    color: #fff !important;
}

.form-control:focus {
    background-color: #2d2d2d !important;
    border-color: #3d3d3d !important;
    box-shadow: none !important;
    color: #fff !important;
}

.btn-primary {
    background-color: #3b82f6;
    border: none;
    padding: 0.5rem 1.25rem;
    font-weight: 500;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #374151;
    border: none;
    padding: 0.5rem 1.25rem;
    font-weight: 500;
}

.btn-secondary:hover {
    background-color: #4b5563;
}
</style>
@endsection 