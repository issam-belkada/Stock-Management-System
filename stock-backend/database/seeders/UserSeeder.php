<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => Role::where('name', 'Admin')->first()->id,
            ]
        );
        
        // Create Manager user
        User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password123'),
                'role_id' => Role::where('name', 'Manager')->first()->id,
            ]
        );
        
        // Create Cashier user
        User::firstOrCreate(
            ['email' => 'cashier@example.com'],
            [
                'name' => 'Cashier User',
                'password' => Hash::make('password123'),
                'role_id' => Role::where('name', 'Cashier')->first()->id,
            ]
        );

    }
}
