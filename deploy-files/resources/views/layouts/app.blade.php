<!DOCTYPE html>
<html lang="en" class="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Assignment')</title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Custom CSS - Make sure this comes AFTER Bootstrap -->
        <style>
            :root {
                --dark-bg: #121212;
                --darker-bg: #0a0a0a;
                --card-bg: #1e1e1e;
                --text-primary: #e0e0e0;
                --text-secondary: #a0a0a0;
                --accent-color: #3b82f6;
                --accent-hover: #2563eb;
                --danger: #ef4444;
                --success: #10b981;
                --border-color: #2e2e2e;
            }

            body {
                background-color: var(--dark-bg);
                color: var(--text-primary);
                min-height: 100vh;
            }

            /* Navbar Styles */
            .navbar {
                background-color: var(--darker-bg, #111827) !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.75rem 0;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .navbar-brand, .nav-link {
                color: #9ca3af !important;
            }

            .nav-link:hover {
                color: #ffffff !important;
                background-color: rgba(59, 130, 246, 0.1);
                transform: translateY(-1px);
            }

            .nav-link.active {
                color: #3b82f6 !important;
                background-color: rgba(59, 130, 246, 0.1);
            }

            /* Icons in nav links */
            .nav-link i {
                margin-right: 0.5rem;
                font-size: 0.9rem;
                opacity: 0.8;
            }

            /* Cart link specific styles */
            .cart-link {
                position: relative;
            }

            .cart-link i {
                font-size: 1.1rem;
            }

            /* Dropdown styles */
            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.5rem 0;
                margin-top: 0.5rem;
                min-width: 180px;
            }

            .dropdown-item {
                color: #e5e5e5;
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                transition: background-color 0.2s ease;
            }

            .dropdown-item:hover {
                background-color: #2d2d2d;
                color: #ffffff;
            }

            .dropdown-item i {
                font-size: 0.9rem;
                width: 16px;
                text-align: center;
            }

            /* Remove the hover display */
            .nav-item.dropdown:hover .dropdown-menu {
                display: none;
            }

            /* Style for the user menu toggle */
            .user-menu {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
            }

            .user-menu:after {
                display: none; /* Remove default caret */
            }

            /* Add a custom caret */
            .user-menu i.fas.fa-user {
                margin-right: 0.5rem;
            }

            /* Style for the form button */
            form .dropdown-item {
                border: none;
                width: 100%;
                text-align: left;
                background: none;
                cursor: pointer;
            }

            /* Ensure proper spacing between items */
            .dropdown-menu li:not(:last-child) {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            /* Mobile navbar toggler */
            .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.1);
                padding: 0.5rem;
            }

            .navbar-toggler:focus {
                box-shadow: none;
                border-color: #3b82f6;
            }

            /* Responsive adjustments */
            @media (max-width: 991.98px) {
                .navbar-collapse {
                    padding: 1rem 0;
                }
                
                .nav-link {
                    padding: 0.75rem 1rem !important;
                }
                
                .navbar-nav {
                    padding: 0.5rem 0;
                }
            }

            /* Optional: Add a subtle gradient to the navbar */
            .navbar::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(
                    90deg,
                    rgba(59, 130, 246, 0) 0%,
                    rgba(59, 130, 246, 0.2) 50%,
                    rgba(59, 130, 246, 0) 100%
                );
            }

            /* Card Styles */
            .card {
                background-color: var(--card-bg);
                border: 1px solid var(--border-color);
                border-radius: 12px;
            }

            .card-title {
                color: var(--text-primary);
            }

            .card-text {
                color: var(--text-secondary);
            }

            /* Button Styles */
            .btn-primary {
                background-color: var(--accent-color);
                border: none;
            }

            .btn-primary:hover {
                background-color: var(--accent-hover);
            }

            .btn-outline-primary {
                color: var(--accent-color);
                border-color: var(--accent-color);
            }

            .btn-outline-primary:hover {
                background-color: var(--accent-color);
                color: var(--text-primary);
            }

            /* Form Controls */
            .form-control {
                background-color: var(--darker-bg);
                border: 1px solid var(--border-color);
                color: var(--text-primary);
            }

            .form-control:focus {
                background-color: var(--darker-bg);
                border-color: var(--accent-color);
                color: var(--text-primary);
            }

            /* Featured Products */
            .featured-products {
                margin-top: 3rem;
            }

            .product-card {
                transition: transform 0.2s ease;
            }

            .product-card:hover {
                transform: translateY(-5px);
            }

            .product-image {
                height: 200px;
                object-fit: cover;
                border-radius: 8px 8px 0 0;
            }

            .price-tag {
                color: var(--accent-color);
                font-size: 1.25rem;
                font-weight: 600;
            }

            .stock-badge {
                position: absolute;
                top: 10px;
                right: 10px;
                background-color: rgba(16, 185, 129, 0.2);
                color: #10b981;
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            /* Welcome Section */
            .welcome-section {
                text-align: center;
                padding: 4rem 0;
            }

            .welcome-heading {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                background: linear-gradient(45deg, var(--text-primary), var(--accent-color));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Table Styles */
            .table {
                color: var(--text-primary);
            }

            .table td, .table th {
                border-color: var(--border-color);
            }

            /* Alert Styles */
            .alert-success {
                background-color: rgba(16, 185, 129, 0.1);
                border-color: var(--success);
                color: var(--success);
            }

            .alert-danger {
                background-color: rgba(239, 68, 68, 0.1);
                border-color: var(--danger);
                color: var(--danger);
            }

            /* Logo Styles */
            .navbar-brand.logo-text {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                font-weight: 700;
                font-size: 1.5rem;
                letter-spacing: -0.5px;
                color: #ffffff !important;
                text-decoration: none;
                transition: all 0.3s ease;
                padding: 0.5rem 0;
            }

            .logo-dot {
                color: #3b82f6;
                font-size: 2rem;
                position: relative;
                top: -2px;
            }

            .user-btn {
                background: none;
                border: none;
                color: #9ca3af;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                transition: color 0.2s ease;
            }

            .user-btn:hover {
                color: #ffffff;
            }

            .user-btn:focus {
                outline: none;
            }

            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.25rem 0;
                min-width: 160px;
            }

            .dropdown-item {
                color: #e5e5e5;
                padding: 0.5rem 1rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                transition: background-color 0.2s ease;
                font-size: 0.9rem;
            }

            .dropdown-item:hover {
                background-color: #2d2d2d;
                color: #ffffff;
            }

            .dropdown-item i {
                font-size: 0.9rem;
                width: 16px;
            }

            /* Remove default dropdown arrow */
            .dropdown-toggle::after {
                display: none;
            }

            /* Style for the form button */
            form .dropdown-item {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
            }

            /* Navbar items styling */
            .navbar-nav .nav-item {
                position: relative;
            }

            /* Cart link */
            .cart-link {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                color: #9ca3af !important;
                transition: color 0.2s ease;
            }

            .cart-link:hover {
                color: #ffffff !important;
            }

            /* User menu button */
            .user-menu {
                background: none;
                border: none;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                color: #9ca3af;
                transition: color 0.2s ease;
                width: 100%;
                text-align: left;
            }

            .user-menu:hover,
            .user-menu:focus {
                color: #ffffff;
            }

            .user-menu::after {
                display: none;
            }

            /* Dropdown menu */
            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.5rem 0;
                margin-top: 0.5rem;
                min-width: 200px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .dropdown-menu-end {
                right: 0;
                left: auto;
            }

            /* Dropdown items */
            .dropdown-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                color: #e5e5e5;
                transition: background-color 0.2s ease;
                font-size: 0.9rem;
            }

            .dropdown-item:hover {
                background-color: #2d2d2d;
                color: #ffffff;
            }

            .dropdown-item i {
                width: 16px;
                text-align: center;
                font-size: 0.9rem;
            }

            /* Dropdown divider */
            .dropdown-divider {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin: 0.5rem 0;
            }

            /* Form inside dropdown */
            .dropdown-form {
                margin: 0;
                padding: 0;
            }

            .dropdown-form .dropdown-item {
                border: none;
                width: 100%;
                background: none;
                cursor: pointer;
                text-align: left;
            }

            /* Fix for mobile */
            @media (max-width: 991.98px) {
                .navbar-nav {
                    padding: 1rem 0;
                }
                
                .dropdown-menu {
                    border: none;
                    background-color: transparent;
                    padding-left: 1rem;
                    box-shadow: none;
                }
                
                .dropdown-item {
                    padding: 0.5rem 1rem;
                }
                
                .dropdown-divider {
                    display: none;
                }
            }

            /* Navbar and dropdown styles */
            .navbar-nav .dropdown-toggle::after {
                display: none;
            }

            .dropdown-menu {
                min-width: 200px;
                margin-top: 0.5rem;
                padding: 0.5rem 0;
                background: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
            }

            .dropdown-item {
                color: #e5e5e5;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
                display: flex;
                align-items: center;
            }

            .dropdown-item:hover {
                background-color: #2d2d2d;
                color: #ffffff;
            }

            .dropdown-divider {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin: 0.25rem 0;
            }

            /* Form button reset */
            .dropdown-item[type="submit"] {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
            }

            /* Navbar link styles */
            .nav-link {
                color: #9ca3af !important;
                transition: color 0.2s ease;
                padding: 0.5rem 1rem !important;
            }

            .nav-link:hover {
                color: #ffffff !important;
            }

            /* Icon alignment */
            .nav-link i {
                font-size: 1rem;
            }

            /* Mobile adjustments */
            @media (max-width: 991.98px) {
                .dropdown-menu {
                    margin-top: 0;
                    border: none;
                    background-color: transparent;
                    box-shadow: none;
                }
                
                .dropdown-item {
                    padding: 0.75rem 1rem;
                }
            }

            /* Dropdown Fixes */
            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                margin-top: 0.5rem;
                min-width: 200px;
                padding: 0.5rem 0;
            }

            .dropdown-item {
                color: #e5e5e5 !important;
                padding: 0.75rem 1.25rem;
                transition: background-color 0.15s ease;
            }

            .dropdown-item:hover, 
            .dropdown-item:focus {
                background-color: #2d2d2d;
                color: #ffffff !important;
            }

            .dropdown-divider {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin: 0.5rem 0;
            }

            /* Fix button styling in dropdown */
            .logout-form button.dropdown-item {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
            }

            /* Ensure dropdown stays visible while interacting */
            .dropdown-menu.show {
                display: block !important;
                opacity: 1 !important;
            }

            /* Increase clickable area */
            .nav-item.dropdown {
                padding: 0.25rem 0;
            }

            .user-menu {
                padding: 0.5rem 1rem !important;
                display: flex !important;
                align-items: center;
                gap: 0.5rem;
            }

            /* Remove any unwanted transitions */
            .dropdown-menu {
                transition: none !important;
                animation: none !important;
            }

            /* Navbar items styling */
            .navbar-nav .nav-link {
                color: #9ca3af !important;
                padding: 0.5rem 1rem !important;
                display: flex !important;
                align-items: center;
                transition: color 0.15s ease;
            }

            .navbar-nav .nav-link:hover {
                color: #ffffff !important;
            }

            /* Cart link specific styling */
            .nav-item .nav-link i {
                font-size: 1rem;
            }

            /* Dropdown menu styling */
            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                margin-top: 0.5rem;
                min-width: 200px;
                padding: 0.5rem 0;
            }

            .dropdown-item {
                color: #e5e5e5 !important;
                padding: 0.75rem 1.25rem;
                transition: background-color 0.15s ease;
            }

            .dropdown-item:hover, 
            .dropdown-item:focus {
                background-color: #2d2d2d;
                color: #ffffff !important;
            }

            .dropdown-divider {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin: 0.5rem 0;
            }

            /* User menu button styling */
            .user-menu {
                color: #9ca3af !important;
                padding: 0.5rem 1rem !important;
                display: flex !important;
                align-items: center;
                cursor: pointer;
            }

            .user-menu:hover {
                color: #ffffff !important;
            }

            .user-menu:focus {
                outline: none !important;
                box-shadow: none !important;
            }

            /* Fix button styling in dropdown */
            .logout-form button.dropdown-item {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                cursor: pointer;
            }

            /* Ensure consistent spacing */
            .navbar-nav {
                gap: 0.5rem;
            }

            /* Ensure consistent icon sizes */
            .nav-link i,
            .dropdown-item i {
                width: 1.25rem;
                text-align: center;
                font-size: 1rem;
            }

            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                min-width: 180px;
                margin-top: 0.5rem;
                padding: 0.5rem 0;
            }

            .dropdown-item {
                color: #e5e5e5 !important;
                font-size: 0.95rem;
                transition: all 0.2s ease;
            }

            .dropdown-item:hover {
                background-color: #2d2d2d;
                color: #ffffff !important;
            }

            .dropdown-divider {
                border-color: rgba(255, 255, 255, 0.1);
            }

            .dropdown-item i {
                width: 20px;
                text-align: center;
                font-size: 0.9rem;
                opacity: 0.8;
            }

            .dropdown-item:hover i {
                opacity: 1;
            }

            .logout-form {
                margin: 0;
            }

            .logout-form button {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
            }

            /* Clean dropdown styles without any dividers */
            .dropdown-menu {
                background-color: #1a1a1a;
                border: 1px solid rgba(255, 255, 255, 0.1);
                padding: 0.25rem 0;
                margin-top: 0.5rem;
                min-width: 180px;
            }

            .dropdown-item {
                color: #e5e5e5 !important;
                padding: 0.75rem 1.25rem;
                display: flex;
                align-items: center;
                transition: background-color 0.15s ease;
            }

            .dropdown-item:hover {
                background-color: #2d2d2d;
                color: #ffffff !important;
            }

            /* Remove ALL divider styles */
            .dropdown-divider,
            .dropdown-menu li:not(:last-child),
            .dropdown-menu li {
                border: none !important;
                border-bottom: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Form button styling */
            .dropdown-menu form {
                margin: 0;
                padding: 0;
            }

            .dropdown-menu button.dropdown-item {
                width: 100%;
                text-align: left;
                background: none;
                border: none;
                padding: 0.75rem 1.25rem;
            }
        </style>
        @stack('styles')
    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand logo-text" href="{{ route('home') }}">
                    Hajusrakendused<span class="logo-dot">.</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('weather') }}">
                                <i class="fas fa-cloud"></i> Weather
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('maps.index') }}">
                                <i class="fas fa-map-marker-alt"></i> Maps
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('blog.index') }}">
                                <i class="fas fa-blog"></i> Blog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.index') }}">
                                <i class="fas fa-shopping-cart"></i> Shop
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('subjects.index') }}">
                                <i class="fas fa-book"></i> Subjects
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        <!-- Cart Link -->
                        <li class="nav-item me-3">
                            <a class="nav-link d-flex align-items-center" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Cart
                            </a>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle user-menu border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i>
                                {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                            </button>
                            
                            <ul class="dropdown-menu dropdown-menu-end">
                                @auth
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user-circle me-2"></i>
                                            Profile
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('login') }}">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            Login
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('register') }}">
                                            <i class="fas fa-user-plus me-2"></i>
                                            Register
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if (session('success'))
                <div class="container">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container">
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure Bootstrap's JavaScript is loaded
            if (typeof bootstrap !== 'undefined') {
                // Initialize all dropdowns using Bootstrap's native functionality
                var dropdownElementList = document.querySelectorAll('.dropdown-toggle');
                var dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl));
            }
        });
        </script>
        @stack('scripts')
    </body>
</html>
