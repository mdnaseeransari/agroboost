<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Farm;
use App\Models\Crop;
use App\Models\InventoryItem;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@agroboost.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'farm_id' => null, // Will set later
        ]);

        $farm = Farm::create([
            'name' => 'Demo Farm',
            'location' => 'Iowa, USA',
            'size_hectares' => 50.5,
            'owner_id' => $admin->id,
        ]);

        $admin->farm_id = $farm->id;
        $admin->save();

        User::create([
            'name' => 'Farmer John',
            'email' => 'farmer@demo.com',
            'password' => Hash::make('password'),
            'farm_id' => $farm->id,
            'role' => 'farmer',
        ]);

        User::create([
            'name' => 'Buyer Jane',
            'email' => 'buyer@demo.com',
            'password' => Hash::make('password'),
            'farm_id' => $farm->id,
            'role' => 'buyer',
        ]);

        // Crops
        Crop::create(['farm_id' => $farm->id, 'name' => 'Corn', 'variety' => 'Golden', 'planting_date' => '2025-04-01', 'expected_harvest_date' => '2025-08-15', 'status' => 'growing']);
        Crop::create(['farm_id' => $farm->id, 'name' => 'Wheat', 'variety' => 'Winter', 'planting_date' => '2024-10-01', 'expected_harvest_date' => '2025-07-20', 'actual_harvest_date' => '2025-07-18', 'yield_kg' => 2500, 'status' => 'harvested']);

        // Inventory
        InventoryItem::create(['farm_id' => $farm->id, 'name' => 'Nitrogen Fertilizer', 'type' => 'fertilizer', 'quantity' => 120, 'unit' => 'kg', 'threshold_alert' => 50]);
        InventoryItem::create(['farm_id' => $farm->id, 'name' => 'Corn Seeds', 'type' => 'seed', 'quantity' => 30, 'unit' => 'kg', 'threshold_alert' => 40]); // low stock

        // Tasks
        Task::create(['farm_id' => $farm->id, 'title' => 'Inspect irrigation', 'due_date' => now()->addDays(2), 'completed' => false]);
        Task::create(['farm_id' => $farm->id, 'title' => 'Fertilize corn field', 'due_date' => now()->subDays(1), 'completed' => false]); // overdue
    }
}
