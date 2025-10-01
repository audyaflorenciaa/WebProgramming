<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Sign in to your account
            </h2>
        </div>
        
        <!-- START: Message Display Block (Registration Success) -->
        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                <p class="font-bold">{{ session('status') }}</p>
                @if (session('username'))
                    <p class="mt-1 text-sm">
                        Your unique username is: 
                        <strong class="text-green-900 bg-green-200 px-2 py-0.5 rounded-full text-base tracking-wider">
                            {{ session('username') }}
                        </strong>
                        <br>Please use this to log in.
                    </p>
                @endif
            </div>
        @endif
        <!-- END: Message Display Block -->

        <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                
                <!-- Email Input -->
                <div>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                           placeholder="Email address" value="{{ old('email') }}">
                </div>
                <!-- Custom Error Message for Email (User not found) -->
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Password Input -->
                <div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                           placeholder="Password">
                </div>
                <!-- Custom Error Message for Password (Wrong password) -->
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Forgot Password Link -->
            <div class="flex items-center justify-end">
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    Forgot your password?
                </a>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign in
                </button>
            </div>
            
            <div class="text-center">
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500">
                    Don't have an account? Register now
                </a>
            </div>
        </form>
    </div>
</div>
@endsection