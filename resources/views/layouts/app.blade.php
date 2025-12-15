<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReVibe - Second Hand Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">ReVibe</a>
                </div>
                
                <div class="flex-1 max-w-3xl mx-4">
                    <form action="{{ route('products.index') }}" method="GET" class="flex">
                        <input type="text" name="search" placeholder="Search for items..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-md hover:bg-blue-700">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('products.create') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-plus mr-1"></i> Sell
                        </a>
                        <a href="{{ route('products.my-products') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-store mr-1"></i> My Items
                        </a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-shopping-cart mr-1"></i> Orders
                        </a>
                        <div class="relative">
                            <button class="flex items-center text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle text-xl"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">ReVibe</h3>
                    <p class="text-gray-400">The best marketplace for second-hand goods. Quality used items at great prices.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Categories</h4>
                    <ul class="space-y-2">
                        @foreach($categories ?? [] as $category)
                        <li><a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-gray-400 hover:text-white">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 ReVibe. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Simple JavaScript for dropdown menu
        document.addEventListener('DOMContentLoaded', function() {
            const profileButton = document.querySelector('button.flex.items-center');
            // Select the dropdown menu by its class structure
            const dropdownMenu = document.querySelector('div.relative > div.absolute.right-0.mt-2'); 
            
            if (profileButton && dropdownMenu) {
                profileButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Toggle the 'hidden' class on the dropdown menu
                    dropdownMenu.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @yield('scripts') 

</body>
</html>