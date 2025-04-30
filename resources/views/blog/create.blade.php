@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="container py-4">
    <div class="card bg-dark">
        <div class="card-body">
            <h1 class="card-title text-white mb-4">Create New Post</h1>

            <form action="{{ route('blog.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label text-white">Title</label>
                    <input type="text" 
                           class="form-control bg-dark text-white border-secondary @error('title') is-invalid @enderror" 
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
                    <label for="categories" class="form-label text-white">Categories</label>
                    <select name="categories[]" 
                            id="categories" 
                            class="form-select bg-dark text-white border-secondary @error('categories') is-invalid @enderror" 
                            multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="text-white-50">Hold Ctrl/Cmd to select multiple categories</small>
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label text-white">Content</label>
                    <textarea class="form-control bg-dark text-white border-secondary @error('content') is-invalid @enderror" 
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

                <div class="d-flex justify-content-between">
                    <a href="{{ route('blog.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.form-control, .form-select {
    background-color: #1a1a1a !important;
    border-color: #2d2d2d !important;
    color: #fff !important;
}

.form-control:focus, .form-select:focus {
    background-color: #2d2d2d !important;
    border-color: #3d3d3d !important;
    box-shadow: none !important;
    color: #fff !important;
}

.form-select option {
    background-color: #1a1a1a;
    color: #fff;
}

.form-select option:checked {
    background-color: #2d2d2d;
}
</style>
@endsection 