@extends('layouts.app')

@section('title', 'Favorite Subjects')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="text-white display-4 fw-bold">Favorite Subjects</h1>
        <br>
        <p><a href="https://tak22reiljan.itmajakas.ee/subjects">https://tak22reiljan.itmajakas.ee/subjects</a></p>
        <a href="{{ route('subjects.create') }}" class="btn btn-dark btn-lg rounded-pill border-light">
            <i class="fas fa-plus-circle me-2"></i> Add New Subject
        </a>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($subjects as $subject)
            <div class="col-md-6 col-lg-4">
                <div class="card bg-dark border-0 rounded-4 shadow-lg h-100 subject-card overflow-hidden">
                    <div class="position-relative">
                        @if($subject->image)
                            <img src="{{ Storage::url($subject->image) }}" 
                                 class="card-img-top object-fit-cover" 
                                 style="height: 250px; width: 100%"
                                 alt="{{ $subject->title }}">
                        @else
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                                 style="height: 250px; background: linear-gradient(45deg, #2c3e50, #3498db);">
                                <i class="fas fa-image fa-3x text-white-50"></i>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 p-3">
                            <div class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill">
                                {{ $subject->genre }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body position-relative">
                        <h3 class="card-title h4 mb-3 text-white">{{ $subject->title }}</h3>
                        <p class="card-text text-white-50">{{ Str::limit($subject->description, 120) }}</p>
                        
                        <div class="mt-3 d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill px-3">{{ $subject->release_year }}</span>
                            <span class="badge bg-secondary rounded-pill px-3">
                                <i class="fas fa-clock me-1"></i>
                                {{ $subject->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-dark border-secondary py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($subject->user->name) }}&background=random" 
                                     class="rounded-circle me-2" 
                                     alt="{{ $subject->user->name }}"
                                     width="30" height="30">
                                <small class="text-white-50">{{ $subject->user->name }}</small>
                            </div>
                            @if(auth()->check() && (auth()->user()->id === $subject->user_id || auth()->user()->is_admin))
                                <div class="btn-group">
                                    <a href="{{ route('subjects.edit', $subject) }}" 
                                       class="btn btn-outline-light btn-sm">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('subjects.destroy', $subject) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm ms-2">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-white-50 py-5">
                    <div class="mb-4">
                        <i class="fas fa-book-open fa-4x mb-3 text-primary"></i>
                        <h2 class="h3">No subjects added yet</h2>
                        <p class="lead">Be the first to add your favorite subject!</p>
                    </div>
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-plus-circle me-2"></i> Add Subject
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
.subject-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.subject-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.2);
}

.object-fit-cover {
    object-fit: cover;
}

.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.card-img-top {
    transition: all 0.3s ease;
}

.subject-card:hover .card-img-top {
    transform: scale(1.05);
}

.rounded-4 {
    border-radius: 1rem !important;
}

.btn-dark.border-light {
    background: #000;
    transition: all 0.3s ease;
}

.btn-dark.border-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.15);
    background: #111;
}

.display-4 {
    font-size: 2.5rem;
    color: #fff;
}
</style>
@endpush
@endsection
