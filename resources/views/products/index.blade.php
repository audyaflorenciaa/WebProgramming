<!-- resources/views/products/index.blade.php -->
@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <!-- Sidebar Filters (Left Column) -->
        <div class="md:col-span-1 space-y-6">
            
            <!-- Category Filter Block (DIPLAYING CATEGORIES) -->
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-bold mb-3 border-b pb-2">All Categories</h3>
                <ul class="space-y-1 text-gray-700">
                    <!-- Link to view All Items (no filter) -->
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="block py-1 hover:text-blue-600 {{ !request()->has('category') ? 'font-semibold text-blue-600' : '' }}">
                            All Items
                        </a>
                    </li>
                    
                    <!-- Loop through Categories from Database -->
                    @if (!empty($categories))
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                   class="block py-1 capitalize hover:text-blue-600 {{ request('category') == $category->slug ? 'font-semibold text-blue-600' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    @else
                         <li class="text-sm text-red-500">No categories found. Run database seeder!</li>
                    @endif
                </ul>
            </div>
            
            <!-- Price Range Block (Placeholder) -->
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-bold mb-3">Price Range</h3>
                <p class="text-sm text-gray-500">The average price is IDR 300</p>
                <input type="range" class="w-full mt-3" min="20" max="1180" value="300">
                <div class="flex justify-between text-xs text-gray-600 mt-1">
                    <span>IDR 20</span>
                    <span>IDR 1180</span>
                </div>
            </div>
            
            <!-- Condition Block (Placeholder) -->
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-bold mb-3">Condition</h3>
                <div class="space-y-2">
                    <label class="flex items-center"><input type="checkbox" class="rounded mr-2"> Like New</label>
                    <label class="flex items-center"><input type="checkbox" class="rounded mr-2"> Good</label>
                    <label class="flex items-center"><input type="checkbox" class="rounded mr-2"> Fair</label>
                    <label class="flex items-center"><input type="checkbox" class="rounded mr-2"> Poor</label>
                </div>
            </div>
            
        </div>
        
        <!-- Product Grid (Right Column) -->
        <div class="md:col-span-3">
            <h2 class="text-2xl font-semibold mb-6">Showing items in All Categories</h2>
            
            <!-- Placeholder for Product Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="bg-white shadow rounded-lg p-4 border border-gray-200">
                        <h3 class="text-lg font-bold">{{ $product->title }}</h3>
                        <p class="text-gray-600">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-blue-500">{{ $product->category->name }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3">No products match your criteria.</p>
                @endforelse
            </div>
            
            <!-- Pagination Placeholder -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
        
    </div>
</div>
@endsection