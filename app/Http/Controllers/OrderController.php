<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product; // Need this to verify product details
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // We will use DB transactions

class OrderController extends Controller
{
    /**
     * Display a list of the authenticated user's orders.
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')
                       ->where('user_id', Auth::id())
                       ->latest()
                       ->get();
                       
        return view('orders.index', compact('orders'));
    }

    /**
     * Process the checkout form and store a new order.
     */
    public function store(Request $request)
    {
        // 1. Validation of Checkout Data
        $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
            'delivery_method' => 'required|in:standard,express,pickup',
            'payment_method' => 'required|in:transfer,cod',
            'shipping_cost' => 'required|numeric|min:0',
            'cart_subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);
        
        $cartItems = session()->get('cart', []);

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Cannot checkout with an empty cart.');
        }

        // 2. Database Transaction for Safety
        DB::transaction(function () use ($request, $cartItems) {
            
            // A. Create the main Order record
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'Pending Payment', // Initial status
                'delivery_address' => $request->address . ', ' . $request->city,
                'delivery_method' => $request->delivery_method,
                'payment_method' => $request->payment_method,
                'total_subtotal' => $request->cart_subtotal,
                'shipping_cost' => $request->shipping_cost,
                'total_amount' => $request->total_amount,
            ]);

            // B. Create Order Items and mark products as sold
            foreach ($cartItems as $productId => $itemData) {
                $product = Product::find($productId);

                if ($product && !$product->is_sold) {
                    // Create the Order Item record (assuming 1 quantity for used goods)
                    $order->orderItems()->create([
                        'product_id' => $productId,
                        'quantity' => 1, 
                        'unit_price' => $itemData['price'],
                        'subtotal' => $itemData['price'] * 1,
                    ]);
                    
                    // Mark the product as sold to prevent double-selling
                    $product->update(['is_sold' => true]);
                }
            }

            // C. Clear the session cart
            session()->forget('cart');
        });

        // 3. Success Redirect
        return redirect()->route('orders.index')->with('success', 'Order placed successfully! Please check the status below.');
    }
    
    // Placeholder for other OrderController methods (show, update, etc.)
}
