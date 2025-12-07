<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">

        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg border border-gray-200">

            <div class="text-center">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Set Your New Password
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your details to finalize your password reset.
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                    <p class="font-bold">Password reset failed:</p>
                    <p>Please fix the errors below.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-1">
                    {{-- REMOVED: <x-input-label for="email" :value="__('Email')" /> --}}
                    <input 
                        id="email"
                        name="email"
                        type="email"
                        :value="old('email', $email)"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email Address" 
                        class="block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 
                               rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="space-y-1">
                    {{-- REMOVED: <x-input-label for="password" :value="__('New Password')" /> --}}
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="New Password"
                        class="block w-full px-3 py-2 bg-white border border-gray-300 placeholder-gray-500 
                               text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="space-y-1">
                    {{-- REMOVED: <x-input-label for="password_confirmation" :value="__('Confirm Password')" /> --}}
                    <input 
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm Password"
                        class="block w-full px-3 py-2 bg-white border border-gray-300 placeholder-gray-500 
                               text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end pt-4">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent 
                               text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Password
                    </button>
                </div>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-500">
                    Back to Login
                </a>
            </div>

        </div>
    </div>

</body>
</html>