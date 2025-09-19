<?php
// App/Http/Controllers/OrderController.php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if ($product->is_sold) {
            return back()->with('error', 'This product is already sold.');
        }

        if ($product->user_id === Auth::id()) {
            return back()->with('error', 'You cannot buy your own product.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:500'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'total_amount' => $product->price * $validated['quantity'],
            'shipping_address' => $validated['shipping_address'],
            'status' => 'pending'
        ]);

        // Mark product as sold
        $product->update(['is_sold' => true]);

        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('product', 'product.user');
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('product')->latest()->get();
        return view('orders.index', compact('orders'));
    }
}