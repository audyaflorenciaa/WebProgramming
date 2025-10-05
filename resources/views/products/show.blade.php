<!-- resources/views/products/show.blade.php -->
@extends('layouts.app') 

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Product Details: {{ $product->title }}</h1>
    
    {{-- Single product details UI goes here --}}
    <p>Product details and Buy button here.</p>
</div>
@endsection