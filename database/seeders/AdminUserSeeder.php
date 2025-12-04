<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin TAPEM',
            'email' => 'admin@tapem.com',
            'phone' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        // Create customer user for testing
        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@tapem.com',
            'phone' => '081234567891',
            'role' => 'customer',
            'password' => Hash::make('customer123'),
        ]);
    }
}
