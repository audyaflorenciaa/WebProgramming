@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Delivery & Payment Details -->
        <div class="lg:col-span-2 bg-white shadow-lg rounded-lg p-6 space-y-8">
            <h1 class="text-3xl font-bold text-gray-800 border-b pb-3">Checkout</h1>
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">{{ session('error') }}</div>
            @endif

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                
                <!-- Delivery Address -->
                <div class="space-y-4 border-b pb-6">
                    <h2 class="text-xl font-semibold text-indigo-600">1. Delivery Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Full Address</label>
                            <input type="text" name="address" id="address" required 
                                   value="{{ old('address', Auth::user()->address ?? '') }}"
                                   class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City / District</label>
                            <input type="text" name="city" id="city" required 
                                   value="{{ old('city', Auth::user()->city ?? '') }}"
                                   class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="text" name="phone" id="phone" required 
                               value="{{ old('phone', Auth::user()->phone ?? '') }}"
                               class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Delivery Options -->
                <div class="space-y-4 border-b py-6">
                    <h2 class="text-xl font-semibold text-indigo-600">2. Delivery Method</h2>
                    <div class="space-y-3" id="delivery-options">
                        <!-- Options will be listed here, linked to JavaScript for price calculation -->
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="delivery_method" value="standard" required 
                                   data-shipping="15000" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-800">Standard Shipping (3-5 Days)</span>
                            <span class="ml-auto font-bold text-gray-700">IDR 15.000</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="delivery_method" value="express" 
                                   data-shipping="35000" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-800">Express Shipping (1-2 Days)</span>
                            <span class="ml-auto font-bold text-gray-700">IDR 35.000</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="delivery_method" value="pickup" 
                                   data-shipping="0" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-800">Local Pickup</span>
                            <span class="ml-auto font-bold text-green-600">FREE</span>
                        </label>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="space-y-4 pt-6">
                    <h2 class="text-xl font-semibold text-indigo-600">3. Payment Method</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="transfer" id="payment-transfer" required 
                                   class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-800">Bank Transfer (BCA)</span>
                        </label>
                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" id="payment-cod"
                                   class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 font-medium text-gray-800">Cash on Delivery (COD)</span>
                        </label>
                    </div>

                    <!-- NEW: Virtual Account Display -->
                    <div id="va-details" class="hidden bg-blue-50 p-4 rounded-md border border-blue-200 mt-4 space-y-2">
                        <p class="text-sm font-bold text-blue-800">BCA Virtual Account Details</p>
                        <p class="text-2xl font-extrabold text-blue-900 tracking-wider" id="va-number">
                            <!-- VA Number will be inserted here by JavaScript -->
                        </p>
                        <p class="text-sm text-gray-600">Please complete payment within 24 hours.</p>
                    </div>
                    <!-- END NEW SECTION -->
                </div>
                
                <!-- Hidden fields for pricing -->
                <input type="hidden" name="cart_subtotal" id="cart_subtotal_input" value="{{ $totalPrice }}">
                <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
                <input type="hidden" name="total_amount" id="total_amount_input" value="{{ $totalPrice }}">

                <!-- Final Submit Button -->
                <div class="mt-8">
                    <button type="submit" id="checkout-submit" disabled
                            class="w-full bg-gray-400 text-white py-3 rounded-lg text-lg font-semibold cursor-not-allowed">
                        Select Delivery to Place Order
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-1">
            <div class="sticky top-4 bg-white shadow-lg rounded-lg p-6 border-t-4 border-pink-500">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Order Summary</h2>

                <div class="space-y-2 text-gray-700">
                    <div class="flex justify-between">
                        <span>Items Subtotal ({{ count($cartItems) }}):</span>
                        <span class="font-medium">IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span>Shipping Estimate:</span>
                        <span id="shipping-display" class="font-medium text-red-500">Not Selected</span>
                    </div>
                    <div class="flex justify-between pt-2 text-2xl font-extrabold text-indigo-600">
                        <span>Order Total:</span>
                        <span id="total-display">IDR {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
                
                <!-- Item List Snippet -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <p class="text-sm font-semibold mb-2">Items:</p>
                    <ul class="text-xs space-y-1 max-h-40 overflow-y-auto">
                        @foreach($cartItems as $item)
                            <li class="flex justify-between text-gray-600">
                                <span>{{ $item['product']->title }}</span>
                                <span>IDR {{ number_format($item['product']->price, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subtotal = parseFloat(document.getElementById('cart_subtotal_input').value);
        const deliveryOptions = document.querySelectorAll('input[name="delivery_method"]');
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const shippingDisplay = document.getElementById('shipping-display');
        const totalDisplay = document.getElementById('total-display');
        const shippingInput = document.getElementById('shipping_cost_input');
        const totalAmountInput = document.getElementById('total_amount_input');
        const submitButton = document.getElementById('checkout-submit');
        
        // NEW VA Elements
        const vaDetails = document.getElementById('va-details');
        const vaNumberDisplay = document.getElementById('va-number');

        /**
         * Generates a random 16-digit BCA Virtual Account number starting with a common code.
         */
        function generateBCAVirtualAccount() {
            // BCA VA numbers typically start with 788, 700, or similar codes.
            // Using a common 5-digit code (e.g., 78888) followed by 11 random digits.
            let va = '78888'; 
            for (let i = 0; i < 11; i++) {
                va += Math.floor(Math.random() * 10);
            }
            return va;
        }

        /**
         * Handles the display of the Virtual Account number.
         */
        function handleVirtualAccountDisplay() {
            const transferSelected = document.getElementById('payment-transfer').checked;

            if (transferSelected) {
                // Generate and display the VA number
                vaNumberDisplay.textContent = generateBCAVirtualAccount();
                vaDetails.classList.remove('hidden');
            } else {
                vaDetails.classList.add('hidden');
                vaNumberDisplay.textContent = '';
            }
        }

        /**
         * Formats a number as IDR currency string.
         */
        function formatIDR(amount) {
            return 'IDR ' + amount.toLocaleString('id-ID');
        }

        /**
         * Recalculates the total price based on selected shipping.
         */
        function recalculateTotal() {
            let shippingCost = 0;
            let isDeliverySelected = false;
            let isPaymentSelected = false;
            
            // Find the selected delivery method
            deliveryOptions.forEach(radio => {
                if (radio.checked) {
                    shippingCost = parseFloat(radio.dataset.shipping);
                    isDeliverySelected = true;
                }
            });

            // Check if payment is selected
            paymentRadios.forEach(radio => {
                if (radio.checked) {
                    isPaymentSelected = true;
                }
            });
            
            handleVirtualAccountDisplay(); // Update VA display based on payment selection

            const total = subtotal + shippingCost;

            // Update shipping display
            if (isDeliverySelected) {
                shippingDisplay.textContent = formatIDR(shippingCost);
                shippingDisplay.classList.remove('text-red-500');
                shippingDisplay.classList.add('text-green-600');
            } else {
                shippingDisplay.textContent = 'Not Selected';
                shippingDisplay.classList.add('text-red-500');
                shippingDisplay.classList.remove('text-green-600');
            }
            
            // Update total display
            totalDisplay.textContent = formatIDR(total);

            // Update hidden input fields for form submission
            shippingInput.value = shippingCost;
            totalAmountInput.value = total;

            // Enable/Disable Submit Button
            if (isDeliverySelected && isPaymentSelected) {
                 submitButton.disabled = false;
                 submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                 submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
                 submitButton.textContent = 'Place Final Order';
            } else {
                submitButton.disabled = true;
                submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                
                if (!isDeliverySelected) {
                    submitButton.textContent = 'Select Delivery to Place Order';
                } else {
                    submitButton.textContent = 'Select Payment to Place Order';
                }
            }
        }
        
        // Listeners for recalculation
        deliveryOptions.forEach(radio => {
            radio.addEventListener('change', recalculateTotal);
        });

        paymentRadios.forEach(radio => {
            radio.addEventListener('change', recalculateTotal);
        });

        // Run initial calculation check
        recalculateTotal();
    });
</script>
@endsection
