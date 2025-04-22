@extends('layouts.app')

@section('title', 'Favorite Subjects')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white">Favorite Subjects</h1>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Subject
        </a>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @php
            session()->forget('success');
        @endphp
    @endif

    <div class="row g-4">
        @forelse($subjects as $subject)
            <div class="col-md-6 col-lg-4">
                <div class="card bg-dark text-white h-100 shadow-sm hover-card">
                    @if($subject->image)
                        <img src="{{ Storage::url($subject->image) }}" 
                             class="card-img-top object-fit-cover" 
                             style="height: 200px"
                             alt="{{ $subject->title }}">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px">
                            <i class="fas fa-image fa-3x text-white-50"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $subject->title }}</h5>
                        <p class="card-text text-white-50">{{ Str::limit($subject->description, 100) }}</p>
                        <div class="mt-2">
                            <span class="badge bg-primary">{{ $subject->genre }}</span>
                            <span class="badge bg-secondary">{{ $subject->release_year }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-dark border-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-white-50">
                                Added by {{ $subject->user->name }}
                                <span class="ms-2">{{ $subject->created_at->diffForHumans() }}</span>
                            </small>
                            <div class="btn-group">
                                @if(auth()->check() && (auth()->user()->id === $subject->user_id || auth()->user()->is_admin))
                                    <a href="{{ route('subjects.edit', $subject) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('subjects.destroy', $subject) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-white-50 py-5">
                    <i class="fas fa-book fa-3x mb-3"></i>
                    <h4>No subjects added yet</h4>
                    <p>Be the first to add your favorite subject!</p>
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary">
                        Add Subject
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .hover-card {
        transition: transform 0.2s ease-in-out;
    }
    .hover-card:hover {
        transform: translateY(-5px);
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush
@endsection
