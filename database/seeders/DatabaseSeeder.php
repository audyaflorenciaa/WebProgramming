<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\CategorySeeder; // <<< Ensure this import is here

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create User
        // $user = User::factory()->create([
        //     'name' => 'Test Seller',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('password'), 
        // ]);
        
        // 2. Run the other seeders
        $this->call([
            CategorySeeder::class, // <<< ADD THIS LINE
        ]);
    }
}