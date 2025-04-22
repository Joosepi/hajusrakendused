@extends('layouts.app')

@section('title', 'Edit Subject')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h4 class="mb-0">Edit Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('subjects.update', $subject) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" 
                                   class="form-control bg-dark text-white @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $subject->title) }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control bg-dark text-white @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      required>{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <input type="text" 
                                   class="form-control bg-dark text-white @error('genre') is-invalid @enderror" 
                                   id="genre" 
                                   name="genre" 
                                   value="{{ old('genre', $subject->genre) }}" 
                                   required>
                            @error('genre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="release_year" class="form-label">Release Year</label>
                            <input type="number" 
                                   class="form-control bg-dark text-white @error('release_year') is-invalid @enderror" 
                                   id="release_year" 
                                   name="release_year" 
                                   value="{{ old('release_year', $subject->release_year) }}" 
                                   min="1900" 
                                   max="{{ date('Y') + 1 }}" 
                                   required>
                            @error('release_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            @if($subject->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($subject->image) }}" 
                                         alt="Current image" 
                                         class="img-thumbnail" 
                                         style="height: 100px">
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control bg-dark text-white @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Subject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
