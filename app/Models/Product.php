<?php
// App/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'description', 'price', 
        'condition', 'brand', 'images', 'video', 'is_sold'
    ];

    /**
     * The attributes that should be cast.
     * Use 'array' for fields stored as JSON or LONGTEXT/TEXT containing JSON.
     */
    protected $casts = [
        'is_sold' => 'boolean',
        'images' => 'array', 
        'video' => 'array',  
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function getConditionLabelAttribute()
    {
        $conditions = [
            'like_new' => 'Like New',
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor'
        ];
        return $conditions[$this->condition] ?? 'Unknown';
    }
}