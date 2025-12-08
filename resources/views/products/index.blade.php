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
                <form id="price-filter-form" action="{{ route('products.index') }}" method="GET">
                    @if(request()->has('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <p class="text-sm text-gray-500 mb-3">
                        Max Price: 
                        <span id="current-price-display" class="font-semibold text-blue-600">
                            IDR {{ number_format(request('max_price', $maxGlobalPrice), 0, ',', '.') }}
                        </span>
                    </p>

                    <input type="range" 
                        name="max_price" 
                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                        
                        step="1000"
                        
                        min="{{ $minGlobalPrice }}" 
                        max="{{ $maxGlobalPrice }}" 
                        
                        value="{{ request('max_price', $maxGlobalPrice) }}"
                        
                        oninput="updatePriceDisplay(this.value)"
                        onchange="document.getElementById('price-filter-form').submit()">

                    <div class="flex justify-between text-xs text-gray-600 mt-2">
                        <span>IDR {{ number_format($minGlobalPrice, 0, ',', '.') }}</span>
                        <span>IDR {{ number_format($maxGlobalPrice, 0, ',', '.') }}</span>
                    </div>
                </form>
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
            
            <!-- Product Cards with Carousel Implemented -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($products as $product)
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-xl relative">
                        
                        <!-- 1. IMAGE CAROUSEL AREA -->
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

                                <!-- Navigation Buttons (Hidden if only one image) -->
                                @if(count($product->images) > 1)
                                    <button type="button" class="carousel-prev absolute top-1/2 left-2 transform -translate-y-1/2 bg-black bg-opacity-30 p-2 rounded-full text-white hover:bg-opacity-50 z-10" onclick="changeSlide('prev', {{ $product->id }})">
                                        &lt;
                                    </button>
                                    <button type="button" class="carousel-next absolute top-1/2 right-2 transform -translate-y-1/2 bg-black bg-opacity-30 p-2 rounded-full text-white hover:bg-opacity-50 z-10" onclick="changeSlide('next', {{ $product->id }})">
                                        &gt;
                                    </button>
                                @endif
                            @else
                                <!-- Placeholder if no image is available -->
                                <a href="{{ route('products.show', $product->id) }}" class="absolute inset-0 flex items-center justify-center text-gray-500">
                                    [No Image]
                                </a>
                            @endif
                            
                        </div>

                        <!-- 2. PRODUCT DETAILS -->
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
            
            <!-- Pagination Placeholder -->
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
     */
    function changeSlide(direction, productId) {
        // Stop the click event from navigating to the product show page
        if (event && event.type === 'click') {
            event.preventDefault();
            event.stopPropagation();
        }

        const carouselContainer = document.querySelector(`.product-carousel[data-product-id="${productId}"]`);
        const slides = carouselContainer.querySelectorAll('.carousel-item');
        
        if (slides.length <= 1) return;

        let currentIndex = -1;
        // Find the current active slide index
        slides.forEach((slide, index) => {
            if (slide.classList.contains('opacity-100')) {
                currentIndex = index;
            }
        });

        // Determine the new index
        let newIndex = currentIndex;
        if (direction === 'next') {
            newIndex = (currentIndex + 1) % slides.length; // Loop back to the start
        } else if (direction === 'prev') {
            newIndex = (currentIndex - 1 + slides.length) % slides.length; // Loop back to the end
        }

        // Apply the visual change (hiding the current, showing the new)
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

    function updatePriceDisplay(value) {
        // Format the number to look like currency (e.g. 15.000)
        let formatted = new Intl.NumberFormat('id-ID').format(value);
        document.getElementById('current-price-display').innerText = 'IDR ' + formatted;
    }

    // Start the auto-advance logic once the entire page is loaded
    document.addEventListener('DOMContentLoaded', () => {
        // Start Carousel
        autoAdvance();

        // Initialize price display
        const slider = document.querySelector('input[name="max_price"]');
        if (slider) {
            updatePriceDisplay(slider.value);
        }
    });
</script>
@endsection