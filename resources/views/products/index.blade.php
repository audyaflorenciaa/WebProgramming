<!-- resources/views/products/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-bold text-lg mb-4">All Categories</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">All Items</a></li>
                    @foreach($categories as $category)
                    <li><a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-gray-700 hover:text-blue-600">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-bold text-lg mb-4">Price Range</h3>
                <p class="text-gray-600 text-sm mb-4">The average price is $300</p>
                <div class="space-y-4">
                    <div>
                        <input type="range" min="0" max="1000" class="w-full">
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>$20</span>
                        <span>$1180</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="font-bold text-lg mb-4">Condition</h3>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox">
                        <span class="ml-2 text-gray-700">Like New</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox">
                        <span class="ml-2 text-gray-700">Good</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox">
                        <span class="ml-2 text-gray-700">Fair</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="form-checkbox">
                        <span class="ml-2 text-gray-700">Poor</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="w-full md:w-3/4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="relative">
                        @if(!empty($product->images))
                        <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                        @endif
                        <div class="absolute top-2 right-2 bg-blue-600 text-white px-2 py-1 rounded text-sm">
                            {{ $product->condition_label }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ $product->title }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 60) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection