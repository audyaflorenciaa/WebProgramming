<?php
// App/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem; 

class Order extends Model
{
    use HasFactory;

    // These fields match the data passed from the checkout form and saved in OrderController.
    protected $fillable = [
        'user_id',
        'status', // e.g., 'Pending Payment', 'Shipped', 'Completed'
        'delivery_address',
        'delivery_method',
        'payment_method',
        'total_subtotal',
        'shipping_cost',
        'total_amount',
    ];

    /**
     * Relationship: An Order belongs to one User (the customer).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: An Order has many OrderItems.
     * This is crucial for linking the individual products to the overall order.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}
