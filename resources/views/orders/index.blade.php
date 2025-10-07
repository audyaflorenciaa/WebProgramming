@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">My Orders</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="bg-blue-50 border-2 border-dashed border-blue-300 p-12 text-center rounded-lg shadow-inner" role="alert">
                <i class="fas fa-box-open text-4xl text-blue-400 mb-4"></i>
                <p class="text-xl font-medium text-blue-700">No orders found.</p>
                <p class="text-gray-500 mt-2">You haven't placed any orders yet. <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Start shopping!</a></p>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($orders as $order)
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden border-t-4 @if($order->status === 'Pending Payment') border-yellow-500 @elseif($order->status === 'Shipped') border-blue-500 @else border-green-500 @endif">
                        
                        <!-- Order Header -->
                        <div class="p-4 bg-gray-50 flex justify-between items-center border-b">
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Order ID</p>
                                <p class="font-bold text-sm text-gray-800">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-gray-500">Order Date</p>
                                <p class="font-bold text-sm text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold uppercase text-gray-500">Total</p>
                                <p class="text-xl font-extrabold text-indigo-600">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Order Details (Status & Items) -->
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-4">
                                <p class="font-bold text-lg">
                                    Status: 
                                    <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full @if($order->status === 'Pending Payment') bg-yellow-100 text-yellow-800 @elseif($order->status === 'Shipped') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif">
                                        {{ $order->status }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    Payment: {{ $order->payment_method === 'transfer' ? 'Bank Transfer' : 'COD' }}
                                </p>
                            </div>

                            <!-- Items in Order -->
                            <div class="space-y-3">
                                <p class="text-sm font-semibold text-gray-700">Items Ordered:</p>
                                @foreach ($order->orderItems as $item)
                                    <div class="flex items-center space-x-4 border-t pt-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                            @php
                                                $firstImage = is_array($item->product->images ?? []) && count($item->product->images) > 0 
                                                    ? asset('storage/' . $item->product->images[0])
                                                    : 'https://placehold.co/48x48/e0e0e0/555555?text=Img';
                                            @endphp
                                            <img src="{{ $firstImage }}" alt="{{ $item->product->title ?? 'Deleted Item' }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-grow">
                                            <p class="font-medium text-gray-800">{{ $item->product->title ?? '[Product Deleted]' }}</p>
                                            <p class="text-sm text-gray-500">IDR {{ number_format($item->unit_price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Address Snippet -->
                            <div class="mt-4 pt-4 border-t border-gray-200 text-sm">
                                <p class="font-semibold text-gray-700">Shipped to:</p>
                                <p class="text-gray-600">{{ $order->delivery_address }} ({{ $order->delivery_method }})</p>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
