@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-4">
    <div class="card bg-dark text-white border-0 shadow">
        <div class="card-header bg-dark border-bottom border-secondary">
            <h2 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>
                Shopping Cart
            </h2>
        </div>
        <div class="card-body">
            @if($cartItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" 
                                                    alt="{{ $item->product->name }}" 
                                                    class="cart-product-image me-3">
                                            @else
                                                <div class="cart-product-placeholder me-3">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">
                                                    {{ Str::limit($item->product->description ?? '', 50) }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.update', $item->id) }}" 
                                            method="POST" 
                                            class="d-flex justify-content-center align-items-center quantity-form">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" 
                                                class="btn btn-sm btn-outline-light quantity-btn" 
                                                onclick="updateQuantity(this, -1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                name="quantity" 
                                                value="{{ $item->quantity }}" 
                                                min="1"
                                                class="form-control form-control-sm quantity-input">
                                            <button type="button" 
                                                class="btn btn-sm btn-outline-light quantity-btn" 
                                                onclick="updateQuantity(this, 1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end">
                                        €{{ number_format($item->product->price, 2) }}
                                    </td>
                                    <td class="text-end">
                                        €{{ number_format($item->product->price * $item->quantity, 2) }}
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.remove', $item->id) }}" 
                                            method="POST" 
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to remove this item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top">
                                <td colspan="3" class="text-end">
                                    <strong>Total:</strong>
                                </td>
                                <td class="text-end">
                                    <strong>€{{ number_format($total, 2) }}</strong>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                        Proceed to Checkout<i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .cart-product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .cart-product-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.5);
    }

    .quantity-form {
        width: 120px;
        gap: 8px;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        background: #2c2c2c;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
    }

    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-weight: 500;
    }

    .table td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }
</style>
@endpush

@push('scripts')
<script>
function updateQuantity(button, change) {
    const form = button.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    const newValue = Math.max(1, parseInt(input.value) + change);
    input.value = newValue;
    form.submit();
}
</script>
@endpush
@endsection 