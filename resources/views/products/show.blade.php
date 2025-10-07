@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden p-6">
        
        <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-3">
            Product Details: {{ $product->title }}
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Left Column: Image Display -->
            <div class="md:col-span-1">
                <div class="w-full h-80 bg-gray-200 rounded-lg overflow-hidden shadow-md">
                    @php
                        $firstImage = is_array($product->images) && count($product->images) > 0 
                            ? asset('storage/' . $product->images[0])
                            : 'https://placehold.co/400x400/e0e0e0/555555?text=No+Image';
                    @endphp
                    <img src="{{ $firstImage }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                </div>
                
                {{-- Placeholder for image gallery/thumbnails if product has more than 1 image --}}
                @if(is_array($product->images) && count($product->images) > 1)
                    <div class="mt-3 flex gap-2 overflow-x-auto p-1 border rounded-lg">
                        @foreach($product->images as $imagePath)
                            <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                <img src="{{ asset('storage/' . $imagePath) }}" alt="Thumbnail" class="w-full h-full object-cover cursor-pointer hover:opacity-75">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Columns: Product Info & Buy Button -->
            <div class="md:col-span-2 space-y-4">
                <p class="text-4xl font-extrabold text-green-700">
                    IDR {{ number_format($product->price, 0, ',', '.') }}
                </p>

                <div class="text-gray-700 space-y-2 border-b pb-4">
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

                <div class="pt-2">
                    <h3 class="text-xl font-semibold mb-2">Description</h3>
                    <p class="whitespace-pre-wrap text-gray-700">{{ $product->description }}</p>
                </div>
                
                <!-- Action Button: Updated to use JS Fetch -->
                @if(!$product->is_sold)
                    <form id="addToCartForm" data-product-id="{{ $product->id }}" class="mt-6">
                        @csrf
                        <button type="submit" id="cartButton" class="w-full bg-indigo-600 text-white py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition duration-150">
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addToCartForm');
    const button = document.getElementById('cartButton');
    
    if (form) {
        const productId = form.dataset.productId;
        const csrfToken = form.querySelector('input[name="_token"]').value;

        form.addEventListener('submit', function(event) {
            // 1. STOP default form submission
            event.preventDefault();

            // Disable button and show loading state
            button.disabled = true;
            button.textContent = 'Adding...';
            button.classList.add('bg-indigo-400');
            button.classList.remove('hover:bg-indigo-700');

            // 2. SEND request via fetch API
            fetch(`{{ route('cart.add', ['product' => '__ID__']) }}`.replace('__ID__', productId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => {
                // Handle 409 (Conflict - Already in Cart) errors thrown by the server
                if (response.status === 409) {
                    return response.json().then(data => { throw new Error(data.error); });
                }
                
                // If the status is not a success status (200), throw a generic error
                if (!response.ok) {
                    throw new Error('Server error while adding product.');
                }
                
                // Read the JSON response (the success message)
                return response.json(); 
            })
            .then(data => {
                // 3. SUCCESS REDIRECT: This is the line that fixes your issue.
                console.log('Product added successfully. Redirecting...');
                window.location.href = '{{ route('checkout.index') }}';
            })
            .catch(error => {
                // 4. Handle Errors (e.g., already in cart, network error)
                console.error('Error:', error);
                
                // Determine the error message to display
                const errorMessage = error.message.includes('already in your cart') 
                                     ? 'Already in cart!' 
                                     : 'Failed to add.';

                button.textContent = errorMessage;
                button.classList.remove('bg-indigo-400');
                button.classList.add('bg-red-600');
                
                // Re-enable the button after a delay
                setTimeout(() => {
                    button.disabled = false;
                    button.textContent = 'Add to Cart';
                    button.classList.remove('bg-red-600');
                    button.classList.add('hover:bg-indigo-700');
                }, 3000);
            });
        });
    }
});
</script>
@endsection