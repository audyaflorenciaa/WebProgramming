@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        
        <div class="md:col-span-1 space-y-6">
            
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-bold mb-3 border-b pb-2">All Categories</h3>
                <ul class="space-y-1 text-gray-700">
                    <li>
                        <a href="{{ route('products.index') }}" 
                           class="block py-1 hover:text-blue-600 {{ !request()->has('category') ? 'font-semibold text-blue-600' : '' }}">
                            All Items
                        </a>
                    </li>
                    
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
            
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-bold mb-3">Price Range</h3>
                <p class="text-sm text-gray-500">The average price is IDR 300</p>
                <input type="range" class="w-full mt-3" min="20" max="1180" value="300">
                <div class="flex justify-between text-xs text-gray-600 mt-1">
                    <span>IDR 20</span>
                    <span>IDR 1180</span>
                </div>
            </div>
            
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
        
        <div class="md:col-span-3">
            <h2 class="text-2xl font-semibold mb-6">Showing items in All Categories</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-xl relative">
                        
                        <div class="relative w-full h-48 overflow-hidden bg-gray-100 product-carousel" data-product-id="{{ $product->id }}">
                            
                            @if(is_array($product->images) && count($product->images) > 0)
                                @foreach($product->images as $index => $imagePath)
                                    @php
                                        $imageUrl = asset('storage/' . $imagePath);
                                    @endphp
                                    <a href="{{ route('products.show', $product->id) }}" 
                                       class="carousel-item absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $product->title }} image {{ $index + 1 }}" 
                                             class="w-full h-full object-cover">
                                    </a>
                                @endforeach

                                @if(count($product->images) > 1)
                                    <button type="button" class="carousel-prev absolute top-1/2 left-2 transform -translate-y-1/2 bg-black bg-opacity-30 p-2 rounded-full text-white hover:bg-opacity-50 z-10" onclick="changeSlide('prev', {{ $product->id }})">
                                        &lt;
                                    </button>
                                    <button type="button" class="carousel-next absolute top-1/2 right-2 transform -translate-y-1/2 bg-black bg-opacity-30 p-2 rounded-full text-white hover:bg-opacity-50 z-10" onclick="changeSlide('next', {{ $product->id }})">
                                        &gt;
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('products.show', $product->id) }}" class="absolute inset-0 flex items-center justify-center text-gray-500">
                                    [No Image]
                                </a>
                            @endif
                            
                        </div>

                        <div class="p-4">
                            <a href="{{ route('products.show', $product->id) }}" class="text-lg font-bold text-gray-800 hover:text-blue-600 line-clamp-2">
                                {{ $product->title }}
                            </a>
                            <p class="text-xl font-extrabold text-green-600 mt-1">
                                IDR {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-blue-600 mt-0.5">
                                {{ $product->category->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-3">No products match your criteria.</p>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('scripts')
<script>
    const AUTO_ADVANCE_DELAY = 4000; // Time in milliseconds (e.g., 4 seconds)

    /**
     * Finds the current active slide and switches to the next/previous one.
     * This function now handles both arrow clicks and the auto-play timer.
     */
    function changeSlide(direction, productId) {
        // Prevent default navigation for arrow clicks
        if (event && event.type === 'click') {
            event.preventDefault();
            event.stopPropagation();
        }

        const carouselContainer = document.querySelector(`.product-carousel[data-product-id="${productId}"]`);
        const slides = carouselContainer.querySelectorAll('.carousel-item');
        
        if (slides.length <= 1) return;

        let currentIndex = -1;
        slides.forEach((slide, index) => {
            if (slide.classList.contains('opacity-100')) {
                currentIndex = index;
            }
        });

        // Determine the new index
        let newIndex = currentIndex;
        if (direction === 'next') {
            newIndex = (currentIndex + 1) % slides.length; 
        } else if (direction === 'prev') {
            newIndex = (currentIndex - 1 + slides.length) % slides.length;
        }

        // Apply the visual change
        slides[currentIndex].classList.remove('opacity-100');
        slides[currentIndex].classList.add('opacity-0');

        slides[newIndex].classList.remove('opacity-0');
        slides[newIndex].classList.add('opacity-100');
    }

    /**
     * Initializes the automatic advancing for all carousels on the page.
     */
    function autoAdvance() {
        const carousels = document.querySelectorAll('.product-carousel');
        
        carousels.forEach(carousel => {
            const productId = carousel.getAttribute('data-product-id');
            const slides = carousel.querySelectorAll('.carousel-item');
            
            // Only start auto-play if there are multiple images
            if (slides.length > 1) {
                // Use a timer to call changeSlide every X seconds
                setInterval(() => {
                    // We pass 'next' to cycle forward automatically
                    changeSlide('next', productId); 
                }, AUTO_ADVANCE_DELAY);
            }
        });
    }

    // Start the auto-advance logic once the entire page is loaded
    document.addEventListener('DOMContentLoaded', autoAdvance);

</script>
@endsection