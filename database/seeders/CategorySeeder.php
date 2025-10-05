<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics', 'Fashion & Apparel', 'Home & Furniture', 'Books & Media',
            'Sports & Outdoors', 'Toys & Games', 'Automotive Parts', 'Jewelry & Accessories',
            'Beauty & Health', 'Art & Collectibles', 'Musical Instruments', 'Pet Supplies',
            'Vintage & Retro', 'DIY & Tools', 'Baby & Kids Gear', 'More'
        ];

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'name' => $category,
                // Using Str::slug to create a URL-friendly slug
                'slug' => Str::slug($category, '-'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('categories')->insert($data);
    }
}