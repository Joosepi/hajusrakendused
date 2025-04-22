@extends('layouts.app')

@section('title', 'Order Successful')

@section('content')
<div class="container py-4">
    <div class="card bg-dark text-white">
        <div class="card-body text-center">
            <div class="mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
            </div>
            <h2 class="mb-4">Thank You for Your Order!</h2>
            <p class="mb-4">Your order has been successfully processed.</p>
            
            <!-- Customer Information -->
            <div class="mb-4">
                <h4>Customer Information</h4>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <table class="table table-dark">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $order->customer_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $order->customer_email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $order->customer_phone }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="mb-4">
                <h4>Order Items</h4>
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>€{{ number_format($item->price, 2) }}</td>
                                <td>€{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Order Total:</th>
                                <th>€{{ number_format($order->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('shop.index') }}" class="btn btn-primary">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 