@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <h3 class="mb-0">Checkout</h3>
                </div>
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="mb-4">
                        <h4>Order Summary</h4>
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                {{ $item->product->name }} x {{ $item->quantity }}
                            </div>
                            <div>
                                €{{ number_format($item->product->price * $item->quantity, 2) }}
                            </div>
                        </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Total:</strong>
                            <strong>€{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="mb-4">
                        <h4>Customer Information</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" id="firstName" class="form-control bg-darker text-white" value="{{ auth()->user()->name }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" id="lastName" class="form-control bg-darker text-white" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control bg-darker text-white" value="{{ auth()->user()->email }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" id="phone" class="form-control bg-darker text-white" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <form id="payment-form" class="mt-4">
                        <div class="mb-3">
                            <label for="card-element" class="form-label">Credit or debit card</label>
                            <div id="card-element" class="form-control bg-darker">
                                <!-- Stripe Element will be inserted here -->
                            </div>
                            <div id="card-errors" class="invalid-feedback d-block" role="alert"></div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="submit-button">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Pay €{{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-darker {
    background-color: rgba(0, 0, 0, 0.2) !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.form-control {
    color: #fff !important;
}

.form-control:focus {
    background-color: rgba(0, 0, 0, 0.3) !important;
    border-color: #4f46e5;
    color: #fff;
}

.StripeElement {
    padding: 12px;
    border-radius: 4px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background-color: rgba(0, 0, 0, 0.2);
}

.StripeElement--focus {
    border-color: #4f46e5;
}

.StripeElement--invalid {
    border-color: #dc2626;
}
</style>
@endpush

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ config('services.stripe.key') }}');
const elements = stripe.elements();
const clientSecret = '{{ $clientSecret }}';

// Create card Element
const cardElement = elements.create('card', {
    style: {
        base: {
            color: '#fff',
            fontFamily: '"Segoe UI", system-ui, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#6b7280'
            }
        },
        invalid: {
            color: '#fca5a5',
            iconColor: '#fca5a5'
        }
    }
});

// Mount the card Element
cardElement.mount('#card-element');

// Handle form submission
const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');
const spinner = submitButton.querySelector('.spinner-border');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    submitButton.disabled = true;
    spinner.classList.remove('d-none');

    // Get customer information
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    try {
        const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: `${firstName} ${lastName}`,
                    email: email,
                    phone: phone
                }
            }
        });

        if (error) {
            throw new Error(error.message);
        }

        // Payment successful, process the order
        const response = await fetch('{{ route('checkout.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                payment_intent: paymentIntent.id,
                customer: {
                    first_name: firstName,
                    last_name: lastName,
                    email: email,
                    phone: phone
                }
            })
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = result.redirect;
        } else {
            throw new Error(result.message);
        }

    } catch (error) {
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
        submitButton.disabled = false;
        spinner.classList.add('d-none');
    }
});
</script>
@endpush
@endsection 