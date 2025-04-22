@extends('layouts.app')

@section('title', 'Hajusrakendused')

@section('content')
<div class="main-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>Welcome to Hajusrakendused</h1>
        <p class="subtitle">Martin Joosep Reiljan</p>
    </div>

    <!-- Main Navigation Cards -->
    <div class="features-grid">
        <a href="{{ route('shop.index') }}" class="feature-card">
            <div class="card-inner">
                <i class="fas fa-shopping-cart icon-blue"></i>
                <h2>Shop</h2>
                <p>Browse our curated collection of premium products</p>
            </div>
        </a>

        <a href="{{ route('blog.index') }}" class="feature-card">
            <div class="card-inner">
                <i class="fas fa-blog icon-blue"></i>
                <h2>Blog</h2>
                <p>Discover insights and latest updates</p>
            </div>
        </a>

        <a href="{{ route('maps.index') }}" class="feature-card">
            <div class="card-inner">
                <i class="fas fa-map-marked-alt icon-blue"></i>
                <h2>Maps</h2>
                <p>Explore locations and navigation</p>
            </div>
        </a>
    </div>

    <!-- Featured Products Section -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <div class="featured-section">
        <h2>Featured Products</h2>
        <div class="products-grid">
            @foreach($featuredProducts as $product)
            <div class="product-card">
                <div class="product-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @if($product->stock > 0)
                        <span class="stock-badge">In Stock</span>
                    @endif
                </div>
                <div class="product-details">
                    <h3>{{ $product->name }}</h3>
                    <p>{{ Str::limit($product->description, 100) }}</p>
                    <div class="product-footer">
                        <span class="price">€{{ number_format($product->price, 2) }}</span>
                        <a href="{{ route('shop.index') }}" class="view-btn">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Hero Section */
    .hero-section {
        text-align: center;
        padding: 3rem 0;
        margin-bottom: 3rem;
        background: var(--card-bg);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .hero-section h1 {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .subtitle {
        font-size: 1.1rem;
        color: var(--text-secondary);
    }

    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 4rem;
    }

    .feature-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 2rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        border-color: var(--accent-color);
    }

    .card-inner {
        text-align: center;
    }

    .icon-blue {
        font-size: 2rem;
        color: #3b82f6;
        margin-bottom: 1rem;
    }

    .card-inner h2 {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .card-inner p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Featured Products */
    .featured-section {
        margin-top: 4rem;
    }

    .featured-section h2 {
        font-size: 1.75rem;
        color: var(--text-primary);
        margin-bottom: 2rem;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .product-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        border-color: var(--accent-color);
    }

    .product-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .stock-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #10b981;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .product-details {
        padding: 1.5rem;
    }

    .product-details h3 {
        color: var(--text-primary);
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .product-details p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .price {
        color: #3b82f6;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .view-btn {
        background: #3b82f6;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
    }

    .view-btn:hover {
        background: #2563eb;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .features-grid, .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 1rem;
        }

        .hero-section {
            padding: 2rem 1rem;
        }

        .hero-section h1 {
            font-size: 2rem;
        }

        .features-grid, .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush 