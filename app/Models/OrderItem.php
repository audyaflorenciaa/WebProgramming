<?php
// App/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    // An OrderItem doesn't need timestamps, but we can keep them for tracing
    public $timestamps = true; 

    // Fields that we allow to be mass assigned when creating the order
    protected $fillable = [
        'order_id', 
        'product_id', 
        'quantity',
        'unit_price',
        'subtotal', // unit_price * quantity (always 1 in your case)
    ];

    /**
     * Relationship: An OrderItem belongs to one Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: An OrderItem belongs to one Product (the item being sold).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
