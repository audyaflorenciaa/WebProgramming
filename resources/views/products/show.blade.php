@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden p-6">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">
            Product Details: {{ $product->title }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="md:col-span-1">
                <div class="w-full h-80 bg-gray-200 rounded-lg overflow-hidden shadow-md">
                    @php
                        $firstImage = is_array($product->images) && count($product->images) > 0 
                            ? asset('storage/' . $product->images[0])
                            : 'https://placehold.co/400x400/e0e0e0/555555?text=No+Image';
                    @endphp
                    <img src="{{ $firstImage }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                <p class="text-4xl font-extrabold text-green-700">
                    IDR {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div class="text-gray-700 space-y-2">
                    <p><strong>Category:</strong> 
                        <span class="text-blue-600">{{ $product->category->name ?? 'N/A' }}</span>
                    </p>
                    <p><strong>Condition:</strong> 
                        <span class="capitalize text-red-500 font-semibold">{{ str_replace('_', ' ', $product->condition) }}</span>
                    </p>
                    <p><strong>Brand:</strong> {{ $product->brand ?? 'N/A' }}</p>
                    <p><strong>Seller:</strong> {{ $product->user->name ?? 'Unknown' }}</p>
                    <p><strong>Status:</strong> 
                        @if($product->is_sold)
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">SOLD</span>
                        @else
                            <span class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t">
                    <h3 class="text-xl font-semibold mb-2">Description</h3>
                    <p class="whitespace-pre-wrap text-gray-700">{{ $product->description }}</p>
                </div>
                
                @if(!$product->is_sold)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition duration-150">
                            Add to Cart
                        </button>
                    </form>
                @else
                    <button class="w-full bg-gray-400 text-white py-3 rounded-lg text-lg font-semibold cursor-not-allowed mt-6" disabled>
                        Item is SOLD
                    </button>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection