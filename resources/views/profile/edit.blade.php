@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Profile</h1>

    <div class="space-y-8 max-w-4xl mx-auto">
        
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Profile Information</h2>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Update Password</h2>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-red-600 border-b pb-2">Delete Account</h2>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
@endsection