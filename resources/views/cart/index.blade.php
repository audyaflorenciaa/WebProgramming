@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">

        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">
            Your Shopping Cart
        </h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        @if (empty($cartItems))
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 p-12 text-center rounded-lg shadow-inner">
                <i class="fas fa-shopping-cart text-4xl text-gray-400 mb-4"></i>
                <p class="text-xl font-medium text-gray-600">Your cart is empty.</p>
                <p class="text-gray-500 mt-2">Go back to the <a href="{{ route('home') }}" class="text-blue-600 hover:underline">homepage</a> to start shopping!</p>
            </div>
        @else
            <!-- Cart Items List -->
            <div class="bg-white shadow-lg rounded-lg divide-y divide-gray-200">
                @foreach ($cartItems as $item)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Image Thumbnail -->
                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                @php
                                    $firstImage = is_array($item['product']->images) && count($item['product']->images) > 0 
                                        ? asset('storage/' . $item['product']->images[0])
                                        : 'https://placehold.co/64x64/e0e0e0/555555?text=Img';
                                @endphp
                                <img src="{{ $firstImage }}" alt="{{ $item['product']->title }}" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Product Details -->
                            <div>
                                <a href="{{ route('products.show', $item['product']->id) }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600">
                                    {{ $item['product']->title }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ $item['product']->category->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-6">
                            <!-- Price -->
                            <p class="text-xl font-bold text-green-600">
                                IDR {{ number_format($item['product']->price, 0, ',', '.') }}
                            </p>
                            
                            <!-- Remove Button -->
                            <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 transition duration-150">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Summary and Checkout -->
            <div class="mt-8 p-6 bg-white shadow-lg rounded-lg border-t-4 border-indigo-600">
                <div class="flex justify-between items-center text-xl font-bold mb-4">
                    <span>Total Items:</span>
                    <span>{{ count($cartItems) }}</span>
                </div>
                <div class="flex justify-between items-center text-2xl font-extrabold text-indigo-600">
                    <span>Cart Subtotal:</span>
                    <span>IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="mt-6 w-full block text-center bg-indigo-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition duration-150 shadow-md">
                    Proceed to Checkout ({{ count($cartItems) }} items)
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
