<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">My Orders</h1>

    @if ($orders->isEmpty())
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-6 rounded-lg" role="alert">
            <p class="font-bold">No orders found.</p>
            <p>You haven't placed any orders yet. <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Start shopping!</a></p>
        </div>
    @else
        {{-- Order list UI here (similar to my-products) --}}
        <p>Order list content goes here.</p>
    @endif
</div>
@endsection