<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Ahmad Naufal',
            'email' => 'ahmad@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);
    }
}