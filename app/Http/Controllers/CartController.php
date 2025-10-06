<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Helper function to build cart items and calculate total.
     */
    protected function getCartData()
    {
        // Retrieve the cart data from the session
        $cart = session()->get('cart', []);
        $cartItems = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if ($product) {
                // For simplicity, we assume quantity is always 1 for used goods
                $quantity = 1; 
                $subtotal = $product->price * $quantity;
                $totalPrice += $subtotal;

                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
            } else {
                // If a product was deleted, clean it from the session
                // We use the ID stored in the session for reliable removal
                unset($cart[$productId]); 
                session()->put('cart', $cart);
            }
        }
        return ['cartItems' => $cartItems, 'totalPrice' => $totalPrice];
    }
    
    /**
     * Display the contents of the shopping cart.
     */
    public function index()
    {
        $data = $this->getCartData();
        return view('cart.index', $data);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        // Get the current cart from the session, default to empty array
        $cart = session()->get('cart', []);
        
        $productId = $product->id;
        
        // For used goods, we only allow one unit of a specific item
        if (isset($cart[$productId])) {
            return back()->with('warning', 'This item is already in your cart (used items are unique)!');
        } else {
            // Add the new item to the cart session
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->title,
                'price' => $product->price,
                'quantity' => 1, // Always 1 for unique used items
            ];
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Product $product)
    {
        $cart = session()->get('cart');

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart.');
    }
    
    /**
     * Display the checkout form.
     */
    public function checkout()
    {
        // Get the cart items and total price
        $data = $this->getCartData();
        
        // Check if the cart is empty before proceeding
        if (empty($data['cartItems'])) {
             return redirect()->route('cart.index')->with('warning', 'Your cart is empty! Add items first.');
        }
        
        // Pass cart data to the checkout view
        return view('cart.checkout', $data); 
    }
}
