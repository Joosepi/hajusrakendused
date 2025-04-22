@extends('layouts.app')

@section('title', 'Shop')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-white">
        <i class="fas fa-gamepad me-2"></i>Gaming & Streaming Gear
    </h1>

    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 product-card bg-dark border-0">
                <div class="card-img-wrapper">
                    <span class="badge bg-success position-absolute m-3">In Stock</span>
                    <img src="{{ asset($product->image_url) }}" 
                         class="card-img-top p-4" 
                         alt="{{ $product->name }}"
                         onerror="this.src='{{ asset('images/products/placeholder.jpg') }}'">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-white">{{ $product->name }}</h5>
                    <p class="card-text text-white mb-3">{{ $product->description }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="price">€{{ number_format($product->price, 2) }}</span>
                            <span class="stock text-white-50">Stock: {{ $product->stock }}</span>
                        </div>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="d-flex gap-2">
                                <input type="number" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->stock }}" 
                                       class="form-control form-control-sm quantity-input">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
    background: linear-gradient(145deg, #1a1a1a, #242424) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

.card-img-wrapper {
    position: relative;
    background: #141414;
    border-radius: 10px 10px 0 0;
    overflow: hidden;
}

.card-img-top {
    height: 200px;
    object-fit: contain;
    background: #141414;
    transition: transform 0.3s;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.price {
    font-size: 1.25rem;
    font-weight: 600;
    color: #3b82f6;
}

.quantity-input {
    width: 80px;
    background-color: #1a1a1a !important;
    border-color: #2d2d2d !important;
    color: #fff !important;
}

.quantity-input:focus {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
}

.btn-primary {
    background: linear-gradient(145deg, #3b82f6, #2563eb);
    border: none;
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .card-img-top {
        height: 180px;
    }
}

.card-text {
    color: rgba(255, 255, 255, 0.9) !important; /* Slightly dimmed white for better readability */
}

.stock {
    color: rgba(255, 255, 255, 0.7) !important; /* Slightly dimmed white for stock text */
}
</style>
@endsection 