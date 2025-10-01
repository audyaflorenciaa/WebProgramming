@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
        </div>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Registration failed:</strong> Please fix the errors below.
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register.submit') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                
                <!-- Full Name Input -->
                <div>
                    <label for="name" class="sr-only">Full Name</label>
                    <input id="name" name="name" type="text" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                           placeholder="Full Name" value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 🌟 NEW: Live Username Preview Display 🌟 -->
                <div class="mt-2 p-2 bg-gray-100 border border-dashed border-gray-400 rounded-md text-sm">
                    <p class="font-medium text-gray-700">
                        Your generated username will be: 
                        <strong id="username-preview" class="text-blue-600 font-bold tracking-wide">[Start typing your name]</strong>
                    </p>
                </div>
                <!-- 🌟 END: Live Username Preview Display 🌟 -->

                <!-- Email Address Input -->
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-500 @enderror"
                           placeholder="Email address" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-500 @enderror"
                           placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Confirm Password">
                </div>
                
                <!-- Phone (Optional) -->
                <div>
                    <label for="phone" class="sr-only">Phone Number (Optional)</label>
                    <input id="phone" name="phone" type="text" 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone') border-red-500 @enderror"
                           placeholder="Phone Number (Optional)" value="{{ old('phone') }}">
                    @error('phone')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address (Optional) -->
                <div>
                    <label for="address" class="sr-only">Address (Optional)</label>
                    <textarea id="address" name="address" rows="3"
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-500 @enderror"
                           placeholder="Address (Optional)">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Register
                </button>
            </div>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500">
                    Already have an account? Sign in
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const usernamePreview = document.getElementById('username-preview');

        // Function to create a URL-friendly slug (lowercase, no spaces, similar to Str::slug)
        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '') // Remove spaces
                .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                .replace(/\-\-+/g, '') // Replace multiple dashes with single dash
                .replace(/^-+/, '') // Trim dash from start
                .replace(/-+$/, ''); // Trim dash from end
        }

        // Event listener to update the username preview when the name input changes
        nameInput.addEventListener('input', function() {
            const name = this.value;
            let username = slugify(name);
            
            // Display a default placeholder if the input is empty
            if (username === '') {
                usernamePreview.textContent = '[Start typing your name]';
                usernamePreview.classList.remove('text-red-500');
                usernamePreview.classList.add('text-blue-600');
                return;
            }

            // Simple check to ensure it's not too short (optional check)
            if (username.length < 3) {
                usernamePreview.textContent = username + ' (Too short)';
                usernamePreview.classList.add('text-red-500');
                usernamePreview.classList.remove('text-blue-600');
            } else {
                usernamePreview.textContent = username;
                usernamePreview.classList.remove('text-red-500');
                usernamePreview.classList.add('text-blue-600');
            }

            // NOTE: This client-side code *cannot* check the database for uniqueness, 
            // but it gives the user a strong indication of what the final username will be.
            // The unique checking logic remains safely on the server side (AuthController.php).
        });
        
        // Trigger the input event once on load if there's old data
        if (nameInput.value) {
            nameInput.dispatchEvent(new Event('input'));
        }
    });
</script>
@endsection
