<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ahmad Naufal',
            'email' => 'naufal@example.com',
            'phone' => '0123456789',
            'address' => 'Johor Bahru',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
        ]);

        User::create([
            'name' => 'Siti Nur',
            'email' => 'siti@example.com',
            'phone' => '0198765432',
            'address' => 'Kuala Lumpur',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
        ]);
    }
}
