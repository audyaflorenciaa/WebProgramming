@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Edit Listing: {{ $product->title }}</h1>
    
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">Please check the fields below and correct the errors.</span>
        </div>
    @endif

    <form id="productForm" action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT') 
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                
                <div class="border p-4 rounded-md bg-gray-50">
                    <h3 class="text-sm font-bold text-gray-700 mb-2">Current Media</h3>
                    
                    @if(is_array($product->images) && count($product->images) > 0)
                        <p class="text-xs font-medium text-gray-600 mb-1">Images ({{ count($product->images) }}):</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($product->images as $index => $imagePath)
                                <div class="relative w-16 h-16 bg-gray-200 rounded-md overflow-hidden shadow-sm group">
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="Current Image" class="w-full h-full object-cover">
                                    <span class="absolute top-0 right-0 p-1 bg-red-600 text-white text-xs font-bold rounded-bl-md opacity-90">
                                        {{ $index + 1 }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(is_array($product->video) && count($product->video) > 0)
                        <p class="text-xs font-medium text-gray-600 mb-1">Video ({{ count($product->video) }}):</p>
                        <span class="px-2 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded">
                            {{ count($product->video) }} file(s) attached
                        </span>
                    @endif

                    <p class="text-xs text-gray-500 mt-3 border-t pt-2">
                        Uploading new files below will **REPLACE** the existing media.
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Replace Images (Max 10)</label>
                    <div class="flex items-center space-x-2">
                        <input type="file" name="images[]" id="images" multiple accept="image/*" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2" >
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Leave blank to keep existing images. Uploading new files will replace all existing ones.</p>
                    @error('images.*')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Replace Video (Optional, Max 3)</label>
                    <div class="flex items-center space-x-2">
                         <input type="file" name="video[]" id="video" multiple accept="video/*" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Leave blank to keep existing videos.</p>
                    @error('video.*')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                    <select name="condition" id="condition" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('condition') border-red-500 @enderror">
                        <option value="">-- Select Condition --</option>
                        <option value="like_new" {{ ($product->condition ?? old('condition')) == 'like_new' ? 'selected' : '' }}>Like New</option>
                        <option value="good" {{ ($product->condition ?? old('condition')) == 'good' ? 'selected' : '' }}>Good</option>
                        <option value="fair" {{ ($product->condition ?? old('condition')) == 'fair' ? 'selected' : '' }}>Fair</option>
                        <option value="poor" {{ ($product->condition ?? old('condition')) == 'poor' ? 'selected' : '' }}>Poor</option>
                    </select>
                    @error('condition')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="space-y-6">
                
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" name="title" id="title" required 
                           value="{{ $product->title ?? old('title') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id" required 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ ($product->category_id ?? old('category_id')) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (IDR)</label>
                    <input type="text" name="price" id="price" required 
                           value="{{ number_format($product->price ?? old('price'), 0, ',', '.') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                           placeholder="e.g., 4.800.000">
                    @error('price')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand (Optional)</label>
                    <input type="text" name="brand" id="brand" 
                           value="{{ $product->brand ?? old('brand') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('brand') border-red-500 @enderror">
                    @error('brand')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_sold" value="0">
                    <input type="checkbox" name="is_sold" id="is_sold" value="1" 
                           {{ ($product->is_sold ?? old('is_sold')) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_sold" class="ml-2 block text-sm font-medium text-gray-700">Mark as Sold</label>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" required 
                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ $product->description ?? old('description') }}</textarea>
            @error('description')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mt-8 flex justify-between space-x-4">
            <a href="{{ route('products.my-products') }}" class="w-1/3 text-center bg-gray-400 text-white py-3 px-4 rounded-md hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-150">
                Cancel
            </a>
            <button type="submit" class="w-2/3 bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-150">
                Update Listing
            </button>
        </div>
    </form>
</div>
@endsection