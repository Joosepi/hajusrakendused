<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Exception;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('shop.index', compact('products'));
    }

    public function addToCart(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to add items to cart');
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // If item exists, increment quantity
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            // If item doesn't exist, create new cart item
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function cart()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.cart', compact('cartItems', 'total'));
    }

    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    public function removeFromCart($id)
    {
        CartItem::where('user_id', auth()->id())
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Initialize Stripe with test key
        Stripe::setApiKey(config('services.stripe.secret'));

        // Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => $total * 100, // Stripe expects amounts in cents
            'currency' => 'eur',
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => auth()->id(),
            ],
        ]);

        return view('shop.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function processCheckout(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get cart items
            $cartItems = CartItem::with('product')
                ->where('user_id', auth()->id())
                ->get();

            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create order with customer details
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'completed',
                'payment_intent_id' => $request->payment_intent,
                'customer_name' => $request->customer['first_name'] . ' ' . $request->customer['last_name'],
                'customer_email' => $request->customer['email'],
                'customer_phone' => $request->customer['phone'],
            ]);

            // Add order items
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            // Clear cart
            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('shop.order.success', $order->id)
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function orderSuccess($orderId)
    {
        $order = Order::with(['items.product'])->findOrFail($orderId);
        return view('shop.order-success', compact('order'));
    }
} 