<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create waste categories (Prices in Koin, 1 Koin = Rp 100)
        WasteCategory::create(['name' => 'Plastik PET', 'price_per_kg' => 30, 'icon' => '♻️']);
        WasteCategory::create(['name' => 'Kardus', 'price_per_kg' => 20, 'icon' => '📦']);
        WasteCategory::create(['name' => 'Kertas', 'price_per_kg' => 15, 'icon' => '📄']);
        WasteCategory::create(['name' => 'Logam', 'price_per_kg' => 50, 'icon' => '🔩']);
        WasteCategory::create(['name' => 'Kaca', 'price_per_kg' => 10, 'icon' => '🪟']);

        // Create test accounts
        User::create([
            'name' => 'Budi Mahasiswa',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'coin_balance' => 5000,
        ]);

        User::create([
            'name' => 'Pak Driver',
            'email' => 'driver@test.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
            'coin_balance' => 0,
        ]);

        User::create([
            'name' => 'Admin Trash-Pay',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'coin_balance' => 0,
        ]);
    }
}
